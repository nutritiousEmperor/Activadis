<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Models\Activity;
use App\Models\Inschrijving; // als je dit model hebt; anders kun je DB::table() blijven gebruiken
use App\Mail\InschrijvingActiviteit;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class ActiviteitenController extends Controller
{
    public function index()
    {
        $query = Activity::withCount(['inschrijvingen' => function ($q) {
            $q->where('confirmed', true); 
        }])
        ->orderBy('date')
        ->orderBy('time');

        // Niet ingelogd: toon alleen activiteiten waar gasten welkom zijn
        if (!Auth::check()) {
            $query->where('gasten', true);
        }

        $activiteiten = $query->get();
        $isLoggedIn   = Auth::check();

        // Voor buttons: welke activiteiten heeft de user al
        $userInschrijvingen = [];
        if ($isLoggedIn) {
            $userInschrijvingen = DB::table('inschrijvingen')
                ->where('user_id', Auth::id())
                ->pluck('activity_id')
                ->toArray();
        }

        return view('activiteiten', [
            'isLoggedIn'         => $isLoggedIn,
            'activiteiten'       => $activiteiten,
            'userInschrijvingen' => $userInschrijvingen,
        ]);
    }

    // ====================== helpers ======================

    /**
     * Restcapaciteit. Null = onbeperkt.
     */
    protected function capacityLeft(Activity $activity): ?int
    {
        if (is_null($activity->max_participants)) {
            return null;
        }

        $count = DB::table('inschrijvingen')
            ->where('activity_id', $activity->id)
            ->count();

        return max(0, (int) $activity->max_participants - $count);
    }

    /**
     * Is de activiteit vol?
     */
    protected function isFull(Activity $activity): bool
    {
        $count = DB::table('inschrijvingen')
            ->where('activity_id', $activity->id)
            ->where('confirmed', true)
            ->count();

        return max(0, (int)$activity->max_participants - $count);
    }

    /**
     * Heeft user of gast-email al een inschrijving?
     */
    protected function alreadySignedUp(?int $userId, int $activityId, ?string $guestEmail = null): bool
    {
        $q = DB::table('inschrijvingen')->where('activity_id', $activityId);

        if ($userId) {
            $q->where('user_id', $userId);
        } else {
            // normaliseer email voor vergelijking
            $q->where('guest_email', strtolower(trim((string) $guestEmail)));
        }

        return $q->exists();
    }

    // ====================== actions ======================

    /**
     * Gast-inschrijving: vereist guest_name + email.
     * Verwacht POST vanaf je modal met fields: activity_id, guest_name, email
     */
    public function guestSignup(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => ['required', 'integer', 'exists:activities,id'],
            'guest_name'  => ['required', 'string', 'min:2', 'max:255'],
            'email'       => ['required', 'email', 'max:255'],
        ], [
            'guest_name.required' => 'Vul je naam in.',
            'email.required'      => 'Vul je e-mailadres in.',
        ]);

        $activity = Activity::withCount('inschrijvingen')->findOrFail($validated['activity_id']);

        // Alleen toegestaan als gasten welkom zijn
        if (!$activity->gasten) {
            return back()->withErrors(['email' => 'Inschrijven als gast is niet toegestaan voor deze activiteit.'])->withInput();
        }

        // Capaciteit check
        $left = $this->capacityLeft($activity);
        if ($left !== null && $left <= 0) {
            return back()->withErrors(['email' => 'Deze activiteit zit vol.'])->withInput();
        }

        // Dubbele inschrijving blokkeren op activity_id + email
        $email = strtolower(trim($validated['email']));
        if ($this->alreadySignedUp(null, $activity->id, $email)) {
            return back()->withErrors(['email' => 'Je bent al ingeschreven met dit e-mailadres.'])->withInput();
        }

        // Genereer random token voor het bevestigen van de inschrijving later in de mail:
        $token = Str::random(64);

        // Opslaan
        DB::table('inschrijvingen')->insert([
            'activity_id' => $activity->id,
            'user_id'     => null,
            'guest_email' => $validated['email'],
            'confirmationToken' => $token,
            'confirmed'          => false,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        Log::info('Gast ingeschreven', [
            'activity_id' => $activity->id,
            'guest_email' => $email,
        ]);

        // Mailing
        $name = 'Gast';
        $data = [
            'name'    => $name,
            'activiteit' => $activity,
            'token'   => $token
        ];

        Mail::to($validated['email'])->send(new InschrijvingActiviteit($data));

        return back()->with('success', 'Bedankt! We hebben je inschrijving ontvangen.');
    }

    /**
     * Auth user-inschrijving: gebruikt user_id, geen guest_* velden.
     */
    public function authSignup(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => ['required', 'integer', 'exists:activities,id'],
        ]);

        $user   = Auth::user();
        $userId = $user->id;

        $activity = Activity::withCount('inschrijvingen')->findOrFail($validated['activity_id']);

        // Capaciteit check
        if ($this->isFull($activity)) {
            return back()->withErrors(['activity_id' => 'Deze activiteit zit vol.']);
        }

        // Dubbele inschrijving user
        if ($this->alreadySignedUp($userId, $activity->id)) {
            return back()->withErrors(['activity_id' => 'Je bent al ingeschreven voor deze activiteit.']);
        }

        // Opslaan (voor ingelogde users slaan we géén guest_email/guest_name op)
        DB::table('inschrijvingen')->insert([
            'activity_id' => $activity->id,
            'user_id'     => $userId,
            'guest_name'  => null,
            'guest_email' => null,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Mailing
        try {
            $data = [
                'name'       => $user->name,
                'activiteit' => $activity,
            ];
            Mail::to($user->email)->send(new InschrijvingActiviteit($data));
        } catch (\Throwable $e) {
            Log::warning('Mail versturen mislukt voor auth-inschrijving', [
                'activity_id' => $activity->id,
                'user_id'     => $userId,
                'error'       => $e->getMessage(),
            ]);
        }

        return back()->with('success', 'Je bent ingeschreven!');
    }

    public function unsubscribe(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => ['required', 'integer', 'exists:activities,id'],
        ]);

        DB::table('inschrijvingen')
            ->where('activity_id', $validated['activity_id'])
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('success', 'Je bent uitgeschreven.');
    }
}

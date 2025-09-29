<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Activity;

class ActiviteitenController extends Controller
{
    public function index()
    {
        // Publiek ziet alleen activiteiten waar gasten welkom zijn
        $query = Activity::withCount('inschrijvingen') // <— telt aantal inschrijvingen
                     ->orderBy('date')->orderBy('time');

        if (!Auth::check()) {
            $query->where('gasten', true);
        }

        $activiteiten = $query->get();
        $isLoggedIn   = Auth::check();

        return view('activiteiten', [
            'isLoggedIn'   => $isLoggedIn,
            'activiteiten' => $activiteiten,
        ]);
    }

    // ====== helpers ======
    protected function capacityLeft(Activity $activity): int
    {
        $count = DB::table('inschrijvingen')->where('activity_id', $activity->id)->count();
        return max(0, (int)$activity->max_participants - $count);
    }

    protected function alreadySignedUp(?int $userId, int $activityId, ?string $guestEmail = null): bool
    {
        $q = DB::table('inschrijvingen')->where('activity_id', $activityId);

        if ($userId) {
            $q->where('user_id', $userId);
        } else {
            $q->where('guest_email', $guestEmail);
        }

        return $q->exists();
    }
    // ======================

    public function guestSignup(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => ['required', 'integer', 'exists:activities,id'],
            'email'       => ['required', 'email'],
        ]);

        $activity = Activity::findOrFail($validated['activity_id']);

        // Alleen toegestaan als gasten welkom zijn
        if (!$activity->gasten) {
            return back()->withErrors(['email' => 'Inschrijven als gast is niet toegestaan voor deze activiteit.']);
        }

        // Capaciteit check
        if ($this->capacityLeft($activity) <= 0) {
            return back()->withErrors(['email' => 'Deze activiteit zit vol.']);
        }

        // Dubbele inschrijving blokkeren
        if ($this->alreadySignedUp(null, $activity->id, $validated['email'])) {
            return back()->withErrors(['email' => 'Je bent al ingeschreven met dit e-mailadres.']);
        }

        // Opslaan
        DB::table('inschrijvingen')->insert([
            'activity_id' => $activity->id,
            'user_id'     => null,
            'guest_email' => $validated['email'],
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        Log::info('Gast ingeschreven', $validated);

        return back()->with('success', 'Bedankt! We hebben je inschrijving ontvangen.');
    }

    public function authSignup(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => ['required', 'integer', 'exists:activities,id'],
        ]);

        $user   = Auth::user();
        $email  = $user->email;              // <— pak mail van het account
        $userId = $user->id;

        $activity = Activity::findOrFail($validated['activity_id']);

        // (capacity & duplicate checks zoals eerder)

        DB::table('inschrijvingen')->insert([
            'activity_id' => $activity->id,
            'user_id'     => $userId,
            'guest_email' => $email,         // <— SLA OP (ook voor medewerkers)
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        Log::info('Medewerker ingeschreven', [
            'user_id'     => $userId,
            'email'       => $email,
            'activity_id' => $activity->id,
        ]);

        return back()->with('success', 'Je bent ingeschreven!');
    }
}

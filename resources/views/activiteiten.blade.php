@php
    use Carbon\Carbon;
@endphp

{{-- Header --}}
<div style="display:flex; justify-content:space-between; align-items:center; padding:12px 24px; border-bottom:1px solid #e5e7eb; margin-bottom:24px;">
    <a href="{{ url('/') }}" style="font-weight:700; text-decoration:none; font-size:18px;">Activiteiten</a>

    <div>
        @auth
            <span style="margin-right:12px;">Ingelogd als {{ Auth::user()->email }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" style="padding:8px 12px; border:1px solid #d1d5db; border-radius:8px; background:white; cursor:pointer;">
                    Uitloggen
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" style="padding:8px 12px; border-radius:8px; background:#2563eb; color:white; text-decoration:none;">Inloggen</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" style="margin-left:8px; padding:8px 12px; border-radius:8px; background:#10b981; color:white; text-decoration:none;">Registreren</a>
            @endif
        @endauth
    </div>
</div>

{{-- Flash toasts (success/error/info) --}}
@foreach (['success','error','info'] as $f)
  @if(session($f))
    <div style="max-width:960px;margin:0 auto;">
      <div style="padding:10px;margin:10px 0;border-radius:10px;
                  background: {{ $f==='success'?'#d1fae5':($f==='error'?'#fee2e2':'#e5e7eb') }};
                  border:1px solid {{ $f==='success'?'#a7f3d0':($f==='error'?'#fecaca':'#d1d5db') }};">
        {{ session($f) }}
      </div>
    </div>
  @endif
@endforeach

{{-- Lijst --}}
<div style="max-width:960px; margin:0 auto; padding:24px;">
    <h1 style="font-size:24px; font-weight:700; margin-bottom:16px;">Beschikbare activiteiten</h1>

    @forelse($activiteiten as $a)
        @php
            // Verwachte kolommen: titel, omschrijving, datum (date), tijd (time), locatie, max_deelnemers
            $datum = $a->datum ? Carbon::parse($a->datum)->format('d-m-Y') : '-';
            $tijd  = $a->tijd ? Carbon::parse($a->tijd)->format('H:i') : '-';
        @endphp

        <div style="border:1px solid #e5e7eb; padding:16px; margin:12px 0; border-radius:8px;">
            <h2 style="font-size:18px; font-weight:600; margin:0 0 6px;">
                {{ $a->titel }}
                @if($a->locatie)
                    <small style="font-weight:400; color:#6b7280;">— {{ $a->locatie }}</small>
                @endif
            </h2>

            @if($a->omschrijving)
                <p><strong>Omschrijving:</strong> {{ $a->omschrijving }}</p>
            @endif

            <p>
                <strong>Datum:</strong> {{ $datum }}
                &nbsp;•&nbsp;
                <strong>Tijd:</strong> {{ $tijd }}
            </p>

            @if(!is_null($a->max_deelnemers))
                <p><strong>Max. deelnemers:</strong> {{ $a->max_deelnemers }}</p>
            @endif

            @auth
                {{-- Ingelogd: direct inschrijven --}}
                <form method="POST" action="{{ route('activiteiten.auth') }}">
                    @csrf
                    <input type="hidden" name="activity_id" value="{{ $a->id }}">
                    <button type="submit"
                        style="margin-top:8px; padding:8px 12px; border-radius:8px; background:#16a34a; color:white; border:none; cursor:pointer;">
                        Inschrijven
                    </button>
                </form>
            @else
                {{-- Gast: popup --}}
                <button class="inschrijf-btn" data-activity="{{ $a->id }}"
                    style="margin-top:8px; padding:8px 12px; border-radius:8px; background:#2563eb; color:white; border:none; cursor:pointer;">
                    Inschrijven
                </button>
            @endauth
        </div>
    @empty
        <p>Geen activiteiten gevonden.</p>
    @endforelse
</div>

{{-- Popup voor gasten --}}
@guest
    <div id="modal-backdrop" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:50;"></div>
    <div id="modal"
         style="display:none; position:fixed; left:50%; top:50%; transform:translate(-50%, -50%);
                background:white; border-radius:12px; padding:20px; width:90%; max-width:420px; z-index:51; box-shadow:0 10px 25px rgba(0,0,0,.15);">
        <h3 style="margin-top:0; font-size:18px; font-weight:700;">Inschrijven als gast</h3>
        <p style="margin:6px 0 14px;">Vul je e-mailadres in om je in te schrijven.</p>

        <form method="POST" action="{{ route('activiteiten.guest') }}">
            @csrf
            <input type="hidden" name="activity_id" id="activity_id" value="">
            <div style="margin-bottom:12px;">
                <label for="email" style="display:block; margin-bottom:6px; font-weight:600;">E-mail</label>
                <input id="email" name="email" type="email" required
                       style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px;">
            </div>
            <div style="display:flex; gap:8px; justify-content:flex-end;">
                <button type="button" id="modal-cancel"
                        style="padding:8px 12px; border-radius:8px; border:1px solid #d1d5db; background:white; cursor:pointer;">
                    Annuleren
                </button>
                <button type="submit"
                        style="padding:8px 12px; border-radius:8px; background:#16a34a; color:white; border:none; cursor:pointer;">
                    Verzenden
                </button>
            </div>
        </form>
    </div>

    {{-- Validatie/flash melding (gast) --}}
    @if (session('success') || $errors->any())
        <div id="toast"
             style="position:fixed; right:16px; top:16px; z-index:60; max-width: 380px;
                    background:#10b981; color:white; padding:12px 14px; border-radius:10px; box-shadow:0 10px 25px rgba(0,0,0,.15);">
            @if (session('success'))
                {{ session('success') }}
            @endif
            @if ($errors->any())
                <div style="margin-top:6px; font-size:14px; color:#fff;">
                    <strong>Let op:</strong>
                    <ul style="margin:6px 0 0 18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <script>
            (function () {
                const el = document.getElementById('toast');
                if (!el) return;
                setTimeout(() => {
                    el.style.transition = 'opacity .4s ease';
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 400);
                }, 3000);
            })();
        </script>
    @endif

    <script>
        (function () {
            const backdrop = document.getElementById('modal-backdrop');
            const modal = document.getElementById('modal');
            const cancel = document.getElementById('modal-cancel');
            const idInput = document.getElementById('activity_id');

            function openModal(activityId) {
                idInput.value = activityId;
                backdrop.style.display = 'block';
                modal.style.display = 'block';
            }
            function closeModal() {
                backdrop.style.display = 'none';
                modal.style.display = 'none';
                idInput.value = '';
            }

            document.querySelectorAll('.inschrijf-btn').forEach(btn => {
                btn.addEventListener('click', () => openModal(btn.dataset.activity));
            });

            backdrop.addEventListener('click', closeModal);
            cancel.addEventListener('click', closeModal);
        })();
    </script>
@endguest

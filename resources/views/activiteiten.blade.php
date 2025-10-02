@php
    use Carbon\Carbon;
@endphp

{{-- Header --}}
<div
    style="display:flex; justify-content:space-between; align-items:center; padding:12px 24px; border-bottom:1px solid #e5e7eb; margin-bottom:24px;">
    <a href="{{ url('/') }}" style="font-weight:700; text-decoration:none; font-size:18px;">Activiteiten</a>
    <div>
        @auth
            <span style="margin-right:12px;">Ingelogd als {{ Auth::user()->email }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit"
                    style="padding:8px 12px; border:1px solid #d1d5db; border-radius:8px; background:white; cursor:pointer;">
                    Uitloggen
                </button>
            </form>
        @else
            <a href="{{ route('login') }}"
               style="padding:8px 12px; border-radius:8px; background:#2563eb; color:white; text-decoration:none;">Inloggen</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}"
                   style="margin-left:8px; padding:8px 12px; border-radius:8px; background:#10b981; color:white; text-decoration:none;">Registreren</a>
            @endif
        @endauth
    </div>
</div>

{{-- Één centrale toast (success + errors) --}}
@if (session('success') || $errors->any())
    <div id="toast" style="max-width:960px;margin:0 auto 12px; padding:10px;border-radius:10px;
                  background: {{ session('success') ? '#d1fae5' : '#fee2e2' }};
                  border:1px solid {{ session('success') ? '#a7f3d0' : '#fecaca' }};">
        @if (session('success'))
            {{ session('success') }}
        @endif
        @if ($errors->any())
            <div style="margin-top:6px;">
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
            setTimeout(() => { el.style.transition = 'opacity .4s'; el.style.opacity = '0'; setTimeout(() => el.remove(), 400); }, 3000);
        })();
    </script>
@endif

{{-- Lijst --}}
<div style="max-width:960px; margin:0 auto; padding:24px;">
    <h1 style="font-size:24px; font-weight:700; margin-bottom:16px;">Beschikbare activiteiten</h1>

    @forelse($activiteiten as $a)
        @php
            $datum = $a->date ? Carbon::parse($a->date)->format('d-m-Y') : '-';
            $tijd = $a->time ? Carbon::parse($a->time)->format('H:i') : '-';

            // Bestanden voor slider ophalen
            $dir   = public_path('activity_photos/'.$a->id);
            $files = file_exists($dir)
                ? array_values(array_filter(scandir($dir), fn($f) => preg_match('/\.(jpe?g|png|webp|gif)$/i', $f)))
                : [];
        @endphp

        <div style="border:1px solid #e5e7eb; padding:16px; margin:12px 0; border-radius:8px;">
            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                <h2 style="font-size:18px; font-weight:600; margin:0;">
                    {{ $a->title }}
                    @if($a->location)
                        <small style="font-weight:400; color:#6b7280;">— {{ $a->location }}</small>
                    @endif
                </h2>

                {{-- Badge gasten/medewerkers --}}
                @if(!empty($a->gasten))
                    <span style="font-size:12px; padding:2px 8px; border-radius:9999px; background:#ecfdf5; color:#047857;">
                        Gasten welkom
                    </span>
                @else
                    <span style="font-size:12px; padding:2px 8px; border-radius:9999px; background:#f3f4f6; color:#374151;">
                        Alleen medewerkers
                    </span>
                @endif
            </div>

            @if($a->description)
                <p style="margin:8px 0 0;"><strong>Omschrijving:</strong> {{ $a->description }}</p>
            @endif

            <p style="margin:8px 0 0;">
                <strong>Datum:</strong> {{ $datum }}
                &nbsp;•&nbsp;
                <strong>Tijd:</strong> {{ $tijd }}
            </p>

            @if(!is_null($a->max_participants))
                <p>
                    <strong>Deelnemers:</strong> {{ $a->inschrijvingen_count }}/{{ $a->max_participants }}
                </p>
            @endif

            {{-- === Gallerij / slider === --}}
            <div class="activity-slider" style="position:relative; margin-top:12px;">
                @if(count($files))
                    <div class="slides" style="position:relative; overflow:hidden; border-radius:10px; height:220px;">
                        @foreach($files as $i => $file)
                            <img
                                src="{{ asset('activity_photos/'.$a->id.'/'.$file) }}"
                                alt="Foto {{ $i+1 }} van {{ $a->title }}"
                                loading="lazy"
                                style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; transition:opacity .35s; opacity:{{ $i === 0 ? '1' : '0' }};">
                        @endforeach
                    </div>

                    {{-- Controls --}}
                    <button class="nav prev" aria-label="Vorige"
                        style="position:absolute; top:50%; left:8px; transform:translateY(-50%);
                               background:rgba(0,0,0,.5); color:#fff; border:none; width:36px; height:36px; border-radius:50%; cursor:pointer;">
                        ‹
                    </button>
                    <button class="nav next" aria-label="Volgende"
                        style="position:absolute; top:50%; right:8px; transform:translateY(-50%);
                               background:rgba(0,0,0,.5); color:#fff; border:none; width:36px; height:36px; border-radius:50%; cursor:pointer;">
                        ›
                    </button>

                    {{-- Dots --}}
                    <div class="dots" style="position:absolute; left:0; right:0; bottom:8px; display:flex; gap:6px; justify-content:center;">
                        @foreach($files as $i => $file)
                            <span data-index="{{ $i }}"
                                  style="width:8px; height:8px; border-radius:9999px; background:{{ $i===0 ? '#111827' : '#d1d5db' }}; display:inline-block; cursor:pointer;"></span>
                        @endforeach
                    </div>
                @else
                    {{-- Geen foto’s: placeholder --}}
                    <div style="height:220px; border-radius:10px; overflow:hidden; background:#f3f4f6; display:flex; align-items:center; justify-content:center;">
                        <img src="{{ asset('images/activity-placeholder.jpg') }}"
                             alt="Geen foto beschikbaar"
                             style="width:100%; height:100%; object-fit:cover; opacity:.9;">
                    </div>
                @endif
            </div>

            {{-- Inschrijfknoppen --}}
            @auth
                @if(in_array($a->id, $userInschrijvingen))
                    <form method="POST" action="{{ route('activiteiten.unsubscribe') }}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="activity_id" value="{{ $a->id }}">
                        <button type="submit" style="margin-top:8px; padding:8px 12px; border-radius:8px; background:#dc2626; color:white; border:none; cursor:pointer;">Uitschrijven</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('activiteiten.auth') }}">
                        @csrf
                        <input type="hidden" name="activity_id" value="{{ $a->id }}">
                        <button type="submit" style="margin-top:8px; padding:8px 12px; border-radius:8px; background:#16a34a; color:white; border:none; cursor:pointer;">Inschrijven</button>
                    </form>
                @endif
            @endauth

            @guest
                {{-- Gast: toon knop alleen als gasten welkom zijn --}}
                @if(!empty($a->gasten))
                    <button class="inschrijf-btn" data-activity="{{ $a->id }}"
                        style="margin-top:8px; padding:8px 12px; border-radius:8px; background:#2563eb; color:white; border:none; cursor:pointer;">
                        Inschrijven
                    </button>
                @endif
            @endguest
        </div>
    @empty
        <p>Geen activiteiten gevonden.</p>
    @endforelse
</div>

{{-- Slider script (eenmalig) --}}
@once
<script>
(function(){
    function initSlider(root){
        const slidesEl = root.querySelector('.slides');
        if (!slidesEl) return;

        const slides = Array.from(slidesEl.querySelectorAll('img'));
        if (slides.length === 0) return;

        let index = 0;
        const dots  = Array.from(root.querySelectorAll('.dots span'));
        const prev  = root.querySelector('.nav.prev');
        const next  = root.querySelector('.nav.next');

        function show(i){
            index = (i + slides.length) % slides.length;
            slides.forEach((img, k) => img.style.opacity = k === index ? '1' : '0');
            dots.forEach((d, k) => d.style.background = k === index ? '#111827' : '#d1d5db');
        }

        prev?.addEventListener('click', () => show(index - 1));
        next?.addEventListener('click', () => show(index + 1));
        dots.forEach(d => d.addEventListener('click', () => show(+d.dataset.index)));

        // Swipe support
        let startX = null;
        slidesEl.addEventListener('touchstart', e => startX = e.touches[0].clientX, {passive:true});
        slidesEl.addEventListener('touchend', e => {
            if (startX === null) return;
            const dx = e.changedTouches[0].clientX - startX;
            if (Math.abs(dx) > 40) show(index + (dx < 0 ? 1 : -1));
            startX = null;
        });
    }

    document.querySelectorAll('.activity-slider').forEach(initSlider);
})();
</script>
@endonce

{{-- Popup voor gasten --}}
@guest
    <div id="modal-backdrop" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:50;"></div>
    <div id="modal"
        style="display:none; position:fixed; left:50%; top:50%; transform:translate(-50%, -50%);
                    background:white; border-radius:12px; padding:20px; width:90%; max-width:420px; z-index:51; box-shadow:0 10px 25px rgba(0,0,0,.15);">
        <h3 style="margin-top:0; font-size:18px; font-weight:700;">Inschrijven als gast</h3>
        <p style="margin:6px 0 14px;">Vul je e-mailadres in om je in te schrijven.</p>

        <form method="POST" action="{{ route('activiteiten.guest') }}" id="guestForm">
            @csrf
            <input type="hidden" name="activity_id" id="activity_id" value="{{ old('activity_id') }}">
            <div style="margin-bottom:12px;">
                <label for="email" style="display:block; margin-bottom:6px; font-weight:600;">E-mail</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
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

    <script>
        (function () {
            const backdrop = document.getElementById('modal-backdrop');
            const modal = document.getElementById('modal');
            const cancelBtn = document.getElementById('modal-cancel');
            const idInput = document.getElementById('activity_id');
            const emailEl = document.getElementById('email');

            function openModal(activityId) {
                idInput.value = activityId || idInput.value;
                backdrop.style.display = 'block';
                modal.style.display = 'block';
                setTimeout(() => emailEl?.focus(), 0);
            }
            function closeModal() {
                backdrop.style.display = 'none';
                modal.style.display = 'none';
            }

            // Open modal vanuit listing
            document.querySelectorAll('.inschrijf-btn').forEach(btn => {
                btn.addEventListener('click', () => openModal(btn.dataset.activity));
            });

            // Sluiten
            backdrop.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', closeModal);

            // Als er validatie-errors waren, open modal opnieuw met oude waarden
            @if ($errors->any())
                openModal(document.getElementById('activity_id')?.value);
            @endif
          })();
    </script>
@endguest

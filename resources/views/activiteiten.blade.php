<x-app-layout>
    <x-slot name="header"></x-slot>


    {{-- Lijst --}}
    <div class="max-w-7xl mx-auto px-6 py-6">
        <h1 class="text-2xl font-bold text-main mb-4 text-white">Beschikbare activiteiten</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($activiteiten as $a)
                @php
                    $datum = $a->date ? \Carbon\Carbon::parse($a->date)->format('d-m-Y') : '-';
                    $tijd  = $a->time ? \Carbon\Carbon::parse($a->time)->format('H:i') : '-';

                    $dir   = public_path('activity_photos/'.$a->id);
                    $files = file_exists($dir)
                        ? array_values(array_filter(scandir($dir), fn($f) => preg_match('/\.(jpe?g|png|webp|gif)$/i', $f)))
                        : [];

                    $images  = array_map(fn($f) => asset("activity_photos/{$a->id}/{$f}"), $files);

                    // Kaart-berekeningen voor min
                    $cnt  = (int) ($a->inschrijvingen_count ?? 0);
                    $min  = is_null($a->min_participants) ? null : (int) $a->min_participants;
                    $minReached = !is_null($min) && $cnt >= $min;
                    $nogNodig   = (!is_null($min) && $cnt < $min) ? ($min - $cnt) : 0;

                    $goal = !is_null($min) ? max(1, $min) : 1;
                    $pct  = min(100, (int) floor(($cnt / $goal) * 100));

                    // Payload naar Alpine met correcte types
                    $payload = [
                        'id'    => (int) $a->id,
                        'title' => (string) $a->title,
                        'location' => $a->location ? (string) $a->location : null,
                        'date'  => (string) $datum,
                        'time'  => (string) $tijd,
                        'description' => (string) ($a->description ?? ''),
                        'gasten' => (bool) $a->gasten,
                        'participants' => [
                            'count' => $cnt,
                            'max'   => is_null($a->max_participants) ? null : (int) $a->max_participants,
                            'min'   => $min,
                        ],
                        'images' => $images,
                    ];
                @endphp

                <div class="rounded-2xl border border-secondary/10 bg-white shadow-sm hover:shadow-md transition flex flex-col">
                    <div class="p-5">
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-lg font-semibold text-primary">
                                {{ $a->title }}
                                @if($a->location)
                                    <span class="ml-2 text-sm text-secondary/70">— {{ $a->location }}</span>
                                @endif
                            </h2>

                            @auth
                                @if($a->gasten)
                                    <span class="inline-flex items-center rounded-full bg-taps text-primary text-xs px-3 py-1">
                                        Gasten welkom
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-secondary/5 text-secondary text-xs px-3 py-1">
                                        Alleen medewerkers
                                    </span>
                                @endif
                            @endauth
                        </div>

                        <div class="mt-2 text-sm text-secondary/80">
                            <p class="leading-6">
                                <span class="font-medium text-primary">Datum:</span> {{ $datum }}
                                <span class="mx-2">•</span>
                                <span class="font-medium text-primary">Tijd:</span> {{ $tijd }}
                            </p>

                            @if(!is_null($a->max_participants))
                                <p class="leading-6">
                                    <span class="font-medium text-primary">Deelnemers:</span>
                                    {{ $cnt }}/{{ (int) $a->max_participants }}
                                </p>
                            @else
                                <p class="leading-6">
                                    <span class="font-medium text-primary">Deelnemers:</span>
                                    {{ $cnt }} / ∞
                                </p>
                            @endif

                            @if(!is_null($min))
                                <p class="leading-6">
                                    <span class="font-medium text-primary">Min. deelnemers:</span>
                                    {{ $min }}

                                    @if(!$minReached)
                                        <span class="ml-2 inline-flex items-center rounded-full bg-yellow-100 text-yellow-900 text-[11px] px-2 py-0.5">
                                            Nog {{ $nogNodig }} nodig
                                        </span>
                                    @else
                                        <span class="ml-2 inline-flex items-center rounded-full bg-green-100 text-green-800 text-[11px] px-2 py-0.5">
                                            Gaat door
                                        </span>
                                    @endif
                                </p>

                                <div class="mt-2 h-1.5 w-full rounded bg-gray-100">
                                    <div class="h-1.5 rounded {{ $minReached ? 'bg-green-500' : 'bg-yellow-500' }}" style="width: {{ $pct }}%;"></div>
                                </div>
                            @endif

                            @if($a->description)
                                <p class="mt-1 text-secondary/90">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($a->description), 80) }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Slider --}}
                    <div class="relative mt-1 px-5" data-payload='@json($payload)'>
                        @if(count($files))
                            <div class="slides relative overflow-hidden rounded-xl h-48 md:h-56 bg-taps cursor-zoom-in">
                                @foreach($files as $i => $file)
                                    <img
                                        src="{{ asset('activity_photos/' . $a->id . '/' . $file) }}"
                                        alt="Foto {{ $i + 1 }} van {{ $a->title }}"
                                        loading="lazy"
                                        class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 {{ $i === 0 ? 'opacity-100' : 'opacity-0' }}">
                                @endforeach
                            </div>

                            <button class="nav prev absolute left-8 top-1/2 -translate-y-1/2 inline-flex items-center justify-center w-9 h-9 rounded-full bg-primary/70 text-white hover:bg-primary transition" aria-label="Vorige">‹</button>
                            <button class="nav next absolute right-8 top-1/2 -translate-y-1/2 inline-flex items-center justify-center w-9 h-9 rounded-full bg-primary/70 text-white hover:bg-primary transition" aria-label="Volgende">›</button>

                            <div class="dots absolute left-0 right-0 -bottom-3 flex items-center justify-center gap-2">
                                @foreach($files as $i => $file)
                                    <span data-index="{{ $i }}" class="h-2.5 w-2.5 rounded-full {{ $i === 0 ? 'bg-main' : 'bg-secondary/30' }} cursor-pointer"></span>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-xl h-48 md:h-56 bg-taps flex items-center justify-center">
                                <span class="text-secondary/60 text-sm">Geen foto’s</span>
                            </div>
                        @endif
                    </div>

                    {{-- Acties onderaan --}}
                    <div class="p-5 mt-auto">
                        <button type="button"
                                class="inline-flex items-center px-4 py-2 rounded-lg border border-secondary/20 text-secondary hover:bg-secondary/5 mr-2"
                                onclick='window.dispatchEvent(new CustomEvent("open-activity",{ detail: @json($payload) }))'>
                            Details
                        </button>

                        @auth
                            @if(in_array($a->id, $userInschrijvingen))
                                <form method="POST" action="{{ route('activiteiten.unsubscribe') }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="activity_id" value="{{ $a->id }}">
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 rounded-lg bg-secondary text-white font-semibold hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-secondary/40">
                                        Uitschrijven
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('activiteiten.auth') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="activity_id" value="{{ $a->id }}">
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 rounded-lg bg-main text-primary font-semibold hover:brightness-95 focus:outline-none focus:ring-2 focus:ring-main/40">
                                        Inschrijven
                                    </button>
                                </form>
                            @endif
                        @endauth

                        @guest
                            @if($a->gasten)
                                <button
                                    class="inschrijf-btn inline-flex items-center px-4 py-2 rounded-lg bg-main text-primary font-semibold hover:brightness-95 focus:outline-none focus:ring-2 focus:ring-main/40 mt-2"
                                    data-activity="{{ $a->id }}">
                                    Inschrijven
                                </button>
                            @endif
                        @endguest
                    </div>
                </div>
            @empty
                <p class="text-main-100">Geen activiteiten gevonden.</p>
            @endforelse
        </div>
    </div>

    {{-- Modal --}}
    <div id="activityModal"
         x-data="activityModal()"
         x-show="isOpen"
         x-transition.opacity
         x-on:open-activity.window="show($event.detail)"
         x-on:keydown.escape.window="close()"
         class="fixed inset-0 z-50"
         style="display:none">
        <!-- backdrop -->
        <div class="absolute inset-0 bg-black/60" @click="close()"></div>

        <!-- sheet -->
        <div class="relative mx-auto my-8 w-[96vw] max-w-6xl bg-white rounded-2xl overflow-hidden shadow-xl">
            <div class="flex flex-col md:flex-row">
                <!-- Gallery -->
                <div class="md:w-5/12 bg-black relative">
                    <template x-if="data.images && data.images.length">
                        <div class="relative h-72 md:h-[28rem]">
                            <template x-for="(src,i) in data.images" :key="i">
                                <img :src="src"
                                     class="absolute inset-0 w-full h-full object-contain transition-opacity duration-300"
                                     :class="i===index ? 'opacity-100' : 'opacity-0'">
                            </template>

                            <button @click="prev()" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-primary/70 text-white hover:bg-primary">‹</button>
                            <button @click="next()" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-primary/70 text-white hover:bg-primary">›</button>

                            <div class="absolute left-0 right-0 bottom-2 flex justify-center gap-2">
                                <template x-for="(src,i) in data.images" :key="'dot-'+i">
                                    <span @click="index=i" class="h-2.5 w-2.5 rounded-full cursor-pointer" :class="i===index ? 'bg-main' : 'bg-white/40'"></span>
                                </template>
                            </div>
                        </div>
                    </template>

                    <template x-if="!data.images || data.images.length===0">
                        <div class="h-72 md:h-[28rem] flex items-center justify-center text-white/70">Geen foto’s</div>
                    </template>

                    <button @click="close()" class="absolute top-3 right-3 w-9 h-9 rounded-full bg-black/60 text-white hover:bg-black">✕</button>
                </div>

                <!-- Details -->
                <div class="md:w-7/12 p-6">
                    <div class="max-h-[28rem] overflow-y-auto pr-1">
                        <h2 class="text-xl font-bold text-primary" x-text="data.title"></h2>
                        <p class="mt-1 text-sm text-secondary/80" x-show="data.location">
                            <span class="font-medium text-primary">Locatie:</span> <span x-text="data.location"></span>
                        </p>
                        <p class="text-sm text-secondary/80">
                            <span class="font-medium text-primary">Datum:</span> <span x-text="data.date"></span>
                            <span class="mx-2">•</span>
                            <span class="font-medium text-primary">Tijd:</span> <span x-text="data.time"></span>
                        </p>

                        <p class="text-sm text-secondary/80"
                           x-show="data.participants && (data.participants.max !== null && data.participants.max !== undefined)">
                            <span class="font-medium text-primary">Deelnemers:</span>
                            <span x-text="Number(data.participants?.count ?? 0)"></span>/<span x-text="Number(data.participants?.max ?? 0)"></span>
                        </p>
                        <p class="text-sm text-secondary/80"
                           x-show="data.participants && (data.participants.max === null || data.participants.max === undefined)">
                            <span class="font-medium text-primary">Deelnemers:</span>
                            <span x-text="Number(data.participants?.count ?? 0)"></span>/∞
                        </p>

                        <!-- Min.-info ook in de modal -->
                        <p class="text-sm text-secondary/80"
                           x-show="data.participants && (data.participants.min !== null && data.participants.min !== undefined)">
                            <span class="font-medium text-primary">Min. deelnemers:</span>
                            <span x-text="Number(data.participants.min)"></span>

                            <template x-if="Number(data.participants?.count ?? 0) < Number(data.participants?.min ?? 0)">
                                <span class="ml-2 inline-flex items-center rounded-full bg-yellow-100 text-yellow-900 text-[11px] px-2 py-0.5">
                                    Nog <span x-text="Math.max(0, Number(data.participants.min ?? 0) - Number(data.participants.count ?? 0))"></span> nodig
                                </span>
                            </template>
                            <template x-if="Number(data.participants?.count ?? 0) >= Number(data.participants?.min ?? 0)">
                                <span class="ml-2 inline-flex items-center rounded-full bg-green-100 text-green-800 text-[11px] px-2 py-0.5">
                                    Gaat door
                                </span>
                            </template>
                        </p>

                        <!-- Volledige omschrijving -->
                        <div class="mt-3 text-secondary/90 whitespace-pre-line" x-text="data.description"></div>

                        @auth
                            <div class="mt-4">
                                <template x-if="data.gasten">
                                  <span class="inline-flex items-center rounded-full bg-taps text-primary text-xs px-3 py-1">
                                    Gasten welkom
                                  </span>
                                </template>
                                <template x-if="!data.gasten">
                                  <span class="inline-flex items-center rounded-full bg-secondary/5 text-secondary text-xs px-3 py-1">
                                    Alleen medewerkers
                                  </span>
                                </template>
                            </div>
                        @endauth

                        @guest
                            <div class="mt-5 text-sm text-secondary/80">
                                Log in om je in te schrijven. Gasten inschrijven kan via de lijst.
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    @once
        <script>
            (function () {
                function initSlider(card) {
                    const container = card.querySelector('[data-payload]');
                    const slidesEl  = card.querySelector('.slides');
                    if (!slidesEl) return;

                    const slides = Array.from(slidesEl.querySelectorAll('img'));
                    if (!slides.length) return;

                    let index = 0;
                    const dots = Array.from(card.querySelectorAll('.dots span'));
                    const prev = card.querySelector('.nav.prev');
                    const next = card.querySelector('.nav.next');

                    function show(i) {
                        index = (i + slides.length) % slides.length;
                        slides.forEach((img, k) => img.style.opacity = k === index ? '1' : '0');
                        dots.forEach((d, k) => {
                            d.className = 'h-2.5 w-2.5 rounded-full cursor-pointer ' + (k === index ? 'bg-main' : 'bg-secondary/30');
                        });
                    }

                    prev?.addEventListener('click', () => show(index - 1));
                    next?.addEventListener('click', () => show(index + 1));
                    dots.forEach(d => d.addEventListener('click', () => show(+d.dataset.index)));

                    slidesEl.addEventListener('click', () => {
                        try {
                            const detail = JSON.parse(container.dataset.payload || '{}');
                            window.dispatchEvent(new CustomEvent('open-activity', { detail }));
                        } catch(e) {}
                    });

                    let startX = null;
                    slidesEl.addEventListener('touchstart', e => startX = e.touches[0].clientX, { passive: true });
                    slidesEl.addEventListener('touchend', e => {
                        if (startX === null) return;
                        const dx = e.changedTouches[0].clientX - startX;
                        if (Math.abs(dx) > 40) show(index + (dx < 0 ? 1 : -1));
                        startX = null;
                    });
                }

                document.querySelectorAll('.rounded-2xl').forEach(card => initSlider(card));
            })();

            function activityModal(){
              return {
                isOpen: false,
                index: 0,
                data: { images: [] },
                show(payload){
                  this.data  = payload || { images: [] };
                  this.index = 0;
                  this.isOpen = true;
                  document.documentElement.classList.add('overflow-hidden');
                },
                close(){
                  this.isOpen = false;
                  document.documentElement.classList.remove('overflow-hidden');
                },
                next(){
                  if(!this.data.images?.length) return;
                  this.index = (this.index + 1) % this.data.images.length;
                },
                prev(){
                  if(!this.data.images?.length) return;
                  this.index = (this.index - 1 + this.data.images.length) % this.data.images.length;
                }
              }
            }
        </script>
    @endonce


   {{-- Popup voor gasten --}}
@guest
    <div id="modal-backdrop" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:50;"></div>

    <div id="modal"
        style="display:none; position:fixed; left:50%; top:50%; transform:translate(-50%, -50%);
               background:white; border-radius:12px; padding:20px; width:90%; max-width:460px; z-index:51; box-shadow:0 10px 25px rgba(0,0,0,.15);">
        <h3 style="margin-top:0; font-size:18px; font-weight:700;">Inschrijven als gast</h3>
        <p style="margin:6px 0 14px;">Vul je naam en e-mail in om je in te schrijven.</p>

        <form method="POST" action="{{ route('activiteiten.guest') }}" id="guestForm" novalidate>
            @csrf
            <input type="hidden" name="activity_id" id="activity_id" value="{{ old('activity_id') }}">

            <div style="margin-bottom:12px;">
                <label for="guest_name" style="display:block; margin-bottom:6px; font-weight:600;">Naam</label>
                <input id="guest_name" name="guest_name" type="text" value="{{ old('guest_name') }}" required
                       minlength="2" maxlength="255"
                       placeholder="Voor- en achternaam"
                       style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px;">
            </div>

            <div style="margin-bottom:12px;">
                <label for="email" style="display:block; margin-bottom:6px; font-weight:600;">E-mail</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                       placeholder="jij@example.com"
                       style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px;">
            </div>

            {{-- Inline foutjes (server-side) --}}
            @if ($errors->any())
                <div style="margin:8px 0 12px; padding:8px; border-radius:8px; background:#fef2f2; border:1px solid #fecaca; color:#7f1d1d; font-size:14px;">
                    <ul style="margin:0 0 0 18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div style="display:flex; gap:8px; justify-content:flex-end;">
                <button type="button" id="modal-cancel"
                        style="padding:8px 12px; border-radius:8px; border:1px solid #d1d5db; background:white; cursor:pointer;">
                    Annuleren
                </button>
                <button type="submit"
                        style="padding:8px 12px; border-radius:8px; background:#16a34a; color:white; border:none; cursor:pointer;">
                    Inschrijven
                </button>
            </div>
        </form>
    </div>

    <script>
        (function () {
            const backdrop = document.getElementById('modal-backdrop');
            const modal    = document.getElementById('modal');
            const cancelBtn= document.getElementById('modal-cancel');
            const idInput  = document.getElementById('activity_id');
            const nameEl   = document.getElementById('guest_name');
            const emailEl  = document.getElementById('email');
            const form     = document.getElementById('guestForm');

            function openModal(activityId) {
                idInput.value = activityId || idInput.value;
                backdrop.style.display = 'block';
                modal.style.display = 'block';
                setTimeout(() => nameEl?.focus(), 0);
            }
            function closeModal() {
                backdrop.style.display = 'none';
                modal.style.display = 'none';
            }

            // Koppel aan alle "Inschrijven" knoppen voor gasten
            document.querySelectorAll('.inschrijf-btn').forEach(btn => {
                btn.addEventListener('click', () => openModal(btn.dataset.activity));
            });

            backdrop.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', closeModal);

            // Client-side sanity check, want mensen typen alles behalve zinnige dingen
            form.addEventListener('submit', (e) => {
                const name  = (nameEl.value || '').trim();
                const email = (emailEl.value || '').trim();
                if (name.length < 2) {
                    e.preventDefault();
                    alert('Vul een geldige naam in (minimaal 2 tekens).');
                    nameEl.focus();
                    return;
                }
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    e.preventDefault();
                    alert('Vul een geldig e-mailadres in.');
                    emailEl.focus();
                    return;
                }
            });

            // Als er server-side errors waren, heropen de modal met de oude waarden
            @if ($errors->any())
                openModal(document.getElementById('activity_id')?.value);
            @endif
        })();
    </script>
@endguest
</x-app-layout>

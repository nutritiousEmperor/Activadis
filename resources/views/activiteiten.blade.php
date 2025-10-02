@php
    use Carbon\Carbon;
@endphp
<x-app-layout>
    <x-slot name="header"></x-slot>

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
    <div class="max-w-7xl mx-auto px-6 py-6">
        <h1 class="text-2xl font-bold text-main mb-4">Beschikbare activiteiten</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($activiteiten as $a)
                @php
                    $datum = $a->date ? Carbon::parse($a->date)->format('d-m-Y') : '-';
                    $tijd  = $a->time ? Carbon::parse($a->time)->format('H:i') : '-';

                    $dir   = public_path('activity_photos/'.$a->id);
                    $files = file_exists($dir)
                        ? array_values(array_filter(scandir($dir), fn($f) => preg_match('/\.(jpe?g|png|webp|gif)$/i', $f)))
                        : [];

                    // Payload voor quick view
                    $images  = array_map(fn($f) => asset("activity_photos/{$a->id}/{$f}"), $files);
                    $payload = [
                        'id'    => $a->id,
                        'title' => $a->title,
                        'location' => $a->location,
                        'date'  => $datum,
                        'time'  => $tijd,
                        'description' => $a->description,
                        'gasten' => (bool) $a->gasten,
                        'participants' => [
                            'count' => $a->inschrijvingen_count,
                            'max'   => $a->max_participants,
                        ],
                        'images' => $images,
                    ];
                @endphp

                <div class="rounded-2xl border border-secondary/10 bg-white shadow-sm hover:shadow-md transition flex flex-col">
                    <div class="p-5">
                        {{-- Titel + badge --}}
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-lg font-semibold text-primary">
                                {{ $a->title }}
                                @if($a->location)
                                    <span class="ml-2 text-sm text-secondary/70">— {{ $a->location }}</span>
                                @endif
                            </h2>

                            @if($a->gasten)
                                <span class="inline-flex items-center rounded-full bg-taps text-primary text-xs px-3 py-1">Gasten welkom</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-secondary/5 text-secondary text-xs px-3 py-1">Alleen medewerkers</span>
                            @endif
                        </div>

                        {{-- Meta --}}
                        <div class="mt-2 text-sm text-secondary/80">
                            <p class="leading-6">
                                <span class="font-medium text-primary">Datum:</span> {{ $datum }}
                                <span class="mx-2">•</span>
                                <span class="font-medium text-primary">Tijd:</span> {{ $tijd }}
                            </p>

                            @if(!is_null($a->max_participants))
                                <p class="leading-6">
                                    <span class="font-medium text-primary">Deelnemers:</span>
                                    {{ $a->inschrijvingen_count }}/{{ $a->max_participants }}
                                </p>
                            @endif

                            @if($a->description)
                                <p class="mt-1 text-secondary/90">{{ $a->description }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Slider (klikbaar voor quick view) --}}
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

                            {{-- Prev/Next --}}
                            <button class="nav prev absolute left-8 top-1/2 -translate-y-1/2 inline-flex items-center justify-center w-9 h-9 rounded-full bg-primary/70 text-white hover:bg-primary transition" aria-label="Vorige">‹</button>
                            <button class="nav next absolute right-8 top-1/2 -translate-y-1/2 inline-flex items-center justify-center w-9 h-9 rounded-full bg-primary/70 text-white hover:bg-primary transition" aria-label="Volgende">›</button>

                            {{-- Dots --}}
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
                        {{-- Details (open modal) --}}
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
                <p class="text-secondary">Geen activiteiten gevonden.</p>
            @endforelse
        </div>
    </div>

    {{-- Quick View Modal --}}
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
        <div class="relative mx-auto my-8 w-[95vw] max-w-5xl bg-white rounded-2xl overflow-hidden shadow-xl">
            <div class="flex flex-col md:flex-row">
                <!-- Gallery -->
                <div class="md:w-1/2 bg-black relative">
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
                <div class="md:w-1/2 p-6">
                    <h2 class="text-xl font-bold text-primary" x-text="data.title"></h2>
                    <p class="mt-1 text-sm text-secondary/80" x-show="data.location">
                        <span class="font-medium text-primary">Locatie:</span> <span x-text="data.location"></span>
                    </p>
                    <p class="text-sm text-secondary/80">
                        <span class="font-medium text-primary">Datum:</span> <span x-text="data.date"></span>
                        <span class="mx-2">•</span>
                        <span class="font-medium text-primary">Tijd:</span> <span x-text="data.time"></span>
                    </p>
                    <p class="text-sm text-secondary/80" x-show="data.participants && data.participants.max">
                        <span class="font-medium text-primary">Deelnemers:</span>
                        <span x-text="data.participants?.count ?? 0"></span>/<span x-text="data.participants?.max ?? '-'"></span>
                    </p>

                    <div class="mt-3 text-secondary/90 whitespace-pre-line" x-text="data.description"></div>

                    {{-- Acties in modal --}}
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

                    // slider klik => open quick view
                    slidesEl.addEventListener('click', () => {
                        try {
                            const detail = JSON.parse(container.dataset.payload || '{}');
                            window.dispatchEvent(new CustomEvent('open-activity', { detail }));
                        } catch(e) {}
                    });

                    // swipe (mobiel)
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

            // Alpine store voor quick view
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

    {{-- Popup voor gasten (bestaand) --}}
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

                document.querySelectorAll('.inschrijf-btn').forEach(btn => {
                    btn.addEventListener('click', () => openModal(btn.dataset.activity));
                });

                backdrop.addEventListener('click', closeModal);
                cancelBtn.addEventListener('click', closeModal);

                @if ($errors->any())
                    openModal(document.getElementById('activity_id')?.value);
                @endif
            })();
        </script>
    @endguest
</x-app-layout>

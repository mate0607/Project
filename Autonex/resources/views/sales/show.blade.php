@extends('layouts.app')

@section('content')

<section class="sales-hero sales-hero-tight">
    <div>
        <p class="sales-kicker">{{ $sale->vehicle_type }}</p>
        <h1 class="page-title">{{ $sale->brand }} {{ $sale->model }}</h1>
    </div>
    <div class="form-actions" style="margin-top: 0; display:flex; gap:8px; align-items:center;">
        @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('sales.edit', $sale) }}" class="market-action-icon" title="Szerkesztés">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </a>
            <form action="{{ route('sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('Biztosan törölni szeretnéd?');" style="display:inline-flex;">
                @csrf
                @method('DELETE')
                <button type="submit" class="market-action-icon market-action-danger" title="Törlés">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fca5a5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </button>
            </form>
        @endif
        <a href="{{ route('sales.index') }}" class="market-action-icon" title="Vissza">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
        </a>
    </div>
</section>

@if($sale->images->count())
    <div class="sale-gallery" style="margin-bottom:16px;">
        {{-- Thumbnail sidebar --}}
        <div class="sale-gallery-thumbs" id="gallery-thumbs">
            @foreach($sale->images as $img)
                <div class="sale-gallery-thumb {{ $loop->first ? 'active' : '' }}" data-index="{{ $loop->index }}" onclick="galleryGoTo({{ $loop->index }})">
                    <img src="{{ asset('storage/' . $img->path) }}" alt="Kép {{ $loop->iteration }}">
                </div>
            @endforeach
        </div>

        {{-- Main image --}}
        <div class="sale-gallery-main" id="gallery-main">
            @foreach($sale->images as $img)
                <img src="{{ asset('storage/' . $img->path) }}" alt="{{ $sale->brand }} {{ $sale->model }}" class="sale-gallery-img" data-index="{{ $loop->index }}" style="display:{{ $loop->first ? 'block' : 'none' }};">
            @endforeach

            @if($sale->images->count() > 1)
                <button type="button" class="gallery-nav gallery-prev" onclick="gallerySlide(-1)">&#10094;</button>
                <button type="button" class="gallery-nav gallery-next" onclick="gallerySlide(1)">&#10095;</button>
                <span class="gallery-counter" id="gallery-counter">1 / {{ $sale->images->count() }}</span>
            @endif
        </div>
    </div>

    {{-- Lightbox --}}
    <div class="gallery-lightbox" id="gallery-lightbox">
        <button type="button" class="gallery-lightbox-close" id="lightbox-close">&times;</button>
        <img id="lightbox-img" src="" alt="Nagyított kép">
    </div>
@elseif($sale->image)
    <div class="card" style="margin-bottom:16px;padding:0;overflow:hidden;">
        <img src="{{ asset('storage/' . $sale->image) }}" alt="{{ $sale->brand }} {{ $sale->model }}" style="width:100%;max-height:520px;object-fit:contain;display:block;background:#0b1220;">
    </div>
@endif

<section class="sales-detail-layout">
    <div class="card sales-detail-main">
        <h3>Fő adatok</h3>
        <div class="sales-detail-grid">
            <div class="sales-detail-item">
                <small>Típus</small>
                <strong>{{ $sale->vehicle_type ?? '—' }}</strong>
            </div>
            <div class="sales-detail-item">
                <small>Márka</small>
                <strong>{{ $sale->brand ?? '—' }}</strong>
            </div>
            <div class="sales-detail-item">
                <small>Modell</small>
                <strong>{{ $sale->model ?? '—' }}</strong>
            </div>
            <div class="sales-detail-item">
                <small>Karosszéria</small>
                <strong>{{ $sale->body_type ?? '—' }}</strong>
            </div>
            <div class="sales-detail-item">
                <small>Üzemanyag</small>
                <strong>{{ $sale->fuel_type ?? '—' }}</strong>
            </div>
            <div class="sales-detail-item">
                <small>Köbcenti</small>
                <strong>{{ $sale->engine_cc ? $sale->engine_cc . ' cm³' : '—' }}</strong>
            </div>
            <div class="sales-detail-item">
                <small>Ár</small>
                <strong>{{ number_format((float) $sale->price, 0, ',', ' ') }} Ft</strong>
            </div>
        </div>
    </div>

    <aside class="card sales-detail-side">
        <h3>Állapot</h3>
        <p><span class="sale-chip sale-chip-soft">{{ $sale->car_condition ?? 'n/a' }}</span></p>
        <p><strong>Kilométer:</strong> {{ $sale->mileage ?? 'n/a' }}</p>
    </aside>
</section>

<div class="card" style="margin-top: 16px;">
    <h3 style="margin-bottom: 10px;">Leírás</h3>
    <p>{{ $sale->description ?: 'Nincs részletes leírás.' }}</p>
</div>

@auth
    @if($sale->car_id)
        <div class="card" style="margin-top: 16px;padding:0;overflow:hidden;">
            <div style="padding:16px 20px;border-bottom:1px solid rgba(255,255,255,.06);">
                <h3 style="margin:0;display:flex;align-items:center;gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    Üzenetek
                </h3>
                <p style="opacity:.6;margin:4px 0 0;font-size:.9rem;">Beszélgetés a szervizzel erről a hirdetésről.</p>
            </div>
            <div id="saleMsgThread" style="max-height:360px;overflow-y:auto;padding:16px;display:flex;flex-direction:column;gap:10px;">
                <p style="text-align:center;opacity:.4;padding:20px 0;" id="saleMsgEmpty">Betöltés...</p>
            </div>
            <form method="POST" action="{{ route('cars.messages.store', $sale->car_id) }}" id="saleMsgForm" style="border-top:1px solid rgba(255,255,255,.06);padding:12px 16px;display:flex;gap:10px;">
                @csrf
                <input type="text" name="message" id="saleMsgInput" placeholder="Írj üzenetet..." required maxlength="2000"
                    style="flex:1;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:10px 14px;color:inherit;font-size:14px;">
                <button type="submit" class="btn issue-btn-main" style="white-space:nowrap;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;vertical-align:middle;"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    Küldés
                </button>
            </form>
        </div>

        <script>
        (function() {
            var thread = document.getElementById('saleMsgThread');
            var form = document.getElementById('saleMsgForm');
            var input = document.getElementById('saleMsgInput');
            var messagesUrl = '{{ route("cars.messages.index", $sale->car_id) }}';
            var storeUrl = '{{ route("cars.messages.store", $sale->car_id) }}';
            var token = '{{ csrf_token() }}';

            function renderMessages(msgs) {
                thread.innerHTML = '';
                if (msgs.length === 0) {
                    thread.innerHTML = '<p style="text-align:center;opacity:.4;padding:20px 0;">Még nincs üzenet. Írj a szerviznek!</p>';
                    return;
                }
                msgs.forEach(function(m) {
                    var align = m.is_mine ? 'flex-end' : 'flex-start';
                    var bg = m.is_mine ? 'rgba(59,130,246,.15)' : 'rgba(255,255,255,.05)';
                    var border = m.is_mine ? 'rgba(59,130,246,.25)' : 'rgba(255,255,255,.08)';
                    var html = '<div style="display:flex;flex-direction:column;align-items:' + align + ';max-width:80%;align-self:' + align + ';">'
                        + '<div style="background:' + bg + ';border:1px solid ' + border + ';border-radius:12px;padding:10px 14px;word-break:break-word;">'
                        + '<small style="opacity:.5;font-size:.75rem;">' + m.sender_name + '</small>'
                        + '<p style="margin:4px 0 0;">' + m.message.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</p>'
                        + '</div>'
                        + '<small style="opacity:.35;font-size:.7rem;margin-top:2px;">' + m.created_at + '</small>'
                        + '</div>';
                    thread.insertAdjacentHTML('beforeend', html);
                });
                thread.scrollTop = thread.scrollHeight;
            }

            function loadMessages() {
                fetch(messagesUrl, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin'
                })
                .then(function(r) {
                    if (!r.ok) throw new Error('HTTP ' + r.status);
                    return r.json();
                })
                .then(renderMessages)
                .catch(function(err) {
                    console.error('loadMessages error:', err);
                    thread.innerHTML = '<p style="text-align:center;opacity:.4;padding:20px 0;">Még nincs üzenet. Írj a szerviznek!</p>';
                });
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var msg = input.value.trim();
                if (!msg) return;
                fetch(storeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ message: msg })
                }).then(function(r) {
                    if (!r.ok) return r.text().then(function(t) { throw new Error('HTTP ' + r.status + ': ' + t); });
                    input.value = '';
                    loadMessages();
                }).catch(function(err) {
                    console.error('sendMessage error:', err);
                    alert('Hiba az üzenet küldésekor: ' + err.message);
                });
            });

            loadMessages();
        })();
        </script>
    @endif
@endauth



<script>
(function() {
    var current = 0;
    var imgs = document.querySelectorAll('#gallery-main .sale-gallery-img');
    var thumbs = document.querySelectorAll('#gallery-thumbs .sale-gallery-thumb');
    var counter = document.getElementById('gallery-counter');
    var total = imgs.length;

    // Lightbox elements
    var lightbox = document.getElementById('gallery-lightbox');
    var lightboxImg = document.getElementById('lightbox-img');
    var lightboxClose = document.getElementById('lightbox-close');

    window.galleryGoTo = function(idx) {
        if (idx < 0 || idx >= total) return;
        imgs[current].style.display = 'none';
        thumbs[current] && thumbs[current].classList.remove('active');
        current = idx;
        imgs[current].style.display = 'block';
        thumbs[current] && thumbs[current].classList.add('active');
        if (counter) counter.textContent = (current + 1) + ' / ' + total;

        // Scroll thumbnail into view
        if (thumbs[current]) {
            thumbs[current].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    };

    window.gallerySlide = function(dir) {
        galleryGoTo((current + dir + total) % total);
    };

    // Click image → open lightbox
    imgs.forEach(function(img) {
        img.addEventListener('click', function() {
            if (!lightbox) return;
            lightboxImg.src = this.src;
            lightbox.classList.add('open');
            document.body.style.overflow = 'hidden';
        });
    });

    // Close lightbox on background click
    if (lightbox) {
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });
    }

    // Close button
    if (lightboxClose) {
        lightboxClose.addEventListener('click', closeLightbox);
    }

    function closeLightbox() {
        if (!lightbox) return;
        lightbox.classList.remove('open');
        document.body.style.overflow = '';
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (lightbox && lightbox.classList.contains('open')) {
            if (e.key === 'Escape') closeLightbox();
            return;
        }
        if (e.key === 'ArrowLeft') gallerySlide(-1);
        if (e.key === 'ArrowRight') gallerySlide(1);
    });
})();
</script>

@endsection

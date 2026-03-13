@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <style>
        .an-form-container { max-width: 680px; margin: 30px auto; }
        .an-form-container h1 { margin: 0 0 20px; font-size: 24px; }
        .an-form { display: grid; gap: 16px; }
        .an-field label { display: block; color: #94a3b8; margin-bottom: 6px; font-size: 14px; }
        .an-field input, .an-field textarea, .an-field select {
            width: 100%; padding: 10px 14px; border-radius: 10px;
            border: 1px solid rgba(148,163,184,0.28); background: #0f1a2e; color: #e2ecff;
            font-size: 15px; box-sizing: border-box;
        }
        .an-field textarea { min-height: 100px; resize: vertical; }
        .an-field input:focus, .an-field textarea:focus, .an-field select:focus {
            outline: none; border-color: #4ed7f1;
        }
        .an-form-actions { display: flex; gap: 10px; margin-top: 4px; }
        .an-submit { background: #4ed7f1; color: #0b1220; border: none; padding: 10px 22px; border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 15px; }
        .an-submit:hover { background: #38bcd6; }
        .an-cancel { background: rgba(148,163,184,0.12); color: #94a3b8; border: 1px solid rgba(148,163,184,0.2); padding: 10px 22px; border-radius: 10px; text-decoration: none; font-size: 15px; }
        .an-cancel:hover { background: rgba(148,163,184,0.2); }
        .an-error { color: #f87171; font-size: 13px; margin-top: 4px; }
    </style>
@endpush

@section('content')
<div class="an-form-container">
    <h1>Új ügyfél értesítés küldése</h1>

    <div class="ad-card" style="padding: 24px;">
        <form method="POST" action="{{ route('admin.notifications.store') }}" class="an-form">
            @csrf

            <div class="an-field" style="position:relative;">
                <label for="user_search">Címzett</label>
                <input type="text" id="user_search" autocomplete="off"
                       placeholder="Keresés név vagy email alapján… (üres = Mindenki)"
                       style="margin-bottom:0;">
                <input type="hidden" name="user_id" id="user_id" value="{{ old('user_id', '') }}">
                <div id="user_dropdown" style="display:none; position:absolute; left:0; right:0; top:100%; z-index:10;
                    max-height:220px; overflow-y:auto; background:#0f1a2e; border:1px solid rgba(148,163,184,0.28);
                    border-radius:0 0 10px 10px; margin-top:-2px;">
                </div>
                @error('user_id') <div class="an-error">{{ $message }}</div> @enderror

                <script>
                (function() {
                    const users = @json($users->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'email' => $u->email]));
                    const search = document.getElementById('user_search');
                    const hidden = document.getElementById('user_id');
                    const dropdown = document.getElementById('user_dropdown');

                    const oldId = hidden.value;
                    if (oldId) {
                        const found = users.find(u => u.id == oldId);
                        if (found) search.value = found.name + ' (' + found.email + ')';
                    }

                    function render(list) {
                        let html = '<div class="udd-item" data-id="" style="padding:9px 14px;cursor:pointer;color:#60a5fa;border-bottom:1px solid rgba(148,163,184,0.12);">Mindenki</div>';
                        list.forEach(u => {
                            html += '<div class="udd-item" data-id="'+u.id+'" style="padding:9px 14px;cursor:pointer;color:#e2ecff;border-bottom:1px solid rgba(148,163,184,0.08);">'+u.name+' <span style="color:#64748b;">('+u.email+')</span></div>';
                        });
                        dropdown.innerHTML = html;
                        dropdown.style.display = list.length || search.value === '' ? 'block' : 'none';
                        dropdown.querySelectorAll('.udd-item').forEach(el => {
                            el.addEventListener('mousedown', function(e) {
                                e.preventDefault();
                                hidden.value = this.dataset.id;
                                search.value = this.dataset.id ? this.textContent.trim() : '';
                                dropdown.style.display = 'none';
                            });
                            el.addEventListener('mouseenter', function() { this.style.background = 'rgba(78,215,241,0.1)'; });
                            el.addEventListener('mouseleave', function() { this.style.background = 'transparent'; });
                        });
                    }

                    search.addEventListener('focus', function() {
                        const q = this.value.toLowerCase();
                        render(users.filter(u => !q || u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q)));
                    });
                    search.addEventListener('input', function() {
                        const q = this.value.toLowerCase();
                        hidden.value = '';
                        render(users.filter(u => !q || u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q)));
                    });
                    search.addEventListener('blur', function() { setTimeout(() => dropdown.style.display = 'none', 150); });
                })();
                </script>
            </div>

            <div class="an-field">
                <label for="title">Cím</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="Pl. Szerviz kész">
                @error('title') <div class="an-error">{{ $message }}</div> @enderror
            </div>

            <div class="an-field">
                <label for="message">Üzenet</label>
                <textarea name="message" id="message" placeholder="Írd ide az értesítés szövegét...">{{ old('message') }}</textarea>
                @error('message') <div class="an-error">{{ $message }}</div> @enderror
            </div>

            <div class="an-form-actions">
                <button type="submit" class="an-submit">Küldés</button>
                <a href="{{ route('admin.notifications.index') }}" class="an-cancel">Mégse</a>
            </div>
        </form>
    </div>
</div>
@endsection

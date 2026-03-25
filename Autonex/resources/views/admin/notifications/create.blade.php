@extends('layouts.app')



@section('content')
<div class="an-form-container" style="max-width:1500px;width:100%;">
    <h1>Új ügyfél értesítés küldése</h1>

    <div class="ad-card" style="padding: 24px; width: 100%; box-sizing: border-box;">
        <form method="POST" action="{{ route('admin.notifications.store') }}" class="an-form">
            @csrf

            <div class="an-field" style="position:relative;">
                <label for="user_search">Címzett</label>
                <input type="text" id="user_search" autocomplete="off"
                       placeholder="Keresés név vagy email alapján… (üres = Mindenki)"
                       style="margin-bottom:0;">
                <input type="hidden" name="user_id" id="user_id" value="{{ old('user_id', request('user_id', '')) }}">
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

                    const currentId = hidden.value;
                    if (currentId) {
                        const found = users.find(u => u.id == currentId);
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
                <input type="text" name="title" id="title" value="{{ old('title', request('title', '')) }}" placeholder="Pl. Szerviz kész">
                @error('title') <div class="an-error">{{ $message }}</div> @enderror
            </div>

            <div class="an-field">
                <label for="message">Üzenet</label>
                <textarea name="message" id="message" placeholder="Írd ide az értesítés szövegét...">{{ old('message', request('message', '')) }}</textarea>
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

@extends('layouts.app')

@section('content')
<section class="anx-form-wrap">
    <div class="anx-form-head">
        <h1>Új ügyfél értesítés</h1>
        <p>Küldj értesítést egy felhasználónak vagy mindenkinek.</p>
    </div>

    <div class="anx-form-card anx-form-card--md">
        <form method="POST" action="{{ route('admin.notifications.store') }}">
            @csrf

            <div class="anx-field anx-user-search">
                <label for="user_search">Címzett</label>
                <input type="text" id="user_search" autocomplete="off" placeholder="Keresés név vagy email alapján… (üres = Mindenki)">
                <input type="hidden" name="user_id" id="user_id" value="{{ old('user_id', request('user_id', '')) }}">
                <div id="user_dropdown" class="anx-user-dropdown"></div>
                @error('user_id') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="anx-field">
                <label for="title">Cím</label>
                <input type="text" name="title" id="title" value="{{ old('title', request('title', '')) }}" placeholder="Pl. Szerviz kész">
                @error('title') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="anx-field">
                <label for="message">Üzenet</label>
                <textarea name="message" id="message" placeholder="Írd ide az értesítés szövegét...">{{ old('message', request('message', '')) }}</textarea>
                @error('message') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="anx-actions">
                <button type="submit" class="anx-btn-primary">Küldés</button>
                <a href="{{ route('admin.notifications.index') }}" class="anx-btn-secondary">Mégse</a>
            </div>
        </form>
    </div>
</section>

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
        dropdown.innerHTML = '';
        var allItem = document.createElement('div');
        allItem.className = 'anx-user-dropdown-item anx-user-dropdown-item--all';
        allItem.dataset.id = '';
        allItem.textContent = 'Mindenki';
        dropdown.appendChild(allItem);
        list.forEach(u => {
            var item = document.createElement('div');
            item.className = 'anx-user-dropdown-item';
            item.dataset.id = u.id;
            item.textContent = u.name + ' ';
            var span = document.createElement('span');
            span.className = 'anx-user-email';
            span.textContent = '(' + u.email + ')';
            item.appendChild(span);
            dropdown.appendChild(item);
        });
        dropdown.style.display = list.length || search.value === '' ? 'block' : 'none';
        dropdown.querySelectorAll('.anx-user-dropdown-item').forEach(el => {
            el.addEventListener('mousedown', function(e) {
                e.preventDefault();
                hidden.value = this.dataset.id;
                search.value = this.dataset.id ? this.textContent.trim() : '';
                dropdown.style.display = 'none';
            });
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
@endsection

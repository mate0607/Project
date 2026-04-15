@extends('layouts.app')

@section('content')
<section class="anx-form-wrap">
    <div class="anx-form-head">
        <h1>Email cím megerősítése</h1>
        <p>Kérjük, ellenőrizd a postaládádat.</p>
    </div>

    <div class="anx-form-card anx-form-card--sm">
        @if (session('resent'))
            <div class="anx-success-box">Új megerősítő link elküldve az email címedre.</div>
        @endif

        <p class="anx-info-text">
            A folytatás előtt kérjük, kattints a megerősítő linkre, amelyet az email címedre küldtünk.
            Ha nem kaptad meg az emailt:
        </p>

        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <div class="anx-actions">
                <button type="submit" class="anx-btn-primary">Új link küldése</button>
            </div>
        </form>
    </div>
</section>
@endsection

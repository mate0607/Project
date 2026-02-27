@extends('layouts.app')

@section('content')

    <section class="cars-hero">
        <p class="cars-kicker">Fleet Management</p>
    </section>

    <div class="cars-layout">
        <aside class="card cars-side-panel">
            <h3>Autó áttekintés</h3>
            <div class="cars-side-metric">
                <span>Összes autó</span>
                <strong>{{ $cars->count() }}</strong>
            </div>
            <div class="cars-side-metric">
                <span>VIN nélküli</span>
                <strong>{{ $cars->whereNull('vin')->count() }}</strong>
            </div>
            <a href="{{ route('cars.create') }}" class="btn car-btn-main">+ Új autó</a>
        </aside>

        <div>
            <div class="page-head cars-page-head">
                <h1 class="page-title">Autók</h1>
            </div>

            <div class="card car-card" style="margin-top:20px;">
                <div class="table-wrap">
                    <table class="table car-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Típus</th>
                                <th>VIN</th>
                                <th>Év</th>
                                <th>Műveletek</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($cars as $car)
                                <tr>
                                    <td>{{ $car->id }}</td>
                                    <td>{{ $car->make_model }}</td>
                                    <td>{{ $car->vin ?? '—' }}</td>
                                    <td>{{ $car->year ?? '—' }}</td>
                                    <td class="table-actions">
                                        <a href="{{ route('cars.show', $car) }}" class="btn-small car-btn">Megnyit</a>
                                        <a href="{{ route('cars.edit', $car) }}" class="btn-small car-btn">Szerkeszt</a>
                                    </td>
                                </tr>
                            @endforeach

                            @if($cars->count() === 0)
                                <tr>
                                    <td colspan="5" class="empty-state">Nincs még autó rögzítve.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
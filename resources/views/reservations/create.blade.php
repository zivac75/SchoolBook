@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>Réserver ce créneau</h3>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Service</dt>
                <dd class="col-sm-9">{{ $availability->service->name ?? '-' }}</dd>
                <dt class="col-sm-3">API</dt>
                <dd class="col-sm-9">{{ $availability->api->name ?? '-' }}</dd>
                <dt class="col-sm-3">Date</dt>
                <dd class="col-sm-9">{{ \Carbon\Carbon::parse($availability->start_datetime)->format('d/m/Y') }}</dd>
                <dt class="col-sm-3">Heure</dt>
                <dd class="col-sm-9">{{ \Carbon\Carbon::parse($availability->start_datetime)->format('H:i') }} - {{ \Carbon\Carbon::parse($availability->end_datetime)->format('H:i') }}</dd>
                <dt class="col-sm-3">Statut</dt>
                <dd class="col-sm-9">
                    @if($availability->status === 'available')
                    <span class="badge bg-success">Disponible</span>
                    @else
                    <span class="badge bg-secondary">Réservé</span>
                    @endif
                </dd>
            </dl>
            <form action="{{ route('reservations.store') }}" method="POST">
                @csrf
                <input type="hidden" name="availability_id" value="{{ $availability->id }}">
                <button type="submit" class="btn btn-primary">Confirmer la réservation</button>
                <a href="{{ route('availabilities.index') }}" class="btn btn-secondary ms-2">Annuler</a>
            </form>
        </div>
    </div>
</div>
@endsection
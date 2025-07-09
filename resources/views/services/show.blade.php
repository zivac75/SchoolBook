@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Détails du Service</h4>
                    <div>
                        <a href="{{ route('services.edit', $service) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="{{ route('services.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informations générales</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Nom :</th>
                                    <td>{{ $service->name }}</td>
                                </tr>
                                <tr>
                                    <th>Description :</th>
                                    <td>{{ $service->description ?: 'Aucune description' }}</td>
                                </tr>
                                <tr>
                                    <th>Durée :</th>
                                    <td>{{ $service->duration_minutes }} minutes</td>
                                </tr>
                                <tr>
                                    <th>Statut :</th>
                                    <td>
                                        @if($service->is_active)
                                        <span class="badge bg-success">Actif</span>
                                        @else
                                        <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Créé le :</th>
                                    <td>{{ $service->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Modifié le :</th>
                                    <td>{{ $service->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Statistiques</h5>
                            <div class="row">
                                <div class="col-6">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h3>{{ $service->availabilities->count() }}</h3>
                                            <p class="mb-0">Créneaux disponibles</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h3>{{ $service->reservations->count() }}</h3>
                                            <p class="mb-0">Réservations</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($service->availabilities->count() > 0)
                    <div class="mt-4">
                        <h5>Créneaux de disponibilité</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Heure</th>
                                        <th>Capacité</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($service->availabilities->take(5) as $availability)
                                    <tr>
                                        <td>{{ $availability->start_datetime->format('d/m/Y') }}</td>
                                        <td>{{ $availability->start_datetime->format('H:i') }} - {{ $availability->end_datetime->format('H:i') }}</td>
                                        <td>{{ $availability->capacity }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($service->availabilities->count() > 5)
                            <p class="text-muted">Et {{ $service->availabilities->count() - 5 }} autres créneaux...</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
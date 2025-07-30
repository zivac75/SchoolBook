@extends('layouts.app')
@push('styles')
<style>
    .stat-card {
        border-radius: 1.5rem;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        height: 100%;
    }

    .stat-card-body {
        padding: 1.5rem;
        display: flex;
        align-items: center;
    }

    .stat-card-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.5rem;
        color: white;
    }

    .stat-card-info {
        color: white;
    }

    .stat-card-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .stat-card-title {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .bg-gradient-primary {
        background: linear-gradient(45deg, #4776E6, #8E54E9);
    }

    .bg-gradient-success {
        background: linear-gradient(45deg, #2DCE89, #2DCECC);
    }

    .bg-gradient-warning {
        background: linear-gradient(45deg, #FB6340, #FBB140);
    }

    .bg-gradient-danger {
        background: linear-gradient(45deg, #F5365C, #F56036);
    }
</style>
@endpush
@section('content')
<div class="container py-4">
    <h2 class="mb-4 fw-bold text-primary">Tableau de bord API</h2>

    <!-- Statistiques en cartes -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-gradient-primary">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-card-info">
                        <div class="stat-card-value">{{ $totalAppointments }}</div>
                        <div class="stat-card-title">Rendez-vous</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-gradient-success">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-card-info">
                        <div class="stat-card-value">{{ $appointmentsByStatus['confirmed'] }}</div>
                        <div class="stat-card-title">Confirmés</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-gradient-warning">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-card-info">
                        <div class="stat-card-value">{{ $appointmentsByStatus['pending'] }}</div>
                        <div class="stat-card-title">En attente</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-gradient-danger">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-card-info">
                        <div class="stat-card-value">{{ $appointmentsByStatus['cancelled'] }}</div>
                        <div class="stat-card-title">Annulés</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des rendez-vous -->
    <div class="card shadow rounded-4 border-0">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h5 class="card-title text-primary mb-0">Vos rendez-vous</h5>
        </div>
        <div class="card-body">
            @if($appointments->isEmpty())
            <p class="text-muted mb-0">Aucun rendez-vous pour le moment.</p>
            @else
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Étudiant</th>
                            <th>Service</th>
                            <th>Date &amp; Heure</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->user->name }}</td>
                            <td>{{ $appointment->availability->service->name }}</td>
                            <td>{{ $appointment->availability->start_datetime->format('d/m/Y H:i') }}</td>
                            <td class="text-capitalize">
                                @if($appointment->status == 'confirmed')
                                <span class="badge bg-success">Confirmé</span>
                                @elseif($appointment->status == 'pending')
                                <span class="badge bg-warning text-dark">En attente</span>
                                @else
                                <span class="badge bg-danger">Annulé</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
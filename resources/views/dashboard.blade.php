@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="mb-4 fw-bold text-primary">Tableau de bord administrateur</h2>

    <!-- Statistiques globales en 4 cartes -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-gradient-primary">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-card-info">
                        <div class="stat-card-value">{{ $totalReservations }}</div>
                        <div class="stat-card-title">Réservations</div>
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
                        <div class="stat-card-value">{{ $reservationsByStatus['confirmed'] }}</div>
                        <div class="stat-card-title">Confirmées</div>
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
                        <div class="stat-card-value">{{ $reservationsByStatus['pending'] }}</div>
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
                        <div class="stat-card-value">{{ $reservationsByStatus['cancelled'] }}</div>
                        <div class="stat-card-title">Annulées</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Taux d'occupation et périodes -->
    <div class="row mb-4">
        <!-- Taux d'occupation -->
        <div class="col-md-6 mb-3">
            <div class="card shadow rounded-4 border-0">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="card-title text-primary">Taux d'occupation</h5>
                </div>
                <div class="card-body">
                    <div class="occupation-chart">
                        <div class="occupation-chart-circle">
                            <div class="occupation-chart-inner">
                                <div class="occupation-chart-value">{{ $occupancyRate['reserved'] }}%</div>
                                <div class="occupation-chart-label">Taux d'occupation</div>
                            </div>
                        </div>
                        <div class="occupation-chart-legend mt-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="occupation-chart-dot bg-primary"></div>
                                <div class="ms-2">Créneaux réservés ({{ $occupancyRate['reserved'] }}%)</div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="occupation-chart-dot bg-success"></div>
                                <div class="ms-2">Créneaux disponibles ({{ $occupancyRate['available'] }}%)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Réservations par période -->
        <div class="col-md-6 mb-3">
            <div class="card shadow rounded-4 border-0">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="card-title text-primary">Réservations par période</h5>
                </div>
                <div class="card-body">
                    <div class="period-stats">
                        <div class="period-stat">
                            <div class="period-stat-icon bg-soft-primary">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div class="period-stat-info">
                                <div class="period-stat-value">{{ $reservationsByPeriod['today'] }}</div>
                                <div class="period-stat-label">Aujourd'hui</div>
                            </div>
                        </div>
                        <div class="period-stat">
                            <div class="period-stat-icon bg-soft-info">
                                <i class="fas fa-calendar-week"></i>
                            </div>
                            <div class="period-stat-info">
                                <div class="period-stat-value">{{ $reservationsByPeriod['this_week'] }}</div>
                                <div class="period-stat-label">Cette semaine</div>
                            </div>
                        </div>
                        <div class="period-stat">
                            <div class="period-stat-icon bg-soft-success">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="period-stat-info">
                                <div class="period-stat-value">{{ $reservationsByPeriod['this_month'] }}</div>
                                <div class="period-stat-label">Ce mois</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Réservations par service -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow rounded-4 border-0">
                <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-primary mb-0">Réservations par service</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($reservationsByService as $service => $count)
                        <div class="col-md-4 mb-3">
                            <div class="service-stat-card">
                                <div class="service-stat-name">{{ $service }}</div>
                                <div class="service-stat-value">{{ $count }}</div>
                                <div class="service-stat-bar">
                                    <div class="service-stat-progress" style="width: {{ $totalReservations > 0 ? ($count / $totalReservations) * 100 : 0 }}%"></div>
                                </div>
                                <div class="service-stat-percent">{{ $totalReservations > 0 ? round(($count / $totalReservations) * 100, 1) : 0 }}%</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtrage par API -->
    <form method="GET" class="mb-4">
        <div class="row align-items-end g-2">
            <div class="col-md-4">
                <label for="api_id" class="form-label">Filtrer par API</label>
                <select name="api_id" id="api_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Tous les APIs</option>
                    @foreach($apis as $api)
                    <option value="{{ $api->id }}" {{ $apiId == $api->id ? 'selected' : '' }}>{{ $api->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <!-- Services par API -->
    @if($selectedApi)
    <div class="row">
        @foreach($services as $service)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow rounded-4 p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center mb-3">
                    <span class="service-icon me-3"><i class="fas fa-certificate"></i></span>
                    <div>
                        <div class="fw-bold fs-5 mb-1">{{ $service->name }}</div>
                        <div class="text-muted small">{{ $service->description }}</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between text-center mb-3">
                    <div>
                        <div class="fw-bold" style="color:#00c6fb; font-size:1.3rem;">12</div>
                        <div class="small text-muted">CRÉNEAUX</div>
                    </div>
                    <div>
                        <div class="fw-bold" style="color:#00c6fb; font-size:1.3rem;">7</div>
                        <div class="small text-muted">DISPONIBLES</div>
                    </div>
                    <div>
                        <div class="fw-bold" style="color:#f6c343; font-size:1.3rem;">5</div>
                        <div class="small text-muted">RÉSERVÉS</div>
                    </div>
                </div>
                <a href="{{ route('availabilities.index', ['service_id' => $service->id, 'api_id' => $selectedApi->id]) }}" class="btn btn-gradient w-100 mt-2"><i class="fas fa-cog me-1"></i> GÉRER LES CRÉNEAUX</a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<style>
    /* Styles généraux */
    .bg-white {
        background: #fff !important;
    }

    .shadow {
        box-shadow: 0 8px 32px rgba(52, 152, 219, 0.10) !important;
    }

    .rounded-4 {
        border-radius: 1.5rem !important;
    }

    .text-primary {
        color: #7f9cf5 !important;
    }

    /* Cartes de statistiques */
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

    /* Gradients de couleur */
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

    /* Graphique d'occupation */
    .occupation-chart {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem 0;
    }

    .occupation-chart-circle {
        width: 180px;
        height: 180px;
        border-radius: 50%;

        background: conic-gradient(#4776E6 0% {
                    {
                    $occupancyRate['reserved']
                }
            }

            %, #2DCE89 {
                    {
                    $occupancyRate['reserved']
                }
            }

            % 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .occupation-chart-inner {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        background: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .occupation-chart-value {
        font-size: 2rem;
        font-weight: 700;
        color: #4776E6;
    }

    .occupation-chart-label {
        font-size: 0.8rem;
        color: #718096;
    }

    .occupation-chart-legend {
        width: 100%;
        max-width: 250px;
    }

    .occupation-chart-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    /* Statistiques par période */
    .period-stats {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
    }

    .period-stat {
        display: flex;
        align-items: center;
        padding: 0.5rem;
    }

    .period-stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.2rem;
    }

    .bg-soft-primary {
        background-color: rgba(71, 118, 230, 0.1);
        color: #4776E6;
    }

    .bg-soft-info {
        background-color: rgba(66, 153, 225, 0.1);
        color: #4299E1;
    }

    .bg-soft-success {
        background-color: rgba(45, 206, 137, 0.1);
        color: #2DCE89;
    }

    .period-stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2D3748;
    }

    .period-stat-label {
        font-size: 0.8rem;
        color: #718096;
    }

    /* Statistiques par service */
    .service-stat-card {
        background: #F7FAFC;
        border-radius: 1rem;
        padding: 1.25rem;
        position: relative;
        transition: all 0.3s ease;
    }

    .service-stat-card:hover {
        background: #EDF2F7;
        transform: translateY(-3px);
    }

    .service-stat-name {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #2D3748;
    }

    .service-stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #4776E6;
        margin-bottom: 0.75rem;
    }

    .service-stat-bar {
        height: 8px;
        background: #E2E8F0;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }

    .service-stat-progress {
        height: 100%;
        background: linear-gradient(45deg, #4776E6, #8E54E9);
        border-radius: 4px;
    }

    .service-stat-percent {
        text-align: right;
        font-size: 0.8rem;
        color: #718096;
    }

    /* Boutons et autres éléments */
    .service-icon {
        color: #7f9cf5;
        font-size: 2rem;
        vertical-align: middle;
    }

    .btn-gradient {
        background: linear-gradient(90deg, #00c6fb 0%, #7f53ac 100%);
        color: #fff;
        border: none;
        border-radius: 1.5rem;
        font-weight: 500;
        padding: 0.5em 1.5em;
        box-shadow: 0 2px 8px rgba(127, 153, 245, 0.08);
        transition: background 0.2s;
    }

    .btn-gradient:hover {
        background: linear-gradient(90deg, #7f53ac 0%, #00c6fb 100%);
        color: #fff;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .period-stats {
            flex-direction: column;
        }

        .period-stat {
            margin-bottom: 1rem;
        }
    }

    @media (max-width: 768px) {
        .rounded-4 {
            border-radius: 1rem !important;
        }

        .service-icon {
            font-size: 1.3rem;
        }

        .btn-gradient {
            font-size: 0.95rem;
            padding: 0.3em 1em;
        }

        h2 {
            font-size: 1.5rem;
        }

        .stat-card-icon {
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
        }

        .stat-card-value {
            font-size: 1.5rem;
        }

        .occupation-chart-circle {
            width: 150px;
            height: 150px;
        }

        .occupation-chart-inner {
            width: 110px;
            height: 110px;
        }

        .occupation-chart-value {
            font-size: 1.5rem;
        }
    }
</style>
@endsection
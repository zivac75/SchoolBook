@extends('layouts.app')

@section('content')
<div class="container py-4">
    @if(isset($isAdmin) && $isAdmin && isset($serviceStats))
    <div class="row mb-5">
        @foreach($services as $service)
        @foreach($apis as $api)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow rounded-4 p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center mb-3">
                    <span class="service-icon me-3"><i class="fas fa-certificate"></i></span>
                    <div>
                        <div class="fw-bold fs-5 mb-1">{{ $service->name }}</div>
                        <div class="text-muted small">{{ $service->description }}</div>
                        <div class="text-muted small"><i class="fas fa-user me-1"></i> {{ $api->name }}</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between text-center mb-3">
                    <div>
                        <div class="fw-bold" style="color:#00c6fb; font-size:1.3rem;">{{ $serviceStats[$service->id][$api->id]['total'] }}</div>
                        <div class="small text-muted">CRÉNEAUX</div>
                    </div>
                    <div>
                        <div class="fw-bold" style="color:#00c6fb; font-size:1.3rem;">{{ $serviceStats[$service->id][$api->id]['disponibles'] }}</div>
                        <div class="small text-muted">DISPONIBLES</div>
                    </div>
                    <div>
                        <div class="fw-bold" style="color:#f6c343; font-size:1.3rem;">{{ $serviceStats[$service->id][$api->id]['reserves'] }}</div>
                        <div class="small text-muted">RÉSERVÉS</div>
                    </div>
                </div>
                <a href="{{ route('availabilities.index', ['service_id' => $service->id, 'api_id' => $api->id]) }}" class="btn btn-gradient w-100 mt-2"><i class="fas fa-cog me-1"></i> GÉRER LES CRÉNEAUX</a>
            </div>
        </div>
        @endforeach
        @endforeach
    </div>
    @endif
    @if(isset($isAdmin) && $isAdmin)
    <div class="mx-auto" style="max-width: 1200px;">
        <div class="bg-white shadow rounded-4 p-4 mb-4">
            <form method="GET" class="row g-3 align-items-end mb-4">
                <div class="col-md-4">
                    <label for="service_id" class="form-label">Service</label>
                    <select name="service_id" id="service_id" class="form-select" onchange="this.form.submit()" required>
                        <option value="">Choisir un service</option>
                        @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ $serviceId == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="api_id" class="form-label">API</label>
                    <select name="api_id" id="api_id" class="form-select" onchange="this.form.submit()" required>
                        <option value="">Choisir un API</option>
                        @foreach($apis as $api)
                        <option value="{{ $api->id }}" {{ $apiId == $api->id ? 'selected' : '' }}>{{ $api->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('availabilities.create') }}" class="btn btn-gradient"><i class="fas fa-plus me-1"></i> Nouveau créneau</a>
                </div>
            </form>
            @if($serviceId && $apiId)
            <div class="d-flex justify-content-between align-items-center mb-3">
                @php
                $prevWeek = $weekStart->copy()->subWeek()->format('Y-m-d');
                $nextWeek = $weekStart->copy()->addWeek()->format('Y-m-d');
                $params = request()->except('week');
                @endphp
                <a href="?{{ http_build_query(array_merge($params, ['week' => $prevWeek])) }}" class="btn btn-nav">&lt;</a>
                <span class="fw-bold fs-5">Semaine du {{ $weekStart->format('d/m/Y') }} au {{ $weekEnd->format('d/m/Y') }}</span>
                <a href="?{{ http_build_query(array_merge($params, ['week' => $nextWeek])) }}" class="btn btn-nav">&gt;</a>
            </div>
            <div class="calendar-admin bg-white rounded-4 shadow p-4 mb-4">
                <div class="row text-center fw-bold mb-2">
                    @php
                    $days = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi'];
                    $dates = collect($calendar)->keys()->sort();
                    $firstDate = $dates->first() ? \Carbon\Carbon::parse($dates->first()) : now();
                    $week = [];
                    for ($i=0; $i<5; $i++) {
                        $week[]=$firstDate->copy()->startOfWeek()->addDays($i);
                        }
                        @endphp
                        @foreach($week as $i => $date)
                        <div class="col">
                            <div>{{ $days[$i] }}</div>
                            <div class="small text-muted">{{ $date->format('d M') }}</div>
                        </div>
                        @endforeach
                </div>
                <form method="POST" action="{{ route('availabilities.updateWeek') }}">
                    @csrf
                    <input type="hidden" name="service_id" value="{{ $serviceId }}">
                    <input type="hidden" name="api_id" value="{{ $apiId }}">
                    <input type="hidden" name="week" value="{{ $weekStart->format('Y-m-d') }}">
                    <div class="row">
                        @foreach($week as $i => $date)
                        <div class="col">
                            @php $dayAvail = $calendar[$date->format('Y-m-d')] ?? []; @endphp
                            @forelse($dayAvail as $a)
                            <a href="{{ route('availabilities.show', $a) }}" style="text-decoration:none;">
                                <div class="mb-2 p-2 rounded text-center slot-admin {{ $a->status }}" style="position:relative; cursor:pointer;">
                                    <span class="fw-bold">{{ \Carbon\Carbon::parse($a->start_datetime)->format('H:i') }}</span>
                                    @if($a->status === 'reserved')
                                    <span class="badge badge-reserved ms-2">Réservé</span>
                                    @elseif($a->status === 'available')
                                    <span class="badge badge-available ms-2">Disponible</span>
                                    @else
                                    <span class="badge bg-secondary ms-2">Désactivé</span>
                                    @endif
                                    <input type="checkbox" name="slots[]" value="{{ $a->id }}" class="d-none" {{ $a->status === 'available' ? 'checked' : '' }}>
                                </div>
                            </a>
                            @empty
                            <div class="text-muted small">-</div>
                            @endforelse
                        </div>
                        @endforeach
                    </div>
                    <div class="d-flex flex-wrap gap-2 mt-4 justify-content-between align-items-center">
                        <div class="d-flex gap-3">
                            <span class="badge badge-available">{{ $stats['disponibles'] ?? 0 }} DISPONIBLES</span>
                            <span class="badge badge-reserved">{{ $stats['reserves'] ?? 0 }} RÉSERVÉS</span>
                            <span class="badge bg-secondary">{{ $stats['desactives'] ?? 0 }} DÉSACTIVÉS</span>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-action btn-activate-all">Tout activer</button>
                            <button type="button" class="btn btn-action btn-deactivate-all">Tout désactiver</button>
                            <button type="submit" class="btn btn-gradient">Enregistrer</button>
                        </div>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
    @else
    {{-- Affichage calendrier étudiant (inchangé) --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form method="GET" class="row g-3 align-items-end mb-0">
            <div class="col-auto">
                <label for="api_id" class="form-label">API</label>
                <select name="api_id" id="api_id" class="form-select" onchange="this.form.submit()">
                    @foreach($apis as $api)
                    <option value="{{ $api->id }}" {{ $apiId == $api->id ? 'selected' : '' }}>{{ $api->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <label for="service_id" class="form-label">Service</label>
                <select name="service_id" id="service_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Tous les services</option>
                    @foreach($services as $service)
                    <option value="{{ $service->id }}" {{ $serviceId == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="week" value="{{ $weekStart->format('Y-m-d') }}">
        </form>
        <div>
            @php
            $prevWeek = $weekStart->copy()->subWeek()->format('Y-m-d');
            $nextWeek = $weekStart->copy()->addWeek()->format('Y-m-d');
            $params = request()->except('week');
            @endphp
            <a href="?{{ http_build_query(array_merge($params, ['week' => $prevWeek])) }}" class="btn btn-outline-primary me-2">&lt;</a>
            <span class="fw-bold">Semaine du {{ $weekStart->format('d/m/Y') }} au {{ $weekEnd->format('d/m/Y') }}</span>
            <a href="?{{ http_build_query(array_merge($params, ['week' => $nextWeek])) }}" class="btn btn-outline-primary ms-2">&gt;</a>
        </div>
    </div>

    <div class="card p-3">
        <div class="row text-center fw-bold mb-2">
            @php
            $days = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi'];
            $dates = collect($calendar)->keys()->sort();
            $firstDate = $dates->first() ? \Carbon\Carbon::parse($dates->first()) : now();
            $week = [];
            for ($i=0; $i<5; $i++) {
                $week[]=$firstDate->copy()->startOfWeek()->addDays($i);
                }
                // Générer une couleur unique par service
                $serviceColors = [];
                foreach($services as $s) {
                $serviceColors[$s->id] = 'hsl('.(crc32($s->name)%360).',80%,80%)';
                }
                @endphp
                @foreach($week as $i => $date)
                <div class="col">
                    <div>{{ $days[$i] }}</div>
                    <div class="small text-muted">{{ $date->format('d M') }}</div>
                </div>
                @endforeach
        </div>
        <div class="row">
            @foreach($week as $i => $date)
            <div class="col">
                @php $dayAvail = $calendar[$date->format('Y-m-d')] ?? []; @endphp
                @forelse($dayAvail as $a)
                <div class="mb-2 p-2 rounded text-center"
                    style="background: {{ $serviceColors[$a->service_id] ?? '#eee' }}; position:relative;">
                    <span class="fw-bold">{{ \Carbon\Carbon::parse($a->start_datetime)->format('H:i') }}</span>
                    @if($a->status === 'reserved')
                    <span class="badge bg-warning ms-2">Réservé</span>
                    @else
                    <span class="badge bg-success ms-2">Disponible</span>
                    @endif
                    <div class="small">{{ $a->service->name ?? '-' }}</div>
                    @if(auth()->check() && auth()->user()->role === 'etudiant' && $a->status === 'available')
                    <a href="{{ route('reservations.create', $a) }}" class="btn btn-sm btn-primary mt-1">Réserver</a>
                    @endif
                </div>
                @empty
                <div class="text-muted small">-</div>
                @endforelse
            </div>
            @endforeach
        </div>
        <div class="row mt-3">
            <div class="col-auto">
                <span class="badge bg-success">Disponible</span> = créneau libre
            </div>
            <div class="col-auto">
                <span class="badge bg-warning">Réservé</span> = créneau réservé
            </div>
            @foreach($services as $s)
            <div class="col-auto">
                <span class="badge" style="background: {{ $serviceColors[$s->id] }}; color:#333;">{{ $s->name }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

<style>
    .bg-white {
        background: #fff !important;
    }

    .shadow {
        box-shadow: 0 8px 32px rgba(52, 152, 219, 0.10) !important;
    }

    .rounded-4 {
        border-radius: 2rem !important;
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

    .btn-nav {
        background: #e3f6fd;
        color: #00c6fb;
        border-radius: 1.5rem;
        font-weight: 500;
        padding: 0.3em 1.1em;
        border: none;
        transition: background 0.2s;
    }

    .btn-nav:hover {
        background: #00c6fb;
        color: #fff;
    }

    .calendar-admin {
        min-height: 340px;
    }

    .slot-admin {
        background: #f8fafc;
        border: 1.5px solid #e3f6fd;
        transition: box-shadow 0.2s, background 0.2s;
        min-height: 60px;
        min-width: 120px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .slot-admin.available {
        background: linear-gradient(90deg, #00c6fb 0%, #7f53ac 100%);
        color: #fff;
    }

    .slot-admin.reserved {
        background: linear-gradient(90deg, #fbc2eb 0%, #f6d365 100%);
        color: #fff;
    }

    .slot-admin:hover {
        box-shadow: 0 2px 12px #00c6fb22;
    }

    .badge-available {
        background: #00c6fb;
        color: #fff;
        font-size: 0.95rem;
        border-radius: 1rem;
        padding: 0.3em 1em;
    }

    .badge-reserved {
        background: #f6c343;
        color: #fff;
        font-size: 0.95rem;
        border-radius: 1rem;
        padding: 0.3em 1em;
    }

    .btn-action {
        background: #e3f6fd;
        color: #00c6fb;
        border-radius: 1.5rem;
        font-weight: 500;
        padding: 0.3em 1.1em;
        border: none;
        transition: background 0.2s;
    }

    .btn-action:hover {
        background: #00c6fb;
        color: #fff;
    }

    @media (max-width: 900px) {
        .calendar-admin {
            padding: 1rem !important;
        }

        .rounded-4 {
            border-radius: 1.2rem !important;
        }
    }

    @media (max-width: 600px) {
        .calendar-admin {
            padding: 0.5rem !important;
        }

        .slot-admin {
            font-size: 0.95rem;
            padding: 0.5rem 0.2rem;
        }

        .btn-gradient,
        .btn-action,
        .btn-nav {
            font-size: 0.95rem;
            padding: 0.3em 1em;
        }
    }
</style>
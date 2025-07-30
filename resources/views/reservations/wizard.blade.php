@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
    <div class="bg-white shadow rounded-4 p-4" style="max-width: 400px; width: 100%;">
        <h3 class="text-center mb-2" style="color: #2d9cdb;"><i class="fas fa-calendar-alt me-2"></i>Réserver un service</h3>
        <p class="text-center text-muted mb-4">Planifiez facilement votre rendez-vous administratif</p>
        <div class="d-flex justify-content-center mb-4">
            <div class="step {{ $step == 1 ? 'active' : '' }}">1</div>
            <div class="step-line"></div>
            <div class="step {{ $step == 2 ? 'active' : '' }}">2</div>
            <div class="step-line"></div>
            <div class="step {{ $step == 3 ? 'active' : '' }}">3</div>
            <div class="step-line"></div>
            <div class="step {{ $step == 4 ? 'active' : '' }}">4</div>
        </div>
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if($step == 1 || ($step == 2 && $serviceId) || ($step == 3 && $serviceId && $apiId))
        <form method="GET" action="{{ route('reservations.wizard') }}" class="mb-0">
            @if($step == 1)
            <input type="hidden" name="step" value="2">
            <div class="mb-3">
                <label for="service_id" class="form-label">Sélectionnez votre service</label>
                <select name="service_id" id="service_id" class="form-select" required>
                    <option value="">Choisissez un service</option>
                    @foreach($services as $service)
                    <option value="{{ $service->id }}" {{ $serviceId == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Suivant</button>
            @elseif($step == 2 && $serviceId)
            <input type="hidden" name="step" value="3">
            <input type="hidden" name="service_id" value="{{ $serviceId }}">
            <div class="mb-3">
                <label for="api_id" class="form-label">Choisissez votre API</label>
                <select name="api_id" id="api_id" class="form-select" required>
                    <option value="">Sélectionnez un API</option>
                    @foreach($apis as $api)
                    <option value="{{ $api->id }}" {{ $apiId == $api->id ? 'selected' : '' }}>{{ $api->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Suivant</button>
            <a href="{{ route('reservations.wizard', ['step' => 1]) }}" class="btn btn-link w-100 mt-2">Retour</a>
            @elseif($step == 3 && $serviceId && $apiId)
            <input type="hidden" name="step" value="4">
            <input type="hidden" name="service_id" value="{{ $serviceId }}">
            <input type="hidden" name="api_id" value="{{ $apiId }}">
            <div class="mb-3">
                <label for="availability_id" class="form-label">Choisissez votre créneau</label>
                <select name="availability_id" id="availability_id" class="form-select" required>
                    <option value="">Sélectionnez un créneau</option>
                    @foreach($availabilities as $a)
                    <option value="{{ $a->id }}">
                        {{ \Carbon\Carbon::parse($a->start_datetime)->format('d/m/Y H:i') }} - {{ $a->api->name ?? '-' }}
                    </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Suivant</button>
            <a href="{{ route('reservations.wizard', ['step' => 2, 'service_id' => $serviceId]) }}" class="btn btn-link w-100 mt-2">Retour</a>
            @endif
        </form>
        @elseif($step == 4 && $selectedAvailability)
        <div class="mb-3 text-center">
            <div class="mb-2"><i class="fas fa-calendar-check fa-2x text-success"></i></div>
            <div class="fw-bold">Service : {{ $selectedAvailability->service->name }}</div>
            <div>Date : {{ \Carbon\Carbon::parse($selectedAvailability->start_datetime)->format('d/m/Y') }}</div>
            <div>Heure : {{ \Carbon\Carbon::parse($selectedAvailability->start_datetime)->format('H:i') }} - {{ \Carbon\Carbon::parse($selectedAvailability->end_datetime)->format('H:i') }}</div>
            <div>API : {{ $selectedAvailability->api->name ?? '-' }}</div>
        </div>
        <form method="POST" action="{{ route('reservations.wizard.store') }}">
            @csrf
            <input type="hidden" name="service_id" value="{{ $selectedAvailability->service_id }}">
            <input type="hidden" name="availability_id" value="{{ $selectedAvailability->id }}">
            <button type="submit" class="btn btn-primary w-100">RÉSERVER MAINTENANT</button>
        </form>
        <a href="{{ route('reservations.wizard', ['step' => 3, 'service_id' => $selectedAvailability->service_id, 'api_id' => $selectedAvailability->user_id]) }}" class="btn btn-link w-100 mt-2">Retour</a>
        @endif
    </div>
</div>
<style>
    .step {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #e3f6fd;
        color: #2d9cdb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
    }

    .step.active {
        background: #2d9cdb;
        color: #fff;
    }

    .step-line {
        width: 32px;
        height: 4px;
        background: #e3f6fd;
        margin: 0 4px;
        border-radius: 2px;
        align-self: center;
    }
</style>
@endsection
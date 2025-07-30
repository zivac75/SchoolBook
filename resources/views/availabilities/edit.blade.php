@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Modifier le créneau</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('availabilities.update', $availability) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="service_id" class="form-label">Service *</label>
                            <select name="service_id" id="service_id" class="form-select @error('service_id') is-invalid @enderror" required>
                                <option value="">-- Sélectionner un service --</option>
                                @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id', $availability->service_id) == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('service_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="start_datetime" class="form-label">Date et heure de début *</label>
                            <input type="datetime-local" name="start_datetime" id="start_datetime" class="form-control @error('start_datetime') is-invalid @enderror" value="{{ old('start_datetime', \Carbon\Carbon::parse($availability->start_datetime)->format('Y-m-d\TH:i')) }}" required>
                            @error('start_datetime')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="end_datetime" class="form-label">Date et heure de fin *</label>
                            <input type="datetime-local" name="end_datetime" id="end_datetime" class="form-control @error('end_datetime') is-invalid @enderror" value="{{ old('end_datetime', \Carbon\Carbon::parse($availability->end_datetime)->format('Y-m-d\TH:i')) }}" required>
                            @error('end_datetime')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Statut *</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="available" {{ old('status', $availability->status) == 'available' ? 'selected' : '' }}>Disponible</option>
                                <option value="reserved" {{ old('status', $availability->status) == 'reserved' ? 'selected' : '' }}>Réservé</option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @if(auth()->user()->role === 'admin')
                        <div class="mb-3">
                            <label for="user_id" class="form-label">API *</label>
                            <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                <option value="">-- Sélectionner un API --</option>
                                @foreach($apis as $api)
                                <option value="{{ $api->id }}" {{ old('user_id', $availability->user_id) == $api->id ? 'selected' : '' }}>{{ $api->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('availabilities.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
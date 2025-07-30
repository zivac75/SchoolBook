@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Créer un nouveau créneau</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('availabilities.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="service_id" class="form-label">Service *</label>
                            <select name="service_id" id="service_id" class="form-select @error('service_id') is-invalid @enderror" required>
                                <option value="">-- Sélectionner un service --</option>
                                @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
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
                            <input type="datetime-local" name="start_datetime" id="start_datetime" class="form-control @error('start_datetime') is-invalid @enderror" value="{{ old('start_datetime') }}" required>
                            @error('start_datetime')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="end_datetime" class="form-label">Date et heure de fin *</label>
                            <input type="datetime-local" name="end_datetime" id="end_datetime" class="form-control @error('end_datetime') is-invalid @enderror" value="{{ old('end_datetime') }}" required>
                            @error('end_datetime')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Champ capacité supprimé --}}
                        @if(auth()->user()->role === 'admin')
                        <div class="mb-3">
                            <label for="user_id" class="form-label">API *</label>
                            <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                <option value="">-- Sélectionner un API --</option>
                                @foreach($apis as $api)
                                <option value="{{ $api->id }}" {{ old('user_id') == $api->id ? 'selected' : '' }}>{{ $api->name }}</option>
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
                                <i class="fas fa-save"></i> Créer le créneau
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
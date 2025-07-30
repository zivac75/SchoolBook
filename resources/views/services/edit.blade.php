@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="mx-auto" style="max-width: 600px;">
        <div class="bg-white shadow rounded-4 p-4">
            <div class="d-flex align-items-center mb-4">
                <span class="service-icon me-3"><i class="fas fa-certificate"></i></span>
                <h2 class="mb-0" style="color: #00c6fb;">Modifier le service</h2>
            </div>
            <form action="{{ route('services.update', $service) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nom du service *</label>
                    <input type="text" class="form-control pastel-input @error('name') is-invalid @enderror"
                        id="name" name="name" value="{{ old('name', $service->name) }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control pastel-input @error('description') is-invalid @enderror"
                        id="description" name="description" rows="3">{{ old('description', $service->description) }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="duration_minutes" class="form-label">Durée en minutes *</label>
                    <input type="number" class="form-control pastel-input @error('duration_minutes') is-invalid @enderror"
                        id="duration_minutes" name="duration_minutes"
                        value="{{ old('duration_minutes', $service->duration_minutes) }}" min="5" max="240" required>
                    @error('duration_minutes')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                            {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Service actif
                        </label>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('services.index') }}" class="btn btn-secondary rounded-pill">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    <button type="submit" class="btn btn-gradient rounded-pill">
                        <i class="fas fa-save"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
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

    .pastel-input {
        background: #f8fafc;
        border-radius: 1rem;
        border: 1px solid #e3f6fd;
        font-size: 1.05rem;
        padding: 0.7em 1em;
        transition: border 0.2s;
    }

    .pastel-input:focus {
        border: 1.5px solid #00c6fb;
        background: #fff;
        box-shadow: 0 0 0 0.1rem #00c6fb22;
    }

    @media (max-width: 700px) {
        .rounded-4 {
            border-radius: 1.2rem !important;
        }

        .service-icon {
            font-size: 1.3rem;
        }

        .btn-gradient {
            font-size: 0.95rem;
            padding: 0.3em 1em;
        }

        h2 {
            font-size: 1.2rem;
        }
    }
</style>
@endsection
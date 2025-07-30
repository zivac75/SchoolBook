@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="mx-auto" style="max-width: 600px;">
        <div class="bg-white shadow rounded-4 p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0" style="color:#00c6fb;"><i class="fas fa-calendar-alt me-2"></i>Détail du créneau</h3>
                <a href="{{ route('availabilities.index') }}" class="btn btn-gradient"><i class="fas fa-arrow-left me-1"></i> Retour</a>
            </div>
            <dl class="row mb-0">
                <dt class="col-sm-4">Service</dt>
                <dd class="col-sm-8 fw-bold">
                    <span class="service-icon me-2"><i class="fas fa-certificate"></i></span>
                    {{ $availability->service->name ?? 'N/A' }}
                </dd>
                <dt class="col-sm-4">Date</dt>
                <dd class="col-sm-8">{{ \Carbon\Carbon::parse($availability->start_datetime)->format('d/m/Y') }}</dd>
                <dt class="col-sm-4">Heure</dt>
                <dd class="col-sm-8">{{ \Carbon\Carbon::parse($availability->start_datetime)->format('H:i') }} - {{ \Carbon\Carbon::parse($availability->end_datetime)->format('H:i') }}</dd>
                <dt class="col-sm-4">Statut</dt>
                <dd class="col-sm-8">
                    @if($availability->status === 'available')
                    <span class="badge badge-available">Disponible</span>
                    @else
                    <span class="badge badge-reserved">Réservé</span>
                    @endif
                </dd>
                <dt class="col-sm-4">Créé le</dt>
                <dd class="col-sm-8">{{ \Carbon\Carbon::parse($availability->created_at)->format('d/m/Y H:i') }}</dd>
            </dl>
            @if(auth()->user() && auth()->user()->role === 'admin')
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('availabilities.edit', $availability) }}" class="btn btn-action"><i class="fas fa-edit me-1"></i>Modifier</a>
                <form action="{{ route('availabilities.destroy', $availability) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression de ce créneau ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-delete"><i class="fas fa-trash me-1"></i>Supprimer</button>
                </form>
            </div>
            @endif
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
        font-size: 1.3rem;
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

    .btn-delete {
        background: #ffe3ec;
        color: #f75c7c;
        border-radius: 1.5rem;
        font-weight: 500;
        padding: 0.3em 1.1em;
        border: none;
        transition: background 0.2s;
    }

    .btn-delete:hover {
        background: #f75c7c;
        color: #fff;
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

    @media (max-width: 700px) {
        .rounded-4 {
            border-radius: 1.2rem !important;
        }

        .service-icon {
            font-size: 1.1rem;
        }

        .btn-gradient,
        .btn-action,
        .btn-delete {
            font-size: 0.95rem;
            padding: 0.3em 1em;
        }

        h3 {
            font-size: 1.2rem;
        }
    }
</style>
@endsection
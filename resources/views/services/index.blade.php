@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="mx-auto" style="max-width: 1100px;">
        <div class="bg-white shadow rounded-4 p-4" style="overflow: hidden;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0" style="color: #00c6fb;"><i class="fas fa-cogs me-2"></i>Gestion des services</h2>
                <a href="{{ route('services.create') }}" class="btn btn-gradient">
                    <i class="fas fa-plus me-1"></i> Ajouter un service
                </a>
            </div>
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th><i class="fas fa-tag me-1"></i> NOM</th>
                            <th><i class="fas fa-file-alt me-1"></i> DESCRIPTION</th>
                            <th><i class="fas fa-toggle-on me-1"></i> STATUT</th>
                            <th><i class="fas fa-tools me-1"></i> ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                        <tr>
                            <td class="fw-bold">
                                <span class="service-icon me-2">
                                    <i class="fas fa-certificate"></i>
                                </span>
                                {{ $service->name }}
                            </td>
                            <td class="text-muted fst-italic">{{ $service->description }}</td>
                            <td>
                                @if($service->is_active)
                                <span class="badge badge-status">Actif</span>
                                @else
                                <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('services.edit', $service) }}" class="btn btn-edit me-2"><i class="fas fa-edit me-1"></i>Modifier</a>
                                <form action="{{ route('services.destroy', $service) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete" onclick="return confirm('Supprimer ce service ?')">
                                        <i class="fas fa-trash me-1"></i>Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Aucun service trouvé.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $services->links() }}
            </div>
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

    .badge-status {
        background: #00c6fb;
        color: #fff;
        font-size: 1rem;
        border-radius: 1rem;
        padding: 0.4em 1.1em;
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

    .btn-edit {
        background: #e3f6fd;
        color: #00c6fb;
        border-radius: 1.5rem;
        font-weight: 500;
        padding: 0.3em 1.1em;
        border: none;
        transition: background 0.2s;
    }

    .btn-edit:hover {
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

    @media (max-width: 700px) {
        .rounded-4 {
            border-radius: 1.2rem !important;
        }

        .service-icon {
            font-size: 1.1rem;
        }

        .btn-gradient,
        .btn-edit,
        .btn-delete {
            font-size: 0.95rem;
            padding: 0.3em 1em;
        }

        h2 {
            font-size: 1.2rem;
        }
    }
</style>
@endsection
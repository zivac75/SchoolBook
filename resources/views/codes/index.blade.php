@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="mx-auto" style="max-width: 1100px;">
        <div class="bg-white shadow rounded-4 p-4" style="overflow: hidden;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0" style="color: #7f53ac;"><i class="fas fa-key me-2"></i>Codes d'inscription</h2>
                <a href="{{ route('codes.create') }}" class="btn btn-gradient">
                    <i class="fas fa-plus me-1"></i> Créer un code
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
                            <th><i class="fas fa-barcode me-1"></i> CODE</th>
                            <th><i class="fas fa-user me-1"></i> NOM</th>
                            <th><i class="fas fa-envelope me-1"></i> EMAIL</th>
                            <th><i class="fas fa-user-tag me-1"></i> RÔLE</th>
                            <th><i class="fas fa-toggle-on me-1"></i> STATUT</th>
                            <th><i class="fas fa-user-cog me-1"></i> CRÉÉ PAR</th>
                            <th><i class="fas fa-tools me-1"></i> ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($codes as $code)
                        <tr>
                            <td class="fw-bold">{{ $code->code }}</td>
                            <td>{{ $code->name }}</td>
                            <td class="text-muted fst-italic">{{ $code->email }}</td>
                            <td>
                                <span class="badge bg-info text-dark text-uppercase">{{ $code->role }}</span>
                            </td>
                            <td>
                                @if($code->utilise)
                                <span class="badge badge-status bg-danger">Utilisé</span>
                                @else
                                <span class="badge badge-status">Disponible</span>
                                @endif
                            </td>
                            <td>{{ $code->creePar ? $code->creePar->name : '-' }}</td>
                            <td>
                                <form action="{{ route('codes.toggle', $code) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-edit me-2">
                                        <i class="fas fa-exchange-alt me-1"></i>
                                        {{ $code->utilise ? 'Marquer disponible' : 'Marquer utilisé' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $codes->links() }}
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

    @media (max-width: 700px) {
        .rounded-4 {
            border-radius: 1.2rem !important;
        }

        .btn-gradient,
        .btn-edit {
            font-size: 0.95rem;
            padding: 0.3em 1em;
        }

        h2 {
            font-size: 1.2rem;
        }
    }
</style>
@endsection
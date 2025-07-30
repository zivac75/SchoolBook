@extends('layouts.app')

@section('content')
<div class="container py-4" style="background: radial-gradient(ellipse at top left, #e0e7ff 0%, #c7d2fe 40%, #fbc2eb 100%); min-height: 100vh;">
    <div class="mx-auto" style="max-width: 900px;">
        <h2 class="mb-4" style="color: #3498db;"><i class="fas fa-bookmark me-2"></i>Mes Réservations</h2>
        <p class="text-muted mb-4">Gérez vos réservations de services administratifs</p>
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(isset($reservations) && $reservations->count())
        <div class="d-flex flex-column gap-3">
            @foreach($reservations as $reservation)
            <div class="card shadow-sm border-0 rounded-4 reservation-card">
                <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between p-3 p-md-4">
                    <div class="d-flex align-items-center mb-3 mb-md-0 flex-shrink-0">
                        <div class="me-3 me-md-4 reservation-icon">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-5 mb-1">{{ $reservation->availability->service->name ?? '-' }}</div>
                            <div class="text-muted mb-1 small">{{ $reservation->availability->service->description ?? '' }}</div>
                            <div class="text-muted small"><i class="far fa-calendar-alt me-1"></i> {{ $reservation->availability->start_datetime->format('d/m/Y') }}
                                <i class="far fa-clock ms-3 me-1"></i> {{ $reservation->availability->start_datetime->format('H:i') }}
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 mt-2 mt-md-0 flex-shrink-0 flex-wrap">
                        @if($reservation->status === 'confirmed')
                        <span class="badge" style="background: #1abc9c; color: #fff; font-size: 1rem;">Confirmé</span>
                        @elseif($reservation->status === 'pending')
                        <span class="badge" style="background: #f6c343; color: #fff; font-size: 1rem;">En attente</span>
                        @elseif($reservation->status === 'cancelled')
                        <span class="badge bg-secondary" style="font-size: 1rem;">Annulée</span>
                        @endif
                        <form method="POST" action="{{ route('reservations.destroy', $reservation) }}" onsubmit="return confirm('Annuler cette réservation ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger ms-2 reservation-cancel-btn">&times; ANNULER</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="alert alert-info mt-4">Aucune réservation trouvée.</div>
        @endif
    </div>
</div>
<style>
    .reservation-card {
        transition: box-shadow 0.2s;
    }

    .reservation-card:hover {
        box-shadow: 0 4px 24px rgba(52, 152, 219, 0.10);
    }

    .reservation-icon {
        font-size: 2.2rem;
        color: #7f9cf5;
        min-width: 2.2rem;
        text-align: center;
    }

    .reservation-cancel-btn {
        border-radius: 20px;
        font-weight: 500;
        padding: 0.3rem 1.1rem;
        font-size: 1rem;
    }

    @media (max-width: 600px) {
        .container {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }

        .reservation-card {
            border-radius: 1.2rem;
        }

        .reservation-icon {
            font-size: 1.5rem;
            min-width: 1.5rem;
        }

        .card-body {
            padding: 1rem !important;
        }

        .reservation-cancel-btn {
            font-size: 0.95rem;
            padding: 0.25rem 0.8rem;
        }

        h2 {
            font-size: 1.3rem;
        }
    }
</style>
@endsection
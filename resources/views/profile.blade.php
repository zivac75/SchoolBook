@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Mes réservations</h3>
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>API</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->availability->service->name ?? '-' }}</td>
                        <td>{{ $reservation->availability->api->name ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($reservation->availability->start_datetime)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($reservation->availability->start_datetime)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->availability->end_datetime)->format('H:i') }}</td>
                        <td>
                            @if($reservation->status === 'pending')
                            <span class="badge bg-warning">En attente</span>
                            @elseif($reservation->status === 'confirmed')
                            <span class="badge bg-success">Confirmée</span>
                            @elseif($reservation->status === 'cancelled')
                            <span class="badge bg-secondary">Annulée</span>
                            @endif
                        </td>
                        <td>
                            @if(in_array($reservation->status, ['pending', 'confirmed']))
                            <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" onsubmit="return confirm('Annuler cette réservation ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Annuler</button>
                            </form>
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucune réservation trouvée.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
<x-mail::message>
    @if($type == 'confirmation')
    # Confirmation de réservation

    Bonjour **{{ $reservation->user->name }}**,

    Votre réservation pour le service **{{ $reservation->service->name }}** a bien été enregistrée.

    **📅 Date :** {{ $reservation->availability->start_datetime->format('d/m/Y') }}
    **🕒 Heure :** {{ $reservation->availability->start_datetime->format('H:i') }} - {{ $reservation->availability->end_datetime->format('H:i') }}

    Merci de vous présenter à l'heure indiquée.
    En cas d'empêchement, pensez à annuler votre réservation depuis votre espace.

    @elseif($type == 'rappel')
    # Rappel de rendez-vous

    Bonjour **{{ $reservation->user->name }}**,

    Petit rappel pour votre rendez-vous **{{ $reservation->service->name }}**.

    **📅 Date :** {{ $reservation->availability->start_datetime->format('d/m/Y') }}
    **🕒 Heure :** {{ $reservation->availability->start_datetime->format('H:i') }} - {{ $reservation->availability->end_datetime->format('H:i') }}

    Nous vous attendons à l'heure prévue.
    Pour toute question, contactez l'administration.

    @elseif($type == 'annulation')
    # Annulation de réservation

    Bonjour **{{ $reservation->user->name }}**,

    Votre réservation pour le service **{{ $reservation->service->name }}** prévue le {{ $reservation->availability->start_datetime->format('d/m/Y') }} à {{ $reservation->availability->start_datetime->format('H:i') }} a été annulée.

    Pour toute question, contactez l'administration.
    @endif



    Merci pour votre confiance,
    L'équipe {{ config('app.name') }}
</x-mail::message>
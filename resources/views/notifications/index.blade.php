@extends('layouts.dashboard')

@section(
    'title',
    'Mes notifications - Plateforme de Suivi Pédagogique'
)

@push('styles')

<style>

    .notifications-page {
        padding: 35px;
    }

    .notifications-container {
        width: 100%;
        max-width: 1050px;
        margin: 0 auto;
    }

    .notifications-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 30px;
    }

    .notifications-header h1 {
        margin: 0 0 8px;
        color: #1e3a8a;
        font-size: 30px;
    }

    .notifications-header p {
        margin: 0;
        color: #64748b;
        line-height: 1.6;
    }

    .notifications-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .notifications-actions form {
        margin: 0;
    }

    .btn-read-all,
    .btn-delete-all {
        padding: 11px 16px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-read-all {
        background: #1d4ed8;
        color: #ffffff;
    }

    .btn-delete-all {
        background: #fee2e2;
        color: #b91c1c;
    }

    .success-message {
        margin-bottom: 22px;
        padding: 14px 18px;
        border-radius: 8px;
        background: #dcfce7;
        color: #166534;
        font-weight: 600;
    }

    .empty-notifications {
        padding: 50px 30px;
        border-radius: 12px;
        background: #ffffff;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
    }

    .empty-notifications h2 {
        margin-top: 0;
        color: #1e3a8a;
    }

    .empty-notifications p {
        margin-bottom: 0;
        color: #64748b;
    }

    .notifications-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .notification-card {
        position: relative;
        padding: 20px;
        border-radius: 12px;
        background: #ffffff;
        border-left: 5px solid #cbd5e1;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .notification-card.unread {
        border-left-color: #1d4ed8;
        background: #eff6ff;
    }

    .notification-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
    }

    .notification-title {
        margin: 0 0 8px;
        color: #1e3a8a;
        font-size: 18px;
    }

    .notification-message {
        margin: 0;
        color: #475569;
        line-height: 1.6;
    }

    .notification-date {
        flex-shrink: 0;
        color: #64748b;
        font-size: 13px;
    }

    .notification-badge {
        display: inline-block;
        margin-top: 12px;
        padding: 5px 9px;
        border-radius: 20px;
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 700;
    }

    .notification-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 16px;
    }

    .notification-actions form {
        margin: 0;
    }

    .notification-button {
        display: inline-block;
        padding: 9px 13px;
        border: none;
        border-radius: 7px;
        background: #1d4ed8;
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
    }

    .notification-button.delete {
        background: #fee2e2;
        color: #b91c1c;
    }

    @media (max-width: 750px) {

        .notifications-page {
            padding: 20px;
        }

        .notifications-header {
            flex-direction: column;
        }

        .notification-top {
            flex-direction: column;
        }

    }

</style>

@endpush


@section('content')

<div class="notifications-page">

    <div class="notifications-container">

        <div class="notifications-header">

            <div>

                <h1>
                    Mes notifications
                </h1>

                <p>
                    Retrouvez ici les informations importantes
                    concernant votre espace pédagogique.
                </p>

            </div>


            @if ($notifications->isNotEmpty())

                <div class="notifications-actions">

                    <form
                        method="POST"
                        action="{{ route('notifications.readAll') }}"
                    >

                        @csrf
                        @method('PUT')

                        <button
                            type="submit"
                            class="btn-read-all"
                        >
                            Tout marquer comme lu
                        </button>

                    </form>


                    <form
                        method="POST"
                        action="{{ route('notifications.destroyAll') }}"
                        onsubmit="return confirm(
                            'Voulez-vous vraiment supprimer toutes vos notifications ?'
                        )"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn-delete-all"
                        >
                            Tout supprimer
                        </button>

                    </form>

                </div>

            @endif

        </div>


        @if (session('success'))

            <div class="success-message">
                {{ session('success') }}
            </div>

        @endif


        @if ($notifications->isEmpty())

            <div class="empty-notifications">

                <h2>
                    Aucune notification
                </h2>

                <p>
                    Vous n'avez aucune notification
                    pour le moment.
                </p>

            </div>

        @else

            <div class="notifications-list">

                @foreach ($notifications as $notification)

                    <div
                        class="notification-card
                        {{ ! $notification->est_lue ? 'unread' : '' }}"
                    >

                        <div class="notification-top">

                            <div>

                                <h2 class="notification-title">
                                    {{ $notification->titre }}
                                </h2>

                                <p class="notification-message">
                                    {{ $notification->message }}
                                </p>

                                @if (! $notification->est_lue)

                                    <span class="notification-badge">
                                        Nouvelle
                                    </span>

                                @endif

                            </div>


                            <div class="notification-date">

                                {{
                                    $notification
                                        ->date_notification
                                        ?->format('d/m/Y H:i')
                                }}

                            </div>

                        </div>


                        <div class="notification-actions">

                            @if (! $notification->est_lue)

                                <form
                                    method="POST"
                                    action="{{
                                        route(
                                            'notifications.read',
                                            $notification->id_notification
                                        )
                                    }}"
                                >

                                    @csrf
                                    @method('PUT')

                                    <button
                                        type="submit"
                                        class="notification-button"
                                    >
                                        @if ($notification->lien)
                                            Voir
                                        @else
                                            Marquer comme lue
                                        @endif
                                    </button>

                                </form>

                            @elseif ($notification->lien)

                                <a
                                    class="notification-button"
                                    href="{{ $notification->lien }}"
                                >
                                    Voir
                                </a>

                            @endif


                            <form
                                method="POST"
                                action="{{
                                    route(
                                        'notifications.destroy',
                                        $notification->id_notification
                                    )
                                }}"
                                onsubmit="return confirm(
                                    'Supprimer cette notification ?'
                                )"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="notification-button delete"
                                >
                                    Supprimer
                                </button>

                            </form>

                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </div>

</div>

@endsection
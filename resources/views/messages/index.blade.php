@extends('layouts.dashboard')

@section(
    'title',
    'Mes messages'
)

@push('styles')
<style>
    .messages-page {
        min-height: 650px;
        padding: 50px 30px 80px;
        background: #f3f6fb;
    }

    .messages-container {
        max-width: 1100px;
        margin: 0 auto;
    }

    .header {
        margin-bottom: 25px;
        padding: 30px;
        border-radius: 20px;
        background: linear-gradient(135deg, #1e3a8a, #2563eb);
        color: white;
    }

    .header h1 {
        margin: 0 0 8px;
    }

    .header p {
        margin: 0;
        color: #dbeafe;
    }

    .message-success {
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 10px;
        background: #f0fdf4;
        color: #15803d;
    }

    .section-card {
        margin-bottom: 30px;
        padding: 25px;
        border-radius: 18px;
        background: white;
    }

    .section-card h2 {
        margin-top: 0;
        color: #172554;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 15px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
    }

    th {
        background: #eff6ff;
        color: #1e3a8a;
    }

    .unread {
        font-weight: 800;
        background: #f8fbff;
    }

    .status {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 800;
    }

    .status.new {
        background: #fee2e2;
        color: #b91c1c;
    }

    .status.read {
        background: #f0fdf4;
        color: #15803d;
    }

    .open-button {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 8px;
        background: #1e3a8a;
        color: white;
        font-weight: 800;
        text-decoration: none;
    }

    .empty {
        padding: 20px;
        color: #64748b;
        text-align: center;
    }
</style>
@endpush

@section('content')

<section class="messages-page">

    <div class="messages-container">

        <header class="header">

            <h1>
                Mes messages
            </h1>

            <p>
                Consultez vos messages reçus
                et vos messages envoyés.
            </p>

        </header>

        @if (session('success'))

            <div class="message-success">
                {{ session('success') }}
            </div>

        @endif


        <section class="section-card">

            <h2>
                Boîte de réception
            </h2>

            @if ($recus->isEmpty())

                <div class="empty">
                    Aucun message reçu.
                </div>

            @else

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>De</th>
                                <th>Sujet</th>
                                <th>Cours</th>
                                <th>Date</th>
                                <th>État</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($recus as $message)

                                <tr class="{{ ! $message->est_lu ? 'unread' : '' }}">

                                    <td>
                                        {{ $message->expediteur?->nom }}
                                    </td>

                                    <td>
                                        {{ $message->sujet }}
                                    </td>

                                    <td>
                                        {{ $message->cours?->titre_cours }}
                                    </td>

                                    <td>
                                        {{
                                            $message
                                                ->date_message
                                                ?->format('d/m/Y H:i')
                                        }}
                                    </td>

                                    <td>

                                        @if (! $message->est_lu)

                                            <span class="status new">
                                                Nouveau
                                            </span>

                                        @else

                                            <span class="status read">
                                                Lu
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        <a
                                            class="open-button"
                                            href="{{
                                                route(
                                                    'messages.show',
                                                    $message->id_message
                                                )
                                            }}"
                                        >
                                            Ouvrir
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </section>


        <section class="section-card">

            <h2>
                Messages envoyés
            </h2>

            @if ($envoyes->isEmpty())

                <div class="empty">
                    Aucun message envoyé.
                </div>

            @else

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>À</th>
                                <th>Sujet</th>
                                <th>Cours</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($envoyes as $message)

                                <tr>

                                    <td>
                                        {{ $message->destinataire?->nom }}
                                    </td>

                                    <td>
                                        {{ $message->sujet }}
                                    </td>

                                    <td>
                                        {{ $message->cours?->titre_cours }}
                                    </td>

                                    <td>
                                        {{
                                            $message
                                                ->date_message
                                                ?->format('d/m/Y H:i')
                                        }}
                                    </td>

                                    <td>

                                        <a
                                            class="open-button"
                                            href="{{
                                                route(
                                                    'messages.show',
                                                    $message->id_message
                                                )
                                            }}"
                                        >
                                            Ouvrir
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </section>

    </div>

</section>

@endsection
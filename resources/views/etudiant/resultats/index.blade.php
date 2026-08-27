@extends('layouts.dashboard')

@section(
    'title',
    'Mes résultats - Espace étudiant'
)

@push('styles')

<style>

    .results-page {
        min-height: 650px;
        padding: 50px 30px 80px;
        background: #f3f6fb;
    }

    .results-container {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
    }

    .header {
        margin-bottom: 25px;
        padding: 30px;
        border-radius: 20px;

        background:
            linear-gradient(
                135deg,
                #1e3a8a,
                #2563eb
            );

        color: #ffffff;
    }

    .header h1 {
        margin: 0 0 10px;
    }

    .header p {
        margin: 0;
        color: #dbeafe;
        line-height: 1.6;
    }

    .message {
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 10px;
    }

    .success {
        background: #f0fdf4;
        color: #15803d;
    }

    .error {
        background: #fef2f2;
        color: #b91c1c;
    }

    .table-wrapper {
        overflow-x: auto;
        border-radius: 18px;
        background: #ffffff;

        box-shadow:
            0 10px 30px
            rgba(30, 64, 175, 0.07);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 17px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
        vertical-align: middle;
    }

    th {
        background: #eff6ff;
        color: #1e3a8a;
        font-weight: 800;
    }

    tbody tr:hover {
        background: #f8fafc;
    }

    .attempt {
        display: inline-block;
        min-width: 95px;
        padding: 8px 10px;
        border-radius: 10px;
        background: #f3f4f6;
        color: #374151;
        font-weight: 800;
        text-align: center;
    }

    .score {
        display: inline-block;
        min-width: 75px;
        padding: 8px 10px;
        border-radius: 10px;
        background: #dbeafe;
        color: #172554;
        font-weight: 800;
        text-align: center;
    }

    .score-good {
        background: #dcfce7;
        color: #15803d;
    }

    .score-medium {
        background: #fef3c7;
        color: #b45309;
    }

    .score-low {
        background: #fee2e2;
        color: #b91c1c;
    }

    .empty {
        padding: 50px;
        border-radius: 18px;
        background: #ffffff;
        text-align: center;
    }

    @media (max-width: 700px) {

        .results-page {
            padding: 40px 20px 60px;
        }

        th,
        td {
            padding: 13px;
            font-size: 14px;
        }

    }

</style>

@endpush


@section('content')

<section class="results-page">

    <div class="results-container">

        <header class="header">

            <h1>
                Mes résultats
            </h1>

            <p>
                Retrouvez l’historique de toutes
                vos tentatives et suivez votre progression.
            </p>

        </header>


        @if (session('success'))

            <div class="message success">
                {{ session('success') }}
            </div>

        @endif


        @if (session('error'))

            <div class="message error">
                {{ session('error') }}
            </div>

        @endif


        @if ($resultats->isEmpty())

            <div class="empty">

                <h2>
                    Aucun résultat
                </h2>

                <p>
                    Vos résultats apparaîtront ici
                    après avoir terminé un quiz.
                </p>

            </div>

        @else

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>
                            <th>Cours</th>
                            <th>Quiz</th>
                            <th>Tentative</th>
                            <th>Score</th>
                            <th>Date</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach ($resultats as $resultat)

                            @php

                                $numeroTentative =
                                    $numerosTentatives[
                                        $resultat->id_resultat
                                    ]
                                    ?? 1;


                                if ($resultat->score >= 80) {

                                    $scoreClass =
                                        'score score-good';

                                } elseif (
                                    $resultat->score >= 50
                                ) {

                                    $scoreClass =
                                        'score score-medium';

                                } else {

                                    $scoreClass =
                                        'score score-low';
                                }

                            @endphp


                            <tr>

                                <td>

                                    {{
                                        $resultat
                                            ->quiz
                                            ?->cours
                                            ?->titre_cours
                                        ?? 'Cours'
                                    }}

                                </td>


                                <td>

                                    {{
                                        $resultat
                                            ->quiz
                                            ?->titre_quiz
                                        ?? 'Quiz'
                                    }}

                                </td>


                                <td>

                                    <span class="attempt">

                                        Tentative
                                        {{ $numeroTentative }}

                                    </span>

                                </td>


                                <td>

                                    <span class="{{ $scoreClass }}">

                                        {{ $resultat->score }} %

                                    </span>

                                </td>


                                <td>

                                    {{
                                        $resultat->date_resultat
                                            ? $resultat
                                                ->date_resultat
                                                ->format('d/m/Y')
                                            : '-'
                                    }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>

</section>

@endsection
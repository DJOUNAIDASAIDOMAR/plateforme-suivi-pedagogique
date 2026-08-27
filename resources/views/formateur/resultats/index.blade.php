@extends('layouts.dashboard')

@section(
    'title',
    'Résultats des étudiants - Espace formateur'
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
        max-width: 1150px;
        margin: 0 auto;
    }

    .page-header {
        margin-bottom: 28px;
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

    .page-header h1 {
        margin: 0 0 10px;
        font-size: 32px;
    }

    .page-header p {
        margin: 0;
        color: #dbeafe;
        line-height: 1.7;
    }

    .table-wrapper {
        overflow-x: auto;

        border-radius: 18px;

        background: #ffffff;

        box-shadow:
            0 12px 35px
            rgba(30, 64, 175, 0.08);
    }

    .results-table {
        width: 100%;
        border-collapse: collapse;
    }

    .results-table th,
    .results-table td {
        padding: 17px 18px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
        vertical-align: middle;
    }

    .results-table th {
        background: #eff6ff;
        color: #1e3a8a;
        font-size: 14px;
        font-weight: 800;
        white-space: nowrap;
    }

    .results-table tbody tr:last-child td {
        border-bottom: none;
    }

    .student-name {
        color: #172554;
        font-weight: 800;
    }

    .course-badge {
        display: inline-block;
        padding: 7px 10px;
        border-radius: 20px;
        background: #dbeafe;
        color: #1e3a8a;
        font-size: 12px;
        font-weight: 800;
    }

    .score {
        display: inline-block;
        min-width: 60px;
        padding: 8px 10px;
        border-radius: 10px;
        background: #eff6ff;
        color: #172554;
        font-weight: 800;
        text-align: center;
    }

    .empty-state {
        padding: 55px 30px;
        border-radius: 18px;
        background: #ffffff;
        text-align: center;
        box-shadow:
            0 12px 35px
            rgba(30, 64, 175, 0.08);
    }

    .empty-state h2 {
        margin: 0 0 10px;
        color: #172554;
    }

    .empty-state p {
        margin: 0;
        color: #6b7280;
        line-height: 1.7;
    }

    @media (max-width: 750px) {
        .results-page {
            padding: 40px 20px 60px;
        }
    }
</style>
@endpush

@section('content')

<section class="results-page">

    <div class="results-container">

        <header class="page-header">

            <h1>
                Résultats des étudiants
            </h1>

            <p>
                Consultez les résultats obtenus
                par les étudiants aux quiz
                de vos cours attribués.
            </p>

        </header>

        @if ($resultats->isEmpty())

            <div class="empty-state">

                <h2>
                    Aucun résultat pour le moment
                </h2>

                <p>
                    Les résultats apparaîtront ici
                    lorsqu’un étudiant aura terminé
                    un quiz appartenant à l’un de vos cours.
                </p>

            </div>

        @else

            <div class="table-wrapper">

                <table class="results-table">

                    <thead>

                        <tr>
                            <th>Étudiant</th>
                            <th>Cours</th>
                            <th>Quiz</th>
                            <th>Score</th>
                            <th>Date</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($resultats as $resultat)

                            <tr>

                                <td class="student-name">

                                    {{
                                        $resultat
                                            ->etudiant
                                            ?->user
                                            ?->nom
                                        ?? 'Étudiant'
                                    }}

                                </td>

                                <td>

                                    <span class="course-badge">

                                        {{
                                            $resultat
                                                ->quiz
                                                ?->cours
                                                ?->titre_cours
                                            ?? 'Cours'
                                        }}

                                    </span>

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

                                    <span class="score">
                                        {{ $resultat->score }}
                                    </span>

                                </td>

                                <td>

                                    {{
                                        $resultat->date_resultat
                                            ? $resultat
                                                ->date_resultat
                                                ->format('d/m/Y')
                                            : 'Non renseignée'
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
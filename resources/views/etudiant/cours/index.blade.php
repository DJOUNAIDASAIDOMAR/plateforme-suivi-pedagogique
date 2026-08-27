@extends('layouts.dashboard')

@section(
    'title',
    'Mes cours - Espace étudiant'
)

@push('styles')
<style>
    .courses-page {
        min-height: 650px;
        padding: 50px 30px 80px;
        background: #f3f6fb;
    }

    .courses-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .courses-header {
        margin-bottom: 30px;
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

    .courses-header h1 {
        margin: 0 0 10px;
        font-size: 32px;
    }

    .courses-header p {
        margin: 0;
        color: #dbeafe;
        line-height: 1.7;
    }

    .filiere-name {
        display: inline-block;
        margin-top: 15px;
        padding: 8px 13px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        font-size: 13px;
        font-weight: 800;
    }

    .message-error {
        margin-bottom: 22px;
        padding: 15px;
        border-radius: 10px;
        background: #fef2f2;
        color: #b91c1c;
    }

    .courses-grid {
        display: grid;
        grid-template-columns:
            repeat(
                3,
                minmax(0, 1fr)
            );
        gap: 24px;
    }

    .course-card {
        display: flex;
        min-height: 340px;
        padding: 25px;

        border: 1px solid #e5e7eb;
        border-radius: 18px;

        flex-direction: column;

        background: #ffffff;

        box-shadow:
            0 12px 35px
            rgba(30, 64, 175, 0.08);

        transition: 0.2s;
    }

    .course-card:hover {
        transform: translateY(-4px);

        box-shadow:
            0 18px 40px
            rgba(30, 64, 175, 0.13);
    }

    .course-badge {
        display: inline-block;
        align-self: flex-start;

        margin-bottom: 15px;
        padding: 7px 11px;

        border-radius: 20px;

        background: #dbeafe;
        color: #1e3a8a;

        font-size: 12px;
        font-weight: 800;
    }

    .course-card h2 {
        margin: 0 0 12px;
        color: #172554;
        font-size: 23px;
    }

    .description {
        margin: 0 0 18px;
        color: #6b7280;
        line-height: 1.7;
    }

    .trainer {
        margin: 0 0 20px;
        color: #4b5563;
        font-size: 14px;
    }

    .statistics {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;

        margin-top: auto;
        margin-bottom: 18px;
    }

    .statistic {
        padding: 12px;
        border-radius: 10px;
        background: #eff6ff;
        text-align: center;
    }

    .statistic strong {
        display: block;
        margin-bottom: 3px;
        color: #1e3a8a;
        font-size: 20px;
    }

    .statistic span {
        color: #6b7280;
        font-size: 13px;
    }

    .open-button {
        display: flex;
        width: 100%;
        padding: 13px 18px;
        border-radius: 10px;
        align-items: center;
        justify-content: center;
        background: #1e3a8a;
        color: #ffffff;
        font-weight: 800;
        text-decoration: none;
    }

    .open-button:hover {
        background: #172554;
    }

    .empty-state {
        padding: 60px 30px;
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

    @media (max-width: 1050px) {
        .courses-grid {
            grid-template-columns:
                repeat(
                    2,
                    minmax(0, 1fr)
                );
        }
    }

    @media (max-width: 700px) {
        .courses-page {
            padding: 40px 20px 60px;
        }

        .courses-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<section class="courses-page">

    <div class="courses-container">

        <header class="courses-header">

            <h1>
                Mes cours
            </h1>

            <p>
                Retrouvez tous les cours disponibles
                dans votre filière.
            </p>

            <span class="filiere-name">

                {{
                    $etudiant
                        ->filier
                        ?->nom_filier
                    ?? 'Filière non renseignée'
                }}

            </span>

        </header>

        @if (session('error'))

            <div class="message-error">
                {{ session('error') }}
            </div>

        @endif

        @if ($cours->isEmpty())

            <div class="empty-state">

                <h2>
                    Aucun cours disponible
                </h2>

                <p>
                    Aucun cours n'a encore été ajouté
                    dans votre filière.
                </p>

            </div>

        @else

            <div class="courses-grid">

                @foreach ($cours as $cour)

                    <article class="course-card">

                        <span class="course-badge">

                            {{
                                $cour
                                    ->filier
                                    ?->nom_filier
                                ?? 'Filière'
                            }}

                        </span>

                        <h2>
                            {{ $cour->titre_cours }}
                        </h2>

                        <p class="description">

                            {{
                                $cour->description
                                ?: 'Aucune description disponible.'
                            }}

                        </p>

                        <p class="trainer">

                            <strong>
                                Formateur :
                            </strong>

                            {{
                                $cour
                                    ->formateur
                                    ?->user
                                    ?->nom
                                ?? 'Non renseigné'
                            }}

                        </p>

                        <div class="statistics">

                            <div class="statistic">

                                <strong>
                                    {{ $cour->lecons_count }}
                                </strong>

                                <span>
                                    Leçons
                                </span>

                            </div>

                            <div class="statistic">

                                <strong>
                                    {{ $cour->quizzes_count }}
                                </strong>

                                <span>
                                    Quiz
                                </span>

                            </div>

                        </div>

                        <a
                            class="open-button"
                            href="{{
                                route(
                                    'etudiant.cours.show',
                                    $cour->id_cours
                                )
                            }}"
                        >
                            Consulter le cours
                        </a>

                    </article>

                @endforeach

            </div>

        @endif

    </div>

</section>

@endsection
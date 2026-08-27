@extends('layouts.dashboard')

@section(
    'title',
    'Mes cours attribués - Espace formateur'
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
        max-width: 760px;
        margin: 0;
        color: #dbeafe;
        line-height: 1.7;
    }

    .courses-grid {
        display: grid;
        grid-template-columns:
            repeat(3, minmax(0, 1fr));
        gap: 24px;
    }

    .course-card {
        display: flex;
        min-height: 330px;
        padding: 25px;

        border: 1px solid #e5e7eb;
        border-radius: 18px;

        flex-direction: column;

        background: #ffffff;

        box-shadow:
            0 12px 35px
            rgba(30, 64, 175, 0.08);
    }

    .filiere-badge {
        display: inline-block;
        align-self: flex-start;

        margin-bottom: 16px;
        padding: 7px 11px;

        border-radius: 25px;

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

    .course-description {
        margin: 0 0 20px;
        color: #6b7280;
        line-height: 1.7;
    }

    .course-statistics {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;

        margin-top: auto;
        margin-bottom: 20px;
    }

    .statistic {
        padding: 13px;
        border-radius: 10px;
        background: #eff6ff;
        text-align: center;
    }

    .statistic strong {
        display: block;
        margin-bottom: 4px;
        color: #1e3a8a;
        font-size: 21px;
    }

    .statistic span {
        color: #6b7280;
        font-size: 13px;
    }

    .course-button {
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

    .course-button:hover {
        background: #172554;
    }

    .empty-state {
        padding: 60px 30px;
        border-radius: 18px;
        background: #ffffff;
        text-align: center;
    }

    @media (max-width: 1050px) {
        .courses-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
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
                Mes cours attribués
            </h1>

            <p>
                Retrouvez les cours qui vous ont été attribués
                par le responsable pédagogique.
                Vous pouvez ensuite gérer les leçons,
                les contenus et les quiz.
            </p>

        </header>

        @if ($cours->isEmpty())

            <div class="empty-state">

                <h2>
                    Aucun cours ne vous est encore attribué
                </h2>

                <p>
                    Les cours attribués par le responsable
                    apparaîtront automatiquement ici.
                </p>

            </div>

        @else

            <div class="courses-grid">

                @foreach ($cours as $cour)

                    <article class="course-card">

                        <span class="filiere-badge">
                            {{
                                $cour->filier?->nom_filier
                                ?? 'Filière non renseignée'
                            }}
                        </span>

                        <h2>
                            {{ $cour->titre_cours }}
                        </h2>

                        <p class="course-description">
                            {{
                                $cour->description
                                ?: 'Aucune description disponible.'
                            }}
                        </p>

                        <div class="course-statistics">

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
                            class="course-button"
                            href="{{
                                route(
                                    'formateur.lecons.index',
                                    $cour->id_cours
                                )
                            }}"
                        >
                            Gérer les leçons
                        </a>

                    </article>

                @endforeach

            </div>

        @endif

    </div>

</section>

@endsection
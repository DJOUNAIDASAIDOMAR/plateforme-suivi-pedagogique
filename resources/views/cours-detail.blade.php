@extends('layouts.site')

@section('title', $cours->titre_cours . ' - Cours')

@push('styles')
<style>
    .course-detail {
        min-height: 650px;
        padding: 70px 30px;
        background: #f3f6fb;
    }

    .course-detail-container {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 25px;
        color: #1e3a8a;
        font-weight: 800;
        text-decoration: none;
    }

    .course-header {
        margin-bottom: 25px;
        padding: 38px;
        border-radius: 22px;

        background:
            linear-gradient(
                135deg,
                #1e3a8a,
                #2563eb
            );

        color: #ffffff;
    }

    .course-header h1 {
        margin: 0 0 15px;
        font-size: 39px;
    }

    .course-header p {
        max-width: 800px;
        margin: 0 0 20px;
        color: #dbeafe;
        font-size: 17px;
        line-height: 1.75;
    }

    .course-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .course-meta span {
        padding: 9px 13px;

        border: 1px solid
            rgba(255, 255, 255, 0.20);

        border-radius: 25px;

        background:
            rgba(255, 255, 255, 0.12);
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIONS DU COURS
    |--------------------------------------------------------------------------
    */

    .course-actions {
        display: grid;

        grid-template-columns:
            repeat(
                2,
                minmax(0, 1fr)
            );

        margin-bottom: 30px;
        gap: 18px;
    }

    .action-card {
        display: flex;
        padding: 24px;

        border-radius: 16px;

        flex-direction: column;
        justify-content: space-between;

        background: #ffffff;

        box-shadow:
            0 10px 30px
            rgba(30, 64, 175, 0.08);
    }

    .action-icon {
        margin-bottom: 12px;
        font-size: 28px;
    }

    .action-card h2 {
        margin: 0 0 8px;
        color: #172554;
        font-size: 20px;
    }

    .action-card p {
        margin: 0 0 18px;
        color: #6b7280;
        line-height: 1.6;
    }

    .action-button {
        display: flex;
        width: 100%;
        padding: 13px 16px;

        border: none;
        border-radius: 10px;

        align-items: center;
        justify-content: center;

        font-weight: 800;
        text-decoration: none;

        cursor: pointer;
    }

    .contact-button {
        background: #1e3a8a;
        color: #ffffff;
    }

    .contact-button:hover {
        background: #1d4ed8;
    }

    .quiz-button {
        background: #15803d;
        color: #ffffff;
    }

    .quiz-button:hover {
        background: #166534;
    }

    .disabled-button {
        background: #e5e7eb;
        color: #6b7280;
        cursor: not-allowed;
    }


    /*
    |--------------------------------------------------------------------------
    | LEÇONS
    |--------------------------------------------------------------------------
    */

    .lessons-card {
        padding: 30px;

        border-radius: 20px;

        background: #ffffff;

        box-shadow:
            0 12px 35px
            rgba(30, 64, 175, 0.08);
    }

    .lessons-card h2 {
        margin: 0 0 23px;
        color: #172554;
    }

    .lesson-item {
        margin-bottom: 15px;
        padding: 20px;

        border: 1px solid #e5e7eb;
        border-radius: 13px;

        background: #f8fafc;
    }

    .lesson-item:last-child {
        margin-bottom: 0;
    }

    .lesson-item h3 {
        margin: 0 0 9px;
        color: #1e3a8a;
    }

    .lesson-item p {
        margin: 0;
        color: #6b7280;
    }

    .no-lessons {
        padding: 25px;

        border-radius: 12px;

        background: #eff6ff;
        color: #1e3a8a;

        text-align: center;
    }


    @media (max-width: 750px) {

        .course-detail {
            padding: 50px 20px;
        }

        .course-header {
            padding: 28px 22px;
        }

        .course-header h1 {
            font-size: 31px;
        }

        .course-actions {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush


@section('content')

<section class="course-detail">

    <div class="course-detail-container">

        <a
            class="back-link"
            href="{{ route('cours.public') }}"
        >
            ← Retour aux cours
        </a>


        {{-- ============================================== --}}
        {{-- EN-TÊTE DU COURS --}}
        {{-- ============================================== --}}

        <header class="course-header">

            <h1>
                {{ $cours->titre_cours }}
            </h1>


            <p>
                {{
                    $cours->description
                    ?:
                    'Aucune description disponible pour ce cours.'
                }}
            </p>


            <div class="course-meta">

                <span>
                    Filière :

                    {{
                        $cours->filier?->nom_filier
                        ?? 'Non renseignée'
                    }}
                </span>


                <span>
                    Formateur :

                    {{
                        $cours
                            ->formateur
                            ?->user
                            ?->nom
                        ?? 'Non renseigné'
                    }}
                </span>


                @if (! empty($cours->niveau))

                    <span>
                        Niveau :
                        {{ $cours->niveau }}
                    </span>

                @endif

            </div>

        </header>


        {{-- ============================================== --}}
        {{-- ACTIONS ÉTUDIANT --}}
        {{-- ============================================== --}}

        @auth

            @if (Auth::user()->role === 'etudiant')

                <section class="course-actions">


                    {{-- CONTACTER LE FORMATEUR --}}

                    <article class="action-card">

                        <div>

                            <div class="action-icon">
                                ✉️
                            </div>

                            <h2>
                                Contacter le formateur
                            </h2>

                            <p>
                                Une question concernant ce cours ?
                                Envoyez directement un message
                                au formateur.
                            </p>

                        </div>


                        @if (
                            $cours->formateur
                            &&
                            $cours->formateur->user
                        )

                            <a
                                class="
                                    action-button
                                    contact-button
                                "
                                href="{{
                                    route(
                                        'messages.create',
                                        [
                                            'idCours' =>
                                                $cours->id_cours,

                                            'idDestinataire' =>
                                                $cours
                                                    ->formateur
                                                    ->user
                                                    ->id_user,
                                        ]
                                    )
                                }}"
                            >
                                ✉️ Contacter le formateur
                            </a>

                        @else

                            <div
                                class="
                                    action-button
                                    disabled-button
                                "
                            >
                                Formateur indisponible
                            </div>

                        @endif

                    </article>


                    {{-- PASSER LE QUIZ --}}

                    <article class="action-card">

                        <div>

                            <div class="action-icon">
                                📝
                            </div>

                            <h2>
                                Passer le quiz
                            </h2>

                            <p>
                                Testez vos connaissances
                                sur ce cours avec le quiz
                                correspondant.
                            </p>

                        </div>


                        @if ($quizDuCours)

                            <a
                                class="
                                    action-button
                                    quiz-button
                                "
                                href="{{
                                    route(
                                        'etudiant.quiz.show',
                                        $quizDuCours->id_quiz
                                    )
                                }}"
                            >
                                📝 Passer le quiz
                            </a>

                        @else

                            <div
                                class="
                                    action-button
                                    disabled-button
                                "
                            >
                                Aucun quiz disponible
                            </div>

                        @endif

                    </article>

                </section>

            @endif

        @endauth


        {{-- ============================================== --}}
        {{-- LEÇONS --}}
        {{-- ============================================== --}}

        <section class="lessons-card">

            <h2>
                Leçons du cours
            </h2>


            @forelse (
                $cours->lecons->sortBy('ordre')
                as $lecon
            )

                <article class="lesson-item">

                    <h3>
                        Leçon {{ $lecon->ordre }} :

                        {{ $lecon->{'titre_leçon'} }}
                    </h3>


                    <p>
                        {{ $lecon->contenus->count() }}

                        contenu{{
                            $lecon->contenus->count() > 1
                                ? 's'
                                : ''
                        }}

                        disponible{{
                            $lecon->contenus->count() > 1
                                ? 's'
                                : ''
                        }}.
                    </p>

                </article>

            @empty

                <div class="no-lessons">
                    Aucune leçon n’est encore
                    disponible pour ce cours.
                </div>

            @endforelse

        </section>

    </div>

</section>

@endsection
@extends('layouts.dashboard')

@section(
    'title',
    $cours->titre_cours . ' - Espace étudiant'
)

@push('styles')

<style>

    .course-page {
        min-height: 650px;
        padding: 50px 30px 80px;
        background: #f3f6fb;
    }

    .course-container {
        width: 100%;
        max-width: 1050px;
        margin: 0 auto;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 20px;
        color: #1e3a8a;
        font-weight: 800;
        text-decoration: none;
    }

    .course-header {
        margin-bottom: 30px;
        padding: 32px;
        border-radius: 20px;

        background:
            linear-gradient(
                135deg,
                #1e3a8a,
                #2563eb
            );

        color: #ffffff;
    }

    .course-header h1 {
        margin: 0 0 10px;
        font-size: 33px;
    }

    .course-header p {
        margin: 0 0 15px;
        color: #dbeafe;
        line-height: 1.7;
    }

    .header-information {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }

    .header-badge {
        display: inline-block;
        padding: 7px 11px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        font-size: 12px;
        font-weight: 800;
    }

    .header-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .contact-button,
    .quiz-button {
        display: inline-flex;
        padding: 12px 18px;
        border-radius: 10px;

        align-items: center;
        justify-content: center;

        font-weight: 800;
        text-decoration: none;

        transition:
            transform 0.2s,
            background 0.2s;
    }

    .contact-button {
        background: #ffffff;
        color: #1e3a8a;
    }

    .contact-button:hover {
        background: #eff6ff;
        transform: translateY(-1px);
    }

    .quiz-button {
        background: #15803d;
        color: #ffffff;
    }

    .quiz-button:hover {
        background: #166534;
        transform: translateY(-1px);
    }

    .quiz-disabled {
        display: inline-flex;
        padding: 12px 18px;
        border-radius: 10px;

        align-items: center;
        justify-content: center;

        background: #e5e7eb;
        color: #6b7280;

        font-weight: 800;
    }

    .section-title {
        margin: 0 0 20px;
        color: #172554;
        font-size: 27px;
    }

    .lesson-card {
        margin-bottom: 18px;
        padding: 25px;
        border-radius: 17px;
        background: #ffffff;

        box-shadow:
            0 10px 30px
            rgba(30, 64, 175, 0.07);
    }

    .lesson-order {
        display: inline-block;
        margin-bottom: 8px;
        color: #1e3a8a;
        font-size: 13px;
        font-weight: 800;
    }

    .lesson-card h2 {
        margin: 0 0 20px;
        color: #172554;
        font-size: 22px;
    }

    .contents-list {
        display: grid;
        gap: 10px;
    }

    .content-item {
        display: flex;
        padding: 14px;

        border: 1px solid #e5e7eb;
        border-radius: 10px;

        align-items: center;
        justify-content: space-between;

        gap: 15px;

        background: #f9fafb;
    }

    .content-title {
        color: #1f2937;
        font-weight: 800;
    }

    .content-type {
        margin-top: 4px;
        color: #6b7280;
        font-size: 13px;
    }

    .content-button {
        display: inline-flex;
        padding: 9px 13px;
        border-radius: 8px;

        background: #1e3a8a;
        color: #ffffff;

        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
        white-space: nowrap;
    }

    .no-content {
        padding: 15px;
        border-radius: 10px;
        background: #f3f6fb;
        color: #6b7280;
    }

    .empty-state {
        padding: 50px 25px;
        border-radius: 17px;
        background: #ffffff;
        text-align: center;
    }

    .quiz-information {
        margin-top: 30px;
        padding: 25px;

        border: 1px solid #bfdbfe;
        border-radius: 15px;

        background: #eff6ff;
    }

    .quiz-information h2 {
        margin: 0 0 8px;
        color: #172554;
    }

    .quiz-information p {
        margin: 0 0 18px;
        color: #6b7280;
        line-height: 1.6;
    }

    .quiz-information-button {
        display: inline-flex;
        padding: 12px 18px;
        border-radius: 10px;

        align-items: center;
        justify-content: center;

        background: #15803d;
        color: #ffffff;

        font-weight: 800;
        text-decoration: none;
    }

    .quiz-information-button:hover {
        background: #166534;
    }

    @media (max-width: 650px) {

        .course-page {
            padding: 40px 20px 60px;
        }

        .content-item {
            align-items: flex-start;
            flex-direction: column;
        }

        .header-actions {
            flex-direction: column;
        }

        .contact-button,
        .quiz-button,
        .quiz-disabled {
            width: 100%;
        }

    }

</style>

@endpush


@section('content')

<section class="course-page">

    <div class="course-container">

        <a
            class="back-link"
            href="{{ route('etudiant.cours.index') }}"
        >
            ← Retour à mes cours
        </a>


        <header class="course-header">

            <h1>
                {{ $cours->titre_cours }}
            </h1>


            <p>
                {{
                    $cours->description
                    ?: 'Aucune description disponible.'
                }}
            </p>


            <div class="header-information">

                <span class="header-badge">

                    {{
                        $cours
                            ->filier
                            ?->nom_filier
                        ?? 'Filière'
                    }}

                </span>


                <span class="header-badge">

                    Formateur :

                    {{
                        $cours
                            ->formateur
                            ?->user
                            ?->nom
                        ?? 'Non renseigné'
                    }}

                </span>

            </div>


            <div class="header-actions">


                {{-- CONTACTER LE FORMATEUR --}}

                @if (
                    $cours->formateur
                    &&
                    $cours->formateur->user
                )

                    <a
                        class="contact-button"
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

                @endif


                {{-- PASSER LE QUIZ --}}

                @if ($quizDuCours)

                    <a
                        class="quiz-button"
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

                    <div class="quiz-disabled">
                        Aucun quiz disponible
                    </div>

                @endif

            </div>

        </header>


        <h2 class="section-title">
            Leçons du cours
        </h2>


        @forelse ($cours->lecons as $lecon)

            <article class="lesson-card">

                <span class="lesson-order">
                    Leçon {{ $lecon->ordre }}
                </span>


                <h2>
                    {{ $lecon->{'titre_leçon'} }}
                </h2>


                @if ($lecon->contenus->isEmpty())

                    <div class="no-content">

                        Aucun contenu n'est encore disponible
                        pour cette leçon.

                    </div>

                @else

                    <div class="contents-list">

                        @foreach (
                            $lecon->contenus
                            as $contenu
                        )

                            <div class="content-item">

                                <div>

                                    <div class="content-title">

                                        {{
                                            $contenu
                                                ->titre_contenu
                                        }}

                                    </div>


                                    <div class="content-type">

                                        Type :

                                        {{
                                            $contenu
                                                ->type_contenu
                                        }}

                                    </div>

                                </div>


                                <a
                                    class="content-button"
                                    href="{{
                                        asset(
                                            'storage/'
                                            . $contenu->fichier
                                        )
                                    }}"
                                    target="_blank"
                                >

                                    @if (
                                        $contenu->type_contenu
                                        === 'Vidéo'
                                    )

                                        Voir la vidéo

                                    @else

                                        Ouvrir le contenu

                                    @endif

                                </a>

                            </div>

                        @endforeach

                    </div>

                @endif

            </article>

        @empty

            <div class="empty-state">

                <h2>
                    Aucune leçon disponible
                </h2>

                <p>
                    Le formateur n'a pas encore ajouté
                    de leçon à ce cours.
                </p>

            </div>

        @endforelse


        {{-- ============================================== --}}
        {{-- QUIZ DU COURS --}}
        {{-- ============================================== --}}

        <section class="quiz-information">

            <h2>
                Quiz du cours
            </h2>


            @if ($quizDuCours)

                <p>
                    Le quiz
                    <strong>
                        {{ $quizDuCours->titre_quiz }}
                    </strong>
                    est disponible pour ce cours.
                </p>


                <a
                    class="quiz-information-button"
                    href="{{
                        route(
                            'etudiant.quiz.show',
                            $quizDuCours->id_quiz
                        )
                    }}"
                >
                    📝 Commencer le quiz
                </a>

            @else

                <p>
                    Aucun quiz n'est encore disponible
                    pour ce cours.
                </p>

            @endif

        </section>

    </div>

</section>

@endsection
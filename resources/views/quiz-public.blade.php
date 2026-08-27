@extends('layouts.site')

@section('title', 'Quiz - Plateforme de Suivi Pédagogique')

@push('styles')
<style>
    .quiz-hero {
        padding: 85px 30px 65px;
        background:
            linear-gradient(
                135deg,
                #eff6ff,
                #dbeafe
            );
        text-align: center;
    }

    .quiz-hero-container {
        width: 100%;
        max-width: 850px;
        margin: 0 auto;
    }

    .quiz-badge {
        display: inline-block;
        margin-bottom: 18px;
        padding: 9px 16px;
        border-radius: 30px;
        background: #ffffff;
        color: #1e3a8a;
        font-size: 14px;
        font-weight: 800;
    }

    .quiz-hero h1 {
        margin: 0 0 18px;
        color: #172554;
        font-size: 47px;
    }

    .quiz-hero p {
        max-width: 720px;
        margin: 0 auto;
        color: #4b5563;
        font-size: 18px;
        line-height: 1.8;
    }

    .quiz-section {
        min-height: 500px;
        padding: 75px 30px;
        background: #f3f6fb;
    }

    .quiz-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .quiz-alert {
        margin-bottom: 25px;
        padding: 16px 18px;
        border: 1px solid #fecaca;
        border-radius: 11px;
        background: #fef2f2;
        color: #b91c1c;
    }

    .quiz-toolbar {
        display: flex;
        margin-bottom: 32px;
        padding: 18px;
        border-radius: 15px;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        background: #ffffff;
        box-shadow:
            0 10px 30px
            rgba(30, 64, 175, 0.07);
    }

    .quiz-toolbar h2 {
        margin: 0;
        color: #172554;
        font-size: 24px;
    }

    .quiz-search {
        width: 100%;
        max-width: 380px;
        padding: 12px 14px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        outline: none;
        font-size: 15px;
    }

    .quiz-search:focus {
        border-color: #1e3a8a;
        box-shadow:
            0 0 0 3px
            rgba(30, 58, 138, 0.12);
    }

    .quiz-grid {
        display: grid;
        grid-template-columns:
            repeat(3, minmax(0, 1fr));
        gap: 25px;
    }

    .quiz-card {
        display: flex;
        overflow: hidden;
        min-height: 390px;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        flex-direction: column;
        background: #ffffff;
        box-shadow:
            0 12px 35px
            rgba(30, 64, 175, 0.08);
        transition: 0.25s;
    }

    .quiz-card:hover {
        transform: translateY(-6px);
        box-shadow:
            0 20px 45px
            rgba(30, 64, 175, 0.15);
    }

    .quiz-card-header {
        display: flex;
        height: 155px;
        padding: 25px;
        align-items: center;
        justify-content: center;
        background:
            linear-gradient(
                135deg,
                #1e3a8a,
                #2563eb
            );
        color: #ffffff;
        text-align: center;
    }

    .quiz-symbol {
        display: flex;
        width: 75px;
        height: 75px;
        border: 2px solid rgba(255, 255, 255, 0.55);
        border-radius: 50%;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.12);
        font-size: 40px;
        font-weight: 900;
    }

    .quiz-card-content {
        display: flex;
        padding: 24px;
        flex: 1;
        flex-direction: column;
    }

    .quiz-course {
        display: inline-block;
        align-self: flex-start;
        margin-bottom: 14px;
        padding: 7px 11px;
        border-radius: 25px;
        background: #dbeafe;
        color: #1e3a8a;
        font-size: 12px;
        font-weight: 800;
    }

    .quiz-card-content h3 {
        margin: 0 0 18px;
        color: #172554;
        font-size: 22px;
        line-height: 1.35;
    }

    .quiz-information {
        display: grid;
        margin-top: auto;
        margin-bottom: 20px;
        gap: 10px;
    }

    .quiz-information p {
        margin: 0;
        color: #4b5563;
        font-size: 14px;
    }

    .quiz-button {
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
        transition: 0.2s;
    }

    .quiz-button:hover {
        background: #172554;
    }

    .empty-quizzes {
        padding: 55px 25px;
        border-radius: 18px;
        background: #ffffff;
        text-align: center;
        box-shadow:
            0 10px 30px
            rgba(30, 64, 175, 0.07);
    }

    .empty-quizzes h2 {
        margin: 0 0 12px;
        color: #172554;
    }

    .empty-quizzes p {
        margin: 0;
        color: #6b7280;
    }

    .quiz-footer {
        padding: 38px 25px;
        background: #172554;
        color: #dbeafe;
        text-align: center;
    }

    .quiz-footer strong {
        color: #ffffff;
        font-size: 18px;
    }

    @media (max-width: 1000px) {
        .quiz-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .quiz-hero {
            padding: 60px 20px 45px;
        }

        .quiz-hero h1 {
            font-size: 37px;
        }

        .quiz-section {
            padding: 55px 20px;
        }

        .quiz-toolbar {
            align-items: stretch;
            flex-direction: column;
        }

        .quiz-search {
            max-width: none;
        }

        .quiz-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<section class="quiz-hero">
    <div class="quiz-hero-container">
        <span class="quiz-badge">
            Évaluation des connaissances
        </span>

        <h1>
            Testez vos connaissances
        </h1>

        <p>
            Découvrez les quiz à choix multiples proposés par les
            formateurs. Connectez-vous pour répondre aux questions
            et consulter vos résultats.
        </p>
    </div>
</section>

<section class="quiz-section">
    <div class="quiz-container">
        @if (session('quiz_error'))
            <div class="quiz-alert">
                {{ session('quiz_error') }}
            </div>
        @endif

        <div class="quiz-toolbar">
            <h2>
                {{ $quizzes->count() }}
                quiz disponible{{ $quizzes->count() > 1 ? 's' : '' }}
            </h2>

            <input
                class="quiz-search"
                id="quiz-search"
                type="search"
                placeholder="Rechercher un quiz..."
            >
        </div>

        @if ($quizzes->isEmpty())
            <div class="empty-quizzes">
                <h2>
                    Aucun quiz disponible
                </h2>

                <p>
                    Les quiz créés par les formateurs apparaîtront
                    automatiquement sur cette page.
                </p>
            </div>
        @else
            <div class="quiz-grid">
                @foreach ($quizzes as $quiz)
                    <article
                        class="quiz-card"
                        data-quiz-name="{{
                            mb_strtolower($quiz->titre_quiz)
                        }}"
                    >
                        <div class="quiz-card-header">
                            <div class="quiz-symbol">
                                ?
                            </div>
                        </div>

                        <div class="quiz-card-content">
                            <span class="quiz-course">
                                {{
                                    $quiz->cours?->titre_cours
                                    ?? 'Cours non renseigné'
                                }}
                            </span>

                            <h3>
                                {{ $quiz->titre_quiz }}
                            </h3>

                            <div class="quiz-information">
                                <p>
                                    <strong>Filière :</strong>

                                    {{
                                        $quiz->cours?->filier?->nom_filier
                                        ?? 'Non renseignée'
                                    }}
                                </p>

                                <p>
                                    <strong>Formateur :</strong>

                                    {{
                                        $quiz->cours?->formateur?->user?->nom
                                        ?? 'Non renseigné'
                                    }}
                                </p>

                                <p>
                                    <strong>Questions :</strong>

                                    {{ $quiz->questions_count }}
                                </p>
                            </div>

                            <a
                                class="quiz-button"
                                href="{{
                                    route(
                                        'quiz.show',
                                        $quiz->id_quiz
                                    )
                                }}"
                            >
                                Ouvrir le quiz
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>

<footer class="quiz-footer">
    <strong>
        Plateforme de Suivi Pédagogique
    </strong>

    <p>
        Apprendre, progresser et réussir.
    </p>
</footer>
@endsection

@push('scripts')
<script>
    const quizSearch = document.getElementById(
        'quiz-search'
    );

    const quizCards = document.querySelectorAll(
        '.quiz-card'
    );

    if (quizSearch) {
        quizSearch.addEventListener('input', function () {
            const search = this.value
                .toLowerCase()
                .trim();

            quizCards.forEach(function (card) {
                const quizName =
                    card.dataset.quizName;

                card.style.display =
                    quizName.includes(search)
                        ? 'flex'
                        : 'none';
            });
        });
    }
</script>
@endpush
@extends('layouts.site')

@section('title', $quiz->titre_quiz . ' - Quiz')

@push('styles')
<style>
    .quiz-detail-section {
        min-height: 650px;
        padding: 70px 30px;
        background: #f3f6fb;
    }

    .quiz-detail-container {
        width: 100%;
        max-width: 950px;
        margin: 0 auto;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 25px;
        color: #1e3a8a;
        font-weight: 800;
        text-decoration: none;
    }

    .quiz-detail-header {
        margin-bottom: 30px;
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

    .quiz-detail-header h1 {
        margin: 0 0 15px;
        font-size: 39px;
    }

    .quiz-detail-header p {
        margin: 0 0 20px;
        color: #dbeafe;
        font-size: 17px;
        line-height: 1.7;
    }

    .quiz-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .quiz-meta span {
        padding: 9px 13px;
        border: 1px solid rgba(255, 255, 255, 0.20);
        border-radius: 25px;
        background: rgba(255, 255, 255, 0.12);
    }

    .quiz-start-card {
        padding: 35px;
        border-radius: 20px;
        background: #ffffff;
        text-align: center;
        box-shadow:
            0 12px 35px
            rgba(30, 64, 175, 0.08);
    }

    .quiz-start-card h2 {
        margin: 0 0 14px;
        color: #172554;
        font-size: 28px;
    }

    .quiz-start-card p {
        max-width: 650px;
        margin: 0 auto 25px;
        color: #6b7280;
        line-height: 1.8;
    }

    .quiz-rules {
        display: grid;
        max-width: 650px;
        margin: 0 auto 28px;
        gap: 12px;
        text-align: left;
    }

    .quiz-rule {
        padding: 14px 16px;
        border-radius: 10px;
        background: #eff6ff;
        color: #1e3a8a;
    }

    .start-button {
        display: inline-flex;
        min-width: 220px;
        padding: 14px 24px;
        border: none;
        border-radius: 10px;
        align-items: center;
        justify-content: center;
        background: #1e3a8a;
        color: #ffffff;
        font-size: 16px;
        font-weight: 800;
        text-decoration: none;
        cursor: pointer;
    }

    .start-button:hover {
        background: #172554;
    }

    .no-question {
        padding: 17px;
        border: 1px solid #fde68a;
        border-radius: 10px;
        background: #fffbeb;
        color: #92400e;
    }

    @media (max-width: 650px) {
        .quiz-detail-section {
            padding: 50px 20px;
        }

        .quiz-detail-header {
            padding: 28px 22px;
        }

        .quiz-detail-header h1 {
            font-size: 31px;
        }

        .quiz-start-card {
            padding: 28px 20px;
        }
    }
</style>
@endpush

@section('content')
<section class="quiz-detail-section">
    <div class="quiz-detail-container">
        <a
            class="back-link"
            href="{{ route('quiz.public') }}"
        >
            ← Retour aux quiz
        </a>

        <header class="quiz-detail-header">
            <h1>
                {{ $quiz->titre_quiz }}
            </h1>

            <p>
                Ce quiz vous permet de tester vos connaissances
                sur le cours associé.
            </p>

            <div class="quiz-meta">
                <span>
                    Cours :
                    {{
                        $quiz->cours?->titre_cours
                        ?? 'Non renseigné'
                    }}
                </span>

                <span>
                    Filière :
                    {{
                        $quiz->cours?->filier?->nom_filier
                        ?? 'Non renseignée'
                    }}
                </span>

                <span>
                    Questions :
                    {{ $quiz->questions->count() }}
                </span>
            </div>
        </header>

        <section class="quiz-start-card">
            @if ($quiz->questions->isEmpty())
                <div class="no-question">
                    Ce quiz ne contient encore aucune question.
                </div>
            @else
                <h2>
                    Prête à commencer ?
                </h2>

                <p>
                    Lisez attentivement chaque question et choisissez
                    une réponse parmi les propositions disponibles.
                </p>

                <div class="quiz-rules">
                    <div class="quiz-rule">
                        Une seule réponse doit être sélectionnée
                        pour chaque question.
                    </div>

                    <div class="quiz-rule">
                        Votre score sera calculé après la validation
                        du quiz.
                    </div>

                    <div class="quiz-rule">
                        Le résultat sera enregistré dans votre espace
                        étudiant.
                    </div>
                </div>

                <button
                    class="start-button"
                    type="button"
                >
                    Commencer le quiz
                </button>
            @endif
        </section>
    </div>
</section>
@endsection
@extends('layouts.dashboard')

@section(
    'title',
    'Mes quiz - Espace étudiant'
)

@push('styles')

<style>

    .quiz-page {
        min-height: 650px;
        padding: 50px 30px 80px;
        background: #f3f6fb;
    }

    .quiz-container {
        width: 100%;
        max-width: 1100px;
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

    .message {
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 10px;
    }

    .error {
        background: #fef2f2;
        color: #b91c1c;
    }

    .quiz-grid {
        display: grid;

        grid-template-columns:
            repeat(
                3,
                minmax(0, 1fr)
            );

        gap: 22px;
    }

    .quiz-card {
        display: flex;
        min-height: 280px;
        padding: 24px;
        border-radius: 17px;
        flex-direction: column;
        background: #ffffff;

        box-shadow:
            0 10px 30px
            rgba(30, 64, 175, 0.08);
    }

    .course-badge {
        align-self: flex-start;
        margin-bottom: 14px;
        padding: 7px 10px;
        border-radius: 20px;
        background: #dbeafe;
        color: #1e3a8a;
        font-size: 12px;
        font-weight: 800;
    }

    .quiz-card h2 {
        margin: 0 0 10px;
        color: #172554;
    }

    .quiz-card p {
        margin: 0 0 15px;
        color: #6b7280;
    }

    .quiz-status {
        margin-bottom: 17px;
        padding: 9px 11px;
        border-radius: 9px;
        background: #f0fdf4;
        color: #15803d;
        font-size: 13px;
        font-weight: 800;
    }

    .quiz-button {
        display: flex;
        margin-top: auto;
        padding: 13px;
        border-radius: 10px;
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

    .retry-button {
        background: #2563eb;
    }

    .retry-button:hover {
        background: #1d4ed8;
    }

    .unavailable {
        margin-top: auto;
        padding: 13px;
        border-radius: 10px;
        background: #f3f4f6;
        color: #6b7280;
        font-weight: 800;
        text-align: center;
    }

    .empty {
        padding: 55px;
        border-radius: 18px;
        background: #ffffff;
        text-align: center;
    }

    @media (max-width: 1000px) {

        .quiz-grid {
            grid-template-columns: 1fr 1fr;
        }

    }

    @media (max-width: 650px) {

        .quiz-grid {
            grid-template-columns: 1fr;
        }

    }

</style>

@endpush


@section('content')

<section class="quiz-page">

    <div class="quiz-container">


        <header class="page-header">

            <h1>
                Mes quiz
            </h1>

            <p>
                Retrouvez les quiz correspondant
                aux cours de votre filière.
                Vous pouvez refaire un quiz pour
                améliorer votre score.
            </p>

        </header>


        @if (session('error'))

            <div class="message error">

                {{ session('error') }}

            </div>

        @endif


        @if ($quiz->isEmpty())

            <div class="empty">

                <h2>
                    Aucun quiz disponible
                </h2>

                <p>
                    Aucun quiz n'a encore été créé
                    pour les cours de votre filière.
                </p>

            </div>

        @else


            <div class="quiz-grid">


                @foreach ($quiz as $unQuiz)


                    <article class="quiz-card">


                        <span class="course-badge">

                            {{
                                $unQuiz
                                    ->cours
                                    ?->titre_cours
                                ?? 'Cours'
                            }}

                        </span>


                        <h2>

                            {{ $unQuiz->titre_quiz }}

                        </h2>


                        <p>

                            {{ $unQuiz->questions_count }}

                            question{{
                                $unQuiz->questions_count > 1
                                    ? 's'
                                    : ''
                            }}

                        </p>


                        @php

                            $dejaRealise =
                                in_array(
                                    $unQuiz->id_quiz,
                                    $quizRealises
                                );

                        @endphp


                        @if ($dejaRealise)

                            <div class="quiz-status">

                                ✓ Vous avez déjà réalisé
                                ce quiz.

                            </div>

                        @endif


                        @if (
                            $unQuiz->questions_count === 0
                        )

                            <div class="unavailable">

                                Quiz pas encore disponible

                            </div>


                        @elseif ($dejaRealise)


                            <a
                                class="quiz-button retry-button"

                                href="{{
                                    route(
                                        'etudiant.quiz.show',
                                        $unQuiz->id_quiz
                                    )
                                }}"
                            >

                                Refaire le quiz

                            </a>


                        @else


                            <a
                                class="quiz-button"

                                href="{{
                                    route(
                                        'etudiant.quiz.show',
                                        $unQuiz->id_quiz
                                    )
                                }}"
                            >

                                Commencer le quiz

                            </a>


                        @endif


                    </article>


                @endforeach


            </div>


        @endif


    </div>

</section>

@endsection
@extends('layouts.dashboard')

@section(
    'title',
    $quiz->titre_quiz . ' - Quiz'
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
        max-width: 850px;
        margin: 0 auto;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 20px;
        color: #1e3a8a;
        font-weight: 800;
        text-decoration: none;
    }

    .quiz-header {
        margin-bottom: 20px;
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

    .quiz-header h1 {
        margin: 0 0 8px;
    }

    .quiz-header p {
        margin: 0;
        color: #dbeafe;
    }

    .quiz-info {
        display: flex;
        margin-bottom: 20px;
        gap: 15px;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .progress-box,
    .timer-box {
        padding: 13px 18px;
        border-radius: 12px;
        background: #ffffff;

        box-shadow:
            0 6px 20px
            rgba(30, 64, 175, 0.08);

        font-weight: 800;
    }

    .progress-box {
        color: #1e3a8a;
    }

    .timer-box {
        min-width: 150px;
        color: #b45309;
        text-align: center;
    }

    .timer-box.timer-danger {
        background: #fef2f2;
        color: #b91c1c;
    }

    .progress-bar-container {
        width: 100%;
        height: 10px;
        margin-bottom: 25px;
        overflow: hidden;
        border-radius: 20px;
        background: #dbeafe;
    }

    .progress-bar {
        width: 0;
        height: 100%;
        border-radius: 20px;
        background: #2563eb;
        transition: width 0.3s ease;
    }

    .question-card {
        display: none;
        margin-bottom: 20px;
        padding: 30px;
        border-radius: 16px;
        background: #ffffff;

        box-shadow:
            0 8px 25px
            rgba(30, 64, 175, 0.06);
    }

    .question-card.active {
        display: block;
    }

    .question-number {
        color: #1e3a8a;
        font-size: 13px;
        font-weight: 800;
    }

    .question-card h2 {
        margin: 10px 0 22px;
        color: #172554;
        font-size: 21px;
        line-height: 1.5;
    }

    .answers-container {
        display: grid;
        gap: 12px;
    }

    .answer {
        display: flex;
        padding: 15px;
        border: 2px solid #e5e7eb;
        border-radius: 11px;

        align-items: flex-start;
        gap: 10px;

        background: #ffffff;

        cursor: pointer;

        transition:
            border-color 0.2s,
            background 0.2s;
    }

    .answer:hover {
        border-color: #93c5fd;
        background: #eff6ff;
    }

    .answer.selected {
        border-color: #2563eb;
        background: #eff6ff;
    }

    .answer input {
        margin-top: 3px;
        accent-color: #2563eb;
    }

    .answer-letter {
        min-width: 22px;
        color: #1e3a8a;
        font-weight: 900;
    }

    .navigation-buttons {
        display: flex;
        margin-top: 25px;
        gap: 12px;
        justify-content: space-between;
    }

    .nav-button,
    .submit-button {
        min-width: 150px;
        padding: 14px 20px;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 800;
        cursor: pointer;
    }

    .previous-button {
        background: #e5e7eb;
        color: #1f2937;
    }

    .next-button {
        margin-left: auto;
        background: #1e3a8a;
        color: #ffffff;
    }

    .submit-button {
        margin-left: auto;
        background: #15803d;
        color: #ffffff;
    }

    .nav-button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .answer-error {
        display: none;
        margin-top: 18px;
        padding: 12px 15px;
        border-radius: 10px;
        background: #fef2f2;
        color: #b91c1c;
        font-weight: 700;
    }

    .answer-error.visible {
        display: block;
    }

    .question-dots {
        display: flex;
        margin: 20px 0;
        gap: 7px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .question-dot {
        width: 30px;
        height: 30px;
        border: none;
        border-radius: 50%;

        background: #dbeafe;
        color: #1e3a8a;

        font-size: 12px;
        font-weight: 800;

        cursor: pointer;
    }

    .question-dot.current {
        background: #1e3a8a;
        color: #ffffff;
    }

    .question-dot.answered {
        background: #dcfce7;
        color: #15803d;
    }

    .question-dot.current.answered {
        background: #1e3a8a;
        color: #ffffff;
    }

    @media (max-width: 650px) {
        .quiz-page {
            padding: 35px 18px 60px;
        }

        .quiz-header {
            padding: 22px;
        }

        .question-card {
            padding: 22px;
        }

        .quiz-info {
            align-items: stretch;
            flex-direction: column;
        }

        .timer-box,
        .progress-box {
            width: 100%;
        }

        .navigation-buttons {
            flex-direction: column;
        }

        .nav-button,
        .submit-button {
            width: 100%;
            margin-left: 0;
        }
    }
</style>
@endpush


@section('content')

<section class="quiz-page">

    <div class="quiz-container">

        <a
            class="back-link"
            href="{{ route('etudiant.quiz.index') }}"
            onclick="
                return confirm(
                    'Voulez-vous vraiment quitter le quiz ? Vos réponses ne seront pas enregistrées.'
                );
            "
        >
            ← Retour à mes quiz
        </a>


        <header class="quiz-header">

            <h1>
                {{ $quiz->titre_quiz }}
            </h1>

            <p>
                Cours :
                {{ $quiz->cours?->titre_cours }}
            </p>

        </header>


        <div class="quiz-info">

            <div
                id="progress-text"
                class="progress-box"
            >
                Question 1 / {{ $quiz->questions->count() }}
            </div>


            <div
                id="timer"
                class="timer-box"
                aria-live="polite"
            >
                Temps restant : 20:00
            </div>

        </div>


        <div class="progress-bar-container">

            <div
                id="progress-bar"
                class="progress-bar"
            ></div>

        </div>


        <div
            id="question-dots"
            class="question-dots"
        >

            @foreach ($quiz->questions as $question)

                <button
                    type="button"
                    class="question-dot"
                    data-question-index="{{ $loop->index }}"
                    title="Question {{ $loop->iteration }}"
                >
                    {{ $loop->iteration }}
                </button>

            @endforeach

        </div>


        <form
            id="quiz-form"
            method="POST"
            action="{{
                route(
                    'etudiant.quiz.submit',
                    $quiz->id_quiz
                )
            }}"
        >

            @csrf


            @foreach ($quiz->questions as $question)

                <article
                    class="question-card"
                    data-question-index="{{ $loop->index }}"
                >

                    <span class="question-number">
                        Question {{ $loop->iteration }}
                    </span>


                    <h2>
                        {{ $question->texte_question }}
                    </h2>


                    <div class="answers-container">

                        <label class="answer">

                            <input
                                type="radio"
                                name="reponses[{{ $question->id_question }}]"
                                value="A"
                            >

                            <span class="answer-letter">
                                A.
                            </span>

                            <span>
                                {{ $question->choix_a }}
                            </span>

                        </label>


                        <label class="answer">

                            <input
                                type="radio"
                                name="reponses[{{ $question->id_question }}]"
                                value="B"
                            >

                            <span class="answer-letter">
                                B.
                            </span>

                            <span>
                                {{ $question->choix_b }}
                            </span>

                        </label>


                        <label class="answer">

                            <input
                                type="radio"
                                name="reponses[{{ $question->id_question }}]"
                                value="C"
                            >

                            <span class="answer-letter">
                                C.
                            </span>

                            <span>
                                {{ $question->choix_c }}
                            </span>

                        </label>


                        <label class="answer">

                            <input
                                type="radio"
                                name="reponses[{{ $question->id_question }}]"
                                value="D"
                            >

                            <span class="answer-letter">
                                D.
                            </span>

                            <span>
                                {{ $question->choix_d }}
                            </span>

                        </label>

                    </div>


                    <div class="answer-error">
                        Veuillez sélectionner une réponse
                        avant de continuer.
                    </div>

                </article>

            @endforeach


            <div class="navigation-buttons">

                <button
                    id="previous-button"
                    class="nav-button previous-button"
                    type="button"
                >
                    ← Précédent
                </button>


                <button
                    id="next-button"
                    class="nav-button next-button"
                    type="button"
                >
                    Suivant →
                </button>


                <button
                    id="submit-button"
                    class="submit-button"
                    type="button"
                    style="display: none;"
                >
                    Terminer le quiz
                </button>

            </div>

        </form>

    </div>

</section>

@endsection


@push('scripts')

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {

            /*
            |--------------------------------------------------------------------------
            | ÉLÉMENTS
            |--------------------------------------------------------------------------
            */

            const form =
                document.getElementById(
                    'quiz-form'
                );

            const questionCards =
                Array.from(
                    document.querySelectorAll(
                        '.question-card'
                    )
                );

            const questionDots =
                Array.from(
                    document.querySelectorAll(
                        '.question-dot'
                    )
                );

            const previousButton =
                document.getElementById(
                    'previous-button'
                );

            const nextButton =
                document.getElementById(
                    'next-button'
                );

            const submitButton =
                document.getElementById(
                    'submit-button'
                );

            const progressText =
                document.getElementById(
                    'progress-text'
                );

            const progressBar =
                document.getElementById(
                    'progress-bar'
                );

            const timerElement =
                document.getElementById(
                    'timer'
                );


            /*
            |--------------------------------------------------------------------------
            | ÉTAT DU QUIZ
            |--------------------------------------------------------------------------
            */

            let currentQuestion = 0;

            const totalQuestions =
                questionCards.length;

            let quizSubmitted = false;


            /*
            |--------------------------------------------------------------------------
            | MINUTEUR
            |--------------------------------------------------------------------------
            |
            | 20 minutes = 1200 secondes.
            |
            | Pour modifier plus tard :
            |
            | 15 minutes = 15 * 60
            | 20 minutes = 20 * 60
            | 30 minutes = 30 * 60
            |
            */

            const quizDuration =
          10 * 60;

            const storageKey =
                'quiz_timer_{{ $quiz->id_quiz }}';


            /*
            | On mémorise l'heure de fin.
            | Ainsi, actualiser la page ne remet pas
            | automatiquement le compteur à 20 minutes.
            */

            let endTime =
                localStorage.getItem(
                    storageKey
                );


            if (! endTime) {

                endTime =
                    Date.now()
                    +
                    (
                        quizDuration
                        * 1000
                    );


                localStorage.setItem(
                    storageKey,
                    endTime
                );
            }


            /*
            |--------------------------------------------------------------------------
            | AFFICHAGE D'UNE QUESTION
            |--------------------------------------------------------------------------
            */

            function showQuestion(
                index
            ) {

                if (
                    index < 0
                    ||
                    index >= totalQuestions
                ) {
                    return;
                }


                currentQuestion =
                    index;


                questionCards.forEach(
                    function (
                        card,
                        cardIndex
                    ) {

                        card.classList.toggle(
                            'active',
                            cardIndex
                            ===
                            currentQuestion
                        );
                    }
                );


                /*
                | Numéro de question.
                */

                progressText.textContent =
                    'Question '
                    + (
                        currentQuestion
                        + 1
                    )
                    + ' / '
                    + totalQuestions;


                /*
                | Barre de progression.
                */

                const percentage =
                    (
                        (
                            currentQuestion
                            + 1
                        )
                        /
                        totalQuestions
                    )
                    * 100;


                progressBar.style.width =
                    percentage
                    + '%';


                /*
                | Boutons.
                */

                previousButton.disabled =
                    currentQuestion
                    === 0;


                if (
                    currentQuestion
                    ===
                    totalQuestions - 1
                ) {

                    nextButton.style.display =
                        'none';

                    submitButton.style.display =
                        'block';

                } else {

                    nextButton.style.display =
                        'block';

                    submitButton.style.display =
                        'none';
                }


                updateDots();


                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }


            /*
            |--------------------------------------------------------------------------
            | QUESTION RÉPONDUE ?
            |--------------------------------------------------------------------------
            */

            function questionIsAnswered(
                index
            ) {

                const card =
                    questionCards[index];


                if (! card) {
                    return false;
                }


                return Boolean(
                    card.querySelector(
                        'input[type="radio"]:checked'
                    )
                );
            }


            /*
            |--------------------------------------------------------------------------
            | MESSAGE D'ERREUR
            |--------------------------------------------------------------------------
            */

            function showAnswerError(
                index
            ) {

                const card =
                    questionCards[index];


                if (! card) {
                    return;
                }


                const error =
                    card.querySelector(
                        '.answer-error'
                    );


                if (error) {
                    error.classList.add(
                        'visible'
                    );
                }
            }


            function hideAnswerError(
                index
            ) {

                const card =
                    questionCards[index];


                if (! card) {
                    return;
                }


                const error =
                    card.querySelector(
                        '.answer-error'
                    );


                if (error) {
                    error.classList.remove(
                        'visible'
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | PETITS NUMÉROS DES QUESTIONS
            |--------------------------------------------------------------------------
            */

            function updateDots() {

                questionDots.forEach(
                    function (
                        dot,
                        index
                    ) {

                        dot.classList.toggle(
                            'current',
                            index
                            ===
                            currentQuestion
                        );


                        dot.classList.toggle(
                            'answered',
                            questionIsAnswered(
                                index
                            )
                        );
                    }
                );
            }


            /*
            |--------------------------------------------------------------------------
            | STYLE DE LA RÉPONSE SÉLECTIONNÉE
            |--------------------------------------------------------------------------
            */

            questionCards.forEach(
                function (
                    card,
                    cardIndex
                ) {

                    const radios =
                        card.querySelectorAll(
                            'input[type="radio"]'
                        );


                    radios.forEach(
                        function (radio) {

                            radio.addEventListener(
                                'change',
                                function () {

                                    const answers =
                                        card.querySelectorAll(
                                            '.answer'
                                        );


                                    answers.forEach(
                                        function (
                                            answer
                                        ) {

                                            answer.classList.remove(
                                                'selected'
                                            );
                                        }
                                    );


                                    const label =
                                        radio.closest(
                                            '.answer'
                                        );


                                    if (label) {

                                        label.classList.add(
                                            'selected'
                                        );
                                    }


                                    hideAnswerError(
                                        cardIndex
                                    );


                                    updateDots();
                                }
                            );
                        }
                    );
                }
            );


            /*
            |--------------------------------------------------------------------------
            | SUIVANT
            |--------------------------------------------------------------------------
            */

            nextButton.addEventListener(
                'click',
                function () {

                    if (
                        ! questionIsAnswered(
                            currentQuestion
                        )
                    ) {

                        showAnswerError(
                            currentQuestion
                        );

                        return;
                    }


                    hideAnswerError(
                        currentQuestion
                    );


                    showQuestion(
                        currentQuestion + 1
                    );
                }
            );


            /*
            |--------------------------------------------------------------------------
            | PRÉCÉDENT
            |--------------------------------------------------------------------------
            */

            previousButton.addEventListener(
                'click',
                function () {

                    showQuestion(
                        currentQuestion - 1
                    );
                }
            );


            /*
            |--------------------------------------------------------------------------
            | ACCÈS AVEC LES NUMÉROS
            |--------------------------------------------------------------------------
            |
            | On permet de revenir sur les questions
            | précédentes ou déjà répondues.
            |
            */

            questionDots.forEach(
                function (
                    dot,
                    index
                ) {

                    dot.addEventListener(
                        'click',
                        function () {

                            /*
                            | On autorise toujours
                            | le retour vers une question
                            | précédente.
                            */

                            if (
                                index
                                <
                                currentQuestion
                            ) {

                                showQuestion(
                                    index
                                );

                                return;
                            }


                            /*
                            | Pour avancer vers une autre
                            | question, la question actuelle
                            | doit être répondue.
                            */

                            if (
                                ! questionIsAnswered(
                                    currentQuestion
                                )
                            ) {

                                showAnswerError(
                                    currentQuestion
                                );

                                return;
                            }


                            showQuestion(
                                index
                            );
                        }
                    );
                }
            );


            /*
            |--------------------------------------------------------------------------
            | TERMINER LE QUIZ
            |--------------------------------------------------------------------------
            */

            submitButton.addEventListener(
                'click',
                function () {

                    /*
                    | Vérifier toutes les questions.
                    */

                    let premiereQuestionSansReponse =
                        -1;


                    for (
                        let i = 0;
                        i < totalQuestions;
                        i++
                    ) {

                        if (
                            ! questionIsAnswered(i)
                        ) {

                            premiereQuestionSansReponse =
                                i;

                            break;
                        }
                    }


                    if (
                        premiereQuestionSansReponse
                        !== -1
                    ) {

                        showQuestion(
                            premiereQuestionSansReponse
                        );


                        showAnswerError(
                            premiereQuestionSansReponse
                        );


                        return;
                    }


                    const confirmation =
                        confirm(
                            'Voulez-vous vraiment terminer le quiz et valider toutes vos réponses ?'
                        );


                    if (! confirmation) {
                        return;
                    }


                    quizSubmitted = true;


                    localStorage.removeItem(
                        storageKey
                    );


                    form.submit();
                }
            );


            /*
            |--------------------------------------------------------------------------
            | MINUTEUR
            |--------------------------------------------------------------------------
            */

            function updateTimer() {

                const now =
                    Date.now();


                let remainingSeconds =
                    Math.floor(
                        (
                            endTime
                            - now
                        )
                        / 1000
                    );


                /*
                | Temps écoulé.
                */

                if (
                    remainingSeconds
                    <= 0
                ) {

                    timerElement.textContent =
                        'Temps écoulé : 00:00';


                    timerElement.classList.add(
                        'timer-danger'
                    );


                    localStorage.removeItem(
                        storageKey
                    );


                    /*
                    | Important :
                    | le contrôleur exige actuellement
                    | toutes les réponses.
                    |
                    | Donc pour chaque question
                    | sans réponse, on envoie une valeur
                    | vide ? NON.
                    |
                    | Nous déclenchons le submit normal.
                    */

                    quizSubmitted = true;


                    form.submit();

                    return;
                }


                const minutes =
                    Math.floor(
                        remainingSeconds
                        / 60
                    );


                const seconds =
                    remainingSeconds
                    % 60;


                const formattedMinutes =
                    String(
                        minutes
                    ).padStart(
                        2,
                        '0'
                    );


                const formattedSeconds =
                    String(
                        seconds
                    ).padStart(
                        2,
                        '0'
                    );


                timerElement.textContent =
                    'Temps restant : '
                    + formattedMinutes
                    + ':'
                    + formattedSeconds;


                /*
                | Rouge pendant la dernière minute.
                */

                if (
                    remainingSeconds
                    <= 60
                ) {

                    timerElement.classList.add(
                        'timer-danger'
                    );

                } else {

                    timerElement.classList.remove(
                        'timer-danger'
                    );
                }
            }


            updateTimer();


            const timerInterval =
                setInterval(
                    function () {

                        if (quizSubmitted) {

                            clearInterval(
                                timerInterval
                            );

                            return;
                        }


                        updateTimer();

                    },
                    1000
                );


            /*
            |--------------------------------------------------------------------------
            | INITIALISATION
            |--------------------------------------------------------------------------
            */

            showQuestion(0);

        }
    );
</script>

@endpush
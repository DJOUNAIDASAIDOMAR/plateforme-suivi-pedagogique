@extends('layouts.dashboard')

@section(
    'title',
    'Questions - ' . $quiz->titre_quiz
)

@push('styles')
<style>
    .page {
        min-height: 650px;
        padding: 50px 30px 80px;
        background: #f3f6fb;
    }

    .container {
        width: 100%;
        max-width: 950px;
        margin: 0 auto;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 20px;
        color: #1e3a8a;
        font-weight: 800;
        text-decoration: none;
    }

    .header {
        margin-bottom: 25px;
        padding: 28px;
        border-radius: 18px;
        background: #1e3a8a;
        color: #ffffff;
    }

    .header h1 {
        margin: 0 0 8px;
    }

    .header p {
        margin: 0;
        color: #dbeafe;
    }

    .form-card,
    .question-card {
        margin-bottom: 20px;
        padding: 28px;
        border-radius: 16px;
        background: #ffffff;
    }

    .form-card h2 {
        margin-top: 0;
        color: #172554;
    }

    .form-group {
        margin-bottom: 17px;
    }

    label {
        display: block;
        margin-bottom: 7px;
        font-weight: 800;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border: 1px solid #d1d5db;
        border-radius: 9px;
        font: inherit;
    }

    .choices-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .submit-button {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 10px;
        background: #1e3a8a;
        color: #ffffff;
        font-weight: 800;
        cursor: pointer;
    }

    .question-card h3 {
        margin: 0 0 17px;
        color: #172554;
    }

    .choice {
        margin-bottom: 8px;
        padding: 10px;
        border-radius: 8px;
        background: #f3f6fb;
    }

    .correct {
        border: 1px solid #86efac;
        background: #f0fdf4;
    }

    .actions {
        display: flex;
        margin-top: 16px;
        gap: 8px;
    }

    .edit-button,
    .delete-button {
        padding: 9px 12px;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
    }

    .edit-button {
        background: #dbeafe;
        color: #1e3a8a;
    }

    .delete-button {
        background: #fee2e2;
        color: #b91c1c;
    }

    .message {
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 10px;
        background: #f0fdf4;
        color: #15803d;
    }

    @media (max-width: 700px) {
        .choices-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<section class="page">

    <div class="container">

        <a
            class="back-link"
            href="{{ route('formateur.quiz.manage') }}"
        >
            ← Retour aux quiz
        </a>

        <header class="header">

            <h1>
                {{ $quiz->titre_quiz }}
            </h1>

            <p>
                Cours :
                {{ $quiz->cours?->titre_cours }}
            </p>

        </header>

        @if (session('success'))
            <div class="message">
                {{ session('success') }}
            </div>
        @endif

        <section class="form-card">

            <h2>
                Ajouter une question
            </h2>

            @if ($errors->any())

                <div style="
                    margin-bottom: 20px;
                    padding: 15px;
                    border-radius: 10px;
                    background: #fef2f2;
                    color: #b91c1c;
                ">

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            @endif

            <form
                method="POST"
                action="{{
                    route(
                        'formateur.quiz.questions.store',
                        $quiz->id_quiz
                    )
                }}"
            >

                @csrf

                <div class="form-group">

                    <label for="texte_question">
                        Question
                    </label>

                    <input
                        class="form-control"
                        id="texte_question"
                        type="text"
                        name="texte_question"
                        value="{{ old('texte_question') }}"
                        required
                    >

                </div>

                <div class="choices-grid">

                    <div class="form-group">

                        <label for="choix_a">
                            Choix A
                        </label>

                        <input
                            class="form-control"
                            id="choix_a"
                            name="choix_a"
                            value="{{ old('choix_a') }}"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label for="choix_b">
                            Choix B
                        </label>

                        <input
                            class="form-control"
                            id="choix_b"
                            name="choix_b"
                            value="{{ old('choix_b') }}"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label for="choix_c">
                            Choix C
                        </label>

                        <input
                            class="form-control"
                            id="choix_c"
                            name="choix_c"
                            value="{{ old('choix_c') }}"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label for="choix_d">
                            Choix D
                        </label>

                        <input
                            class="form-control"
                            id="choix_d"
                            name="choix_d"
                            value="{{ old('choix_d') }}"
                            required
                        >

                    </div>

                </div>

                <div class="form-group">

                    <label for="bonne_reponse">
                        Bonne réponse
                    </label>

                    <select
                        class="form-control"
                        id="bonne_reponse"
                        name="bonne_reponse"
                        required
                    >

                        <option value="">
                            Sélectionnez la bonne réponse
                        </option>

                        <option value="A">
                            A
                        </option>

                        <option value="B">
                            B
                        </option>

                        <option value="C">
                            C
                        </option>

                        <option value="D">
                            D
                        </option>

                    </select>

                </div>

                <button
                    class="submit-button"
                    type="submit"
                >
                    Ajouter la question
                </button>

            </form>

        </section>

        @foreach ($questions as $question)

            <article class="question-card">

                <h3>
                    Question {{ $loop->iteration }} :
                    {{ $question->texte_question }}
                </h3>

                <div class="choice {{
                    $question->bonne_reponse === 'A'
                        ? 'correct'
                        : ''
                }}">
                    A. {{ $question->choix_a }}
                </div>

                <div class="choice {{
                    $question->bonne_reponse === 'B'
                        ? 'correct'
                        : ''
                }}">
                    B. {{ $question->choix_b }}
                </div>

                <div class="choice {{
                    $question->bonne_reponse === 'C'
                        ? 'correct'
                        : ''
                }}">
                    C. {{ $question->choix_c }}
                </div>

                <div class="choice {{
                    $question->bonne_reponse === 'D'
                        ? 'correct'
                        : ''
                }}">
                    D. {{ $question->choix_d }}
                </div>

                <div class="actions">

                    <a
                        class="edit-button"
                        href="{{
                            route(
                                'formateur.quiz.questions.edit',
                                [
                                    $quiz->id_quiz,
                                    $question->id_question
                                ]
                            )
                        }}"
                    >
                        Modifier
                    </a>

                    <form
                        method="POST"
                        action="{{
                            route(
                                'formateur.quiz.questions.destroy',
                                [
                                    $quiz->id_quiz,
                                    $question->id_question
                                ]
                            )
                        }}"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            class="delete-button"
                            type="submit"
                        >
                            Supprimer
                        </button>

                    </form>

                </div>

            </article>

        @endforeach

    </div>

</section>

@endsection
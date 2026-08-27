@extends('layouts.dashboard')

@section(
    'title',
    'Gérer les quiz - Espace formateur'
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
        max-width: 1050px;
        margin: 0 auto;
    }

    .page-header {
        margin-bottom: 25px;
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

    .success {
        background: #f0fdf4;
        color: #15803d;
    }

    .error {
        background: #fef2f2;
        color: #b91c1c;
    }

    .form-card {
        margin-bottom: 35px;
        padding: 32px;
        border-radius: 18px;
        background: #ffffff;

        box-shadow:
            0 12px 35px
            rgba(30, 64, 175, 0.08);
    }

    .form-card h2 {
        margin: 0 0 8px;
        color: #172554;
    }

    .description {
        margin: 0 0 25px;
        color: #6b7280;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 800;
    }

    .form-control {
        width: 100%;
        padding: 13px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        font: inherit;
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

    .quiz-title {
        margin-bottom: 20px;
        color: #172554;
    }

    .quiz-card {
        display: flex;
        margin-bottom: 15px;
        padding: 20px;
        border-radius: 14px;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        background: #ffffff;
    }

    .badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 20px;
        background: #dbeafe;
        color: #1e3a8a;
        font-size: 12px;
        font-weight: 800;
    }

    .quiz-card h3 {
        margin: 8px 0;
        color: #172554;
    }

    .quiz-card p {
        margin: 0;
        color: #6b7280;
    }

    .actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .questions-button,
    .edit-button,
    .delete-button {
        padding: 9px 12px;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
    }

    .questions-button {
        background: #1e3a8a;
        color: #ffffff;
    }

    .edit-button {
        background: #dbeafe;
        color: #1e3a8a;
    }

    .delete-button {
        background: #fee2e2;
        color: #b91c1c;
    }

    .empty {
        padding: 45px;
        border-radius: 15px;
        background: #ffffff;
        text-align: center;
    }
</style>
@endpush

@section('content')

<section class="quiz-page">

    <div class="quiz-container">

        <header class="page-header">

            <h1>
                Gérer les quiz
            </h1>

            <p>
                Sélectionnez un cours qui vous est attribué
                puis créez un quiz QCM.
            </p>

        </header>

        @if (session('success'))
            <div class="message success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="message error">
                {{ session('error') }}
            </div>
        @endif

        <section class="form-card">

            <h2>
                Créer un quiz
            </h2>

            <p class="description">
                Le quiz sera automatiquement rattaché
                au cours sélectionné.
            </p>

            @if ($errors->any())

                <div class="message error">

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>

                </div>

            @endif

            @if ($cours->isEmpty())

                <div class="empty">

                    <h3>
                        Aucun cours attribué
                    </h3>

                    <p>
                        Le responsable doit d'abord
                        vous attribuer un cours.
                    </p>

                </div>

            @else

                <form
                    method="POST"
                    action="{{ route('formateur.quiz.store') }}"
                >

                    @csrf

                    <div class="form-group">

                        <label for="id_cours">
                            Cours
                        </label>

                        <select
                            class="form-control"
                            id="id_cours"
                            name="id_cours"
                            required
                        >

                            <option value="">
                                Sélectionnez un cours
                            </option>

                            @foreach ($cours as $cour)

                                <option
                                    value="{{ $cour->id_cours }}"
                                    {{
                                        (string) old('id_cours')
                                        === (string) $cour->id_cours
                                            ? 'selected'
                                            : ''
                                    }}
                                >
                                    {{ $cour->titre_cours }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="form-group">

                        <label for="titre_quiz">
                            Titre du quiz
                        </label>

                        <input
                            class="form-control"
                            id="titre_quiz"
                            type="text"
                            name="titre_quiz"
                            value="{{ old('titre_quiz') }}"
                            placeholder="Exemple : Quiz HTML"
                            required
                        >

                    </div>

                    <button
                        class="submit-button"
                        type="submit"
                    >
                        Créer le quiz
                    </button>

                </form>

            @endif

        </section>

        <h2 class="quiz-title">
            Mes quiz
        </h2>

        @forelse ($quiz as $unQuiz)

            <article class="quiz-card">

                <div>

                    <span class="badge">
                        {{
                            $unQuiz->cours?->titre_cours
                            ?? 'Cours'
                        }}
                    </span>

                    <h3>
                        {{ $unQuiz->titre_quiz }}
                    </h3>

                    <p>
                        {{ $unQuiz->questions_count }}
                        question{{
                            $unQuiz->questions_count > 1
                                ? 's'
                                : ''
                        }}
                    </p>

                </div>

                <div class="actions">

                    <a
                        class="questions-button"
                        href="{{
                            route(
                                'formateur.quiz.questions',
                                $unQuiz->id_quiz
                            )
                        }}"
                    >
                        Gérer les questions
                    </a>

                    <a
                        class="edit-button"
                        href="{{
                            route(
                                'formateur.quiz.edit',
                                $unQuiz->id_quiz
                            )
                        }}"
                    >
                        Modifier
                    </a>

                    <form
                        method="POST"
                        action="{{
                            route(
                                'formateur.quiz.destroy',
                                $unQuiz->id_quiz
                            )
                        }}"
                        onsubmit="
                            return confirm(
                                'Voulez-vous supprimer ce quiz ?'
                            );
                        "
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

        @empty

            <div class="empty">
                Aucun quiz créé pour le moment.
            </div>

        @endforelse

    </div>

</section>

@endsection
@extends('layouts.dashboard')

@section('title', 'Questions du quiz')

@section('content')

<div style="
    min-height:650px;
    padding:50px 30px;
    background:#f3f6fb;
">

<div style="max-width:900px;margin:auto;">

    <a href="{{ route('responsable.quiz.index') }}">
        ← Retour aux quiz
    </a>

    <div style="
        margin:20px 0;
        padding:30px;
        border-radius:18px;
        background:#1e3a8a;
        color:white;
    ">

        <h1>{{ $quiz->titre_quiz }}</h1>

        <p>
            {{ $quiz->cours?->titre_cours }}
        </p>

    </div>

    <div style="
        margin-bottom:30px;
        padding:30px;
        border-radius:18px;
        background:white;
    ">

        <h2>Ajouter une question</h2>

        <form
            method="POST"
            action="{{
                route(
                    'responsable.quiz.questions.store',
                    $quiz->id_quiz
                )
            }}"
        >

            @csrf

            @foreach (
                [
                    'texte_question' => 'Question',
                    'choix_a' => 'Choix A',
                    'choix_b' => 'Choix B',
                    'choix_c' => 'Choix C',
                    'choix_d' => 'Choix D',
                ]
                as $name => $label
            )

                <label>
                    {{ $label }}
                </label>

                <input
                    type="text"
                    name="{{ $name }}"
                    required
                    style="
                        width:100%;
                        padding:13px;
                        margin-bottom:15px;
                    "
                >

            @endforeach

            <label>Bonne réponse</label>

            <select
                name="bonne_reponse"
                required
                style="
                    width:100%;
                    padding:13px;
                    margin-bottom:18px;
                "
            >

                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>

            </select>

            <button
                style="
                    width:100%;
                    padding:14px;
                    border:0;
                    border-radius:10px;
                    background:#1e3a8a;
                    color:white;
                    font-weight:800;
                "
            >
                Ajouter la question
            </button>

        </form>

    </div>

    @foreach ($quiz->questions as $question)

        <div style="
            margin-bottom:15px;
            padding:22px;
            border-radius:14px;
            background:white;
        ">

            <strong>
                {{ $question->texte_question }}
            </strong>

            <p>
                A. {{ $question->choix_a }}<br>
                B. {{ $question->choix_b }}<br>
                C. {{ $question->choix_c }}<br>
                D. {{ $question->choix_d }}
            </p>

            <p>
                Bonne réponse :
                <strong>
                    {{ $question->bonne_reponse }}
                </strong>
            </p>

            <a
                href="{{
                    route(
                        'responsable.quiz.questions.edit',
                        $question->id_question
                    )
                }}"
            >
                Modifier
            </a>

            <form
                method="POST"
                action="{{
                    route(
                        'responsable.quiz.questions.destroy',
                        $question->id_question
                    )
                }}"
                style="display:inline;"
            >

                @csrf
                @method('DELETE')

                <button>
                    Supprimer
                </button>

            </form>

        </div>

    @endforeach

</div>

</div>

@endsection
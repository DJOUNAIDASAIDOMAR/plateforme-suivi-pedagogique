@extends('layouts.dashboard')

@section('title', 'Gérer les quiz')

@section('content')

<div style="
    min-height:650px;
    padding:50px 30px;
    background:#f3f6fb;
">

<div style="max-width:1050px;margin:auto;">

    <div style="
        margin-bottom:25px;
        padding:30px;
        border-radius:20px;
        background:linear-gradient(135deg,#1e3a8a,#2563eb);
        color:white;
    ">

        <h1>Gérer les quiz</h1>

        <p>
            Gérez les quiz de tous les cours.
        </p>

    </div>

    <div style="
        margin-bottom:30px;
        padding:30px;
        border-radius:18px;
        background:white;
    ">

        <h2>Créer un quiz</h2>

        <form
            method="POST"
            action="{{ route('responsable.quiz.store') }}"
        >

            @csrf

            <select
                name="id_cours"
                required
                style="
                    width:100%;
                    padding:13px;
                    margin-bottom:15px;
                "
            >

                <option value="">
                    Choisir un cours
                </option>

                @foreach ($cours as $cour)

                    <option value="{{ $cour->id_cours }}">
                        {{ $cour->titre_cours }}
                    </option>

                @endforeach

            </select>

            <input
                type="text"
                name="titre_quiz"
                placeholder="Titre du quiz"
                required
                style="
                    width:100%;
                    padding:13px;
                    margin-bottom:15px;
                "
            >

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
                Créer le quiz
            </button>

        </form>

    </div>

    @foreach ($quizzes as $quiz)

        <div style="
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:20px;
            margin-bottom:12px;
            padding:20px;
            border-radius:13px;
            background:white;
        ">

            <div>

                <strong>
                    {{ $quiz->titre_quiz }}
                </strong>

                <br>

                {{ $quiz->cours?->titre_cours }}

                <br>

                <small>
                    {{ $quiz->questions_count }}
                    question(s) —
                    {{ $quiz->resultats_count }}
                    résultat(s)
                </small>

            </div>

            <div style="display:flex;gap:8px;">

                <a
                    href="{{
                        route(
                            'responsable.quiz.questions',
                            $quiz->id_quiz
                        )
                    }}"
                >
                    Questions
                </a>

                <a
                    href="{{
                        route(
                            'responsable.quiz.edit',
                            $quiz->id_quiz
                        )
                    }}"
                >
                    Modifier
                </a>

                <form
                    method="POST"
                    action="{{
                        route(
                            'responsable.quiz.destroy',
                            $quiz->id_quiz
                        )
                    }}"
                >

                    @csrf
                    @method('DELETE')

                    <button>
                        Supprimer
                    </button>

                </form>

            </div>

        </div>

    @endforeach

</div>

</div>

@endsection
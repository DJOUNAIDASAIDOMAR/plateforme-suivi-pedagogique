@extends('layouts.dashboard')

@section('title', 'Modifier une question')

@section('content')

<div style="
    min-height:650px;
    padding:50px 30px;
    background:#f3f6fb;
">

<div style="max-width:800px;margin:auto;">

    <a
        href="{{
            route(
                'responsable.quiz.questions',
                $question->id_quiz
            )
        }}"
    >
        ← Retour aux questions
    </a>

    <div style="
        margin-top:20px;
        padding:35px;
        border-radius:20px;
        background:white;
    ">

        <h1>Modifier la question</h1>

        <form
            method="POST"
            action="{{
                route(
                    'responsable.quiz.questions.update',
                    $question->id_question
                )
            }}"
        >

            @csrf
            @method('PUT')

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

                <label>{{ $label }}</label>

                <input
                    type="text"
                    name="{{ $name }}"
                    value="{{ $question->{$name} }}"
                    required
                    style="
                        width:100%;
                        padding:13px;
                        margin-bottom:15px;
                    "
                >

            @endforeach

            <select
                name="bonne_reponse"
                required
                style="
                    width:100%;
                    padding:13px;
                    margin-bottom:18px;
                "
            >

                @foreach (['A','B','C','D'] as $lettre)

                    <option
                        value="{{ $lettre }}"
                        {{
                            $question->bonne_reponse
                            === $lettre
                                ? 'selected'
                                : ''
                        }}
                    >
                        {{ $lettre }}
                    </option>

                @endforeach

            </select>

            <button
                style="
                    width:100%;
                    padding:14px;
                    background:#1e3a8a;
                    color:white;
                    border:0;
                    border-radius:10px;
                "
            >
                Enregistrer
            </button>

        </form>

    </div>

</div>

</div>

@endsection
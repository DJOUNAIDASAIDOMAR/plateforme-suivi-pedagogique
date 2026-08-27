@extends('layouts.dashboard')

@section('title', 'Modifier une question')

@section('content')

<div style="
    min-height: 650px;
    padding: 50px 30px;
    background: #f3f6fb;
">

    <div style="
        max-width: 800px;
        margin: 0 auto;
    ">

        <a
            href="{{
                route(
                    'formateur.quiz.questions',
                    $quiz->id_quiz
                )
            }}"
            style="
                display: inline-block;
                margin-bottom: 20px;
                color: #1e3a8a;
                font-weight: 800;
                text-decoration: none;
            "
        >
            ← Retour aux questions
        </a>

        <div style="
            padding: 35px;
            border-radius: 20px;
            background: #ffffff;
        ">

            <h1 style="
                margin-top: 0;
                color: #172554;
            ">
                Modifier la question
            </h1>

            <form
                method="POST"
                action="{{
                    route(
                        'formateur.quiz.questions.update',
                        [
                            $quiz->id_quiz,
                            $question->id_question
                        ]
                    )
                }}"
            >

                @csrf
                @method('PUT')

                @php
                    $champs = [
                        'texte_question' => 'Question',
                        'choix_a' => 'Choix A',
                        'choix_b' => 'Choix B',
                        'choix_c' => 'Choix C',
                        'choix_d' => 'Choix D',
                    ];
                @endphp

                @foreach ($champs as $nom => $label)

                    <div style="margin-bottom: 18px;">

                        <label style="
                            display: block;
                            margin-bottom: 8px;
                            font-weight: 800;
                        ">
                            {{ $label }}
                        </label>

                        <input
                            type="text"
                            name="{{ $nom }}"
                            value="{{
                                old(
                                    $nom,
                                    $question->{$nom}
                                )
                            }}"
                            required
                            style="
                                width: 100%;
                                padding: 13px;
                                border: 1px solid #d1d5db;
                                border-radius: 9px;
                            "
                        >

                    </div>

                @endforeach

                <div style="margin-bottom: 20px;">

                    <label style="
                        display: block;
                        margin-bottom: 8px;
                        font-weight: 800;
                    ">
                        Bonne réponse
                    </label>

                    <select
                        name="bonne_reponse"
                        required
                        style="
                            width: 100%;
                            padding: 13px;
                            border: 1px solid #d1d5db;
                            border-radius: 9px;
                        "
                    >

                        @foreach (['A', 'B', 'C', 'D'] as $lettre)

                            <option
                                value="{{ $lettre }}"
                                {{
                                    old(
                                        'bonne_reponse',
                                        $question->bonne_reponse
                                    ) === $lettre
                                        ? 'selected'
                                        : ''
                                }}
                            >
                                {{ $lettre }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <button
                    type="submit"
                    style="
                        width: 100%;
                        padding: 14px;
                        border: none;
                        border-radius: 10px;
                        background: #1e3a8a;
                        color: white;
                        font-weight: 800;
                        cursor: pointer;
                    "
                >
                    Enregistrer les modifications
                </button>

            </form>

        </div>

    </div>

</div>

@endsection
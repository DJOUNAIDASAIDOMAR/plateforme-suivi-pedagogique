@extends('layouts.dashboard')

@section('title', 'Modifier le quiz')

@section('content')

<div style="
    min-height: 650px;
    padding: 50px 30px;
    background: #f3f6fb;
">

    <div style="
        max-width: 750px;
        margin: 0 auto;
    ">

        <a
            href="{{ route('formateur.quiz.manage') }}"
            style="
                display: inline-block;
                margin-bottom: 20px;
                color: #1e3a8a;
                font-weight: 800;
                text-decoration: none;
            "
        >
            ← Retour aux quiz
        </a>

        <div style="
            padding: 35px;
            border-radius: 20px;
            background: white;
        ">

            <h1 style="
                margin-top: 0;
                color: #172554;
            ">
                Modifier le quiz
            </h1>

            <form
                method="POST"
                action="{{
                    route(
                        'formateur.quiz.update',
                        $quiz->id_quiz
                    )
                }}"
            >

                @csrf
                @method('PUT')

                <div style="margin-bottom: 20px;">

                    <label style="
                        display: block;
                        margin-bottom: 8px;
                        font-weight: 800;
                    ">
                        Cours
                    </label>

                    <select
                        name="id_cours"
                        required
                        style="
                            width: 100%;
                            padding: 13px;
                            border: 1px solid #d1d5db;
                            border-radius: 9px;
                        "
                    >

                        @foreach ($cours as $cour)

                            <option
                                value="{{ $cour->id_cours }}"
                                {{
                                    (string) old(
                                        'id_cours',
                                        $quiz->id_cours
                                    )
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

                <div style="margin-bottom: 20px;">

                    <label style="
                        display: block;
                        margin-bottom: 8px;
                        font-weight: 800;
                    ">
                        Titre du quiz
                    </label>

                    <input
                        type="text"
                        name="titre_quiz"
                        value="{{
                            old(
                                'titre_quiz',
                                $quiz->titre_quiz
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
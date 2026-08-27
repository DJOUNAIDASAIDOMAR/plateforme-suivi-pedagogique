@extends('layouts.dashboard')

@section('title', 'Modifier un quiz')

@section('content')

<div style="
    min-height:650px;
    padding:50px 30px;
    background:#f3f6fb;
">

<div style="max-width:750px;margin:auto;">

    <a href="{{ route('responsable.quiz.index') }}">
        ← Retour aux quiz
    </a>

    <div style="
        margin-top:20px;
        padding:35px;
        border-radius:20px;
        background:white;
    ">

        <h1>Modifier le quiz</h1>

        <form
            method="POST"
            action="{{
                route(
                    'responsable.quiz.update',
                    $quiz->id_quiz
                )
            }}"
        >

            @csrf
            @method('PUT')

            <select
                name="id_cours"
                required
                style="
                    width:100%;
                    padding:13px;
                    margin-bottom:18px;
                "
            >

                @foreach ($cours as $cour)

                    <option
                        value="{{ $cour->id_cours }}"
                        {{
                            $quiz->id_cours
                            == $cour->id_cours
                                ? 'selected'
                                : ''
                        }}
                    >
                        {{ $cour->titre_cours }}
                    </option>

                @endforeach

            </select>

            <input
                type="text"
                name="titre_quiz"
                value="{{ $quiz->titre_quiz }}"
                required
                style="
                    width:100%;
                    padding:13px;
                    margin-bottom:18px;
                "
            >

            <button
                style="
                    width:100%;
                    padding:14px;
                    border:0;
                    background:#1e3a8a;
                    color:white;
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
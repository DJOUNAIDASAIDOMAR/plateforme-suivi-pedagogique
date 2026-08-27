@extends('layouts.dashboard')

@section('title', 'Modifier une leçon')

@section('content')

<div style="
    padding:50px 30px;
    min-height:650px;
    background:#f3f6fb;
">

    <div style="max-width:750px;margin:auto;">

        <a
            href="{{ route('responsable.lecons.index') }}"
            style="
                color:#1e3a8a;
                font-weight:800;
                text-decoration:none;
            "
        >
            ← Retour
        </a>

        <div style="
            margin-top:20px;
            padding:35px;
            border-radius:20px;
            background:white;
        ">

            <h1>Modifier la leçon</h1>

            <form
                method="POST"
                action="{{
                    route(
                        'responsable.lecons.update',
                        $lecon->{'id_leçon'}
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
                                $lecon->id_cours
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
                    name="titre_leçon"
                    value="{{ $lecon->{'titre_leçon'} }}"
                    required
                    style="
                        width:100%;
                        padding:13px;
                        margin-bottom:18px;
                    "
                >

                <input
                    type="number"
                    min="1"
                    name="ordre"
                    value="{{ $lecon->ordre }}"
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
                        border-radius:10px;
                        background:#1e3a8a;
                        color:white;
                        font-weight:800;
                    "
                >
                    Enregistrer
                </button>

            </form>

        </div>

    </div>

</div>

@endsection
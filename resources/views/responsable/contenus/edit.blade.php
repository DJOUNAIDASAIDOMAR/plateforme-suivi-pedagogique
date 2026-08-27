@extends('layouts.dashboard')

@section('title', 'Modifier un contenu')

@section('content')

<div style="
    min-height:650px;
    padding:50px 30px;
    background:#f3f6fb;
">

<div style="
    max-width:750px;
    margin:auto;
">

    <a
        href="{{ route('responsable.contenus.index') }}"
        style="
            color:#1e3a8a;
            font-weight:800;
            text-decoration:none;
        "
    >
        ← Retour aux contenus
    </a>

    <div style="
        margin-top:20px;
        padding:35px;
        border-radius:20px;
        background:white;
    ">

        <h1>
            Modifier le contenu
        </h1>

        <form
            method="POST"
            action="{{
                route(
                    'responsable.contenus.update',
                    $contenu->id_contenu
                )
            }}"
            enctype="multipart/form-data"
        >

            @csrf
            @method('PUT')

            <select
                name="id_cours"
                required
                style="
                    width:100%;
                    padding:13px;
                    margin-bottom:15px;
                "
            >

                @foreach ($cours as $cour)

                    <option
                        value="{{ $cour->id_cours }}"
                        {{
                            $contenu
                                ->lecon
                                ?->id_cours
                            == $cour->id_cours
                                ? 'selected'
                                : ''
                        }}
                    >
                        {{ $cour->titre_cours }}
                    </option>

                @endforeach

            </select>

            <select
                name="id_lecon"
                required
                style="
                    width:100%;
                    padding:13px;
                    margin-bottom:15px;
                "
            >

                @foreach ($lecons as $lecon)

                    <option
                        value="{{ $lecon->{'id_leçon'} }}"
                        {{
                            $contenu->id_lecon
                            == $lecon->{'id_leçon'}
                                ? 'selected'
                                : ''
                        }}
                    >

                        {{ $lecon->cours?->titre_cours }}

                        -

                        {{ $lecon->{'titre_leçon'} }}

                    </option>

                @endforeach

            </select>

            <input
                type="text"
                name="titre_contenu"
                value="{{ $contenu->titre_contenu }}"
                required
                style="
                    width:100%;
                    padding:13px;
                    margin-bottom:15px;
                "
            >

            <select
                name="type_contenu"
                required
                style="
                    width:100%;
                    padding:13px;
                    margin-bottom:15px;
                "
            >

                @foreach (
                    [
                        'PDF',
                        'Document',
                        'Présentation',
                        'Image',
                        'Autre'
                    ]
                    as $type
                )

                    <option
                        value="{{ $type }}"
                        {{
                            $contenu->type_contenu
                            === $type
                                ? 'selected'
                                : ''
                        }}
                    >
                        {{ $type }}
                    </option>

                @endforeach

            </select>

            <input
                type="file"
                name="fichier"
                style="
                    width:100%;
                    margin-bottom:20px;
                "
            >

            <p>
                Laissez vide pour conserver
                le fichier actuel.
            </p>

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
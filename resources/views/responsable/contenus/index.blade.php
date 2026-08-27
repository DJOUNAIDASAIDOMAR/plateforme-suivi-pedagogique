@extends('layouts.dashboard')

@section('title', 'Gérer les contenus')

@section('content')

<div style="
    min-height:650px;
    padding:50px 30px;
    background:#f3f6fb;
">

<div style="max-width:1050px;margin:auto;">

    <div style="
        padding:30px;
        margin-bottom:25px;
        border-radius:20px;
        background:linear-gradient(135deg,#1e3a8a,#2563eb);
        color:white;
    ">

        <h1>Gérer les contenus</h1>

        <p>
            Ajoutez des ressources dans
            n'importe quelle leçon.
        </p>

    </div>

    <div style="
        padding:30px;
        margin-bottom:30px;
        border-radius:18px;
        background:white;
    ">

        <h2>Ajouter un contenu</h2>

        <form
            method="POST"
            action="{{ route('responsable.contenus.store') }}"
            enctype="multipart/form-data"
        >

            @csrf

            <select
                name="id_cours"
                id="id_cours"
                required
                style="
                    width:100%;
                    padding:13px;
                    margin-bottom:15px;
                "
            >

                <option value="">
                    Sélectionnez un cours
                </option>

                @foreach ($cours as $cour)

                    <option value="{{ $cour->id_cours }}">
                        {{ $cour->titre_cours }}
                    </option>

                @endforeach

            </select>

            <select
                name="id_lecon"
                id="id_lecon"
                required
                style="
                    width:100%;
                    padding:13px;
                    margin-bottom:15px;
                "
            >

                <option value="">
                    Sélectionnez une leçon
                </option>

                @foreach ($lecons as $lecon)

                    <option
                        value="{{ $lecon->{'id_leçon'} }}"
                        data-cours="{{ $lecon->id_cours }}"
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
                placeholder="Titre du contenu"
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

                <option value="PDF">PDF</option>
                <option value="Document">Document</option>
                <option value="Présentation">Présentation</option>
                <option value="Image">Image</option>
                <option value="Autre">Autre</option>

            </select>

            <input
                type="file"
                name="fichier"
                required
                style="
                    width:100%;
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
                Ajouter le contenu
            </button>

        </form>

    </div>

    <h2>Contenus existants</h2>

    @foreach ($contenus as $contenu)

        <div style="
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:20px;
            margin-bottom:12px;
            padding:18px;
            border-radius:12px;
            background:white;
        ">

            <div>

                <strong>
                    {{ $contenu->titre_contenu }}
                </strong>

                <br>

                {{
                    $contenu
                        ->lecon
                        ?->cours
                        ?->titre_cours
                }}

                →

                {{
                    $contenu
                        ->lecon
                        ?->{'titre_leçon'}
                }}

                <br>

                <small>
                    {{ $contenu->type_contenu }}
                </small>

            </div>

            <div style="display:flex;gap:8px;">

                <a
                    href="{{ asset('storage/' . $contenu->fichier) }}"
                    target="_blank"
                >
                    Ouvrir
                </a>

                <a
                    href="{{
                        route(
                            'responsable.contenus.edit',
                            $contenu->id_contenu
                        )
                    }}"
                >
                    Modifier
                </a>

                <form
                    method="POST"
                    action="{{
                        route(
                            'responsable.contenus.destroy',
                            $contenu->id_contenu
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
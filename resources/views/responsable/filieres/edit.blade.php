@extends('layouts.dashboard')

@section('title', 'Modifier une filière')

@section('content')

<div style="
    min-height:650px;
    padding:50px 30px;
    background:#f3f6fb;
">

    <div style="max-width:700px;margin:auto;">

        <a
            href="{{ route('responsable.filieres.index') }}"
            style="
                display:inline-block;
                margin-bottom:20px;
                color:#1e3a8a;
                font-weight:800;
                text-decoration:none;
            "
        >
            ← Retour aux filières
        </a>

        <div style="
            padding:35px;
            border-radius:20px;
            background:white;
        ">

            <h1 style="color:#172554;">
                Modifier la filière
            </h1>

            <form
                method="POST"
                action="{{
                    route(
                        'responsable.filieres.update',
                        $filiere->id_filier
                    )
                }}"
            >

                @csrf
                @method('PUT')

                <input
                    type="text"
                    name="nom_filier"
                    value="{{
                        old(
                            'nom_filier',
                            $filiere->nom_filier
                        )
                    }}"
                    required
                    style="
                        width:100%;
                        padding:13px;
                        margin-bottom:20px;
                        border:1px solid #d1d5db;
                        border-radius:10px;
                    "
                >

                <button
                    style="
                        width:100%;
                        padding:14px;
                        border:none;
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
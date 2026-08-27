@extends('layouts.dashboard')

@section('title', 'Gérer les leçons')

@push('styles')
<style>
    .page {
        min-height:650px;
        padding:50px 30px;
        background:#f3f6fb;
    }

    .container {
        max-width:1050px;
        margin:auto;
    }

    .header {
        margin-bottom:25px;
        padding:30px;
        border-radius:20px;
        background:linear-gradient(135deg,#1e3a8a,#2563eb);
        color:white;
    }

    .card {
        margin-bottom:30px;
        padding:30px;
        border-radius:18px;
        background:white;
    }

    .group {
        margin-bottom:18px;
    }

    label {
        display:block;
        margin-bottom:7px;
        font-weight:800;
    }

    .control {
        width:100%;
        padding:13px;
        border:1px solid #d1d5db;
        border-radius:10px;
    }

    .button {
        width:100%;
        padding:14px;
        border:none;
        border-radius:10px;
        background:#1e3a8a;
        color:white;
        font-weight:800;
    }

    .lesson {
        display:flex;
        margin-bottom:12px;
        padding:18px;
        border-radius:12px;
        align-items:center;
        justify-content:space-between;
        background:white;
    }

    .actions {
        display:flex;
        gap:8px;
    }

    .edit,
    .delete {
        padding:9px 12px;
        border:none;
        border-radius:8px;
        font-weight:700;
        text-decoration:none;
    }

    .edit {
        background:#dbeafe;
        color:#1e3a8a;
    }

    .delete {
        background:#fee2e2;
        color:#b91c1c;
    }
</style>
@endpush

@section('content')

<section class="page">

    <div class="container">

        <header class="header">

            <h1>Gérer les leçons</h1>

            <p>
                Le responsable peut intervenir
                sur les leçons de tous les cours.
            </p>

        </header>

        <div class="card">

            <h2>Ajouter une leçon</h2>

            <form
                method="POST"
                action="{{ route('responsable.lecons.store') }}"
            >

                @csrf

                <div class="group">

                    <label>Cours</label>

                    <select
                        class="control"
                        name="id_cours"
                        required
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

                </div>

                <div class="group">

                    <label>Titre de la leçon</label>

                    <input
                        class="control"
                        type="text"
                        name="titre_leçon"
                        required
                    >

                </div>

                <div class="group">

                    <label>Ordre</label>

                    <input
                        class="control"
                        type="number"
                        min="1"
                        name="ordre"
                        required
                    >

                </div>

                <button class="button">
                    Ajouter la leçon
                </button>

            </form>

        </div>

        <h2>Liste des leçons</h2>

        @foreach ($lecons as $lecon)

            <div class="lesson">

                <div>

                    <strong>
                        {{ $lecon->cours?->titre_cours }}
                    </strong>

                    <br>

                    Leçon {{ $lecon->ordre }}
                    :
                    {{ $lecon->{'titre_leçon'} }}

                    <br>

                    <small>
                        {{ $lecon->contenus_count }}
                        contenu(s)
                    </small>

                </div>

                <div class="actions">

                    <a
                        class="edit"
                        href="{{
                            route(
                                'responsable.lecons.edit',
                                $lecon->{'id_leçon'}
                            )
                        }}"
                    >
                        Modifier
                    </a>

                    <form
                        method="POST"
                        action="{{
                            route(
                                'responsable.lecons.destroy',
                                $lecon->{'id_leçon'}
                            )
                        }}"
                    >

                        @csrf
                        @method('DELETE')

                        <button class="delete">
                            Supprimer
                        </button>

                    </form>

                </div>

            </div>

        @endforeach

    </div>

</section>

@endsection
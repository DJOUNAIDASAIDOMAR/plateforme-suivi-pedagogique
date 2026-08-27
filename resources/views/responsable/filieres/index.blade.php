@extends('layouts.dashboard')

@section('title', 'Gérer les filières')

@push('styles')
<style>
    .page {
        min-height: 650px;
        padding: 50px 30px;
        background: #f3f6fb;
    }

    .container {
        max-width: 1000px;
        margin: auto;
    }

    .header {
        padding: 30px;
        margin-bottom: 25px;
        border-radius: 20px;
        background: linear-gradient(135deg, #1e3a8a, #2563eb);
        color: white;
    }

    .header h1 {
        margin: 0 0 8px;
    }

    .form-card,
    .list-card {
        margin-bottom: 25px;
        padding: 30px;
        border-radius: 18px;
        background: white;
    }

    .control {
        width: 100%;
        padding: 13px;
        margin-bottom: 15px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
    }

    .add-button {
        width: 100%;
        padding: 13px;
        border: none;
        border-radius: 10px;
        background: #1e3a8a;
        color: white;
        font-weight: 800;
        cursor: pointer;
    }

    .item {
        display: flex;
        padding: 18px;
        border-bottom: 1px solid #e5e7eb;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .actions {
        display: flex;
        gap: 8px;
    }

    .edit,
    .delete {
        padding: 9px 12px;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        text-decoration: none;
    }

    .edit {
        background: #dbeafe;
        color: #1e3a8a;
    }

    .delete {
        background: #fee2e2;
        color: #b91c1c;
        cursor: pointer;
    }

    .message {
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 10px;
    }

    .success {
        background: #f0fdf4;
        color: #15803d;
    }

    .error {
        background: #fef2f2;
        color: #b91c1c;
    }
</style>
@endpush

@section('content')

<section class="page">

    <div class="container">

        <header class="header">

            <h1>
                Gérer les filières
            </h1>

            <p>
                Ajoutez et organisez
                les filières de la plateforme.
            </p>

        </header>

        @if (session('success'))
            <div class="message success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="message error">
                {{ session('error') }}
            </div>
        @endif

        <div class="form-card">

            <h2>Ajouter une filière</h2>

            <form
                method="POST"
                action="{{ route('responsable.filieres.store') }}"
            >

                @csrf

                <input
                    class="control"
                    type="text"
                    name="nom_filier"
                    placeholder="Nom de la filière"
                    value="{{ old('nom_filier') }}"
                    required
                >

                <button class="add-button">
                    Ajouter la filière
                </button>

            </form>

        </div>

        <div class="list-card">

            <h2>Filières existantes</h2>

            @foreach ($filieres as $filiere)

                <div class="item">

                    <div>

                        <strong>
                            {{ $filiere->nom_filier }}
                        </strong>

                        <br>

                        <small>
                            {{ $filiere->etudiants_count }}
                            étudiant(s) —
                            {{ $filiere->cours_count }}
                            cours
                        </small>

                    </div>

                    <div class="actions">

                        <a
                            class="edit"
                            href="{{
                                route(
                                    'responsable.filieres.edit',
                                    $filiere->id_filier
                                )
                            }}"
                        >
                            Modifier
                        </a>

                        <form
                            method="POST"
                            action="{{
                                route(
                                    'responsable.filieres.destroy',
                                    $filiere->id_filier
                                )
                            }}"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                class="delete"
                                type="submit"
                                onclick="
                                    return confirm(
                                        'Supprimer cette filière ?'
                                    );
                                "
                            >
                                Supprimer
                            </button>

                        </form>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>

@endsection
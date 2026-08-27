@extends('layouts.dashboard')

@section('title', 'Modifier un utilisateur')

@push('styles')
<style>
    .page {
        min-height: 650px;
        padding: 50px 30px 80px;
        background: #f3f6fb;
    }

    .container {
        max-width: 780px;
        margin: 0 auto;
    }

    .back {
        display: inline-block;
        margin-bottom: 20px;
        color: #1e3a8a;
        font-weight: 800;
        text-decoration: none;
    }

    .card {
        padding: 35px;
        border-radius: 20px;
        background: white;
    }

    .card h1 {
        margin-top: 0;
        color: #172554;
    }

    .role-box {
        margin-bottom: 25px;
        padding: 15px;
        border-radius: 10px;
        background: #eff6ff;
        color: #1e3a8a;
    }

    .group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 800;
    }

    .control {
        width: 100%;
        padding: 13px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        font: inherit;
    }

    .button {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 10px;
        background: #1e3a8a;
        color: white;
        font-weight: 800;
        cursor: pointer;
    }

    .error {
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 10px;
        background: #fef2f2;
        color: #b91c1c;
    }
</style>
@endpush

@section('content')

<section class="page">
    <div class="container">

        <a
            class="back"
            href="{{ route('responsable.utilisateurs.index') }}"
        >
            ← Retour aux utilisateurs
        </a>

        <div class="card">

            <h1>
                Modifier l'utilisateur
            </h1>

            <div class="role-box">

                Rôle :

                <strong>
                    {{
                        $utilisateur->role === 'etudiant'
                            ? 'Étudiant'
                            : 'Formateur'
                    }}
                </strong>

            </div>

            @if ($errors->any())

                <div class="error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            @endif

            <form
                method="POST"
                action="{{
                    route(
                        'responsable.utilisateurs.update',
                        $utilisateur->id_user
                    )
                }}"
            >

                @csrf
                @method('PUT')

                <div class="group">

                    <label for="nom">
                        Nom complet
                    </label>

                    <input
                        class="control"
                        id="nom"
                        type="text"
                        name="nom"
                        value="{{ old('nom', $utilisateur->nom) }}"
                        required
                    >

                </div>

                <div class="group">

                    <label for="email">
                        Adresse e-mail
                    </label>

                    <input
                        class="control"
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $utilisateur->email) }}"
                        required
                    >

                </div>

                @if ($utilisateur->role === 'etudiant')

                    <div class="group">

                        <label for="id_filier">
                            Filière
                        </label>

                        <select
                            class="control"
                            id="id_filier"
                            name="id_filier"
                            required
                        >

                            @foreach ($filieres as $filiere)

                                <option
                                    value="{{ $filiere->id_filier }}"
                                    {{
                                        (string)
                                        old(
                                            'id_filier',
                                            $utilisateur
                                                ->etudiant
                                                ?->id_filier
                                        )
                                        ===
                                        (string)
                                        $filiere->id_filier
                                            ? 'selected'
                                            : ''
                                    }}
                                >
                                    {{ $filiere->nom_filier }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                @endif

                @if ($utilisateur->role === 'formateur')

                    <div class="group">

                        <label for="specialité">
                            Spécialité
                        </label>

                        <input
                            class="control"
                            id="specialité"
                            type="text"
                            name="specialité"
                            value="{{
                                old(
                                    'specialité',
                                    $utilisateur
                                        ->formateur
                                        ?->{'specialité'}
                                )
                            }}"
                            required
                        >

                    </div>

                @endif

                <button
                    class="button"
                    type="submit"
                >
                    Enregistrer les modifications
                </button>

            </form>

        </div>

    </div>
</section>

@endsection
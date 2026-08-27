@extends('layouts.site')

@section('title', 'Ajouter un cours')

@push('styles')
<style>
    .form-page {
        min-height: 650px;
        padding: 55px 30px 80px;
        background: #f3f6fb;
    }

    .form-container {
        width: 100%;
        max-width: 820px;
        margin: 0 auto;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 22px;
        color: #1e3a8a;
        font-weight: 800;
        text-decoration: none;
    }

    .form-card {
        padding: 38px;
        border-radius: 22px;
        background: #ffffff;
        box-shadow:
            0 15px 40px
            rgba(30, 64, 175, 0.10);
    }

    .form-card h1 {
        margin: 0 0 10px;
        color: #172554;
        font-size: 32px;
    }

    .form-introduction {
        margin: 0 0 30px;
        color: #6b7280;
        line-height: 1.7;
    }

    .error-box {
        margin-bottom: 22px;
        padding: 15px;
        border: 1px solid #fecaca;
        border-radius: 10px;
        background: #fef2f2;
        color: #b91c1c;
    }

    .error-box ul {
        margin: 0;
        padding-left: 20px;
    }

    .form-group {
        margin-bottom: 21px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #1f2937;
        font-weight: 800;
    }

    .form-control {
        width: 100%;
        padding: 13px 14px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        outline: none;
        font: inherit;
    }

    .form-control:focus {
        border-color: #1e3a8a;
        box-shadow:
            0 0 0 3px
            rgba(30, 58, 138, 0.12);
    }

    textarea.form-control {
        min-height: 160px;
        resize: vertical;
    }

    .submit-button {
        width: 100%;
        padding: 14px 20px;
        border: none;
        border-radius: 10px;
        background: #1e3a8a;
        color: #ffffff;
        font-size: 16px;
        font-weight: 800;
        cursor: pointer;
    }

    .submit-button:hover {
        background: #172554;
    }

    @media (max-width: 650px) {
        .form-page {
            padding: 40px 20px 60px;
        }

        .form-card {
            padding: 28px 22px;
        }
    }
</style>
@endpush

@section('content')
<section class="form-page">
    <div class="form-container">
        <a
            class="back-link"
            href="{{ route('formateur.cours.index') }}"
        >
            ← Retour à mes cours
        </a>

        <div class="form-card">
            <h1>
                Ajouter un cours
            </h1>

            <p class="form-introduction">
                Remplissez les informations du nouveau cours.
            </p>

            @if ($errors->any())
                <div class="error-box">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('formateur.cours.store') }}"
            >
                @csrf

                <div class="form-group">
                    <label for="titre_cours">
                        Titre du cours
                    </label>

                    <input
                        class="form-control"
                        id="titre_cours"
                        type="text"
                        name="titre_cours"
                        value="{{ old('titre_cours') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="description">
                        Description
                    </label>

                    <textarea
                        class="form-control"
                        id="description"
                        name="description"
                        required
                    >{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="niveau">
                        Niveau
                    </label>

                    <input
                        class="form-control"
                        id="niveau"
                        type="text"
                        name="niveau"
                        value="{{ old('niveau') }}"
                        placeholder="Exemple : Débutant"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="id_filier">
                        Filière
                    </label>

                    <select
                        class="form-control"
                        id="id_filier"
                        name="id_filier"
                        required
                    >
                        <option value="">
                            Sélectionnez une filière
                        </option>

                        @foreach ($filieres as $filiere)
                            <option
                                value="{{ $filiere->id_filier }}"
                                {{
                                    (string) old('id_filier')
                                    ===
                                    (string) $filiere->id_filier
                                        ? 'selected'
                                        : ''
                                }}
                            >
                                {{ $filiere->nom_filier }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button
                    class="submit-button"
                    type="submit"
                >
                    Enregistrer le cours
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
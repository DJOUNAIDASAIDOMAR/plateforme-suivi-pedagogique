@extends('layouts.dashboard')

@section(
    'title',
    'Mon profil - Espace étudiant'
)

@push('styles')
<style>
    .profile-page {
        min-height: 650px;
        padding: 50px 30px 80px;
        background: #f3f6fb;
    }

    .profile-container {
        width: 100%;
        max-width: 850px;
        margin: 0 auto;
    }

    .header {
        margin-bottom: 25px;
        padding: 30px;
        border-radius: 20px;
        background:
            linear-gradient(
                135deg,
                #1e3a8a,
                #2563eb
            );
        color: #ffffff;
    }

    .header h1 {
        margin: 0 0 8px;
    }

    .header p {
        margin: 0;
        color: #dbeafe;
    }

    .card {
        padding: 35px;
        border-radius: 20px;
        background: #ffffff;
    }

    .info {
        margin-bottom: 25px;
        padding: 18px;
        border-radius: 12px;
        background: #eff6ff;
        line-height: 1.8;
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

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 800;
    }

    .form-control {
        width: 100%;
        padding: 13px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        font: inherit;
    }

    .password-section {
        margin-top: 30px;
        padding-top: 25px;
        border-top: 1px solid #e5e7eb;
    }

    .save-button {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 10px;
        background: #1e3a8a;
        color: #ffffff;
        font-weight: 800;
        cursor: pointer;
    }
</style>
@endpush

@section('content')

<section class="profile-page">

    <div class="profile-container">

        <header class="header">

            <h1>
                Mon profil
            </h1>

            <p>
                Consultez et modifiez
                vos informations personnelles.
            </p>

        </header>

        @if (session('success'))

            <div class="message success">
                {{ session('success') }}
            </div>

        @endif

        @if ($errors->any())

            <div class="message error">

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>

            </div>

        @endif

        <div class="card">

            <div class="info">

                <strong>
                    Filière :
                </strong>

                {{
                    $user
                        ->etudiant
                        ?->filier
                        ?->nom_filier
                    ?? 'Non renseignée'
                }}

                <br>

                <strong>
                    Progression :
                </strong>

                {{
                    $user
                        ->etudiant
                        ?->progression
                    ?? 0
                }} %

                <br>

                <strong>
                    Date d'inscription :
                </strong>

                {{
                    $user->date_inscription
                        ? $user
                            ->date_inscription
                            ->format('d/m/Y')
                        : 'Non renseignée'
                }}

            </div>

            <form
                method="POST"
                action="{{ route('etudiant.profil.update') }}"
            >

                @csrf
                @method('PUT')

                <div class="form-group">

                    <label for="nom">
                        Nom complet
                    </label>

                    <input
                        class="form-control"
                        id="nom"
                        type="text"
                        name="nom"
                        value="{{ old('nom', $user->nom) }}"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="email">
                        Adresse e-mail
                    </label>

                    <input
                        class="form-control"
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                    >

                </div>

                <section class="password-section">

                    <h3>
                        Changer le mot de passe
                    </h3>

                    <p>
                        Laissez vide si vous ne souhaitez
                        pas modifier votre mot de passe.
                    </p>

                    <div class="form-group">

                        <label for="password">
                            Nouveau mot de passe
                        </label>

                        <input
                            class="form-control"
                            id="password"
                            type="password"
                            name="password"
                        >

                    </div>

                    <div class="form-group">

                        <label for="password_confirmation">
                            Confirmer le mot de passe
                        </label>

                        <input
                            class="form-control"
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                        >

                    </div>

                </section>

                <button
                    class="save-button"
                    type="submit"
                >
                    Enregistrer les modifications
                </button>

            </form>

        </div>

    </div>

</section>

@endsection
@extends('layouts.dashboard')

@section(
    'title',
    'Mon profil - Espace formateur'
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

    .profile-header {
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

    .profile-header h1 {
        margin: 0 0 10px;
        font-size: 32px;
    }

    .profile-header p {
        margin: 0;
        color: #dbeafe;
        line-height: 1.7;
    }

    .success-message {
        margin-bottom: 20px;
        padding: 15px 18px;
        border-radius: 10px;
        background: #f0fdf4;
        color: #15803d;
    }

    .error-box {
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 10px;
        background: #fef2f2;
        color: #b91c1c;
    }

    .profile-card {
        padding: 35px;
        border-radius: 20px;
        background: #ffffff;

        box-shadow:
            0 12px 35px
            rgba(30, 64, 175, 0.08);
    }

    .profile-card h2 {
        margin: 0 0 25px;
        color: #172554;
    }

    .form-group {
        margin-bottom: 20px;
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
        background: #ffffff;
        color: #1f2937;
        font: inherit;
        outline: none;
    }

    .form-control:focus {
        border-color: #1e3a8a;

        box-shadow:
            0 0 0 3px
            rgba(30, 58, 138, 0.12);
    }

    .info-box {
        margin-bottom: 25px;
        padding: 15px;
        border-radius: 10px;
        background: #eff6ff;
        color: #1e3a8a;
        line-height: 1.6;
    }

    .submit-button {
        width: 100%;
        padding: 14px;
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

    .password-section {
        margin-top: 30px;
        padding-top: 25px;
        border-top: 1px solid #e5e7eb;
    }

    .password-section h3 {
        margin: 0 0 8px;
        color: #172554;
    }

    .password-section p {
        margin: 0 0 20px;
        color: #6b7280;
        line-height: 1.6;
    }

    @media (max-width: 650px) {
        .profile-page {
            padding: 40px 20px 60px;
        }

        .profile-card {
            padding: 25px 20px;
        }
    }
</style>
@endpush

@section('content')

<section class="profile-page">

    <div class="profile-container">

        <header class="profile-header">

            <h1>
                Mon profil
            </h1>

            <p>
                Consultez et modifiez
                vos informations personnelles.
            </p>

        </header>


        @if (session('success'))

            <div class="success-message">
                {{ session('success') }}
            </div>

        @endif


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


        <div class="profile-card">

            <h2>
                Mes informations
            </h2>

            <div class="info-box">

                Rôle :
                <strong>
                    Formateur
                </strong>

                <br>

                Date d'inscription :
                <strong>

                    {{
                        $user->date_inscription
                            ? $user
                                ->date_inscription
                                ->format('d/m/Y')
                            : 'Non renseignée'
                    }}

                </strong>

            </div>


            <form
                method="POST"
                action="{{ route('formateur.profil.update') }}"
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
                        value="{{
                            old(
                                'nom',
                                $user->nom
                            )
                        }}"
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
                        value="{{
                            old(
                                'email',
                                $user->email
                            )
                        }}"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="specialité">
                        Spécialité
                    </label>

                    <input
                        class="form-control"
                        id="specialité"
                        type="text"
                        name="specialité"
                        value="{{
                            old(
                                'specialité',
                                $user
                                    ->formateur
                                    ?->{'specialité'}
                            )
                        }}"
                        required
                    >

                </div>


                <div class="password-section">

                    <h3>
                        Changer le mot de passe
                    </h3>

                    <p>
                        Laissez ces champs vides
                        si vous ne souhaitez pas
                        modifier votre mot de passe.
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

                </div>


                <button
                    class="submit-button"
                    type="submit"
                >
                    Enregistrer les modifications
                </button>

            </form>

        </div>

    </div>

</section>

@endsection
@extends('layouts.site')

@section(
    'title',
    'Créer un compte - Plateforme de Suivi Pédagogique'
)

@push('styles')

<style>

    .register-page {
        min-height: 100vh;
        padding: 45px 20px 80px;
        background:
            linear-gradient(
                135deg,
                #f8fafc,
                #eef4ff
            );
    }


    .register-container {
        width: 100%;
        max-width: 650px;
        margin: 0 auto;
        padding: 34px;
        border-radius: 20px;
        background: #ffffff;
        box-shadow:
            0 15px 45px
            rgba(30, 64, 175, 0.10);
    }


    .register-header {
        margin-bottom: 30px;
        text-align: center;
    }


    .register-header h1 {
        margin: 0 0 10px;
        color: #1d4ed8;
        font-size: 30px;
    }


    .register-header p {
        margin: 0;
        color: #6b7280;
        font-size: 16px;
    }


    .form-group {
        margin-bottom: 20px;
    }


    .form-label {
        display: block;
        margin-bottom: 8px;
        color: #111827;
        font-weight: 700;
    }


    .form-input,
    .form-select {
        width: 100%;
        min-height: 46px;
        padding: 11px 13px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        background: #ffffff;
        color: #111827;
        font-size: 15px;
        box-sizing: border-box;
    }


    .form-input:focus,
    .form-select:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow:
            0 0 0 3px
            rgba(37, 99, 235, 0.10);
    }


    .roles {
        display: grid;
        grid-template-columns:
            repeat(2, 1fr);
        gap: 14px;
    }


    .role-option {
        position: relative;
    }


    .role-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }


    .role-card {
        display: flex;
        min-height: 88px;
        padding: 15px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        color: #111827;
        font-weight: 800;
        text-align: center;
        cursor: pointer;
        transition: 0.2s;
    }


    .role-card:hover {
        border-color: #93c5fd;
        background: #eff6ff;
    }


    .role-option input:checked + .role-card {
        border-color: #2563eb;
        background: #dbeafe;
        color: #1d4ed8;
    }


    .conditional-field {
        display: none;
    }


    .password-wrapper {
        position: relative;
    }


    .password-wrapper .form-input {
        padding-right: 85px;
    }


    .password-toggle {
        position: absolute;
        top: 50%;
        right: 14px;
        padding: 0;
        border: 0;
        background: transparent;
        color: #2563eb;
        font-weight: 700;
        cursor: pointer;
        transform: translateY(-50%);
    }


    .password-rules {
        margin-top: 10px;
        padding: 15px;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        background: #eff6ff;
        color: #1e3a8a;
        font-size: 14px;
    }


    .password-rules strong {
        display: block;
        margin-bottom: 8px;
    }


    .password-rules ul {
        margin: 0;
        padding-left: 20px;
    }


    .password-rules li {
        margin: 4px 0;
    }


    .error-message {
        margin-top: 6px;
        color: #dc2626;
        font-size: 13px;
        font-weight: 600;
    }


    .general-errors {
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid #fecaca;
        border-radius: 10px;
        background: #fef2f2;
        color: #b91c1c;
    }


    .submit-button {
        width: 100%;
        min-height: 48px;
        margin-top: 10px;
        padding: 12px 20px;
        border: 0;
        border-radius: 10px;
        background: #2563eb;
        color: #ffffff;
        font-size: 16px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.2s;
    }


    .submit-button:hover {
        background: #1d4ed8;
    }


    .login-link {
        margin-top: 22px;
        text-align: center;
        color: #6b7280;
    }


    .login-link a {
        color: #2563eb;
        font-weight: 700;
        text-decoration: none;
    }


    @media (max-width: 650px) {

        .register-container {
            padding: 25px 20px;
        }


        .roles {
            grid-template-columns: 1fr;
        }
    }

</style>

@endpush


@section('content')

<section class="register-page">

    <div class="register-container">


        <header class="register-header">

            <h1>
                Créer un compte
            </h1>

            <p>
                Inscrivez-vous sur la plateforme
                de suivi pédagogique.
            </p>

        </header>


        @if ($errors->any())

            <div class="general-errors">

                <strong>
                    Veuillez vérifier les informations saisies.
                </strong>

            </div>

        @endif


        <form
            action="{{ route('register.store') }}"
            method="POST"
        >

            @csrf


            {{-- NOM --}}

            <div class="form-group">

                <label
                    for="nom"
                    class="form-label"
                >
                    Nom complet
                </label>

                <input
                    id="nom"
                    type="text"
                    name="nom"
                    class="form-input"
                    value="{{ old('nom') }}"
                    required
                    autocomplete="name"
                >

                @error('nom')

                    <div class="error-message">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            {{-- EMAIL --}}

            <div class="form-group">

                <label
                    for="email"
                    class="form-label"
                >
                    Adresse e-mail
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-input"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                >

                @error('email')

                    <div class="error-message">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            {{-- RÔLE --}}

            <div class="form-group">

                <div class="form-label">
                    Choisissez votre rôle
                </div>


                <div class="roles">


                    {{-- ÉTUDIANT --}}

                    <label class="role-option">

                        <input
                            type="radio"
                            name="role"
                            value="etudiant"
                            {{
                                old('role') === 'etudiant'
                                    ? 'checked'
                                    : ''
                            }}
                            required
                        >

                        <span class="role-card">
                            Étudiant
                        </span>

                    </label>


                    {{-- FORMATEUR --}}

                    <label class="role-option">

                        <input
                            type="radio"
                            name="role"
                            value="formateur"
                            {{
                                old('role') === 'formateur'
                                    ? 'checked'
                                    : ''
                            }}
                            required
                        >

                        <span class="role-card">
                            Formateur
                        </span>

                    </label>


                </div>


                @error('role')

                    <div class="error-message">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            {{-- FILIÈRE ÉTUDIANT --}}

            <div
                id="studentFields"
                class="conditional-field form-group"
            >

                <label
                    for="id_filier"
                    class="form-label"
                >
                    Filière
                </label>


                <select
                    id="id_filier"
                    name="id_filier"
                    class="form-select"
                >

                    <option value="">
                        Sélectionnez votre filière
                    </option>


                    @foreach ($filieres as $filiere)

                        <option
                            value="{{ $filiere->id_filier }}"
                            {{
                                old('id_filier')
                                == $filiere->id_filier
                                    ? 'selected'
                                    : ''
                            }}
                        >
                            {{ $filiere->nom_filier }}
                        </option>

                    @endforeach

                </select>


                @error('id_filier')

                    <div class="error-message">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            {{-- SPÉCIALITÉ FORMATEUR --}}

            <div
                id="teacherFields"
                class="conditional-field form-group"
            >

                <label
                    for="specialite"
                    class="form-label"
                >
                    Spécialité
                </label>


                <input
                    id="specialite"
                    type="text"
                    name="specialite"
                    class="form-input"
                    value="{{ old('specialite') }}"
                    placeholder="Exemple : Développement Web"
                >


                @error('specialite')

                    <div class="error-message">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            {{-- MOT DE PASSE --}}

            <div class="form-group">

                <label
                    for="password"
                    class="form-label"
                >
                    Mot de passe
                </label>


                <div class="password-wrapper">

                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-input"
                        required
                        autocomplete="new-password"
                    >


                    <button
                        type="button"
                        class="password-toggle"
                        onclick="togglePassword('password', this)"
                    >
                        Afficher
                    </button>

                </div>


                <div class="password-rules">

                    <strong>
                        Le mot de passe doit contenir :
                    </strong>

                    <ul>
                        <li>
                            Au moins 8 caractères
                        </li>

                        <li>
                            Une lettre majuscule
                        </li>

                        <li>
                            Une lettre minuscule
                        </li>

                        <li>
                            Un chiffre
                        </li>

                        <li>
                            Un caractère spécial
                        </li>
                    </ul>

                </div>


                @error('password')

                    <div class="error-message">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            {{-- CONFIRMATION MOT DE PASSE --}}

            <div class="form-group">

                <label
                    for="password_confirmation"
                    class="form-label"
                >
                    Confirmer le mot de passe
                </label>


                <div class="password-wrapper">

                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="form-input"
                        required
                        autocomplete="new-password"
                    >


                    <button
                        type="button"
                        class="password-toggle"
                        onclick="
                            togglePassword(
                                'password_confirmation',
                                this
                            )
                        "
                    >
                        Afficher
                    </button>

                </div>

            </div>


            {{-- BOUTON INSCRIPTION --}}

            <button
                type="submit"
                class="submit-button"
            >
                Créer mon compte
            </button>


        </form>


        <div class="login-link">

            Vous avez déjà un compte ?

            <a href="{{ route('login') }}">
                Se connecter
            </a>

        </div>


    </div>

</section>


<script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const roleInputs =
                document.querySelectorAll(
                    'input[name="role"]'
                );

            const studentFields =
                document.getElementById(
                    'studentFields'
                );

            const teacherFields =
                document.getElementById(
                    'teacherFields'
                );

            const studentSelect =
                document.getElementById(
                    'id_filier'
                );

            const teacherInput =
                document.getElementById(
                    'specialite'
                );


            function updateRoleFields() {

                const checkedRole =
                    document.querySelector(
                        'input[name="role"]:checked'
                    );


                const role =
                    checkedRole
                        ? checkedRole.value
                        : null;


                /*
                |--------------------------------------------------------------------------
                | ÉTUDIANT
                |--------------------------------------------------------------------------
                */

                if (role === 'etudiant') {

                    studentFields.style.display =
                        'block';

                    teacherFields.style.display =
                        'none';

                    studentSelect.required =
                        true;

                    teacherInput.required =
                        false;

                    teacherInput.value =
                        '';

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | FORMATEUR
                |--------------------------------------------------------------------------
                */

                if (role === 'formateur') {

                    studentFields.style.display =
                        'none';

                    teacherFields.style.display =
                        'block';

                    studentSelect.required =
                        false;

                    teacherInput.required =
                        true;

                    studentSelect.value =
                        '';

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | AUCUN RÔLE
                |--------------------------------------------------------------------------
                */

                studentFields.style.display =
                    'none';

                teacherFields.style.display =
                    'none';

                studentSelect.required =
                    false;

                teacherInput.required =
                    false;
            }


            roleInputs.forEach(
                function (input) {

                    input.addEventListener(
                        'change',
                        updateRoleFields
                    );
                }
            );


            updateRoleFields();
        }
    );


    function togglePassword(
        inputId,
        button
    ) {

        const input =
            document.getElementById(
                inputId
            );


        if (input.type === 'password') {

            input.type =
                'text';

            button.textContent =
                'Masquer';

        } else {

            input.type =
                'password';

            button.textContent =
                'Afficher';
        }
    }

</script>

@endsection
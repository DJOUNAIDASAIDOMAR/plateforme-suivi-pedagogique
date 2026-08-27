<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Inscription - Plateforme de suivi pédagogique
    </title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            padding: 40px 20px;
            font-family: Arial, sans-serif;

            background:
                linear-gradient(
                    135deg,
                    #eef4ff,
                    #f8fbff
                );

            color: #1f2937;
        }

        .auth-container {
            width: 100%;
            max-width: 650px;
            margin: 0 auto;
            padding: 35px;
            background: #ffffff;
            border-radius: 18px;

            box-shadow:
                0 15px 45px
                rgba(30, 64, 175, 0.12);
        }

        .auth-header {
            margin-bottom: 28px;
            text-align: center;
        }

        .auth-header h1 {
            margin-bottom: 10px;
            color: #1d4ed8;
            font-size: 30px;
        }

        .auth-header p {
            color: #6b7280;
            line-height: 1.6;
        }

        .alert {
            margin-bottom: 20px;
            padding: 14px 16px;
            border-radius: 10px;
        }

        .alert-error {
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #b91c1c;
        }

        .alert ul {
            padding-left: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .form-control {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            outline: none;
            font-size: 15px;
            transition: 0.2s;
        }

        .form-control:focus {
            border-color: #2563eb;

            box-shadow:
                0 0 0 3px
                rgba(37, 99, 235, 0.12);
        }

        .role-grid {
            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 12px;
        }

        .role-option {
            position: relative;
        }

        .role-option input {
            position: absolute;
            opacity: 0;
        }

        .role-option label {
            display: flex;
            min-height: 90px;
            padding: 15px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            align-items: center;
            justify-content: center;
            text-align: center;
            cursor: pointer;
            transition: 0.2s;
        }

        .role-option input:checked + label {
            border-color: #2563eb;
            background: #eff6ff;
            color: #1d4ed8;
        }

        .conditional-field {
            display: none;
        }

        /*
        |--------------------------------------------------------------------------
        | RÈGLES DU MOT DE PASSE
        |--------------------------------------------------------------------------
        */

        .password-requirements {
            margin-top: 10px;
            padding: 14px 16px;
            border: 1px solid #dbeafe;
            border-radius: 10px;
            background: #eff6ff;
        }

        .password-requirements-title {
            margin-bottom: 9px;
            color: #1e3a8a;
            font-size: 13px;
            font-weight: 800;
        }

        .password-requirements ul {
            padding-left: 20px;
            color: #475569;
            font-size: 13px;
            line-height: 1.8;
        }

        .password-requirements li.valid {
            color: #15803d;
            font-weight: 700;
        }

        .password-requirements li.invalid {
            color: #64748b;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-control {
            padding-right: 95px;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 12px;
            border: none;
            background: transparent;
            color: #2563eb;
            font-weight: 700;
            cursor: pointer;
            transform: translateY(-50%);
        }

        .button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: #2563eb;
            color: #ffffff;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
        }

        .button:hover {
            background: #1d4ed8;
        }

        .auth-footer {
            margin-top: 22px;
            text-align: center;
        }

        .auth-footer a {
            color: #2563eb;
            font-weight: 700;
            text-decoration: none;
        }

        @media (max-width: 650px) {

            .auth-container {
                padding: 25px 20px;
            }

            .role-grid {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

    <main class="auth-container">

        <header class="auth-header">

            <h1>
                Créer un compte
            </h1>

            <p>
                Inscrivez-vous sur la plateforme
                de suivi pédagogique.
            </p>

        </header>


        @if ($errors->any())

            <div class="alert alert-error">

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
            action="{{ route('register.store') }}"
        >

            @csrf


            <div class="form-group">

                <label for="nom">
                    Nom complet
                </label>

                <input
                    class="form-control"
                    id="nom"
                    type="text"
                    name="nom"
                    value="{{ old('nom') }}"
                    required
                    autocomplete="name"
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
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                >

            </div>


            <div class="form-group">

                <label>
                    Choisissez votre rôle
                </label>

                <div class="role-grid">

                    <div class="role-option">

                        <input
                            id="role-etudiant"
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

                        <label for="role-etudiant">
                            Étudiant
                        </label>

                    </div>


                    <div class="role-option">

                        <input
                            id="role-formateur"
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

                        <label for="role-formateur">
                            Formateur
                        </label>

                    </div>


                    <div class="role-option">

                        <input
                            id="role-responsable"
                            type="radio"
                            name="role"
                            value="responsable_pedagogique"
                            {{
                                old('role')
                                === 'responsable_pedagogique'
                                    ? 'checked'
                                    : ''
                            }}
                            required
                        >

                        <label for="role-responsable">
                            Responsable pédagogique
                        </label>

                    </div>

                </div>

            </div>


            <div
                class="form-group conditional-field"
                id="filiere-field"
            >

                <label for="id_filier">
                    Filière
                </label>

                <select
                    class="form-control"
                    id="id_filier"
                    name="id_filier"
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


            <div
                class="form-group conditional-field"
                id="specialite-field"
            >

                <label for="specialite">
                    Spécialité
                </label>

                <input
                    class="form-control"
                    id="specialite"
                    type="text"
                    name="specialite"
                    value="{{ old('specialite') }}"
                    placeholder="Exemple : développement web"
                >

            </div>


            <div class="form-group">

                <label for="password">
                    Mot de passe
                </label>

                <div class="password-wrapper">

                    <input
                        class="form-control"
                        id="password"
                        type="password"
                        name="password"
                        required
                        minlength="8"
                        autocomplete="new-password"
                    >

                    <button
                        class="password-toggle"
                        type="button"
                        data-target="password"
                    >
                        Afficher
                    </button>

                </div>


                <div class="password-requirements">

                    <div class="password-requirements-title">
                        Le mot de passe doit contenir :
                    </div>

                    <ul>

                        <li
                            class="invalid"
                            id="rule-length"
                        >
                            Au moins 8 caractères
                        </li>

                        <li
                            class="invalid"
                            id="rule-uppercase"
                        >
                            Au moins une lettre majuscule
                        </li>

                        <li
                            class="invalid"
                            id="rule-lowercase"
                        >
                            Au moins une lettre minuscule
                        </li>

                        <li
                            class="invalid"
                            id="rule-number"
                        >
                            Au moins un chiffre
                        </li>

                        <li
                            class="invalid"
                            id="rule-symbol"
                        >
                            Au moins un caractère spécial
                            (@, !, #, $, %, &, *, ?, ...)
                        </li>

                    </ul>

                </div>

            </div>


            <div class="form-group">

                <label for="password_confirmation">
                    Confirmer le mot de passe
                </label>

                <div class="password-wrapper">

                    <input
                        class="form-control"
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        minlength="8"
                        autocomplete="new-password"
                    >

                    <button
                        class="password-toggle"
                        type="button"
                        data-target="password_confirmation"
                    >
                        Afficher
                    </button>

                </div>

            </div>


            <button
                class="button"
                type="submit"
            >
                S’inscrire
            </button>

        </form>


        <footer class="auth-footer">

            Vous avez déjà un compte ?

            <a href="{{ route('login') }}">
                Se connecter
            </a>

        </footer>

    </main>


    <script>

        /*
        |--------------------------------------------------------------------------
        | AFFICHAGE DES CHAMPS SELON LE RÔLE
        |--------------------------------------------------------------------------
        */

        const roleInputs =
            document.querySelectorAll(
                'input[name="role"]'
            );

        const filiereField =
            document.getElementById(
                'filiere-field'
            );

        const specialiteField =
            document.getElementById(
                'specialite-field'
            );

        const filiereSelect =
            document.getElementById(
                'id_filier'
            );

        const specialiteInput =
            document.getElementById(
                'specialite'
            );


        function updateProfileFields() {

            const checkedRole =
                document.querySelector(
                    'input[name="role"]:checked'
                );

            const role =
                checkedRole
                    ? checkedRole.value
                    : '';


            filiereField.style.display =
                role === 'etudiant'
                    ? 'block'
                    : 'none';


            specialiteField.style.display =
                role === 'formateur'
                    ? 'block'
                    : 'none';


            filiereSelect.required =
                role === 'etudiant';


            specialiteInput.required =
                role === 'formateur';
        }


        roleInputs.forEach(
            (input) => {

                input.addEventListener(
                    'change',
                    updateProfileFields
                );

            }
        );


        updateProfileFields();


        /*
        |--------------------------------------------------------------------------
        | VÉRIFICATION VISUELLE DU MOT DE PASSE
        |--------------------------------------------------------------------------
        */

        const passwordInput =
            document.getElementById(
                'password'
            );


        const rules = {

            length:
                document.getElementById(
                    'rule-length'
                ),

            uppercase:
                document.getElementById(
                    'rule-uppercase'
                ),

            lowercase:
                document.getElementById(
                    'rule-lowercase'
                ),

            number:
                document.getElementById(
                    'rule-number'
                ),

            symbol:
                document.getElementById(
                    'rule-symbol'
                ),

        };


        function setRuleState(
            element,
            isValid
        ) {

            element.classList.toggle(
                'valid',
                isValid
            );

            element.classList.toggle(
                'invalid',
                ! isValid
            );
        }


        passwordInput.addEventListener(
            'input',
            function () {

                const value =
                    passwordInput.value;


                setRuleState(
                    rules.length,
                    value.length >= 8
                );


                setRuleState(
                    rules.uppercase,
                    /[A-Z]/.test(value)
                );


                setRuleState(
                    rules.lowercase,
                    /[a-z]/.test(value)
                );


                setRuleState(
                    rules.number,
                    /[0-9]/.test(value)
                );


                setRuleState(
                    rules.symbol,
                    /[^A-Za-z0-9]/.test(value)
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | AFFICHER / MASQUER LE MOT DE PASSE
        |--------------------------------------------------------------------------
        */

        const toggleButtons =
            document.querySelectorAll(
                '.password-toggle'
            );


        toggleButtons.forEach(
            (button) => {

                button.addEventListener(
                    'click',
                    function () {

                        const target =
                            document.getElementById(
                                button.dataset.target
                            );


                        if (
                            target.type
                            === 'password'
                        ) {
                            target.type = 'text';

                            button.textContent =
                                'Masquer';
                        } else {
                            target.type =
                                'password';

                            button.textContent =
                                'Afficher';
                        }

                    }
                );

            }
        );

    </script>

</body>

</html>
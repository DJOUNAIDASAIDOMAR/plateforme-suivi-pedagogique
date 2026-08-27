<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Nouveau mot de passe - Plateforme de suivi pédagogique
    </title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            min-height: 100vh;
            padding: 25px;
            align-items: center;
            justify-content: center;
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
            max-width: 520px;
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
            font-size: 29px;
        }

        .auth-header p {
            color: #6b7280;
            line-height: 1.6;
        }

        .alert-error {
            margin-bottom: 20px;
            padding: 14px 16px;
            border: 1px solid #fecaca;
            border-radius: 10px;
            background: #fef2f2;
            color: #b91c1c;
        }

        .alert-error ul {
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
        }

        .form-control:focus {
            border-color: #2563eb;

            box-shadow:
                0 0 0 3px
                rgba(37, 99, 235, 0.12);
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

        .password-requirements {
            margin-top: 10px;
            padding: 14px 16px;
            border: 1px solid #dbeafe;
            border-radius: 10px;
            background: #eff6ff;
        }

        .password-requirements strong {
            display: block;
            margin-bottom: 8px;
            color: #1e3a8a;
            font-size: 13px;
        }

        .password-requirements ul {
            padding-left: 20px;
            color: #64748b;
            font-size: 13px;
            line-height: 1.8;
        }

        .password-requirements li.valid {
            color: #15803d;
            font-weight: 700;
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
    </style>
</head>

<body>

<main class="auth-container">

    <header class="auth-header">

        <h1>
            Nouveau mot de passe
        </h1>

        <p>
            Choisissez un nouveau mot de passe sécurisé
            pour votre compte.
        </p>

    </header>


    @if ($errors->any())

        <div class="alert-error">

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
        action="{{ route('password.update') }}"
    >

        @csrf


        <input
            type="hidden"
            name="token"
            value="{{ $token }}"
        >


        <div class="form-group">

            <label for="email">
                Adresse e-mail
            </label>

            <input
                class="form-control"
                id="email"
                type="email"
                name="email"
                value="{{ old('email', $email) }}"
                required
                autocomplete="email"
            >

        </div>


        <div class="form-group">

            <label for="password">
                Nouveau mot de passe
            </label>


            <div class="password-wrapper">

                <input
                    class="form-control"
                    id="password"
                    type="password"
                    name="password"
                    minlength="8"
                    required
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

                <strong>
                    Le mot de passe doit contenir :
                </strong>

                <ul>

                    <li id="rule-length">
                        Au moins 8 caractères
                    </li>

                    <li id="rule-uppercase">
                        Au moins une lettre majuscule
                    </li>

                    <li id="rule-lowercase">
                        Au moins une lettre minuscule
                    </li>

                    <li id="rule-number">
                        Au moins un chiffre
                    </li>

                    <li id="rule-symbol">
                        Au moins un caractère spécial
                        (@, !, #, $, %, &, *, ?, ...)
                    </li>

                </ul>

            </div>

        </div>


        <div class="form-group">

            <label for="password_confirmation">
                Confirmer le nouveau mot de passe
            </label>


            <div class="password-wrapper">

                <input
                    class="form-control"
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    minlength="8"
                    required
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
            Réinitialiser mon mot de passe
        </button>

    </form>

</main>


<script>

    const passwordInput =
        document.getElementById('password');


    const rules = {
        length:
            document.getElementById('rule-length'),

        uppercase:
            document.getElementById('rule-uppercase'),

        lowercase:
            document.getElementById('rule-lowercase'),

        number:
            document.getElementById('rule-number'),

        symbol:
            document.getElementById('rule-symbol')
    };


    function updateRule(element, valid) {
        element.classList.toggle(
            'valid',
            valid
        );
    }


    passwordInput.addEventListener(
        'input',
        function () {

            const value =
                passwordInput.value;


            updateRule(
                rules.length,
                value.length >= 8
            );


            updateRule(
                rules.uppercase,
                /[A-Z]/.test(value)
            );


            updateRule(
                rules.lowercase,
                /[a-z]/.test(value)
            );


            updateRule(
                rules.number,
                /[0-9]/.test(value)
            );


            updateRule(
                rules.symbol,
                /[^A-Za-z0-9]/.test(value)
            );
        }
    );


    document
        .querySelectorAll('.password-toggle')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    const field =
                        document.getElementById(
                            button.dataset.target
                        );


                    if (field.type === 'password') {

                        field.type = 'text';

                        button.textContent =
                            'Masquer';

                    } else {

                        field.type = 'password';

                        button.textContent =
                            'Afficher';
                    }
                }
            );
        });

</script>

</body>

</html>
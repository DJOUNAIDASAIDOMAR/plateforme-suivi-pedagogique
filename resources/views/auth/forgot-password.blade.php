<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Mot de passe oublié - Plateforme de suivi pédagogique
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
            max-width: 500px;
            padding: 35px;
            border-radius: 18px;
            background: #ffffff;

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

        .alert {
            margin-bottom: 20px;
            padding: 14px 16px;
            border-radius: 10px;
            line-height: 1.6;
        }

        .alert-success {
            border: 1px solid #bbf7d0;
            background: #f0fdf4;
            color: #15803d;
        }

        .alert-error {
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #b91c1c;
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

    </style>

</head>

<body>

<main class="auth-container">

    <header class="auth-header">

        <h1>
            Mot de passe oublié ?
        </h1>

        <p>
            Saisissez l’adresse e-mail associée à votre compte.
            Nous vous enverrons un lien sécurisé pour choisir
            un nouveau mot de passe.
        </p>

    </header>


    @if (session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if ($errors->any())

        <div class="alert alert-error">

            @foreach ($errors->all() as $error)

                <div>
                    {{ $error }}
                </div>

            @endforeach

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('password.email') }}"
    >

        @csrf


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
                autofocus
                autocomplete="email"
            >

        </div>


        <button
            class="button"
            type="submit"
        >
            Envoyer le lien de réinitialisation
        </button>

    </form>


    <footer class="auth-footer">

        <a href="{{ route('login') }}">
            ← Retour à la connexion
        </a>

    </footer>

</main>

</body>

</html>
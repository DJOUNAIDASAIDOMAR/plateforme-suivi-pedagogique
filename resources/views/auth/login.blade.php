<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Connexion - Plateforme de suivi pédagogique
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
            max-width: 470px;
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

        .alert-success {
            border: 1px solid #bbf7d0;
            background: #f0fdf4;
            color: #15803d;
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
        }

        .form-control:focus {
            border-color: #2563eb;

            box-shadow:
                0 0 0 3px
                rgba(37, 99, 235, 0.12);
        }

        .forgot-password {
            margin-top: -8px;
            margin-bottom: 20px;
            text-align: right;
        }

        .forgot-password a {
            color: #2563eb;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .remember-row {
            display: flex;
            margin-bottom: 20px;
            gap: 9px;
            align-items: center;
        }

        .remember-row label {
            cursor: pointer;
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
            Connexion
        </h1>

        <p>
            Accédez à votre espace pédagogique.
        </p>

    </header>


    @if (session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


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
        action="{{ route('login.store') }}"
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


        <div class="form-group">

            <label for="password">
                Mot de passe
            </label>

            <input
                class="form-control"
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            >

        </div>


        <div class="forgot-password">

            <a href="{{ route('password.request') }}">
                Mot de passe oublié ?
            </a>

        </div>


        <div class="remember-row">

            <input
                id="remember"
                type="checkbox"
                name="remember"
                value="1"
            >

            <label for="remember">
                Se souvenir de moi
            </label>

        </div>


        <button
            class="button"
            type="submit"
        >
            Se connecter
        </button>

    </form>


    <footer class="auth-footer">

        Vous n’avez pas encore de compte ?

        <a href="{{ route('register') }}">
            S’inscrire
        </a>

    </footer>

</main>

</body>

</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Tableau de bord
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 40px 20px;
            font-family: Arial, sans-serif;
            background: #f3f6fb;
            color: #1f2937;
        }

        .dashboard {
            width: 100%;
            max-width: 850px;
            margin: 0 auto;
            padding: 35px;
            background: #ffffff;
            border-radius: 18px;
            box-shadow:
                0 15px 45px
                rgba(30, 64, 175, 0.10);
        }

        h1 {
            margin-top: 0;
            color: #1d4ed8;
        }

        .message {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            background: #f0fdf4;
            color: #15803d;
        }

        .information {
            margin: 20px 0;
            padding: 20px;
            border-radius: 12px;
            background: #eff6ff;
        }

        .information p {
            margin: 8px 0;
        }

        .logout-button {
            padding: 12px 20px;
            border: none;
            border-radius: 9px;
            background: #dc2626;
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }

        .logout-button:hover {
            background: #b91c1c;
        }
    </style>
</head>

<body>
    @php
        $roleLabel = match (auth()->user()->role) {
            'etudiant' => 'Étudiant',
            'formateur' => 'Formateur',
            'responsable_pedagogique' => 'Responsable pédagogique',
            default => 'Utilisateur',
        };
    @endphp

    <main class="dashboard">
        <h1>
            Bienvenue {{ auth()->user()->nom }}
        </h1>

        @if (session('success'))
            <div class="message">
                {{ session('success') }}
            </div>
        @endif

        <div class="information">
            <p>
                <strong>Nom :</strong>
                {{ auth()->user()->nom }}
            </p>

            <p>
                <strong>E-mail :</strong>
                {{ auth()->user()->email }}
            </p>

            <p>
                <strong>Rôle :</strong>
                {{ $roleLabel }}
            </p>
        </div>

        <p>
            Cette page est provisoire. Nous créerons ensuite un
            tableau de bord différent pour chaque rôle.
        </p>

        <form
            method="POST"
            action="{{ route('logout') }}"
        >
            @csrf

            <button
                class="logout-button"
                type="submit"
            >
                Se déconnecter
            </button>
        </form>
    </main>
</body>
</html>
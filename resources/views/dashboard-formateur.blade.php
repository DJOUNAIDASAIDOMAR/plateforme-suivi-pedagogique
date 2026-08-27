@extends('layouts.dashboard')

@section(
    'title',
    'Espace formateur - Plateforme de Suivi Pédagogique'
)

@push('styles')
<style>
    .dashboard-page {
        padding: 30px;
    }

    .topbar {
        display: flex;

        margin-bottom: 30px;
        padding: 20px 25px;

        border-radius: 15px;

        align-items: center;
        justify-content: space-between;

        gap: 20px;

        background: #ffffff;

        box-shadow:
            0 8px 25px
            rgba(30, 64, 175, 0.08);
    }

    .topbar h1 {
        margin: 0;

        color: #1d4ed8;

        font-size: 26px;
    }

    .user-name {
        font-weight: 800;
    }

    .success-message {
        margin-bottom: 25px;

        padding: 15px;

        border: 1px solid #bbf7d0;
        border-radius: 10px;

        background: #f0fdf4;
        color: #15803d;
    }

    .welcome-card {
        margin-bottom: 25px;

        padding: 28px;

        border-radius: 18px;

        background:
            linear-gradient(
                135deg,
                #2563eb,
                #1e3a8a
            );

        color: #ffffff;
    }

    .welcome-card h2 {
        margin: 0 0 10px;

        font-size: 28px;
    }

    .welcome-card p {
        margin: 0;

        color: #dbeafe;

        line-height: 1.7;
    }

    .cards {
        display: grid;

        grid-template-columns:
            repeat(3, 1fr);

        gap: 20px;

        margin-bottom: 25px;
    }

    .card {
        padding: 22px;

        border-radius: 15px;

        background: #ffffff;

        box-shadow:
            0 8px 25px
            rgba(30, 64, 175, 0.08);
    }

    .card h3 {
        margin: 0 0 12px;

        color: #1d4ed8;

        font-size: 17px;
    }

    .card .value {
        font-size: 27px;
        font-weight: 800;
    }

    .information-card {
        padding: 25px;

        border-radius: 15px;

        background: #ffffff;

        box-shadow:
            0 8px 25px
            rgba(30, 64, 175, 0.08);
    }

    .information-card h2 {
        margin: 0 0 20px;

        color: #1d4ed8;
    }

    .information-row {
        display: flex;

        padding: 14px 0;

        border-bottom: 1px solid #e5e7eb;

        gap: 15px;
    }

    .information-row:last-child {
        border-bottom: none;
    }

    .information-label {
        width: 190px;

        flex-shrink: 0;

        font-weight: 800;
    }

    @media (max-width: 950px) {
        .cards {
            grid-template-columns: 1fr;
        }

        .topbar {
            align-items: flex-start;
            flex-direction: column;
        }
    }

    @media (max-width: 650px) {
        .dashboard-page {
            padding: 20px;
        }

        .information-row {
            flex-direction: column;
        }

        .information-label {
            width: auto;
        }
    }
</style>
@endpush


@section('content')

<section class="dashboard-page">

    <header class="topbar">

        <h1>
            Tableau de bord formateur
        </h1>

        <div class="user-name">
            {{ $user->nom }}
        </div>

    </header>


    @if (session('success'))

        <div class="success-message">
            {{ session('success') }}
        </div>

    @endif


    <section class="welcome-card">

        <h2>
            Bienvenue {{ $user->nom }}
        </h2>

        <p>
            Consultez les cours qui vous sont attribués,
            ajoutez les leçons et les contenus pédagogiques,
            créez les quiz et suivez les résultats
            des étudiants.
        </p>

    </section>


    <section class="cards">

        <article class="card">

            <h3>
                Cours attribués
            </h3>

            <div class="value">

                {{
                    $user->formateur
                        ? $user->formateur
                            ->cours()
                            ->count()
                        : 0
                }}

            </div>

        </article>


        <article class="card">

            <h3>
                Quiz créés
            </h3>

            <div class="value">

                {{
                    $user->formateur
                        ? $user->formateur
                            ->cours()
                            ->withCount('quizzes')
                            ->get()
                            ->sum('quizzes_count')
                        : 0
                }}

            </div>

        </article>


        <article class="card">

            <h3>
                Étudiants suivis
            </h3>

            <div class="value">
                0
            </div>

        </article>

    </section>


    <section class="information-card">

        <h2>
            Mes informations
        </h2>


        <div class="information-row">

            <div class="information-label">
                Nom complet
            </div>

            <div>
                {{ $user->nom }}
            </div>

        </div>


        <div class="information-row">

            <div class="information-label">
                Adresse e-mail
            </div>

            <div>
                {{ $user->email }}
            </div>

        </div>


        <div class="information-row">

            <div class="information-label">
                Rôle
            </div>

            <div>
                Formateur
            </div>

        </div>


        <div class="information-row">

            <div class="information-label">
                Spécialité
            </div>

            <div>

                {{
                    $user
                        ->formateur
                        ?->{'specialité'}
                    ?? 'Spécialité non renseignée'
                }}

            </div>

        </div>


        <div class="information-row">

            <div class="information-label">
                Date d’inscription
            </div>

            <div>

                {{
                    $user->date_inscription
                        ? $user
                            ->date_inscription
                            ->format('d/m/Y')
                        : 'Non renseignée'
                }}

            </div>

        </div>

    </section>

</section>

@endsection
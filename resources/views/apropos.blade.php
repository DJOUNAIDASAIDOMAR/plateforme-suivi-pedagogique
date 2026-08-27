@extends('layouts.site')

@section('title', 'À propos - Plateforme de Suivi Pédagogique')

@push('styles')
<style>
    .about-hero {
        padding: 90px 30px 70px;
        background:
            linear-gradient(
                135deg,
                #eff6ff,
                #dbeafe
            );
        text-align: center;
    }

    .about-hero-container {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
    }

    .about-badge {
        display: inline-block;
        margin-bottom: 18px;
        padding: 9px 16px;
        border-radius: 30px;
        background: #ffffff;
        color: #1d4ed8;
        font-size: 14px;
        font-weight: 800;
    }

    .about-hero h1 {
        margin: 0 0 20px;
        color: #172554;
        font-size: 48px;
        line-height: 1.2;
    }

    .about-hero p {
        max-width: 760px;
        margin: 0 auto;
        color: #4b5563;
        font-size: 18px;
        line-height: 1.8;
    }

    .about-section {
        padding: 80px 30px;
        background: #ffffff;
    }

    .about-section-light {
        background: #f3f6fb;
    }

    .about-container {
        width: 100%;
        max-width: 1150px;
        margin: 0 auto;
    }

    .about-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 45px;
        align-items: center;
    }

    .about-content h2 {
        margin: 0 0 20px;
        color: #1d4ed8;
        font-size: 34px;
    }

    .about-content p {
        margin: 0 0 16px;
        color: #6b7280;
        font-size: 17px;
        line-height: 1.8;
    }

    .about-card {
        padding: 32px;
        border-radius: 20px;
        background:
            linear-gradient(
                135deg,
                #1e3a8a,
                #2563eb
            );
        color: #ffffff;
        box-shadow:
            0 20px 45px
            rgba(30, 64, 175, 0.22);
    }

    .about-card h3 {
        margin: 0 0 18px;
        font-size: 27px;
    }

    .about-card p {
        margin: 0;
        color: #dbeafe;
        line-height: 1.8;
    }

    .values-title {
        max-width: 720px;
        margin: 0 auto 42px;
        text-align: center;
    }

    .values-title h2 {
        margin: 0 0 14px;
        color: #172554;
        font-size: 36px;
    }

    .values-title p {
        margin: 0;
        color: #6b7280;
        font-size: 17px;
        line-height: 1.7;
    }

    .values-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
    }

    .value-card {
        padding: 28px;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        background: #ffffff;
        box-shadow:
            0 10px 28px
            rgba(30, 64, 175, 0.06);
    }

    .value-number {
        display: flex;
        width: 50px;
        height: 50px;
        margin-bottom: 18px;
        border-radius: 14px;
        align-items: center;
        justify-content: center;
        background: #dbeafe;
        color: #1d4ed8;
        font-weight: 900;
    }

    .value-card h3 {
        margin: 0 0 12px;
        color: #1e3a8a;
        font-size: 20px;
    }

    .value-card p {
        margin: 0;
        color: #6b7280;
        line-height: 1.7;
    }

    .about-cta {
        padding: 70px 30px;
        background: #ffffff;
    }

    .about-cta-box {
        display: flex;
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        padding: 40px;
        border-radius: 22px;
        align-items: center;
        justify-content: space-between;
        gap: 30px;
        background:
            linear-gradient(
                135deg,
                #2563eb,
                #1e3a8a
            );
        color: #ffffff;
    }

    .about-cta-box h2 {
        margin: 0 0 10px;
        font-size: 30px;
    }

    .about-cta-box p {
        margin: 0;
        color: #dbeafe;
        line-height: 1.7;
    }

    .about-cta-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .about-cta-actions a {
        display: inline-flex;
        min-height: 46px;
        padding: 12px 20px;
        border-radius: 9px;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        text-decoration: none;
    }

    .about-register {
        background: #ffffff;
        color: #1d4ed8;
    }

    .about-login {
        border: 1px solid #ffffff;
        color: #ffffff;
    }

    .about-footer {
        padding: 35px 25px;
        background: #172554;
        color: #dbeafe;
        text-align: center;
    }

    .about-footer strong {
        color: #ffffff;
    }

    @media (max-width: 900px) {
        .about-grid {
            grid-template-columns: 1fr;
        }

        .values-grid {
            grid-template-columns: 1fr;
        }

        .about-cta-box {
            align-items: flex-start;
            flex-direction: column;
        }
    }

    @media (max-width: 650px) {
        .about-hero {
            padding: 65px 20px 50px;
        }

        .about-hero h1 {
            font-size: 37px;
        }

        .about-section {
            padding: 60px 20px;
        }

        .values-title h2 {
            font-size: 30px;
        }

        .about-cta {
            padding: 50px 20px;
        }

        .about-cta-box {
            padding: 30px 22px;
        }

        .about-cta-actions {
            width: 100%;
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<section class="about-hero">
    <div class="about-hero-container">
        <span class="about-badge">
            À propos de notre plateforme
        </span>

        <h1>
            Une plateforme pensée pour accompagner
            la réussite des étudiants
        </h1>

        <p>
            La Plateforme de Suivi Pédagogique facilite l’accès aux
            cours, aux ressources, aux quiz et aux résultats tout au
            long du parcours de formation.
        </p>
    </div>
</section>

<section class="about-section">
    <div class="about-container">
        <div class="about-grid">
            <div class="about-content">
                <h2>
                    Notre mission
                </h2>

                <p>
                    Notre mission est de proposer un espace numérique
                    simple, clair et organisé permettant aux étudiants
                    de retrouver facilement les contenus correspondant
                    à leur filière.
                </p>

                <p>
                    Les formateurs peuvent créer et organiser les cours,
                    les leçons, les ressources et les quiz, tandis que
                    le responsable pédagogique supervise l’ensemble de
                    la plateforme.
                </p>

                <p>
                    Chaque utilisateur dispose ainsi d’un espace adapté
                    à son rôle et à ses besoins.
                </p>
            </div>

            <div class="about-card">
                <h3>
                    Un suivi pédagogique complet
                </h3>

                <p>
                    La plateforme permet de centraliser les cours,
                    d’évaluer les connaissances avec des quiz à choix
                    multiples et de consulter les résultats afin de
                    suivre la progression des étudiants.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="about-section about-section-light">
    <div class="about-container">
        <header class="values-title">
            <h2>
                Nos objectifs
            </h2>

            <p>
                Nous souhaitons simplifier l’apprentissage et améliorer
                la communication entre les différents acteurs de la
                formation.
            </p>
        </header>

        <div class="values-grid">
            <article class="value-card">
                <div class="value-number">
                    01
                </div>

                <h3>
                    Faciliter l’accès aux cours
                </h3>

                <p>
                    Les étudiants retrouvent les cours, les leçons et
                    les ressources pédagogiques depuis un seul espace.
                </p>
            </article>

            <article class="value-card">
                <div class="value-number">
                    02
                </div>

                <h3>
                    Évaluer les connaissances
                </h3>

                <p>
                    Les quiz à choix multiples permettent de tester les
                    acquis et de consulter rapidement les scores.
                </p>
            </article>

            <article class="value-card">
                <div class="value-number">
                    03
                </div>

                <h3>
                    Suivre la progression
                </h3>

                <p>
                    Les résultats et les informations pédagogiques
                    permettent de mieux accompagner chaque étudiant.
                </p>
            </article>
        </div>
    </div>
</section>

<section class="about-cta">
    <div class="about-cta-box">
        <div>
            <h2>
                Rejoignez la plateforme
            </h2>

            <p>
                Créez votre compte pour accéder à votre espace et aux
                fonctionnalités correspondant à votre rôle.
            </p>
        </div>

        <div class="about-cta-actions">
            <a
                href="{{ route('register') }}"
                class="about-register"
            >
                Inscription
            </a>

            <a
                href="{{ route('login') }}"
                class="about-login"
            >
                Connexion
            </a>
        </div>
    </div>
</section>

<footer class="about-footer">
    <strong>
        Plateforme de Suivi Pédagogique
    </strong>

    <p>
        Apprendre, progresser et réussir.
    </p>
</footer>
@endsection
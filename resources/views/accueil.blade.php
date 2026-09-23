@extends('layouts.site')

@section('title', 'Accueil - Plateforme de Suivi Pédagogique')

@push('styles')
<style>
    .hero {
        min-height: 620px;
        padding: 75px 30px;
        background:
linear-gradient(
135deg,
#F8FAFC 0%,
#EEF4FF 45%,
#DBEAFE 100%);
    }

    .hero-container {
        display: grid;
        width: 100%;
        max-width: 1250px;
        margin: 0 auto;
        align-items: center;
        grid-template-columns: 1fr 1fr;
        gap: 65px;
    }

    .hero-badge {
        display: inline-block;
        margin-bottom: 20px;
        padding: 9px 16px;
        border-radius: 30px;
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 14px;
        font-weight: 800;
    }

    .hero-content h1 {
        margin: 0 0 24px;
        color: #172554;
        font-size: 53px;
        line-height: 1.13;
    }

    .hero-content h1 span {
        color: #2563eb;
    }

    .hero-content p {
        max-width: 650px;
        margin: 0 0 30px;
        color: #4b5563;
        font-size: 18px;
        line-height: 1.8;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
    }

    .hero-button {
        display: inline-flex;
        min-height: 49px;
        padding: 13px 23px;
        border-radius: 9px;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        text-decoration: none;
        transition: 0.2s;
    }

    .hero-button-primary {
        background: #2563eb;
        color: #ffffff;
    }

    .hero-button-primary:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
    }

    .hero-button-secondary {
        border: 1px solid #2563eb;
        background: #ffffff;
        color: #2563eb;
    }

    .hero-button-secondary:hover {
        background: #eff6ff;
        transform: translateY(-2px);
    }

    .hero-image-wrapper {
        position: relative;
    }

    .hero-image-background {
    position: absolute;
    top: -22px;
    right: -22px;
    width: 100%;
    height: 100%;
    border-radius: 35px;

    background: linear-gradient(
        135deg,
        #0F172A 0%,
        #1E3A8A 60%,
        #2563EB 100%
    );

    box-shadow:
        0 25px 60px rgba(30,58,138,.35);

    z-index: 1;
}

   .hero-image {
    position: relative;
    z-index: 2;

    width: 100%;
    height: 500px;

    object-fit: cover;

    border-radius: 30px;

    border: 8px solid white;

    box-shadow:
        0 25px 55px rgba(15,23,42,.25);

    transition: .4s;
}

.hero-image:hover{
    transform: scale(1.02);
}

    .section {
        padding: 80px 30px;
    }

    .section-white {
        background: #ffffff;
    }

    .section-light {
        background: #f3f6fb;
    }

    .section-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .section-heading {
        max-width: 760px;
        margin: 0 auto 45px;
        text-align: center;
    }

    .small-title {
        display: inline-block;
        margin-bottom: 12px;
        color: #2563eb;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .section-heading h2 {
        margin: 0 0 15px;
        color: #172554;
        font-size: 38px;
    }

    .section-heading p {
        margin: 0;
        color: #6b7280;
        font-size: 17px;
        line-height: 1.7;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .feature-card {
        padding: 28px 23px;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        background: #ffffff;
        text-align: center;
        box-shadow:
            0 10px 30px
            rgba(30, 64, 175, 0.06);
        transition: 0.2s;
    }

    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow:
            0 18px 40px
            rgba(30, 64, 175, 0.13);
    }

    .feature-icon {
        display: flex;
        width: 58px;
        height: 58px;
        margin: 0 auto 19px;
        border-radius: 16px;
        align-items: center;
        justify-content: center;
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 25px;
        font-weight: 900;
    }

    .feature-card h3 {
        margin: 0 0 12px;
        color: #1e3a8a;
        font-size: 19px;
    }

    .feature-card p {
        margin: 0;
        color: #6b7280;
        line-height: 1.7;
    }

    .filiere-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
    }

    .filiere-card {
        position: relative;
        overflow: hidden;
        min-height: 225px;
        padding: 30px;
        border-radius: 20px;
        background: #ffffff;
        box-shadow:
            0 12px 35px
            rgba(30, 64, 175, 0.09);
    }

    .filiere-number {
        position: absolute;
        top: -20px;
        right: 10px;
        color: #eff6ff;
        font-size: 100px;
        font-weight: 900;
    }

    .filiere-card h3 {
        position: relative;
        margin: 0 0 15px;
        color: #1d4ed8;
        font-size: 23px;
    }

    .filiere-card p {
        position: relative;
        margin: 0;
        color: #6b7280;
        line-height: 1.75;
    }

    .technology-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 14px;
    }

    .technology {
        padding: 22px 10px;
        border: 1px solid #dbeafe;
        border-radius: 15px;
        background: #ffffff;
        text-align: center;
        box-shadow:
            0 8px 22px
            rgba(30, 64, 175, 0.06);
        transition: 0.2s;
    }

    .technology:hover {
        border-color: #2563eb;
        transform: translateY(-4px);
    }

    .technology-code {
        display: flex;
        width: 55px;
        height: 55px;
        margin: 0 auto 13px;
        border-radius: 14px;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 15px;
        font-weight: 900;
    }

    .technology h3 {
        margin: 0;
        color: #1f2937;
        font-size: 15px;
    }

    .call-to-action {
        display: grid;
        padding: 48px;
        border-radius: 25px;
        align-items: center;
        grid-template-columns: 1fr auto;
        gap: 35px;
        background:
            linear-gradient(
                135deg,
                #2563eb,
                #1e3a8a
            );
        color: #ffffff;
    }

    .call-to-action h2 {
        margin: 0 0 13px;
        font-size: 33px;
    }

    .call-to-action p {
        max-width: 730px;
        margin: 0;
        color: #dbeafe;
        font-size: 17px;
        line-height: 1.7;
    }

    .call-to-action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .call-to-action-buttons a {
        display: inline-flex;
        min-height: 47px;
        padding: 12px 21px;
        border-radius: 9px;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        text-decoration: none;
    }

    .cta-register {
        background: #ffffff;
        color: #1d4ed8;
    }

    .cta-login {
        border: 1px solid #ffffff;
        color: #ffffff;
    }

    .main-footer {
        padding: 45px 25px;
        background: #172554;
        color: #dbeafe;
        text-align: center;
    }

    .main-footer h2 {
        margin: 0 0 10px;
        color: #ffffff;
        font-size: 22px;
    }

    .main-footer p {
        margin: 5px 0;
    }

    @media (max-width: 1100px) {
        .hero-container {
            grid-template-columns: 1fr;
        }

        .hero-content {
            text-align: center;
        }

        .hero-content p {
            margin-right: auto;
            margin-left: auto;
        }

        .hero-actions {
            justify-content: center;
        }

        .hero-image-wrapper {
            max-width: 650px;
            margin: 0 auto;
        }

        .feature-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .technology-grid {
            grid-template-columns: repeat(4, 1fr);
        }

        .call-to-action {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .call-to-action-buttons {
            justify-content: center;
        }
    }

    @media (max-width: 750px) {
        .hero {
            padding: 55px 20px;
        }

        .hero-content h1 {
            font-size: 38px;
        }

        .hero-image {
            height: 340px;
        }

        .section {
            padding: 60px 20px;
        }

        .section-heading h2 {
            font-size: 30px;
        }

        .feature-grid,
        .filiere-grid {
            grid-template-columns: 1fr;
        }

        .technology-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .call-to-action {
            padding: 32px 22px;
        }

        .call-to-action-buttons {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<section class="hero">
    <div class="hero-container">
        <div class="hero-content">
            <span class="hero-badge">
               Plateforme de suivi pédagogique
            </span>

            <h1>
                Développez vos compétences et
                <span>suivez votre progression</span>
            </h1>

            <p>
                La Plateforme de Suivi Pédagogique accompagne les
                étudiants dans leur apprentissage. Consultez les
                cours de votre filière, accédez aux ressources,
                passez les quiz et suivez vos résultats.
            </p>

            <div class="hero-actions">
                <a
                    href="{{ route('register') }}"
                    class="hero-button hero-button-primary"
                >
                    Commencer maintenant
                </a>

                <a
                    href="{{ route('cours.public') }}"
                    class="hero-button hero-button-secondary"
                >
                    Découvrir les cours
                </a>
            </div>
        </div>

        <div class="hero-image-wrapper">
            <div class="hero-image-background"></div>

            <img
                class="hero-image"
                src="{{ asset('images/hero.png') }}"
                alt="Étudiante suivant une formation en ligne"
            >
        </div>
    </div>
</section>

<section class="section section-white">
    <div class="section-container">
        <header class="section-heading">
            <span class="small-title">
                Nos avantages
            </span>

            <h2>
                Pourquoi choisir notre plateforme ?
            </h2>

            <p>
                Une solution simple et complète pour accompagner les
                étudiants et faciliter le travail des formateurs.
            </p>
        </header>

        <div class="feature-grid">
            <article class="feature-card">
                <div class="feature-icon">01</div>

                <h3>Cours organisés</h3>

                <p>
                    Retrouvez facilement les cours, les leçons et
                    les documents correspondant à votre filière.
                </p>
            </article>

            <article class="feature-card">
                <div class="feature-icon">02</div>

                <h3>Quiz interactifs</h3>

                <p>
                    Testez vos connaissances grâce à des quiz à
                    choix multiples préparés par les formateurs.
                </p>
            </article>

            <article class="feature-card">
                <div class="feature-icon">03</div>

                <h3>Suivi des résultats</h3>

                <p>
                    Consultez vos scores et identifiez les notions
                    qui nécessitent davantage de révision.
                </p>
            </article>

            <article class="feature-card">
                <div class="feature-icon">04</div>

                <h3>Progression personnelle</h3>

                <p>
                    Visualisez votre évolution pédagogique depuis
                    votre espace étudiant personnel.
                </p>
            </article>
        </div>
    </div>
</section>

<section class="section section-light">
    <div class="section-container">
        <header class="section-heading">
            <span class="small-title">
                Nos filières
            </span>

            <h2>
                Des formations adaptées à votre projet
            </h2>

            <p>
                Choisissez votre filière et accédez aux cours
                correspondant à votre parcours de formation.
            </p>
        </header>

        <div class="filiere-grid">
            <article class="filiere-card">
                <div class="filiere-number">01</div>

                <h3>
                    Développement Web et Web Mobile
                </h3>

                <p>
                    Apprenez à créer des sites et des applications
                    web avec les technologies front-end, back-end
                    et les bases de données.
                </p>
            </article>

            <article class="filiere-card">
                <div class="filiere-number">02</div>

                <h3>
                    Intelligence Artificielle et Data
                </h3>

                <p>
                    Découvrez l’analyse des données, les principes
                    de l’intelligence artificielle et les modèles
                    d’apprentissage automatique.
                </p>
            </article>

            <article class="filiere-card">
                <div class="filiere-number">03</div>

                <h3>
                    Informatique Bureautique
                </h3>

                <p>
                    Développez votre maîtrise des outils numériques,
                    des logiciels bureautiques et de la gestion des
                    documents professionnels.
                </p>
            </article>
        </div>
    </div>
</section>

<section class="section section-white">
    <div class="section-container">
        <header class="section-heading">
            <span class="small-title">
                Technologies
            </span>

            <h2>
                Les compétences enseignées
            </h2>

            <p>
                Découvrez les principales technologies abordées dans
                les cours proposés sur la plateforme.
            </p>
        </header>

        <div class="technology-grid">
            <article class="technology">
                <div class="technology-code">HTML</div>
                <h3>HTML</h3>
            </article>

            <article class="technology">
                <div class="technology-code">CSS</div>
                <h3>CSS</h3>
            </article>

            <article class="technology">
                <div class="technology-code">JS</div>
                <h3>JavaScript</h3>
            </article>

            <article class="technology">
                <div class="technology-code">PHP</div>
                <h3>PHP</h3>
            </article>

            <article class="technology">
                <div class="technology-code">JAVA</div>
                <h3>Java</h3>
            </article>

            <article class="technology">
                <div class="technology-code">PY</div>
                <h3>Python</h3>
            </article>

            <article class="technology">
                <div class="technology-code">SQL</div>
                <h3>SQL</h3>
            </article>
        </div>
    </div>
</section>

<section class="section section-light">
    <div class="section-container">
        <div class="call-to-action">
            <div>
                <h2>
                    Commencez votre apprentissage dès maintenant
                </h2>

                <p>
                    Créez votre compte pour accéder aux cours, aux
                    contenus pédagogiques, aux quiz et à votre suivi
                    personnel.
                </p>
            </div>

            <div class="call-to-action-buttons">
                <a
                    href="{{ route('register') }}"
                    class="cta-register"
                >
                    S’inscrire
                </a>

                <a
                    href="{{ route('login') }}"
                    class="cta-login"
                >
                    Se connecter
                </a>
            </div>
        </div>
    </div>
</section>

<footer class="main-footer">
    <h2>
        Plateforme de Suivi Pédagogique
    </h2>

    <p>
        Apprendre, progresser et réussir.
    </p>

    <p>
        Projet de formation Développement Web et Web Mobile.
    </p>
</footer>
@endsection
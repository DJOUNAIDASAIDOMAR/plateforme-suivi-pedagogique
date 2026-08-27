@extends('layouts.site')

@section('title', 'Contact - Plateforme de Suivi Pédagogique')

@push('styles')
<style>
    .contact-hero {
        padding: 85px 30px 65px;
        background:
            linear-gradient(
                135deg,
                #eff6ff,
                #dbeafe
            );
        text-align: center;
    }

    .contact-hero-container {
        width: 100%;
        max-width: 850px;
        margin: 0 auto;
    }

    .contact-badge {
        display: inline-block;
        margin-bottom: 18px;
        padding: 9px 16px;
        border-radius: 30px;
        background: #ffffff;
        color: #1d4ed8;
        font-size: 14px;
        font-weight: 800;
    }

    .contact-hero h1 {
        margin: 0 0 18px;
        color: #172554;
        font-size: 47px;
    }

    .contact-hero p {
        max-width: 720px;
        margin: 0 auto;
        color: #4b5563;
        font-size: 18px;
        line-height: 1.8;
    }

    .contact-section {
        padding: 75px 30px;
        background: #f3f6fb;
    }

    .contact-container {
        display: grid;
        width: 100%;
        max-width: 1150px;
        margin: 0 auto;
        grid-template-columns: 0.8fr 1.2fr;
        gap: 30px;
        align-items: stretch;
    }

    .contact-information {
        padding: 35px;
        border-radius: 22px;
        background:
            linear-gradient(
                145deg,
                #1e3a8a,
                #2563eb
            );
        color: #ffffff;
        box-shadow:
            0 20px 45px
            rgba(30, 64, 175, 0.22);
    }

    .contact-information h2 {
        margin: 0 0 14px;
        font-size: 29px;
    }

    .contact-introduction {
        margin: 0 0 30px;
        color: #dbeafe;
        line-height: 1.75;
    }

    .contact-detail {
        display: flex;
        margin-bottom: 20px;
        padding: 17px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 13px;
        align-items: flex-start;
        gap: 15px;
        background: rgba(255, 255, 255, 0.10);
    }

    .contact-detail:last-child {
        margin-bottom: 0;
    }

    .contact-icon {
        display: flex;
        width: 43px;
        height: 43px;
        flex-shrink: 0;
        border-radius: 12px;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        color: #1d4ed8;
        font-weight: 900;
    }

    .contact-detail h3 {
        margin: 0 0 6px;
        font-size: 16px;
    }

    .contact-detail p {
        margin: 0;
        color: #dbeafe;
        line-height: 1.6;
    }

    .contact-form-card {
        padding: 38px;
        border-radius: 22px;
        background: #ffffff;
        box-shadow:
            0 15px 40px
            rgba(30, 64, 175, 0.10);
    }

    .contact-form-card h2 {
        margin: 0 0 10px;
        color: #172554;
        font-size: 29px;
    }

    .contact-form-card > p {
        margin: 0 0 28px;
        color: #6b7280;
        line-height: 1.7;
    }

    .success-message {
        margin-bottom: 22px;
        padding: 15px;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        background: #f0fdf4;
        color: #15803d;
    }

    .error-message {
        margin-bottom: 22px;
        padding: 15px;
        border: 1px solid #fecaca;
        border-radius: 10px;
        background: #fef2f2;
        color: #b91c1c;
    }

    .error-message ul {
        margin: 0;
        padding-left: 20px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #1f2937;
        font-weight: 700;
    }

    .form-control {
        width: 100%;
        padding: 13px 14px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        outline: none;
        font: inherit;
        transition: 0.2s;
    }

    .form-control:focus {
        border-color: #2563eb;
        box-shadow:
            0 0 0 3px
            rgba(37, 99, 235, 0.12);
    }

    textarea.form-control {
        min-height: 145px;
        resize: vertical;
    }

    .submit-button {
        width: 100%;
        padding: 14px 20px;
        border: none;
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
        transform: translateY(-1px);
    }

    .contact-footer {
        padding: 38px 25px;
        background: #172554;
        color: #dbeafe;
        text-align: center;
    }

    .contact-footer strong {
        color: #ffffff;
        font-size: 18px;
    }

    .contact-footer p {
        margin: 8px 0 0;
    }

    @media (max-width: 900px) {
        .contact-container {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 650px) {
        .contact-hero {
            padding: 60px 20px 45px;
        }

        .contact-hero h1 {
            font-size: 36px;
        }

        .contact-section {
            padding: 55px 20px;
        }

        .contact-information,
        .contact-form-card {
            padding: 28px 22px;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }
    }
</style>
@endpush

@section('content')
<section class="contact-hero">
    <div class="contact-hero-container">
        <span class="contact-badge">
            Nous contacter
        </span>

        <h1>
            Une question ou besoin d’aide ?
        </h1>

        <p>
            Contactez l’équipe de la Plateforme de Suivi Pédagogique.
            Nous vous répondrons concernant les cours, les comptes
            utilisateurs ou le fonctionnement de la plateforme.
        </p>
    </div>
</section>

<section class="contact-section">
    <div class="contact-container">
        <aside class="contact-information">
            <h2>
                Nos coordonnées
            </h2>

            <p class="contact-introduction">
                Vous pouvez utiliser le formulaire ou nous contacter
                directement grâce aux informations ci-dessous.
            </p>

            <div class="contact-detail">
                <div class="contact-icon">
                    @
                </div>

                <div>
                    <h3>
                        Adresse e-mail
                    </h3>

                    <p>
                        contact@suivi-pedagogique.fr
                    </p>
                </div>
            </div>

            <div class="contact-detail">
                <div class="contact-icon">
                    T
                </div>

                <div>
                    <h3>
                        Téléphone
                    </h3>

                    <p>
                        +33 1 00 00 00 00
                    </p>
                </div>
            </div>

            <div class="contact-detail">
                <div class="contact-icon">
                    A
                </div>

                <div>
                    <h3>
                        Adresse
                    </h3>

                    <p>
                        Paris, France
                    </p>
                </div>
            </div>

            <div class="contact-detail">
                <div class="contact-icon">
                    H
                </div>

                <div>
                    <h3>
                        Horaires
                    </h3>

                    <p>
                        Du lundi au vendredi<br>
                        De 9 h à 17 h
                    </p>
                </div>
            </div>
        </aside>

        <div class="contact-form-card">
            <h2>
                Envoyer un message
            </h2>

            <p>
                Complétez le formulaire. Tous les champs sont
                obligatoires.
            </p>

            @if (session('contact_success'))
                <div class="success-message">
                    {{ session('contact_success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="error-message">
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
                action="{{ route('contact.store') }}"
            >
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="nom">
                            Nom complet
                        </label>

                        <input
                            class="form-control"
                            id="nom"
                            type="text"
                            name="nom"
                            value="{{ old('nom', auth()->user()->nom ?? '') }}"
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
                            value="{{ old('email', auth()->user()->email ?? '') }}"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="sujet">
                        Sujet
                    </label>

                    <input
                        class="form-control"
                        id="sujet"
                        type="text"
                        name="sujet"
                        value="{{ old('sujet') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="message">
                        Votre message
                    </label>

                    <textarea
                        class="form-control"
                        id="message"
                        name="message"
                        required
                    >{{ old('message') }}</textarea>
                </div>

                <button
                    class="submit-button"
                    type="submit"
                >
                    Envoyer le message
                </button>
            </form>
        </div>
    </div>
</section>

<footer class="contact-footer">
    <strong>
        Plateforme de Suivi Pédagogique
    </strong>

    <p>
        Apprendre, progresser et réussir.
    </p>
</footer>
@endsection
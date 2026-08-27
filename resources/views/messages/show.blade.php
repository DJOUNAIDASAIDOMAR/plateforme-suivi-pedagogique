@extends('layouts.dashboard')

@section(
    'title',
    $message->sujet
)

@push('styles')
<style>
    .page {
        min-height: 650px;
        padding: 50px 30px 80px;
        background: #f3f6fb;
    }

    .container {
        max-width: 850px;
        margin: 0 auto;
    }

    .back {
        display: inline-block;
        margin-bottom: 20px;
        color: #1e3a8a;
        font-weight: 800;
        text-decoration: none;
    }

    .mail-card {
        margin-bottom: 25px;
        padding: 35px;
        border-radius: 20px;
        background: white;
    }

    .mail-card h1 {
        margin: 0 0 20px;
        color: #172554;
    }

    .mail-info {
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e5e7eb;
        line-height: 1.8;
    }

    .mail-message {
        color: #374151;
        line-height: 1.8;
        white-space: pre-wrap;
    }

    .reply-card {
        padding: 30px;
        border-radius: 20px;
        background: white;
    }

    .reply-card h2 {
        margin-top: 0;
        color: #172554;
    }

    textarea {
        width: 100%;
        min-height: 150px;
        padding: 13px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        resize: vertical;
        font: inherit;
    }

    .button {
        width: 100%;
        margin-top: 12px;
        padding: 14px;
        border: none;
        border-radius: 10px;
        background: #1e3a8a;
        color: white;
        font-weight: 800;
        cursor: pointer;
    }

    .success {
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 10px;
        background: #f0fdf4;
        color: #15803d;
    }
</style>
@endpush

@section('content')

<section class="page">

    <div class="container">

        <a
            class="back"
            href="{{ route('messages.index') }}"
        >
            ← Retour aux messages
        </a>

        @if (session('success'))

            <div class="success">
                {{ session('success') }}
            </div>

        @endif

        <article class="mail-card">

            <h1>
                {{ $message->sujet }}
            </h1>

            <div class="mail-info">

                <strong>De :</strong>
                {{ $message->expediteur?->nom }}

                <br>

                <strong>À :</strong>
                {{ $message->destinataire?->nom }}

                <br>

                <strong>Cours :</strong>
                {{ $message->cours?->titre_cours }}

                <br>

                <strong>Date :</strong>

                {{
                    $message
                        ->date_message
                        ?->format('d/m/Y H:i')
                }}

            </div>

            <div class="mail-message">
                {{ $message->message }}
            </div>

        </article>


        @if (
            (int) $message->id_destinataire
            ===
            (int) $user->id_user
        )

            <section class="reply-card">

                <h2>
                    Répondre
                </h2>

                <form
                    method="POST"
                    action="{{
                        route(
                            'messages.reply',
                            $message->id_message
                        )
                    }}"
                >

                    @csrf

                    <textarea
                        name="message"
                        maxlength="3000"
                        placeholder="Écrivez votre réponse..."
                        required
                    >{{ old('message') }}</textarea>

                    <button
                        class="button"
                        type="submit"
                    >
                        Envoyer la réponse
                    </button>

                </form>

            </section>

        @endif

    </div>

</section>

@endsection
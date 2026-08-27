@extends('layouts.dashboard')

@section(
    'title',
    'Contacter le formateur'
)

@push('styles')
<style>
    .page {
        min-height: 650px;
        padding: 50px 30px 80px;
        background: #f3f6fb;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
    }

    .back {
        display: inline-block;
        margin-bottom: 20px;
        color: #1e3a8a;
        font-weight: 800;
        text-decoration: none;
    }

    .card {
        padding: 35px;
        border-radius: 20px;
        background: white;
    }

    .card h1 {
        margin-top: 0;
        color: #172554;
    }

    .group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 800;
    }

    .control {
        width: 100%;
        padding: 13px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        background: white;
        font: inherit;
    }

    textarea.control {
        min-height: 160px;
        resize: vertical;
    }

    .readonly {
        background: #f3f4f6;
    }

    .button {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 10px;
        background: #1e3a8a;
        color: white;
        font-weight: 800;
        cursor: pointer;
    }

    .error {
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 10px;
        background: #fef2f2;
        color: #b91c1c;
    }
</style>
@endpush

@section('content')

<section class="page">

    <div class="container">

        <a
            class="back"
            href="{{ route('etudiant.cours.show', $cours->id_cours) }}"
        >
            ← Retour au cours
        </a>

        <div class="card">

            <h1>
                Contacter le formateur
            </h1>

            @if ($errors->any())

                <div class="error">

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            @endif

            <form
                method="POST"
                action="{{
                    route(
                        'messages.store',
                        [
                            'idCours' =>
                                $cours->id_cours,

                            'idDestinataire' =>
                                $destinataire->id_user,
                        ]
                    )
                }}"
            >

                @csrf

                <div class="group">

                    <label>
                        Nom
                    </label>

                    <input
                        class="control readonly"
                        type="text"
                        value="{{ $user->nom }}"
                        readonly
                    >

                </div>

                <div class="group">

                    <label>
                        À
                    </label>

                    <input
                        class="control readonly"
                        type="text"
                        value="{{ $destinataire->nom }}"
                        readonly
                    >

                </div>

                <div class="group">

                    <label>
                        Cours
                    </label>

                    <input
                        class="control readonly"
                        type="text"
                        value="{{ $cours->titre_cours }}"
                        readonly
                    >

                </div>

                <div class="group">

                    <label for="sujet">
                        Sujet
                    </label>

                    <input
                        class="control"
                        id="sujet"
                        type="text"
                        name="sujet"
                        value="{{ old('sujet') }}"
                        maxlength="150"
                        required
                    >

                </div>

                <div class="group">

                    <label for="message">
                        Message
                    </label>

                    <textarea
                        class="control"
                        id="message"
                        name="message"
                        maxlength="3000"
                        required
                    >{{ old('message') }}</textarea>

                </div>

                <button
                    class="button"
                    type="submit"
                >
                    Envoyer le message
                </button>

            </form>

        </div>

    </div>

</section>

@endsection
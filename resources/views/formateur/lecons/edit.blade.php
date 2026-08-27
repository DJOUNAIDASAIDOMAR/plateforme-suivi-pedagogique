@extends('layouts.dashboard')

@section('title', 'Modifier une leçon')

@push('styles')
<style>
    .form-page {
        min-height: 650px;
        padding: 50px 30px 80px;
        background: #f3f6fb;
    }

    .form-container {
        width: 100%;
        max-width: 750px;
        margin: 0 auto;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 20px;
        color: #1e3a8a;
        font-weight: 800;
        text-decoration: none;
    }

    .form-card {
        padding: 35px;
        border-radius: 20px;
        background: #ffffff;
    }

    .form-card h1 {
        margin: 0 0 8px;
        color: #172554;
    }

    .form-card > p {
        margin: 0 0 28px;
        color: #6b7280;
    }

    .error-box {
        margin-bottom: 20px;
        padding: 15px;
        background: #fef2f2;
        color: #b91c1c;
        border-radius: 10px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 800;
    }

    .form-control {
        width: 100%;
        padding: 13px;
        border: 1px solid #d1d5db;
        border-radius: 9px;
        font: inherit;
    }

    .submit-button {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 10px;
        background: #1e3a8a;
        color: #ffffff;
        font-weight: 800;
        cursor: pointer;
    }
</style>
@endpush

@section('content')

<section class="form-page">

    <div class="form-container">

        <a
            class="back-link"
            href="{{
                route(
                    'formateur.lecons.index',
                    $cours->id_cours
                )
            }}"
        >
            ← Retour aux leçons
        </a>

        <div class="form-card">

            <h1>
                Modifier la leçon
            </h1>

            <p>
                Cours : {{ $cours->titre_cours }}
            </p>

            @if ($errors->any())

                <div class="error-box">
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
                action="{{
                    route(
                        'formateur.lecons.update',
                        [
                            $cours->id_cours,
                            $lecon->{'id_leçon'}
                        ]
                    )
                }}"
            >

                @csrf
                @method('PUT')

                <div class="form-group">

                    <label for="titre_leçon">
                        Titre de la leçon
                    </label>

                    <input
                        class="form-control"
                        id="titre_leçon"
                        type="text"
                        name="titre_leçon"
                        value="{{
                            old(
                                'titre_leçon',
                                $lecon->{'titre_leçon'}
                            )
                        }}"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="ordre">
                        Ordre
                    </label>

                    <input
                        class="form-control"
                        id="ordre"
                        type="number"
                        min="1"
                        name="ordre"
                        value="{{
                            old(
                                'ordre',
                                $lecon->ordre
                            )
                        }}"
                        required
                    >

                </div>

                <button
                    class="submit-button"
                    type="submit"
                >
                    Enregistrer les modifications
                </button>

            </form>

        </div>

    </div>

</section>

@endsection
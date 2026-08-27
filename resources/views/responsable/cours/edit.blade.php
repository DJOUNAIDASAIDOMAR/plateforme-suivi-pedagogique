@extends('layouts.dashboard')

@section(
    'title',
    'Modifier un cours - Responsable pédagogique'
)

@push('styles')
<style>
    .form-page {
        min-height: 650px;
        padding: 55px 30px 80px;
        background: #f3f6fb;
    }

    .form-container {
        width: 100%;
        max-width: 850px;
        margin: 0 auto;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 22px;
        color: #1e3a8a;
        font-weight: 800;
        text-decoration: none;
    }

    .form-card {
        padding: 38px;
        border-radius: 22px;
        background: #ffffff;
        box-shadow:
            0 15px 40px
            rgba(30, 64, 175, 0.10);
    }

    .form-card h1 {
        margin: 0 0 10px;
        color: #172554;
        font-size: 32px;
    }

    .introduction {
        margin: 0 0 30px;
        color: #6b7280;
        line-height: 1.7;
    }

    .error-box {
        margin-bottom: 22px;
        padding: 15px;
        border: 1px solid #fecaca;
        border-radius: 10px;
        background: #fef2f2;
        color: #b91c1c;
    }

    .error-box ul {
        margin: 0;
        padding-left: 20px;
    }

    .form-group {
        margin-bottom: 21px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #1f2937;
        font-weight: 800;
    }

    .form-control {
        width: 100%;
        padding: 13px 14px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        outline: none;
        background: #ffffff;
        color: #1f2937;
        font: inherit;
    }

    .form-control:focus {
        border-color: #1e3a8a;

        box-shadow:
            0 0 0 3px
            rgba(30, 58, 138, 0.12);
    }

    textarea.form-control {
        min-height: 150px;
        resize: vertical;
    }

    .help-text {
        margin-top: 7px;

        color: #6b7280;

        font-size: 13px;

        line-height: 1.5;
    }

    .submit-button {
        width: 100%;

        padding: 14px 20px;

        border: none;
        border-radius: 10px;

        background: #1e3a8a;
        color: #ffffff;

        font-size: 16px;
        font-weight: 800;

        cursor: pointer;
    }

    .submit-button:hover {
        background: #172554;
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
                    'responsable.cours.index'
                )
            }}"
        >
            ← Retour aux cours
        </a>


        <div class="form-card">

            <h1>
                Modifier le cours
            </h1>


            <p class="introduction">
                Modifiez les informations du cours,
                sa filière ou le formateur auquel
                il est attribué.
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
                        'responsable.cours.update',
                        $cours->id_cours
                    )
                }}"
            >

                @csrf

                @method('PUT')


                <div class="form-group">

                    <label for="titre_cours">
                        Titre du cours
                    </label>

                    <input
                        class="form-control"
                        id="titre_cours"
                        type="text"
                        name="titre_cours"
                        value="{{
                            old(
                                'titre_cours',
                                $cours->titre_cours
                            )
                        }}"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="description">
                        Description
                    </label>

                    <textarea
                        class="form-control"
                        id="description"
                        name="description"
                        required
                    >{{ old('description', $cours->description) }}</textarea>

                </div>


                <div class="form-group">

                    <label for="id_filier">
                        Filière
                    </label>

                    <select
                        class="form-control"
                        id="id_filier"
                        name="id_filier"
                        required
                    >

                        @foreach ($filieres as $filiere)

                            <option
                                value="{{
                                    $filiere->id_filier
                                }}"
                                {{
                                    (string)
                                    old(
                                        'id_filier',
                                        $cours->id_filier
                                    )
                                    ===
                                    (string)
                                    $filiere->id_filier
                                        ? 'selected'
                                        : ''
                                }}
                            >

                                {{
                                    $filiere->nom_filier
                                }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="form-group">

                    <label for="id_formateur">
                        Formateur attribué
                    </label>

                    <select
                        class="form-control"
                        id="id_formateur"
                        name="id_formateur"
                        required
                    >

                        @foreach (
                            $formateurs
                            as $formateur
                        )

                            <option
                                value="{{
                                    $formateur->id_formateur
                                }}"
                                {{
                                    (string)
                                    old(
                                        'id_formateur',
                                        $cours->id_formateur
                                    )
                                    ===
                                    (string)
                                    $formateur->id_formateur
                                        ? 'selected'
                                        : ''
                                }}
                            >

                                {{
                                    $formateur
                                        ->user
                                        ?->nom
                                    ?? 'Formateur'
                                }}

                                @if (
                                    $formateur
                                        ->{'specialité'}
                                )

                                    -
                                    {{
                                        $formateur
                                            ->{'specialité'}
                                    }}

                                @endif

                            </option>

                        @endforeach

                    </select>


                    <div class="help-text">
                        Vous pouvez attribuer ce cours
                        à un autre formateur.
                    </div>

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
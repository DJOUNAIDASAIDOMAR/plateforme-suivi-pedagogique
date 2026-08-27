@extends('layouts.dashboard')

@section(
    'title',
    'Modifier un contenu - Espace formateur'
)

@push('styles')
<style>
    .form-page {
        min-height: 650px;
        padding: 50px 30px 80px;
        background: #f3f6fb;
    }

    .form-container {
        width: 100%;
        max-width: 800px;
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
        box-shadow:
            0 12px 35px
            rgba(30, 64, 175, 0.09);
    }

    .form-card h1 {
        margin: 0 0 10px;
        color: #172554;
    }

    .form-description {
        margin: 0 0 25px;
        color: #6b7280;
    }

    .error-box {
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 10px;
        background: #fef2f2;
        color: #b91c1c;
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
        background: #ffffff;
        font: inherit;
    }

    .current-file {
        margin-top: 8px;
        color: #6b7280;
        font-size: 13px;
        line-height: 1.5;
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

    .submit-button:hover {
        background: #1d4ed8;
    }
</style>
@endpush


@section('content')

<section class="form-page">

    <div class="form-container">

        <a
            class="back-link"
            href="{{ route('formateur.contenus.manage') }}"
        >
            ← Retour aux contenus
        </a>


        <div class="form-card">

            <h1>
                Modifier le contenu
            </h1>

            <p class="form-description">
                Modifiez les informations
                de la ressource pédagogique.
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
                        'formateur.contenus.update',
                        $contenu->id_contenu
                    )
                }}"
                enctype="multipart/form-data"
            >

                @csrf
                @method('PUT')


                {{-- COURS --}}

                <div class="form-group">

                    <label for="id_cours">
                        Cours
                    </label>

                    <select
                        class="form-control"
                        id="id_cours"
                        name="id_cours"
                        required
                    >

                        @foreach ($cours as $cour)

                            <option
                                value="{{ $cour->id_cours }}"
                                {{
                                    (string)
                                    old(
                                        'id_cours',
                                        $contenu
                                            ->lecon
                                            ?->id_cours
                                    )
                                    ===
                                    (string)
                                    $cour->id_cours
                                        ? 'selected'
                                        : ''
                                }}
                            >
                                {{ $cour->titre_cours }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- LEÇON --}}

                <div class="form-group">

                    <label for="id_lecon">
                        Leçon
                    </label>

                    <select
                        class="form-control"
                        id="id_lecon"
                        name="id_lecon"
                        required
                    >

                        @foreach ($lecons as $lecon)

                            <option
                                value="{{ $lecon->{'id_leçon'} }}"
                                data-cours="{{ $lecon->id_cours }}"
                                {{
                                    (string)
                                    old(
                                        'id_lecon',
                                        $contenu->id_lecon
                                    )
                                    ===
                                    (string)
                                    $lecon->{'id_leçon'}
                                        ? 'selected'
                                        : ''
                                }}
                            >
                                {{
                                    $lecon->cours?->titre_cours
                                }}

                                -

                                Leçon {{ $lecon->ordre }}

                                :

                                {{ $lecon->{'titre_leçon'} }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- TITRE --}}

                <div class="form-group">

                    <label for="titre_contenu">
                        Titre du contenu
                    </label>

                    <input
                        class="form-control"
                        id="titre_contenu"
                        type="text"
                        name="titre_contenu"
                        value="{{
                            old(
                                'titre_contenu',
                                $contenu->titre_contenu
                            )
                        }}"
                        required
                    >

                </div>


                {{-- TYPE DE CONTENU --}}

                <div class="form-group">

                    <label for="type_contenu">
                        Type de contenu
                    </label>

                    <select
                        class="form-control"
                        id="type_contenu"
                        name="type_contenu"
                        required
                    >

                        @foreach (
                            [
                                'PDF',
                                'Document',
                                'Présentation',
                                'Image',
                                'Vidéo',
                                'Autre'
                            ]
                            as $type
                        )

                            <option
                                value="{{ $type }}"
                                {{
                                    old(
                                        'type_contenu',
                                        $contenu->type_contenu
                                    )
                                    === $type
                                        ? 'selected'
                                        : ''
                                }}
                            >

                                @if ($type === 'PDF')
                                    📄 PDF
                                @elseif ($type === 'Document')
                                    📝 Document
                                @elseif ($type === 'Présentation')
                                    📊 Présentation
                                @elseif ($type === 'Image')
                                    🖼️ Image
                                @elseif ($type === 'Vidéo')
                                    🎥 Vidéo
                                @else
                                    📁 Autre
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- FICHIER --}}

                <div class="form-group">

                    <label for="fichier">
                        Nouveau fichier
                    </label>

                    <input
                        class="form-control"
                        id="fichier"
                        type="file"
                        name="fichier"
                    >

                    <div
                        id="fichier-aide"
                        class="current-file"
                    >
                        Laissez vide pour conserver
                        le fichier actuel.
                    </div>


                    @if (! empty($contenu->fichier))

                        <div class="current-file">

                            Fichier actuel :

                            <a
                                href="{{
                                    asset(
                                        'storage/'
                                        . $contenu->fichier
                                    )
                                }}"
                                target="_blank"
                            >
                                @if (
                                    $contenu->type_contenu
                                    === 'Vidéo'
                                )
                                    Voir la vidéo actuelle
                                @else
                                    Ouvrir le fichier actuel
                                @endif
                            </a>

                        </div>

                    @endif

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


@push('scripts')

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const coursSelect =
                document.getElementById(
                    'id_cours'
                );

            const leconSelect =
                document.getElementById(
                    'id_lecon'
                );

            const typeSelect =
                document.getElementById(
                    'type_contenu'
                );

            const fichierInput =
                document.getElementById(
                    'fichier'
                );

            const fichierAide =
                document.getElementById(
                    'fichier-aide'
                );


            /*
            |--------------------------------------------------------------------------
            | FILTRER LES LEÇONS SELON LE COURS
            |--------------------------------------------------------------------------
            */

            if (
                coursSelect
                &&
                leconSelect
            ) {

                const toutesLesOptions =
                    Array.from(
                        leconSelect.options
                    );


                function filtrerLecons() {

                    const idCours =
                        coursSelect.value;

                    const leconSelectionnee =
                        leconSelect.value;


                    leconSelect.innerHTML = '';


                    toutesLesOptions.forEach(
                        function (option) {

                            if (
                                option.dataset.cours
                                === idCours
                            ) {

                                const copie =
                                    option.cloneNode(true);


                                if (
                                    copie.value
                                    === leconSelectionnee
                                ) {
                                    copie.selected = true;
                                }


                                leconSelect.appendChild(
                                    copie
                                );
                            }
                        }
                    );
                }


                coursSelect.addEventListener(
                    'change',
                    filtrerLecons
                );
            }


            /*
            |--------------------------------------------------------------------------
            | ADAPTER LE TYPE DE FICHIER
            |--------------------------------------------------------------------------
            */

            function adapterTypeFichier() {

                if (
                    ! typeSelect
                    ||
                    ! fichierInput
                ) {
                    return;
                }


                const type =
                    typeSelect.value;

                let accept = '';

                let aide =
                    'Laissez vide pour conserver '
                    + 'le fichier actuel.';


                if (type === 'PDF') {

                    accept =
                        '.pdf,application/pdf';

                    aide =
                        'PDF sélectionné. '
                        + 'Laissez vide pour conserver '
                        + 'le fichier actuel.';
                }


                if (type === 'Document') {

                    accept =
                        '.doc,.docx,.odt,.txt';

                    aide =
                        'Document sélectionné '
                        + '(DOC, DOCX, ODT ou TXT). '
                        + 'Laissez vide pour conserver '
                        + 'le fichier actuel.';
                }


                if (type === 'Présentation') {

                    accept =
                        '.ppt,.pptx,.odp';

                    aide =
                        'Présentation sélectionnée '
                        + '(PPT, PPTX ou ODP). '
                        + 'Laissez vide pour conserver '
                        + 'le fichier actuel.';
                }


                if (type === 'Image') {

                    accept =
                        'image/*';

                    aide =
                        'Image sélectionnée. '
                        + 'Laissez vide pour conserver '
                        + 'le fichier actuel.';
                }


                if (type === 'Vidéo') {

                    accept =
                        'video/mp4,'
                        + 'video/webm,'
                        + 'video/ogg,'
                        + '.mp4,.webm,.ogv';

                    aide =
                        'Vidéo sélectionnée '
                        + '(MP4, WebM ou OGV). '
                        + 'Laissez vide pour conserver '
                        + 'la vidéo actuelle.';
                }


                fichierInput.setAttribute(
                    'accept',
                    accept
                );


                if (fichierAide) {
                    fichierAide.textContent =
                        aide;
                }
            }


            if (typeSelect) {

                typeSelect.addEventListener(
                    'change',
                    adapterTypeFichier
                );

                adapterTypeFichier();
            }

        }
    );
</script>

@endpush
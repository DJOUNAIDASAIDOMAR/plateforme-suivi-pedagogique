@extends('layouts.dashboard')

@section(
    'title',
    'Gérer les contenus - Espace formateur'
)

@push('styles')
<style>
    .content-page {
        min-height: 650px;
        padding: 50px 30px 80px;
        background: #f3f6fb;
    }

    .content-container {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
    }

    .page-header {
        margin-bottom: 25px;
        padding: 30px;
        border-radius: 20px;
        background:
            linear-gradient(
                135deg,
                #1e3a8a,
                #2563eb
            );
        color: #ffffff;
    }

    .page-header h1 {
        margin: 0 0 10px;
        font-size: 32px;
    }

    .page-header p {
        margin: 0;
        color: #dbeafe;
        line-height: 1.7;
    }

    .message {
        margin-bottom: 20px;
        padding: 15px 18px;
        border-radius: 10px;
    }

    .success {
        background: #f0fdf4;
        color: #15803d;
    }

    .error {
        background: #fef2f2;
        color: #b91c1c;
    }

    .form-card {
        margin-bottom: 35px;
        padding: 32px;
        border-radius: 18px;
        background: #ffffff;
        box-shadow:
            0 12px 35px
            rgba(30, 64, 175, 0.08);
    }

    .form-card h2 {
        margin: 0 0 8px;
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

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 800;
    }

    .form-control {
        width: 100%;
        padding: 13px 14px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        background: #ffffff;
        font: inherit;
    }

    .form-help {
        margin-top: 7px;
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
        font-size: 16px;
        font-weight: 800;
        cursor: pointer;
    }

    .contents-title {
        margin-bottom: 20px;
        color: #172554;
    }

    .content-card {
        display: flex;
        margin-bottom: 15px;
        padding: 20px;
        border-radius: 14px;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        background: #ffffff;
        box-shadow:
            0 8px 25px
            rgba(30, 64, 175, 0.07);
    }

    .badge {
        display: inline-block;
        margin-right: 6px;
        margin-bottom: 8px;
        padding: 6px 10px;
        border-radius: 20px;
        background: #dbeafe;
        color: #1e3a8a;
        font-size: 12px;
        font-weight: 800;
    }

    .content-card h3 {
        margin: 4px 0 8px;
        color: #172554;
    }

    .content-type {
        margin: 0;
        color: #6b7280;
    }

    .actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .download-button,
    .edit-button,
    .delete-button {
        padding: 9px 12px;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
    }

    .download-button {
        background: #dcfce7;
        color: #15803d;
    }

    .edit-button {
        background: #dbeafe;
        color: #1e3a8a;
    }

    .delete-button {
        background: #fee2e2;
        color: #b91c1c;
    }

    .empty {
        padding: 45px;
        border-radius: 15px;
        background: #ffffff;
        text-align: center;
    }

    @media (max-width: 750px) {
        .content-page {
            padding: 40px 20px 60px;
        }

        .content-card {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')

<section class="content-page">

    <div class="content-container">

        <header class="page-header">
            <h1>
                Gérer les contenus
            </h1>

            <p>
                Choisissez un cours et une leçon,
                puis ajoutez la ressource pédagogique
                correspondante.
            </p>
        </header>


        @if (session('success'))
            <div class="message success">
                {{ session('success') }}
            </div>
        @endif


        @if (session('error'))
            <div class="message error">
                {{ session('error') }}
            </div>
        @endif


        <section class="form-card">

            <h2>
                Ajouter un contenu
            </h2>

            <p class="form-description">
                Le contenu sera rattaché
                à la leçon sélectionnée.
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


            @if ($cours->isEmpty())

                <div class="empty">
                    <h3>
                        Aucun cours attribué
                    </h3>

                    <p>
                        Le responsable pédagogique doit
                        d'abord vous attribuer un cours.
                    </p>
                </div>

            @elseif ($lecons->isEmpty())

                <div class="empty">
                    <h3>
                        Aucune leçon disponible
                    </h3>

                    <p>
                        Vous devez d'abord créer
                        une leçon avant d'ajouter un contenu.
                    </p>
                </div>

            @else

                <form
                    method="POST"
                    action="{{ route('formateur.contenus.store') }}"
                    enctype="multipart/form-data"
                >

                    @csrf


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

                            <option value="">
                                Sélectionnez un cours
                            </option>

                            @foreach ($cours as $cour)
                                <option
                                    value="{{ $cour->id_cours }}"
                                    {{
                                        (string) old('id_cours')
                                        ===
                                        (string) $cour->id_cours
                                            ? 'selected'
                                            : ''
                                    }}
                                >
                                    {{ $cour->titre_cours }}
                                </option>
                            @endforeach

                        </select>
                    </div>


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

                            <option value="">
                                Sélectionnez une leçon
                            </option>

                            @foreach ($lecons as $lecon)
                                <option
                                    value="{{ $lecon->{'id_leçon'} }}"
                                    data-cours="{{ $lecon->id_cours }}"
                                    {{
                                        (string) old('id_lecon')
                                        ===
                                        (string) $lecon->{'id_leçon'}
                                            ? 'selected'
                                            : ''
                                    }}
                                >
                                    {{
                                        $lecon->cours?->titre_cours
                                        ?? 'Cours'
                                    }}

                                    -

                                    Leçon {{ $lecon->ordre }}

                                    :

                                    {{ $lecon->{'titre_leçon'} }}
                                </option>
                            @endforeach

                        </select>
                    </div>


                    <div class="form-group">
                        <label for="titre_contenu">
                            Titre du contenu
                        </label>

                        <input
                            class="form-control"
                            id="titre_contenu"
                            type="text"
                            name="titre_contenu"
                            value="{{ old('titre_contenu') }}"
                            placeholder="Exemple : Support du cours HTML"
                            required
                        >
                    </div>


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

                            <option value="">
                                Sélectionnez un type
                            </option>

                            <option
                                value="PDF"
                                {{
                                    old('type_contenu') === 'PDF'
                                        ? 'selected'
                                        : ''
                                }}
                            >
                                📄 PDF
                            </option>

                            <option
                                value="Document"
                                {{
                                    old('type_contenu') === 'Document'
                                        ? 'selected'
                                        : ''
                                }}
                            >
                                📝 Document
                            </option>

                            <option
                                value="Présentation"
                                {{
                                    old('type_contenu') === 'Présentation'
                                        ? 'selected'
                                        : ''
                                }}
                            >
                                📊 Présentation
                            </option>

                            <option
                                value="Image"
                                {{
                                    old('type_contenu') === 'Image'
                                        ? 'selected'
                                        : ''
                                }}
                            >
                                🖼️ Image
                            </option>

                            <option
                                value="Vidéo"
                                {{
                                    old('type_contenu') === 'Vidéo'
                                        ? 'selected'
                                        : ''
                                }}
                            >
                                🎥 Vidéo
                            </option>

                            <option
                                value="Autre"
                                {{
                                    old('type_contenu') === 'Autre'
                                        ? 'selected'
                                        : ''
                                }}
                            >
                                📁 Autre
                            </option>

                        </select>
                    </div>


                    <div class="form-group">
                        <label for="fichier">
                            Fichier
                        </label>

                        <input
                            class="form-control"
                            id="fichier"
                            type="file"
                            name="fichier"
                            required
                        >

                        <p
                            id="fichier-aide"
                            class="form-help"
                        >
                            Sélectionnez le fichier correspondant
                            au type de contenu choisi.
                        </p>
                    </div>


                    <button
                        class="submit-button"
                        type="submit"
                    >
                        Ajouter le contenu
                    </button>

                </form>

            @endif

        </section>


        <h2 class="contents-title">
            Mes contenus
        </h2>


        @forelse ($contenus as $contenu)

            <article class="content-card">

                <div>

                    <span class="badge">
                        {{
                            $contenu
                                ->lecon
                                ?->cours
                                ?->titre_cours
                            ?? 'Cours'
                        }}
                    </span>

                    <span class="badge">
                        Leçon
                        {{
                            $contenu
                                ->lecon
                                ?->ordre
                        }}
                    </span>


                    <h3>
                        {{ $contenu->titre_contenu }}
                    </h3>


                    <p class="content-type">
                        Type :
                        {{ $contenu->type_contenu }}
                    </p>

                </div>


                <div class="actions">

                    <a
                        class="download-button"
                        href="{{ asset('storage/' . $contenu->fichier) }}"
                        target="_blank"
                    >
                        @if ($contenu->type_contenu === 'Vidéo')
                            Voir la vidéo
                        @else
                            Ouvrir
                        @endif
                    </a>


                    <a
                        class="edit-button"
                        href="{{
                            route(
                                'formateur.contenus.edit',
                                $contenu->id_contenu
                            )
                        }}"
                    >
                        Modifier
                    </a>


                    <form
                        method="POST"
                        action="{{
                            route(
                                'formateur.contenus.destroy',
                                $contenu->id_contenu
                            )
                        }}"
                        onsubmit="
                            return confirm(
                                'Voulez-vous vraiment supprimer ce contenu ?'
                            );
                        "
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            class="delete-button"
                            type="submit"
                        >
                            Supprimer
                        </button>

                    </form>

                </div>

            </article>

        @empty

            <div class="empty">
                <h3>
                    Aucun contenu
                </h3>

                <p>
                    Les contenus ajoutés apparaîtront ici.
                </p>
            </div>

        @endforelse

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
            | FILTRER LES LEÇONS PAR COURS
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


                    leconSelect.innerHTML = '';


                    const optionVide =
                        document.createElement(
                            'option'
                        );

                    optionVide.value = '';

                    optionVide.textContent =
                        'Sélectionnez une leçon';


                    leconSelect.appendChild(
                        optionVide
                    );


                    toutesLesOptions.forEach(
                        function (option) {

                            if (! option.value) {
                                return;
                            }


                            if (
                                option.dataset.cours
                                === idCours
                            ) {

                                leconSelect.appendChild(
                                    option.cloneNode(true)
                                );
                            }
                        }
                    );
                }


                coursSelect.addEventListener(
                    'change',
                    filtrerLecons
                );


                if (coursSelect.value) {
                    filtrerLecons();
                }
            }


            /*
            |--------------------------------------------------------------------------
            | ADAPTER LE CHAMP FICHIER AU TYPE
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
                    'Sélectionnez le fichier correspondant '
                    + 'au type de contenu choisi.';


                if (type === 'PDF') {

                    accept =
                        '.pdf,application/pdf';

                    aide =
                        'Sélectionnez un fichier PDF.';
                }


                if (type === 'Document') {

                    accept =
                        '.doc,.docx,.odt,.txt';

                    aide =
                        'Sélectionnez un document '
                        + '(DOC, DOCX, ODT ou TXT).';
                }


                if (type === 'Présentation') {

                    accept =
                        '.ppt,.pptx,.odp';

                    aide =
                        'Sélectionnez une présentation '
                        + '(PPT, PPTX ou ODP).';
                }


                if (type === 'Image') {

                    accept =
                        'image/*';

                    aide =
                        'Sélectionnez une image.';
                }


                if (type === 'Vidéo') {

                    accept =
                        'video/mp4,'
                        + 'video/webm,'
                        + 'video/ogg,'
                        + '.mp4,.webm,.ogv';

                    aide =
                        'Sélectionnez une vidéo '
                        + '(MP4, WebM ou OGV).';
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
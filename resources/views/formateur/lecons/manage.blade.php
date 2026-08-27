@extends('layouts.dashboard')

@section(
    'title',
    'Gérer les leçons - Espace formateur'
)

@push('styles')
<style>
    .lesson-management-page {
        min-height: 650px;
        padding: 50px 30px 80px;
        background: #f3f6fb;
    }

    .lesson-management-container {
        width: 100%;
        max-width: 1050px;
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
        margin-bottom: 22px;
        padding: 15px 18px;
        border-radius: 10px;
    }

    .message-success {
        border: 1px solid #bbf7d0;
        background: #f0fdf4;
        color: #15803d;
    }

    .message-error {
        border: 1px solid #fecaca;
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
        font-size: 25px;
    }

    .form-description {
        margin: 0 0 25px;
        color: #6b7280;
        line-height: 1.6;
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

        color: #1f2937;
        font-weight: 800;
    }

    .form-control {
        width: 100%;
        padding: 13px 14px;

        border: 1px solid #d1d5db;
        border-radius: 10px;

        background: #ffffff;
        color: #1f2937;

        font: inherit;
        outline: none;
    }

    .form-control:focus {
        border-color: #1e3a8a;

        box-shadow:
            0 0 0 3px
            rgba(30, 58, 138, 0.12);
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

    .lessons-title {
        margin: 0 0 20px;

        color: #172554;

        font-size: 26px;
    }

    .lesson-card {
        display: flex;

        margin-bottom: 14px;
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

    .course-badge {
        display: inline-block;

        margin-bottom: 7px;
        padding: 6px 10px;

        border-radius: 20px;

        background: #dbeafe;
        color: #1e3a8a;

        font-size: 12px;
        font-weight: 800;
    }

    .lesson-card h3 {
        margin: 4px 0 7px;
        color: #172554;
    }

    .lesson-information {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }

    .lesson-actions {
        display: flex;
        gap: 8px;
    }

    .edit-button,
    .delete-button {
        padding: 9px 12px;

        border: none;
        border-radius: 8px;

        font-weight: 700;
        text-decoration: none;

        cursor: pointer;
    }

    .edit-button {
        background: #dbeafe;
        color: #1e3a8a;
    }

    .delete-button {
        background: #fee2e2;
        color: #b91c1c;
    }

    .empty-state {
        padding: 45px 25px;

        border-radius: 16px;

        background: #ffffff;

        text-align: center;
    }

    .empty-state h3 {
        margin: 0 0 8px;
        color: #172554;
    }

    .empty-state p {
        margin: 0;
        color: #6b7280;
    }

    @media (max-width: 700px) {
        .lesson-management-page {
            padding: 40px 20px 60px;
        }

        .lesson-card {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')

<section class="lesson-management-page">

    <div class="lesson-management-container">

        <header class="page-header">

            <h1>
                Gérer les leçons
            </h1>

            <p>
                Sélectionnez un cours parmi les cours
                qui vous ont été attribués,
                puis ajoutez une nouvelle leçon.
            </p>

        </header>


        @if (session('success'))

            <div class="message message-success">
                {{ session('success') }}
            </div>

        @endif


        @if (session('error'))

            <div class="message message-error">
                {{ session('error') }}
            </div>

        @endif


        <section class="form-card">

            <h2>
                Ajouter une leçon
            </h2>

            <p class="form-description">
                Choisissez le cours concerné,
                puis renseignez le titre
                et l'ordre de la leçon.
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

                <div class="empty-state">

                    <h3>
                        Aucun cours disponible
                    </h3>

                    <p>
                        Aucun cours ne vous a encore été attribué
                        par le responsable pédagogique.
                    </p>

                </div>

            @else

                <form
                    method="POST"
                    action="{{
                        route(
                            'formateur.lecons.manage.store'
                        )
                    }}"
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
                                        === (string) $cour->id_cours
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

                        <label for="titre_leçon">
                            Titre de la leçon
                        </label>

                        <input
                            class="form-control"
                            id="titre_leçon"
                            type="text"
                            name="titre_leçon"
                            value="{{ old('titre_leçon') }}"
                            placeholder="Exemple : Introduction au HTML"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="ordre">
                            Ordre de la leçon
                        </label>

                        <input
                            class="form-control"
                            id="ordre"
                            type="number"
                            min="1"
                            name="ordre"
                            value="{{ old('ordre') }}"
                            placeholder="Exemple : 1"
                            required
                        >

                    </div>


                    <button
                        class="submit-button"
                        type="submit"
                    >
                        Ajouter la leçon
                    </button>

                </form>

            @endif

        </section>


        <h2 class="lessons-title">
            Mes leçons
        </h2>


        @forelse ($lecons as $lecon)

            <article class="lesson-card">

                <div>

                    <span class="course-badge">
                        {{
                            $lecon
                                ->cours
                                ?->titre_cours
                            ?? 'Cours'
                        }}
                    </span>

                    <h3>
                        Leçon {{ $lecon->ordre }}
                        :
                        {{ $lecon->{'titre_leçon'} }}
                    </h3>

                    <p class="lesson-information">

                        {{ $lecon->contenus_count }}

                        contenu{{
                            $lecon->contenus_count > 1
                                ? 's'
                                : ''
                        }}

                    </p>

                </div>


                <div class="lesson-actions">

                    <a
                        class="edit-button"
                        href="{{
                            route(
                                'formateur.lecons.edit',
                                [
                                    $lecon->id_cours,
                                    $lecon->{'id_leçon'}
                                ]
                            )
                        }}"
                    >
                        Modifier
                    </a>


                    <form
                        method="POST"
                        action="{{
                            route(
                                'formateur.lecons.destroy',
                                [
                                    $lecon->id_cours,
                                    $lecon->{'id_leçon'}
                                ]
                            )
                        }}"
                        onsubmit="
                            return confirm(
                                'Voulez-vous vraiment supprimer cette leçon ?'
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

            <div class="empty-state">

                <h3>
                    Aucune leçon
                </h3>

                <p>
                    Les leçons ajoutées apparaîtront ici.
                </p>

            </div>

        @endforelse

    </div>

</section>

@endsection
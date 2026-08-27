@extends('layouts.dashboard')

@section('title', 'Gestion des cours - Responsable pédagogique')

@push('styles')
<style>
    .responsable-cours-page {
        min-height: 100vh;
        padding: 50px 35px 80px;
        background: #f3f6fb;
    }

    .responsable-cours-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .cours-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;

        margin-bottom: 28px;
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

    .cours-header h1 {
        margin: 0 0 8px;
        font-size: 32px;
    }

    .cours-header p {
        margin: 0;
        color: #dbeafe;
        line-height: 1.6;
    }

    .add-course-button {
        display: inline-flex;

        min-width: 185px;

        padding: 15px 20px;

        border-radius: 12px;

        align-items: center;
        justify-content: center;

        background: #ffffff;
        color: #1e3a8a;

        font-size: 15px;
        font-weight: 800;

        text-decoration: none;

        transition: 0.2s;
    }

    .add-course-button:hover {
        background: #eff6ff;
        transform: translateY(-2px);
    }

    .success-message,
    .error-message {
        margin-bottom: 22px;
        padding: 15px 18px;

        border-radius: 12px;

        font-size: 15px;
    }

    .success-message {
        border: 1px solid #86efac;
        background: #f0fdf4;
        color: #15803d;
    }

    .error-message {
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #b91c1c;
    }

    .courses-table-wrapper {
        overflow-x: auto;

        border-radius: 18px;

        background: #ffffff;

        box-shadow:
            0 12px 35px
            rgba(30, 64, 175, 0.08);
    }

    .courses-table {
        width: 100%;

        border-collapse: collapse;
    }

    .courses-table th {
        padding: 18px;

        background: #eff6ff;
        color: #1e3a8a;

        font-size: 14px;
        font-weight: 800;

        text-align: left;
        white-space: nowrap;
    }

    .courses-table td {
        padding: 18px;

        border-top: 1px solid #e5e7eb;

        color: #1f2937;

        vertical-align: middle;
    }

    .course-title {
        color: #172554;
        font-weight: 800;
    }

    .filiere-badge {
        display: inline-block;

        padding: 7px 11px;

        border-radius: 20px;

        background: #dbeafe;
        color: #1e3a8a;

        font-size: 12px;
        font-weight: 800;

        white-space: nowrap;
    }

    .actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .edit-button {
        display: inline-flex;

        padding: 9px 13px;

        border-radius: 8px;

        align-items: center;
        justify-content: center;

        background: #dbeafe;
        color: #1e3a8a;

        font-weight: 700;

        text-decoration: none;
    }

    .edit-button:hover {
        background: #bfdbfe;
    }

    .delete-form {
        margin: 0;
    }

    .delete-button {
        padding: 9px 13px;

        border: none;
        border-radius: 8px;

        background: #fee2e2;
        color: #dc2626;

        font-weight: 700;

        cursor: pointer;
    }

    .delete-button:hover {
        background: #fecaca;
    }

    .empty-courses {
        padding: 60px 30px;

        border-radius: 18px;

        background: #ffffff;

        text-align: center;

        box-shadow:
            0 12px 35px
            rgba(30, 64, 175, 0.08);
    }

    .empty-courses h2 {
        margin: 0 0 10px;
        color: #172554;
    }

    .empty-courses p {
        margin: 0 0 25px;
        color: #6b7280;
    }

    @media (max-width: 800px) {
        .responsable-cours-page {
            padding: 35px 20px 60px;
        }

        .cours-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .add-course-button {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')

<section class="responsable-cours-page">

    <div class="responsable-cours-container">

        <header class="cours-header">

            <div>

                <h1>
                    Gérer les cours
                </h1>

                <p>
                    Créez les cours et attribuez-les
                    aux formateurs.
                </p>

            </div>

            <a
                href="{{ route('responsable.cours.create') }}"
                class="add-course-button"
            >
                Ajouter un cours
            </a>

        </header>

        @if (session('success'))

            <div class="success-message">
                {{ session('success') }}
            </div>

        @endif

        @if (session('error'))

            <div class="error-message">
                {{ session('error') }}
            </div>

        @endif


        @if ($cours->isEmpty())

            <div class="empty-courses">

                <h2>
                    Aucun cours
                </h2>

                <p>
                    Aucun cours n'a encore été créé.
                </p>

                <a
                    href="{{ route('responsable.cours.create') }}"
                    class="add-course-button"
                >
                    Ajouter le premier cours
                </a>

            </div>

        @else

            <div class="courses-table-wrapper">

                <table class="courses-table">

                    <thead>

                        <tr>

                            <th>
                                Cours
                            </th>

                            <th>
                                Filière
                            </th>

                            <th>
                                Formateur
                            </th>

                            <th>
                                Leçons
                            </th>

                            <th>
                                Quiz
                            </th>

                            <th>
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($cours as $cour)

                            <tr>

                                <td>

                                    <span class="course-title">
                                        {{ $cour->titre_cours }}
                                    </span>

                                </td>

                                <td>

                                    <span class="filiere-badge">

                                        {{
                                            $cour->filier
                                                ?->nom_filier
                                            ?? 'Non renseignée'
                                        }}

                                    </span>

                                </td>

                                <td>

                                    {{
                                        $cour
                                            ->formateur
                                            ?->user
                                            ?->nom
                                        ?? 'Non attribué'
                                    }}

                                </td>

                                <td>
                                    {{ $cour->lecons_count }}
                                </td>

                                <td>
                                    {{ $cour->quizzes_count }}
                                </td>

                                <td>

                                    <div class="actions">

                                        <a
                                            href="{{
                                                route(
                                                    'responsable.cours.edit',
                                                    $cour->id_cours
                                                )
                                            }}"
                                            class="edit-button"
                                        >
                                            Modifier
                                        </a>

                                        <form
                                            action="{{
                                                route(
                                                    'responsable.cours.destroy',
                                                    $cour->id_cours
                                                )
                                            }}"
                                            method="POST"
                                            class="delete-form"
                                            onsubmit="
                                                return confirm(
                                                    'Voulez-vous vraiment supprimer ce cours ?'
                                                );
                                            "
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="delete-button"
                                            >
                                                Supprimer
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>

</section>

@endsection
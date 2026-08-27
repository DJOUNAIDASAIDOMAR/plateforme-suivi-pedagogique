@extends('layouts.dashboard')

@section(
    'title',
    'Leçons - ' . $cours->titre_cours
)

@push('styles')
<style>
    .page {
        min-height: 650px;
        padding: 50px 30px 80px;
        background: #f3f6fb;
    }

    .container {
        width: 100%;
        max-width: 1050px;
        margin: 0 auto;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 20px;
        color: #1e3a8a;
        font-weight: 800;
        text-decoration: none;
    }

    .page-header {
        display: flex;
        margin-bottom: 25px;
        padding: 28px;
        border-radius: 18px;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        background:
            linear-gradient(
                135deg,
                #1e3a8a,
                #2563eb
            );
        color: #ffffff;
    }

    .page-header h1 {
        margin: 0 0 7px;
    }

    .page-header p {
        margin: 0;
        color: #dbeafe;
    }

    .add-button {
        padding: 13px 18px;
        border-radius: 10px;
        background: #ffffff;
        color: #1e3a8a;
        font-weight: 800;
        text-decoration: none;
        white-space: nowrap;
    }

    .message {
        margin-bottom: 20px;
        padding: 15px;
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

    .lesson-number {
        color: #1e3a8a;
        font-size: 13px;
        font-weight: 800;
    }

    .lesson-card h3 {
        margin: 5px 0;
        color: #172554;
    }

    .lesson-card p {
        margin: 0;
        color: #6b7280;
    }

    .actions {
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

    .empty {
        padding: 50px;
        border-radius: 16px;
        background: #ffffff;
        text-align: center;
    }
</style>
@endpush

@section('content')

<section class="page">

    <div class="container">

        <a
            class="back-link"
            href="{{ route('formateur.cours.index') }}"
        >
            ← Retour à mes cours
        </a>

        <header class="page-header">

            <div>
                <h1>
                    Leçons : {{ $cours->titre_cours }}
                </h1>

                <p>
                    Ajoutez et organisez les leçons de ce cours.
                </p>
            </div>

            <a
                class="add-button"
                href="{{
                    route(
                        'formateur.lecons.create',
                        $cours->id_cours
                    )
                }}"
            >
                Ajouter une leçon
            </a>

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

        @forelse ($lecons as $lecon)

            <article class="lesson-card">

                <div>

                    <span class="lesson-number">
                        Leçon {{ $lecon->ordre }}
                    </span>

                    <h3>
                        {{ $lecon->{'titre_leçon'} }}
                    </h3>

                    <p>
                        {{ $lecon->contenus_count }}
                        contenu{{
                            $lecon->contenus_count > 1
                                ? 's'
                                : ''
                        }}
                    </p>

                </div>

                <div class="actions">

                    <a
                        class="edit-button"
                        href="{{
                            route(
                                'formateur.lecons.edit',
                                [
                                    $cours->id_cours,
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
                                    $cours->id_cours,
                                    $lecon->{'id_leçon'}
                                ]
                            )
                        }}"
                        onsubmit="
                            return confirm(
                                'Voulez-vous supprimer cette leçon ?'
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

                <h2>
                    Aucune leçon
                </h2>

                <p>
                    Commencez par ajouter
                    la première leçon de ce cours.
                </p>

            </div>

        @endforelse

    </div>

</section>

@endsection
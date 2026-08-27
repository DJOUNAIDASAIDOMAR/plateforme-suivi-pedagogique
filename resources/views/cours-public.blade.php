@extends('layouts.site')

@section('title', 'Cours - Plateforme de Suivi Pédagogique')

@push('styles')
<style>
    .courses-hero {
        padding: 85px 30px 65px;
        background:
            linear-gradient(
                135deg,
                #eff6ff,
                #dbeafe
            );
        text-align: center;
    }

    .courses-hero-container {
        width: 100%;
        max-width: 850px;
        margin: 0 auto;
    }

    .courses-badge {
        display: inline-block;
        margin-bottom: 18px;
        padding: 9px 16px;
        border-radius: 30px;
        background: #ffffff;
        color: #1e3a8a;
        font-size: 14px;
        font-weight: 800;
    }

    .courses-hero h1 {
        margin: 0 0 18px;
        color: #172554;
        font-size: 47px;
    }

    .courses-hero p {
        max-width: 720px;
        margin: 0 auto;
        color: #4b5563;
        font-size: 18px;
        line-height: 1.8;
    }

    .courses-section {
        min-height: 500px;
        padding: 75px 30px;
        background: #f3f6fb;
    }

    .courses-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .courses-alert {
        margin-bottom: 25px;
        padding: 16px 18px;
        border: 1px solid #fecaca;
        border-radius: 11px;
        background: #fef2f2;
        color: #b91c1c;
    }

    .courses-toolbar {
        display: flex;
        margin-bottom: 32px;
        padding: 18px;
        border-radius: 15px;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        background: #ffffff;
        box-shadow:
            0 10px 30px
            rgba(30, 64, 175, 0.07);
    }

    .courses-toolbar h2 {
        margin: 0;
        color: #172554;
        font-size: 24px;
    }

    .search-input {
        width: 100%;
        max-width: 380px;
        padding: 12px 14px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        outline: none;
        font-size: 15px;
    }

    .search-input:focus {
        border-color: #1e3a8a;
        box-shadow:
            0 0 0 3px
            rgba(30, 58, 138, 0.12);
    }

    .courses-grid {
        display: grid;
        grid-template-columns:
            repeat(3, minmax(0, 1fr));
        gap: 25px;
    }

    .course-card {
        display: flex;
        overflow: hidden;
        min-height: 430px;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        flex-direction: column;
        background: #ffffff;
        box-shadow:
            0 12px 35px
            rgba(30, 64, 175, 0.08);
        transition: 0.25s;
    }

    .course-card:hover {
        transform: translateY(-6px);
        box-shadow:
            0 20px 45px
            rgba(30, 64, 175, 0.15);
    }

    .course-image {
        display: flex;
        height: 175px;
        padding: 25px;
        align-items: center;
        justify-content: center;
        background:
            linear-gradient(
                135deg,
                #1e3a8a,
                #2563eb
            );
        color: #ffffff;
        text-align: center;
    }

    .course-initials {
        font-size: 35px;
        font-weight: 900;
        letter-spacing: 2px;
    }

    .course-content {
        display: flex;
        padding: 24px;
        flex: 1;
        flex-direction: column;
    }

    .course-filiere {
        display: inline-block;
        align-self: flex-start;
        margin-bottom: 14px;
        padding: 7px 11px;
        border-radius: 25px;
        background: #dbeafe;
        color: #1e3a8a;
        font-size: 12px;
        font-weight: 800;
    }

    .course-content h3 {
        margin: 0 0 12px;
        color: #172554;
        font-size: 22px;
        line-height: 1.35;
    }

    .course-description {
        display: -webkit-box;
        overflow: hidden;
        margin: 0 0 20px;
        color: #6b7280;
        line-height: 1.7;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
    }

    .course-information {
        display: grid;
        margin-top: auto;
        margin-bottom: 20px;
        gap: 9px;
    }

    .course-information p {
        margin: 0;
        color: #4b5563;
        font-size: 14px;
    }

    .course-button {
        display: flex;
        width: 100%;
        padding: 13px 18px;
        border-radius: 10px;
        align-items: center;
        justify-content: center;
        background: #1e3a8a;
        color: #ffffff;
        font-weight: 800;
        text-decoration: none;
        transition: 0.2s;
    }

    .course-button:hover {
        background: #172554;
    }

    .empty-courses {
        padding: 55px 25px;
        border-radius: 18px;
        background: #ffffff;
        text-align: center;
        box-shadow:
            0 10px 30px
            rgba(30, 64, 175, 0.07);
    }

    .empty-courses h2 {
        margin: 0 0 12px;
        color: #172554;
    }

    .empty-courses p {
        margin: 0;
        color: #6b7280;
    }

    .courses-footer {
        padding: 38px 25px;
        background: #172554;
        color: #dbeafe;
        text-align: center;
    }

    .courses-footer strong {
        color: #ffffff;
        font-size: 18px;
    }

    @media (max-width: 1000px) {
        .courses-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .courses-hero {
            padding: 60px 20px 45px;
        }

        .courses-hero h1 {
            font-size: 37px;
        }

        .courses-section {
            padding: 55px 20px;
        }

        .courses-toolbar {
            align-items: stretch;
            flex-direction: column;
        }

        .search-input {
            max-width: none;
        }

        .courses-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<section class="courses-hero">
    <div class="courses-hero-container">
        <span class="courses-badge">
            Catalogue pédagogique
        </span>

        <h1>
            Découvrez les cours disponibles
        </h1>

        <p>
            Consultez la présentation des cours proposés sur la
            plateforme. Une connexion est nécessaire pour accéder
            aux leçons, aux contenus et aux quiz.
        </p>
    </div>
</section>

<section class="courses-section">
    <div class="courses-container">
        @if (session('cours_error'))
            <div class="courses-alert">
                {{ session('cours_error') }}
            </div>
        @endif

        <div class="courses-toolbar">
            <h2>
                {{ $cours->count() }}
                cours disponible{{ $cours->count() > 1 ? 's' : '' }}
            </h2>

            <input
                class="search-input"
                id="course-search"
                type="search"
                placeholder="Rechercher un cours..."
            >
        </div>

        @if ($cours->isEmpty())
            <div class="empty-courses">
                <h2>
                    Aucun cours disponible
                </h2>

                <p>
                    Les cours ajoutés par les formateurs apparaîtront
                    automatiquement sur cette page.
                </p>
            </div>
        @else
            <div class="courses-grid" id="courses-grid">
                @foreach ($cours as $cour)
                    @php
                        $words = preg_split(
                            '/\s+/',
                            trim($cour->titre_cours)
                        );

                        $initials = collect($words)
                            ->filter()
                            ->take(3)
                            ->map(
                                fn ($word) =>
                                    mb_strtoupper(
                                        mb_substr($word, 0, 1)
                                    )
                            )
                            ->implode('');
                    @endphp

                    <article
                        class="course-card"
                        data-course-name="{{
                            mb_strtolower($cour->titre_cours)
                        }}"
                    >
                        <div class="course-image">
                            <div class="course-initials">
                                {{ $initials }}
                            </div>
                        </div>

                        <div class="course-content">
                            <span class="course-filiere">
                                {{
                                    $cour->filier?->nom_filier
                                    ?? 'Filière non renseignée'
                                }}
                            </span>

                            <h3>
                                {{ $cour->titre_cours }}
                            </h3>

                            <p class="course-description">
                                {{
                                    $cour->description
                                    ?: 'Aucune description disponible pour ce cours.'
                                }}
                            </p>

                            <div class="course-information">
                                <p>
                                    <strong>Formateur :</strong>

                                    {{
                                        $cour->formateur?->user?->nom
                                        ?? 'Non renseigné'
                                    }}
                                </p>

                                <p>
                                    <strong>Niveau :</strong>

                                    {{
                                        $cour->niveau
                                        ?: 'Non renseigné'
                                    }}
                                </p>

                                <p>
                                    <strong>Leçons :</strong>

                                    {{ $cour->lecons_count }}
                                </p>

                                <p>
                                    <strong>Quiz :</strong>

                                    {{ $cour->quizzes_count }}
                                </p>
                            </div>

                            <a
                                class="course-button"
                                href="{{
                                    route(
                                        'cours.show',
                                        $cour->id_cours
                                    )
                                }}"
                            >
                                Voir le cours
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>

<footer class="courses-footer">
    <strong>
        Plateforme de Suivi Pédagogique
    </strong>

    <p>
        Apprendre, progresser et réussir.
    </p>
</footer>
@endsection

@push('scripts')
<script>
    const searchInput = document.getElementById(
        'course-search'
    );

    const courseCards = document.querySelectorAll(
        '.course-card'
    );

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const search = this.value
                .toLowerCase()
                .trim();

            courseCards.forEach(function (card) {
                const courseName =
                    card.dataset.courseName;

                card.style.display =
                    courseName.includes(search)
                        ? 'flex'
                        : 'none';
            });
        });
    }
</script>
@endpush
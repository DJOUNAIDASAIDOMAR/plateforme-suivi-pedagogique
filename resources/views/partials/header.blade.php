<header class="main-header">
    <div class="main-header-container">

        <a
            href="{{ route('accueil') }}"
            class="main-logo"
        >
            Plateforme de Suivi Pédagogique
        </a>

        <nav class="main-navigation">

            <a
                href="{{ route('accueil') }}"
                class="{{ request()->routeIs('accueil') ? 'active' : '' }}"
            >
                Accueil
            </a>

            <a
                href="{{ route('cours.public') }}"
                class="{{ request()->routeIs('cours.*') ? 'active' : '' }}"
            >
                Cours
            </a>

            <a
                href="{{ route('quiz.public') }}"
                class="{{ request()->routeIs('quiz.*') ? 'active' : '' }}"
            >
                Quiz
            </a>

            <a
                href="{{ route('apropos') }}"
                class="{{ request()->routeIs('apropos') ? 'active' : '' }}"
            >
                À propos
            </a>

            <a
                href="{{ route('contact') }}"
                class="{{ request()->routeIs('contact') ? 'active' : '' }}"
            >
                Contact
            </a>

        </nav>

        <div class="main-header-actions">

            @auth

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="header-logout-form"
                >
                    @csrf

                    <button
                        type="submit"
                        class="header-logout-button"
                    >
                        Déconnexion
                    </button>
                </form>

            @else

                <a
                    href="{{ route('login') }}"
                    class="login-link"
                >
                    Connexion
                </a>

                <a
                    href="{{ route('register') }}"
                    class="register-link"
                >
                    Inscription
                </a>

            @endauth

        </div>

    </div>
</header>
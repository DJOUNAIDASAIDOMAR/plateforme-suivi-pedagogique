<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield(
            'title',
            'Plateforme de Suivi Pédagogique'
        )
    </title>

    <link
        rel="stylesheet"
        href="{{ asset('css/site.css') }}"
    >

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: #f3f6fb;
            color: #1f2937;
        }

        .dashboard-sidebar {
            position: fixed;
            top: 74px;
            bottom: 0;
            left: 0;
            width: 250px;
            padding: 25px 20px;
            overflow-y: auto;
            background: #1d4ed8;
            color: #ffffff;
        }

        .dashboard-sidebar-title {
            margin-bottom: 32px;
            color: #ffffff;
            font-size: 20px;
            font-weight: 800;
            text-align: center;
        }

        .dashboard-sidebar-user {
            margin-top: 7px;
            color: #bfdbfe;
            font-size: 13px;
            font-weight: 500;
        }

        .dashboard-menu {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .dashboard-menu li {
            margin-bottom: 9px;
        }

        .dashboard-menu a {
            display: flex;
            width: 100%;
            min-height: 45px;
            padding: 12px 15px;
            border-radius: 9px;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            color: #ffffff;
            font-size: 15px;
            text-decoration: none;
            transition: 0.2s;
        }

        .dashboard-menu a:hover,
        .dashboard-menu a.active {
            background: rgba(255, 255, 255, 0.18);
        }

        .notification-menu-text {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .notification-bell-wrapper {
            position: relative;
            display: inline-flex;
            width: 27px;
            height: 27px;
            align-items: center;
            justify-content: center;
        }

        .notification-bell {
            font-size: 19px;
            line-height: 1;
        }

        .notification-red-badge {
            position: absolute;
            top: -8px;
            right: -10px;

            display: inline-flex;
            min-width: 19px;
            height: 19px;
            padding: 0 5px;

            border: 2px solid #1d4ed8;
            border-radius: 20px;

            align-items: center;
            justify-content: center;

            background: #dc2626;
            color: #ffffff;

            font-size: 10px;
            font-weight: 900;
            line-height: 1;
        }

        .message-count {
            display: inline-flex;
            min-width: 23px;
            height: 23px;
            padding: 0 7px;
            border-radius: 20px;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            color: #1d4ed8;
            font-size: 12px;
            font-weight: 800;
        }

        .dashboard-content {
            min-height: 100vh;
            margin-left: 250px;
            padding-top: 74px;
        }

        @media (max-width: 950px) {

            .dashboard-sidebar {
                position: static;
                width: 100%;
                height: auto;
            }

            .dashboard-content {
                margin-left: 0;
                padding-top: 0;
            }

        }

    </style>

    @stack('styles')

</head>

<body>

@include('partials.header')


@php

    $nombreNotificationsNonLues = 0;
    $nombreMessagesNonLus = 0;

    if (auth()->check()) {

        $nombreNotificationsNonLues =
            \App\Models\Notification::where(
                'id_user',
                auth()->user()->id_user
            )
            ->where(
                'est_lue',
                false
            )
            ->count();


        if (
            in_array(
                auth()->user()->role,
                [
                    'etudiant',
                    'formateur',
                ],
                true
            )
        ) {

            $nombreMessagesNonLus =
                \App\Models\Message::where(
                    'id_destinataire',
                    auth()->user()->id_user
                )
                ->where(
                    'est_lu',
                    false
                )
                ->count();

        }

    }

@endphp


<aside class="dashboard-sidebar">

    <div class="dashboard-sidebar-title">

        @if (auth()->user()->role === 'etudiant')

            Espace étudiant

        @elseif (auth()->user()->role === 'formateur')

            Espace formateur

        @elseif (
            auth()->user()->role
            === 'responsable_pedagogique'
        )

            Responsable pédagogique

        @endif

        <div class="dashboard-sidebar-user">
            {{ auth()->user()->nom }}
        </div>

    </div>


    {{-- ================================================= --}}
    {{-- ÉTUDIANT --}}
    {{-- ================================================= --}}

    @if (auth()->user()->role === 'etudiant')

        <ul class="dashboard-menu">

            <li>
                <a
                    class="{{
                        request()->routeIs('dashboard')
                            ? 'active'
                            : ''
                    }}"
                    href="{{ route('dashboard') }}"
                >
                    <span>Tableau de bord</span>
                </a>
            </li>


            <li>
                <a
                    class="{{
                        request()->routeIs('etudiant.cours.*')
                            ? 'active'
                            : ''
                    }}"
                    href="{{ route('etudiant.cours.index') }}"
                >
                    <span>Mes cours</span>
                </a>
            </li>


            <li>
                <a
                    class="{{
                        request()->routeIs('etudiant.quiz.*')
                            ? 'active'
                            : ''
                    }}"
                    href="{{ route('etudiant.quiz.index') }}"
                >
                    <span>Mes quiz</span>
                </a>
            </li>


            <li>
                <a
                    class="{{
                        request()->routeIs('etudiant.resultats.*')
                            ? 'active'
                            : ''
                    }}"
                    href="{{ route('etudiant.resultats.index') }}"
                >
                    <span>Mes résultats</span>
                </a>
            </li>


            <li>
                <a
                    class="{{
                        request()->routeIs('messages.*')
                            ? 'active'
                            : ''
                    }}"
                    href="{{ route('messages.index') }}"
                >

                    <span>Messages</span>

                    @if ($nombreMessagesNonLus > 0)

                        <span class="message-count">
                            {{ $nombreMessagesNonLus }}
                        </span>

                    @endif

                </a>
            </li>


            <li>

                <a
                    class="{{
                        request()->routeIs('notifications.*')
                            ? 'active'
                            : ''
                    }}"
                    href="{{ route('notifications.index') }}"
                >

                    <span class="notification-menu-text">

                        <span>
                            Notifications
                        </span>

                        <span class="notification-bell-wrapper">

                            <span class="notification-bell">
                                🔔
                            </span>

                            @if (
                                $nombreNotificationsNonLues > 0
                            )

                                <span class="notification-red-badge">

                                    {{
                                        $nombreNotificationsNonLues > 99
                                            ? '99+'
                                            : $nombreNotificationsNonLues
                                    }}

                                </span>

                            @endif

                        </span>

                    </span>

                </a>

            </li>


            <li>
                <a
                    class="{{
                        request()->routeIs('etudiant.profil.*')
                            ? 'active'
                            : ''
                    }}"
                    href="{{ route('etudiant.profil.index') }}"
                >
                    <span>Mon profil</span>
                </a>
            </li>

        </ul>

    @endif


    {{-- ================================================= --}}
    {{-- FORMATEUR --}}
    {{-- ================================================= --}}

    @if (auth()->user()->role === 'formateur')

        <ul class="dashboard-menu">

            <li>
                <a
                    class="{{
                        request()->routeIs('dashboard')
                            ? 'active'
                            : ''
                    }}"
                    href="{{ route('dashboard') }}"
                >
                    <span>Tableau de bord</span>
                </a>
            </li>


            <li>
                <a
                    class="{{
                        request()->routeIs('formateur.cours.*')
                            ? 'active'
                            : ''
                    }}"
                    href="{{ route('formateur.cours.index') }}"
                >
                    <span>Mes cours attribués</span>
                </a>
            </li>


            <li>
                <a
                    class="{{
                        request()->routeIs('formateur.lecons.*')
                            ? 'active'
                            : ''
                    }}"
                    href="{{ route('formateur.lecons.manage') }}"
                >
                    <span>Gérer les leçons</span>
                </a>
            </li>


            <li>
                <a
                    class="{{
                        request()->routeIs('formateur.contenus.*')
                            ? 'active'
                            : ''
                    }}"
                    href="{{ route('formateur.contenus.manage') }}"
                >
                    <span>Gérer les contenus</span>
                </a>
            </li>


            <li>
                <a
                    class="{{
                        request()->routeIs('formateur.quiz.*')
                            ? 'active'
                            : ''
                    }}"
                    href="{{ route('formateur.quiz.manage') }}"
                >
                    <span>Gérer les quiz</span>
                </a>
            </li>


            <li>
                <a
                    class="{{
                        request()->routeIs('formateur.resultats.*')
                            ? 'active'
                            : ''
                    }}"
                    href="{{ route('formateur.resultats.index') }}"
                >
                    <span>Résultats des étudiants</span>
                </a>
            </li>


            <li>
                <a
                    class="{{
                        request()->routeIs('messages.*')
                            ? 'active'
                            : ''
                    }}"
                    href="{{ route('messages.index') }}"
                >

                    <span>Messages</span>

                    @if ($nombreMessagesNonLus > 0)

                        <span class="message-count">
                            {{ $nombreMessagesNonLus }}
                        </span>

                    @endif

                </a>
            </li>


            <li>

                <a
                    class="{{
                        request()->routeIs('notifications.*')
                            ? 'active'
                            : ''
                    }}"
                    href="{{ route('notifications.index') }}"
                >

                    <span class="notification-menu-text">

                        <span>
                            Notifications
                        </span>

                        <span class="notification-bell-wrapper">

                            <span class="notification-bell">
                                🔔
                            </span>

                            @if (
                                $nombreNotificationsNonLues > 0
                            )

                                <span class="notification-red-badge">

                                    {{
                                        $nombreNotificationsNonLues > 99
                                            ? '99+'
                                            : $nombreNotificationsNonLues
                                    }}

                                </span>

                            @endif

                        </span>

                    </span>

                </a>

            </li>


            <li>
                <a
                    class="{{
                        request()->routeIs('formateur.profil.*')
                            ? 'active'
                            : ''
                    }}"
                    href="{{ route('formateur.profil.index') }}"
                >
                    <span>Mon profil</span>
                </a>
            </li>

        </ul>

    @endif


    {{-- ================================================= --}}
    {{-- RESPONSABLE PÉDAGOGIQUE --}}
    {{-- ================================================= --}}

    @if (
        auth()->user()->role
        === 'responsable_pedagogique'
    )

        <ul class="dashboard-menu">

            <li>
                <a
                    class="{{
                        request()->routeIs('dashboard')
                            ? 'active'
                            : ''
                    }}"
                    href="{{ route('dashboard') }}"
                >
                    <span>Tableau de bord</span>
                </a>
            </li>


            <li>
                <a
                    class="{{
                        request()->routeIs(
                            'responsable.utilisateurs.*'
                        )
                            ? 'active'
                            : ''
                    }}"
                    href="{{
                        route(
                            'responsable.utilisateurs.index'
                        )
                    }}"
                >
                    <span>Gérer les utilisateurs</span>
                </a>
            </li>


            <li>
                <a
                    class="{{
                        request()->routeIs(
                            'responsable.filieres.*'
                        )
                            ? 'active'
                            : ''
                    }}"
                    href="{{
                        route(
                            'responsable.filieres.index'
                        )
                    }}"
                >
                    <span>Gérer les filières</span>
                </a>
            </li>


            <li>
                <a
                    class="{{
                        request()->routeIs(
                            'responsable.cours.*'
                        )
                            ? 'active'
                            : ''
                    }}"
                    href="{{
                        route(
                            'responsable.cours.index'
                        )
                    }}"
                >
                    <span>Gérer les cours</span>
                </a>
            </li>


            <li>
                <a
                    class="{{
                        request()->routeIs(
                            'responsable.lecons.*'
                        )
                            ? 'active'
                            : ''
                    }}"
                    href="{{
                        route(
                            'responsable.lecons.index'
                        )
                    }}"
                >
                    <span>Gérer les leçons</span>
                </a>
            </li>


            <li>
                <a
                    class="{{
                        request()->routeIs(
                            'responsable.contenus.*'
                        )
                            ? 'active'
                            : ''
                    }}"
                    href="{{
                        route(
                            'responsable.contenus.index'
                        )
                    }}"
                >
                    <span>Gérer les contenus</span>
                </a>
            </li>


            <li>
                <a
                    class="{{
                        request()->routeIs(
                            'responsable.quiz.*'
                        )
                            ? 'active'
                            : ''
                    }}"
                    href="{{
                        route(
                            'responsable.quiz.index'
                        )
                    }}"
                >
                    <span>Gérer les quiz</span>
                </a>
            </li>


            <li>
                <a
                    class="{{
                        request()->routeIs(
                            'responsable.suivi.*'
                        )
                            ? 'active'
                            : ''
                    }}"
                    href="{{
                        route(
                            'responsable.suivi.index'
                        )
                    }}"
                >
                    <span>Suivi des étudiants</span>
                </a>
            </li>


            <li>
                <a
                    class="{{
                        request()->routeIs(
                            'responsable.resultats.*'
                        )
                            ? 'active'
                            : ''
                    }}"
                    href="{{
                        route(
                            'responsable.resultats.index'
                        )
                    }}"
                >
                    <span>Résultats des quiz</span>
                </a>
            </li>


            <li>

                <a
                    class="{{
                        request()->routeIs('notifications.*')
                            ? 'active'
                            : ''
                    }}"
                    href="{{ route('notifications.index') }}"
                >

                    <span class="notification-menu-text">

                        <span>
                            Notifications
                        </span>

                        <span class="notification-bell-wrapper">

                            <span class="notification-bell">
                                🔔
                            </span>

                            @if (
                                $nombreNotificationsNonLues > 0
                            )

                                <span class="notification-red-badge">

                                    {{
                                        $nombreNotificationsNonLues > 99
                                            ? '99+'
                                            : $nombreNotificationsNonLues
                                    }}

                                </span>

                            @endif

                        </span>

                    </span>

                </a>

            </li>


            <li>
                <a
                    class="{{
                        request()->routeIs(
                            'responsable.profil.*'
                        )
                            ? 'active'
                            : ''
                    }}"
                    href="{{
                        route(
                            'responsable.profil.index'
                        )
                    }}"
                >
                    <span>Mon profil</span>
                </a>
            </li>

        </ul>

    @endif

</aside>

<main
    id="contenu-principal"
    class="dashboard-content"
    tabindex="-1"
>
    @yield('content')
</main>

@include('partials.accessibility')

@stack('scripts')

</body>
</html>
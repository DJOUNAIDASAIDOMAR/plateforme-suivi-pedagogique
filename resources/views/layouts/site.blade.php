<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="Plateforme de suivi pédagogique destinée aux étudiants en Développement Web et Web Mobile."
    >

    <title>
        @yield(
            'title',
            'Plateforme de suivi pédagogique'
        )
    </title>

    <link
        rel="stylesheet"
        href="{{ asset('css/site.css') }}"
    >

    @stack('styles')
</head>

<body>

    @include('partials.header')


    <main
        id="contenu-principal"
        class="page-with-header"
        tabindex="-1"
    >

        @yield('content')

    </main>


    @include('partials.accessibility')


    @stack('scripts')

</body>

</html>
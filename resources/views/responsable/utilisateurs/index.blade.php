@extends('layouts.dashboard')

@section('title', 'Gérer les utilisateurs')

@push('styles')
<style>
    .page {
        min-height: 650px;
        padding: 50px 30px 80px;
        background: #f3f6fb;
    }

    .container {
        max-width: 1150px;
        margin: 0 auto;
    }

    .header {
        margin-bottom: 25px;
        padding: 30px;
        border-radius: 20px;
        background: linear-gradient(135deg, #1e3a8a, #2563eb);
        color: white;
    }

    .header h1 {
        margin: 0 0 10px;
    }

    .header p {
        margin: 0;
        color: #dbeafe;
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

    .table-wrapper {
        overflow-x: auto;
        border-radius: 18px;
        background: white;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 16px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
    }

    th {
        background: #eff6ff;
        color: #1e3a8a;
    }

    .role {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 20px;
        background: #dbeafe;
        color: #1e3a8a;
        font-size: 12px;
        font-weight: 800;
    }

    .button {
        display: inline-block;
        padding: 9px 13px;
        border-radius: 8px;
        background: #1e3a8a;
        color: white;
        font-weight: 700;
        text-decoration: none;
    }
</style>
@endpush

@section('content')

<section class="page">
    <div class="container">

        <header class="header">
            <h1>Gérer les utilisateurs</h1>
            <p>
                Consultez et modifiez les comptes
                des étudiants et des formateurs.
            </p>
        </header>

        @if (session('success'))
            <div class="message success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-wrapper">

            <table>

                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>E-mail</th>
                        <th>Rôle</th>
                        <th>Information</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($utilisateurs as $utilisateur)

                        <tr>

                            <td>
                                <strong>{{ $utilisateur->nom }}</strong>
                            </td>

                            <td>
                                {{ $utilisateur->email }}
                            </td>

                            <td>
                                <span class="role">
                                    {{
                                        $utilisateur->role === 'etudiant'
                                            ? 'Étudiant'
                                            : 'Formateur'
                                    }}
                                </span>
                            </td>

                            <td>

                                @if ($utilisateur->role === 'etudiant')

                                    {{
                                        $utilisateur
                                            ->etudiant
                                            ?->filier
                                            ?->nom_filier
                                        ?? 'Filière non renseignée'
                                    }}

                                @else

                                    {{
                                        $utilisateur
                                            ->formateur
                                            ?->{'specialité'}
                                        ?? 'Spécialité non renseignée'
                                    }}

                                @endif

                            </td>

                            <td>

                                <a
                                    class="button"
                                    href="{{
                                        route(
                                            'responsable.utilisateurs.edit',
                                            $utilisateur->id_user
                                        )
                                    }}"
                                >
                                    Modifier
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5">
                                Aucun utilisateur.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>
</section>

@endsection
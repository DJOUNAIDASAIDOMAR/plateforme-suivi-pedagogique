@extends('layouts.dashboard')

@section('title', 'Suivi des étudiants')

@section('content')

<div style="
    min-height:650px;
    padding:50px 30px;
    background:#f3f6fb;
">

<div style="max-width:1150px;margin:auto;">

    <div style="
        margin-bottom:25px;
        padding:30px;
        border-radius:20px;
        background:linear-gradient(135deg,#1e3a8a,#2563eb);
        color:white;
    ">

        <h1>Suivi des étudiants</h1>

        <p>
            Consultez la progression générale
            et l'activité des étudiants.
        </p>

    </div>

    <div style="
        overflow:auto;
        border-radius:18px;
        background:white;
    ">

        <table style="
            width:100%;
            border-collapse:collapse;
        ">

            <thead>

                <tr style="background:#eff6ff;">

                    <th style="padding:16px;">Étudiant</th>
                    <th style="padding:16px;">Filière</th>
                    <th style="padding:16px;">Progression</th>
                    <th style="padding:16px;">Quiz réalisés</th>

                </tr>

            </thead>

            <tbody>

                @foreach ($etudiants as $etudiant)

                    <tr>

                        <td style="padding:16px;">
                            {{ $etudiant->user?->nom }}
                        </td>

                        <td style="padding:16px;">
                            {{ $etudiant->filier?->nom_filier }}
                        </td>

                        <td style="padding:16px;">
                            {{ $etudiant->progression }} %
                        </td>

                        <td style="padding:16px;">
                            {{ $etudiant->resultats_count }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

</div>

@endsection
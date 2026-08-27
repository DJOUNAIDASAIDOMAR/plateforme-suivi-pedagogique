@extends('layouts.dashboard')

@section('title', 'Résultats des quiz')

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

        <h1>Résultats des quiz</h1>

        <p>
            Consultez tous les résultats
            des étudiants.
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
                    <th style="padding:16px;">Cours</th>
                    <th style="padding:16px;">Quiz</th>
                    <th style="padding:16px;">Score</th>
                    <th style="padding:16px;">Date</th>
                </tr>

            </thead>

            <tbody>

                @forelse ($resultats as $resultat)

                    <tr>

                        <td style="padding:16px;">
                            {{
                                $resultat
                                    ->etudiant
                                    ?->user
                                    ?->nom
                            }}
                        </td>

                        <td style="padding:16px;">
                            {{
                                $resultat
                                    ->etudiant
                                    ?->filier
                                    ?->nom_filier
                            }}
                        </td>

                        <td style="padding:16px;">
                            {{
                                $resultat
                                    ->quiz
                                    ?->cours
                                    ?->titre_cours
                            }}
                        </td>

                        <td style="padding:16px;">
                            {{
                                $resultat
                                    ->quiz
                                    ?->titre_quiz
                            }}
                        </td>

                        <td style="padding:16px;">
                            <strong>
                                {{ $resultat->score }} %
                            </strong>
                        </td>

                        <td style="padding:16px;">
                            {{
                                $resultat->date_resultat
                                    ? $resultat
                                        ->date_resultat
                                        ->format('d/m/Y')
                                    : '-'
                            }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" style="padding:20px;">
                            Aucun résultat.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</div>

@endsection
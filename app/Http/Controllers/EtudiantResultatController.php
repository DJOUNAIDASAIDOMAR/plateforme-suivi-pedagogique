<?php

namespace App\Http\Controllers;

use App\Models\Resultat;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EtudiantResultatController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        abort_unless(
            $user
            && $user->role === 'etudiant'
            && $user->etudiant,
            403,
            'Accès réservé aux étudiants.'
        );

        $etudiant = $user->etudiant;

        /*
        |--------------------------------------------------------------------------
        | RÉSULTATS DE L'ÉTUDIANT
        |--------------------------------------------------------------------------
        |
        | On récupère maintenant TOUTES les tentatives.
        |
        | id_resultat permet de connaître l'ordre réel des tentatives,
        | même lorsque plusieurs tentatives sont réalisées le même jour.
        |
        */

        $resultats = Resultat::with([
            'quiz.cours',
        ])
            ->where(
                'id_etudiant',
                $etudiant->id_etudiant
            )
            ->orderByDesc('id_resultat')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | NUMÉRO DE CHAQUE TENTATIVE
        |--------------------------------------------------------------------------
        |
        | On groupe les résultats par quiz.
        |
        | Pour chaque quiz :
        | première tentative  = 1
        | deuxième tentative  = 2
        | troisième tentative = 3
        | etc.
        |
        */

        $numerosTentatives = [];

        $resultats
            ->groupBy('id_quiz')
            ->each(
                function ($tentatives) use (
                    &$numerosTentatives
                ) {
                    $tentativesTriees =
                        $tentatives
                            ->sortBy('id_resultat')
                            ->values();

                    foreach (
                        $tentativesTriees
                        as $index => $resultat
                    ) {
                        $numerosTentatives[
                            $resultat->id_resultat
                        ] = $index + 1;
                    }
                }
            );

        return view(
            'etudiant.resultats.index',
            compact(
                'resultats',
                'etudiant',
                'numerosTentatives'
            )
        );
    }
}
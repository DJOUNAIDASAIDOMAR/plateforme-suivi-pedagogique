<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Resultat;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FormateurResultatController extends Controller
{
    /**
     * Retourne le formateur connecté.
     */
    private function formateurConnecte()
    {
        $user = Auth::user();

        abort_unless(
            $user
            && $user->role === 'formateur'
            && $user->formateur,
            403,
            'Accès réservé aux formateurs.'
        );

        return $user->formateur;
    }

    /**
     * Affiche les résultats des étudiants
     * uniquement pour les quiz des cours
     * attribués au formateur connecté.
     */
    public function index(): View
    {
        $formateur = $this->formateurConnecte();

        $cours = Cours::where(
            'id_formateur',
            $formateur->id_formateur
        )
            ->orderBy('titre_cours')
            ->get();

        $idsCours = $cours->pluck('id_cours');

        $resultats = Resultat::with([
            'etudiant.user',
            'quiz.cours',
        ])
            ->whereHas(
                'quiz',
                function ($query) use ($idsCours) {
                    $query->whereIn(
                        'id_cours',
                        $idsCours
                    );
                }
            )
            ->orderByDesc('date_resultat')
            ->get();

        return view(
            'formateur.resultats.index',
            compact(
                'resultats',
                'cours'
            )
        );
    }
}
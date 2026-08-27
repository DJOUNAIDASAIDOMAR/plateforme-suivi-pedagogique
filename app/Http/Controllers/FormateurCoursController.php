<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FormateurCoursController extends Controller
{
    /**
     * Vérifie que l'utilisateur connecté
     * est bien un formateur.
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
     * Affiche uniquement les cours
     * attribués au formateur connecté.
     */
    public function index(): View
    {
        $formateur = $this->formateurConnecte();

        $cours = Cours::with([
            'filier',
        ])
            ->withCount([
                'lecons',
                'quizzes',
            ])
            ->where(
                'id_formateur',
                $formateur->id_formateur
            )
            ->orderBy('titre_cours')
            ->get();

        return view(
            'formateur.cours.index',
            compact('cours')
        );
    }
}
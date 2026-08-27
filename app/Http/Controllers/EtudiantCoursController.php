<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EtudiantCoursController extends Controller
{
    /**
     * Retourne l'étudiant connecté.
     */
    private function etudiantConnecte()
    {
        $user = Auth::user();

        abort_unless(
            $user
            && $user->role === 'etudiant'
            && $user->etudiant,
            403,
            'Accès réservé aux étudiants.'
        );

        return $user->etudiant;
    }


    /**
     * Affiche uniquement les cours
     * de la filière de l'étudiant connecté.
     */
    public function index(): View
    {
        $etudiant =
            $this->etudiantConnecte();

        $cours = Cours::with([
            'filier',
            'formateur.user',
        ])
            ->withCount([
                'lecons',
                'quizzes',
            ])
            ->where(
                'id_filier',
                $etudiant->id_filier
            )
            ->orderBy('titre_cours')
            ->get();

        return view(
            'etudiant.cours.index',
            compact(
                'cours',
                'etudiant'
            )
        );
    }


    /**
     * Affiche un cours uniquement s'il
     * appartient à la filière de l'étudiant.
     */
    public function show(
        int $idCours
    ): View|RedirectResponse {

        $etudiant =
            $this->etudiantConnecte();


        /*
        |--------------------------------------------------------------------------
        | RÉCUPÉRER LE COURS
        |--------------------------------------------------------------------------
        */

        $cours = Cours::with([
            'filier',
            'formateur.user',
            'lecons.contenus',
        ])
            ->where(
                'id_filier',
                $etudiant->id_filier
            )
            ->find($idCours);


        if (! $cours) {

            return redirect()
                ->route(
                    'etudiant.cours.index'
                )
                ->with(
                    'error',
                    'Ce cours n’appartient pas à votre filière.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | TRIER LES LEÇONS
        |--------------------------------------------------------------------------
        */

        $cours->setRelation(
            'lecons',
            $cours
                ->lecons
                ->sortBy('ordre')
                ->values()
        );


        /*
        |--------------------------------------------------------------------------
        | RÉCUPÉRER LE QUIZ DU COURS
        |--------------------------------------------------------------------------
        */

        $quizDuCours = Quiz::where(
            'id_cours',
            $cours->id_cours
        )
            ->orderBy('id_quiz')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | AFFICHER LA VUE
        |--------------------------------------------------------------------------
        */

        return view(
            'etudiant.cours.show',
            compact(
                'cours',
                'etudiant',
                'quizDuCours'
            )
        );
    }
}
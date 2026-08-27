<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CoursPublicController extends Controller
{
    /**
     * Affiche la liste publique des cours.
     */
    public function index(): View
    {
        $cours = Cours::with([
            'filier',
            'formateur.user',
        ])
            ->withCount([
                'lecons',
                'quizzes',
            ])
            ->orderBy('titre_cours')
            ->get();

        return view(
            'cours-public',
            compact('cours')
        );
    }


    /**
     * Affiche le détail d'un cours.
     */
    public function show(
        int $id
    ): View|RedirectResponse {

        if (! Auth::check()) {

            return redirect()
                ->route('login')
                ->with(
                    'success',
                    'Connectez-vous ou créez un compte pour accéder à ce cours.'
                );
        }


        $cours = Cours::with([
            'filier',
            'formateur.user',
            'lecons.contenus',
        ])
            ->findOrFail($id);


        $user = Auth::user();


        if (
            $user->role === 'etudiant'
            &&
            $user->etudiant
            &&
            (int) $user->etudiant->id_filier
            !==
            (int) $cours->id_filier
        ) {

            return redirect()
                ->route('cours.public')
                ->with(
                    'cours_error',
                    'Ce cours n’appartient pas à votre filière.'
                );
        }


        $quizDuCours = Quiz::where(
            'id_cours',
            $cours->id_cours
        )
            ->orderBy('id_quiz')
            ->first();


        return view(
            'cours-detail',
            compact(
                'cours',
                'quizDuCours'
            )
        );
    }
}
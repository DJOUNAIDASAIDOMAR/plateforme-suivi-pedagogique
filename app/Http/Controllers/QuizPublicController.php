<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuizPublicController extends Controller
{
    /**
     * Affiche la liste publique des quiz.
     */
    public function index(): View
    {
        $quizzes = Quiz::with([
            'cours.filier',
            'cours.formateur.user',
        ])
            ->withCount('questions')
            ->orderBy('titre_quiz')
            ->get();

        return view(
            'quiz-public',
            compact('quizzes')
        );
    }

    /**
     * Affiche le détail d’un quiz.
     */
    public function show(int $id): View|RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()
                ->route('login')
                ->with(
                    'success',
                    'Connectez-vous ou créez un compte pour accéder à ce quiz.'
                );
        }

        $quiz = Quiz::with([
            'cours.filier',
            'cours.formateur.user',
            'questions',
        ])->findOrFail($id);

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Vérification de la filière de l’étudiant
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === 'etudiant'
            && $user->etudiant
            && (int) $user->etudiant->id_filier !==
                (int) $quiz->cours->id_filier
        ) {
            return redirect()
                ->route('quiz.public')
                ->with(
                    'quiz_error',
                    'Ce quiz n’appartient pas à votre filière.'
                );
        }

        return view(
            'quiz-detail',
            compact('quiz')
        );
    }
}
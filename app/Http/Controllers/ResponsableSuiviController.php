<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ResponsableSuiviController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        abort_unless(
            $user
            && $user->role === 'responsable_pedagogique'
            && $user->responsablePedagogique,
            403,
            'Accès réservé au responsable pédagogique.'
        );

        $etudiants = Etudiant::with([
            'user',
            'filier',
            'resultats.quiz',
        ])
            ->get()
            ->sortBy(
                fn ($etudiant) =>
                    $etudiant->user?->nom ?? ''
            );

        foreach ($etudiants as $etudiant) {

            /*
            |--------------------------------------------------------------------------
            | Nombre de quiz différents réalisés
            |--------------------------------------------------------------------------
            | Si l'étudiant refait 7 fois le même quiz,
            | cela compte toujours comme 1 quiz réalisé.
            */
            $quizRealises = $etudiant->resultats
                ->pluck('id_quiz')
                ->unique()
                ->count();

            /*
            |--------------------------------------------------------------------------
            | Nombre total de quiz disponibles dans sa filière
            |--------------------------------------------------------------------------
            */
            $totalQuiz = \App\Models\Quiz::whereHas(
                'cours',
                function ($query) use ($etudiant) {
                    $query->where(
                        'id_filier',
                        $etudiant->id_filier
                    );
                }
            )->count();

            /*
            |--------------------------------------------------------------------------
            | Calcul automatique de la progression
            |--------------------------------------------------------------------------
            */
            if ($totalQuiz > 0) {
                $progression = round(
                    ($quizRealises / $totalQuiz) * 100
                );
            } else {
                $progression = 0;
            }

            /*
            |--------------------------------------------------------------------------
            | Valeurs utilisées dans la vue
            |--------------------------------------------------------------------------
            */
            $etudiant->quiz_realises = $quizRealises;
            $etudiant->progression_calculee = $progression;
        }

        return view(
            'responsable.suivi.index',
            compact('etudiants')
        );
    }
}
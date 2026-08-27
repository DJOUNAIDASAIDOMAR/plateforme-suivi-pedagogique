<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Resultat;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EtudiantQuizController extends Controller
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
     * Affiche les quiz de la filière
     * de l'étudiant connecté.
     */
    public function index(): View
    {
        $etudiant =
            $this->etudiantConnecte();


        $quiz = Quiz::with([
            'cours',
        ])
            ->withCount(
                'questions'
            )
            ->whereHas(
                'cours',
                function ($query) use (
                    $etudiant
                ) {
                    $query->where(
                        'id_filier',
                        $etudiant->id_filier
                    );
                }
            )
            ->orderByDesc(
                'id_quiz'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | QUIZ DÉJÀ RÉALISÉS AU MOINS UNE FOIS
        |--------------------------------------------------------------------------
        |
        | On garde cette information uniquement pour
        | pouvoir afficher "Refaire le quiz" dans la vue.
        |
        | Cela ne bloque PLUS l'accès au quiz.
        |
        */

        $quizRealises =
            Resultat::where(
                'id_etudiant',
                $etudiant->id_etudiant
            )
                ->distinct()
                ->pluck(
                    'id_quiz'
                )
                ->toArray();


        return view(
            'etudiant.quiz.index',
            compact(
                'quiz',
                'quizRealises',
                'etudiant'
            )
        );
    }


    /**
     * Affiche un quiz.
     */
    public function show(
        int $idQuiz
    ): View|RedirectResponse {

        $etudiant =
            $this->etudiantConnecte();


        /*
        |--------------------------------------------------------------------------
        | RÉCUPÉRER LE QUIZ
        |--------------------------------------------------------------------------
        */

        $quiz = Quiz::with([
            'cours',
            'questions',
        ])
            ->whereHas(
                'cours',
                function ($query) use (
                    $etudiant
                ) {
                    $query->where(
                        'id_filier',
                        $etudiant->id_filier
                    );
                }
            )
            ->find(
                $idQuiz
            );


        /*
        |--------------------------------------------------------------------------
        | QUIZ D'UNE AUTRE FILIÈRE
        |--------------------------------------------------------------------------
        */

        if (! $quiz) {

            return redirect()
                ->route(
                    'etudiant.quiz.index'
                )
                ->with(
                    'error',
                    'Ce quiz n’appartient pas à votre filière.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | VÉRIFIER QUE LE QUIZ CONTIENT DES QUESTIONS
        |--------------------------------------------------------------------------
        */

        if (
            $quiz->questions->isEmpty()
        ) {

            return redirect()
                ->route(
                    'etudiant.quiz.index'
                )
                ->with(
                    'error',
                    'Ce quiz ne contient encore aucune question.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | NOMBRE DE TENTATIVES DÉJÀ EFFECTUÉES
        |--------------------------------------------------------------------------
        */

        $nombreTentatives =
            Resultat::where(
                'id_etudiant',
                $etudiant->id_etudiant
            )
                ->where(
                    'id_quiz',
                    $quiz->id_quiz
                )
                ->count();


        return view(
            'etudiant.quiz.show',
            compact(
                'quiz',
                'etudiant',
                'nombreTentatives'
            )
        );
    }


    /**
     * Corrige le quiz et enregistre
     * UNE NOUVELLE tentative.
     */
    public function submit(
        Request $request,
        int $idQuiz
    ): RedirectResponse {

        $etudiant =
            $this->etudiantConnecte();


        /*
        |--------------------------------------------------------------------------
        | RÉCUPÉRER LE QUIZ
        |--------------------------------------------------------------------------
        */

        $quiz = Quiz::with([
            'questions',
            'cours.formateur.user',
        ])
            ->whereHas(
                'cours',
                function ($query) use (
                    $etudiant
                ) {
                    $query->where(
                        'id_filier',
                        $etudiant->id_filier
                    );
                }
            )
            ->findOrFail(
                $idQuiz
            );


        /*
        |--------------------------------------------------------------------------
        | VÉRIFIER QUE LE QUIZ CONTIENT DES QUESTIONS
        |--------------------------------------------------------------------------
        */

        if (
            $quiz->questions->isEmpty()
        ) {

            return redirect()
                ->route(
                    'etudiant.quiz.index'
                )
                ->with(
                    'error',
                    'Ce quiz ne contient aucune question.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATION DES RÉPONSES
        |--------------------------------------------------------------------------
        |
        | nullable :
        |
        | si le minuteur arrive à 00:00,
        | le formulaire peut être envoyé même si
        | certaines questions n'ont pas de réponse.
        |
        | Une question non répondue vaut 0 point.
        |
        */

        $rules = [];


        foreach (
            $quiz->questions
            as $question
        ) {

            $rules[
                'reponses.'
                . $question->id_question
            ] = [
                'nullable',
                'in:A,B,C,D',
            ];
        }


        $validated =
            $request->validate(
                $rules,
                [
                    'reponses.*.in' =>
                        'Une des réponses envoyées n’est pas valide.',
                ],
                [
                    'reponses.*' =>
                        'réponse',
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | RÉPONSES DE L'ÉTUDIANT
        |--------------------------------------------------------------------------
        */

        $reponsesEtudiant =
            $validated['reponses']
            ?? [];


        /*
        |--------------------------------------------------------------------------
        | CALCUL DES BONNES RÉPONSES
        |--------------------------------------------------------------------------
        */

        $bonnesReponses = 0;


        foreach (
            $quiz->questions
            as $question
        ) {

            $reponseEtudiant =
                $reponsesEtudiant[
                    $question->id_question
                ]
                ?? null;


            /*
            | Pas de réponse :
            | 0 point.
            */

            if (
                $reponseEtudiant === null
                ||
                $reponseEtudiant === ''
            ) {
                continue;
            }


            /*
            | Bonne réponse.
            */

            if (
                strtoupper(
                    $reponseEtudiant
                )
                ===
                strtoupper(
                    $question->bonne_reponse
                )
            ) {
                $bonnesReponses++;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | SCORE
        |--------------------------------------------------------------------------
        */

        $nombreQuestions =
            $quiz->questions->count();


        $score = (int) round(
            (
                $bonnesReponses
                /
                $nombreQuestions
            )
            * 100
        );


        /*
        |--------------------------------------------------------------------------
        | NUMÉRO DE LA NOUVELLE TENTATIVE
        |--------------------------------------------------------------------------
        */

        $ancienneTentatives =
            Resultat::where(
                'id_etudiant',
                $etudiant->id_etudiant
            )
                ->where(
                    'id_quiz',
                    $quiz->id_quiz
                )
                ->count();


        $numeroTentative =
            $ancienneTentatives + 1;


        /*
        |--------------------------------------------------------------------------
        | ENREGISTRER UNE NOUVELLE LIGNE
        |--------------------------------------------------------------------------
        |
        | Grâce à id_resultat AUTO_INCREMENT,
        | plusieurs résultats peuvent maintenant
        | avoir le même id_etudiant et le même id_quiz.
        |
        */

        Resultat::create([

            'id_etudiant' =>
                $etudiant->id_etudiant,

            'id_quiz' =>
                $quiz->id_quiz,

            'score' =>
                $score,

            'date_resultat' =>
                now()->toDateString(),

        ]);


        /*
        |--------------------------------------------------------------------------
        | NOTIFIER LE FORMATEUR
        |--------------------------------------------------------------------------
        */

        if (
            $quiz
                ->cours
                ?->formateur
                ?->user
        ) {

            NotificationService::pourUtilisateur(

                $quiz
                    ->cours
                    ->formateur
                    ->user
                    ->id_user,

                'Nouveau résultat de quiz',

                Auth::user()->nom
                    . ' a terminé le quiz « '
                    . $quiz->titre_quiz
                    . ' »'
                    . ' - tentative '
                    . $numeroTentative
                    . ' - avec un score de '
                    . $score
                    . ' %.',

                'resultat',

                route(
                    'formateur.resultats.index'
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | REDIRECTION
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'etudiant.resultats.index'
            )
            ->with(
                'success',

                'Quiz terminé. '
                . 'Tentative '
                . $numeroTentative
                . ' : '
                . $score
                . ' %.'
            );
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Question;
use App\Models\Quiz;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ResponsableQuizController extends Controller
{
    private function verifierResponsable(): void
    {
        $user = Auth::user();

        abort_unless(
            $user
            && $user->role === 'responsable_pedagogique'
            && $user->responsablePedagogique,
            403,
            'Accès réservé au responsable pédagogique.'
        );
    }

    public function index(): View
    {
        $this->verifierResponsable();

        $cours = Cours::with([
            'filier',
            'formateur.user',
        ])
            ->orderBy('titre_cours')
            ->get();

        $quizzes = Quiz::with([
            'cours.filier',
            'cours.formateur.user',
        ])
            ->withCount([
                'questions',
                'resultats',
            ])
            ->orderBy('titre_quiz')
            ->get();

        return view(
            'responsable.quiz.index',
            compact(
                'cours',
                'quizzes'
            )
        );
    }

    public function store(
        Request $request
    ): RedirectResponse {
        $this->verifierResponsable();

        $validated = $request->validate([
            'titre_quiz' => [
                'required',
                'string',
                'max:100',
            ],

            'id_cours' => [
                'required',
                'integer',

                Rule::exists(
                    'cours',
                    'id_cours'
                ),
            ],
        ]);

        $cours = Cours::findOrFail(
            $validated['id_cours']
        );

        Quiz::create([
            'titre_quiz' =>
                $validated['titre_quiz'],

            'id_cours' =>
                $cours->id_cours,
        ]);

        NotificationService::pourEtudiantsFiliere(
            $cours->id_filier,
            'Nouveau quiz disponible',
            'Un nouveau quiz « '
                . $validated['titre_quiz']
                . ' » est disponible pour le cours « '
                . $cours->titre_cours
                . ' ».',
            'quiz',
            route(
                'etudiant.quiz.index'
            )
        );

        return redirect()
            ->route(
                'responsable.quiz.index'
            )
            ->with(
                'success',
                'Le quiz a été créé avec succès.'
            );
    }

    public function edit(
        int $id
    ): View {
        $this->verifierResponsable();

        $quiz = Quiz::with([
            'cours',
        ])->findOrFail($id);

        $cours = Cours::with([
            'filier',
            'formateur.user',
        ])
            ->orderBy('titre_cours')
            ->get();

        return view(
            'responsable.quiz.edit',
            compact(
                'quiz',
                'cours'
            )
        );
    }

    public function update(
        Request $request,
        int $id
    ): RedirectResponse {
        $this->verifierResponsable();

        $quiz =
            Quiz::findOrFail($id);

        $validated = $request->validate([
            'titre_quiz' => [
                'required',
                'string',
                'max:100',
            ],

            'id_cours' => [
                'required',
                'integer',

                Rule::exists(
                    'cours',
                    'id_cours'
                ),
            ],
        ]);

        $quiz->update([
            'titre_quiz' =>
                $validated['titre_quiz'],

            'id_cours' =>
                $validated['id_cours'],
        ]);

        return redirect()
            ->route(
                'responsable.quiz.index'
            )
            ->with(
                'success',
                'Le quiz a été modifié avec succès.'
            );
    }

    public function destroy(
        int $id
    ): RedirectResponse {
        $this->verifierResponsable();

        $quiz = Quiz::withCount([
            'questions',
            'resultats',
        ])->findOrFail($id);

        if ($quiz->questions_count > 0) {
            return back()->with(
                'error',
                'Impossible de supprimer ce quiz car il contient des questions.'
            );
        }

        if ($quiz->resultats_count > 0) {
            return back()->with(
                'error',
                'Impossible de supprimer ce quiz car des étudiants ont déjà des résultats pour ce quiz.'
            );
        }

        $quiz->delete();

        return redirect()
            ->route(
                'responsable.quiz.index'
            )
            ->with(
                'success',
                'Le quiz a été supprimé avec succès.'
            );
    }

    public function questions(
        int $idQuiz
    ): View {
        $this->verifierResponsable();

        $quiz = Quiz::with([
            'cours.filier',
            'cours.formateur.user',
            'questions',
        ])->findOrFail($idQuiz);

        return view(
            'responsable.quiz.questions',
            compact('quiz')
        );
    }

    public function storeQuestion(
        Request $request,
        int $idQuiz
    ): RedirectResponse {
        $this->verifierResponsable();

        $quiz =
            Quiz::findOrFail($idQuiz);

        $validated = $request->validate([
            'texte_question' => [
                'required',
                'string',
                'max:255',
            ],

            'choix_a' => [
                'required',
                'string',
                'max:255',
            ],

            'choix_b' => [
                'required',
                'string',
                'max:255',
            ],

            'choix_c' => [
                'required',
                'string',
                'max:255',
            ],

            'choix_d' => [
                'required',
                'string',
                'max:255',
            ],

            'bonne_reponse' => [
                'required',

                Rule::in([
                    'A',
                    'B',
                    'C',
                    'D',
                ]),
            ],
        ]);

        Question::create([
            'texte_question' =>
                $validated['texte_question'],

            'choix_a' =>
                $validated['choix_a'],

            'choix_b' =>
                $validated['choix_b'],

            'choix_c' =>
                $validated['choix_c'],

            'choix_d' =>
                $validated['choix_d'],

            'bonne_reponse' =>
                $validated['bonne_reponse'],

            'id_quiz' =>
                $quiz->id_quiz,
        ]);

        return redirect()
            ->route(
                'responsable.quiz.questions',
                $quiz->id_quiz
            )
            ->with(
                'success',
                'La question a été ajoutée avec succès.'
            );
    }

    public function editQuestion(
        int $idQuestion
    ): View {
        $this->verifierResponsable();

        $question = Question::with([
            'quiz.cours',
        ])->findOrFail($idQuestion);

        return view(
            'responsable.quiz.question-edit',
            compact('question')
        );
    }

    public function updateQuestion(
        Request $request,
        int $idQuestion
    ): RedirectResponse {
        $this->verifierResponsable();

        $question =
            Question::findOrFail(
                $idQuestion
            );

        $validated = $request->validate([
            'texte_question' => [
                'required',
                'string',
                'max:255',
            ],

            'choix_a' => [
                'required',
                'string',
                'max:255',
            ],

            'choix_b' => [
                'required',
                'string',
                'max:255',
            ],

            'choix_c' => [
                'required',
                'string',
                'max:255',
            ],

            'choix_d' => [
                'required',
                'string',
                'max:255',
            ],

            'bonne_reponse' => [
                'required',

                Rule::in([
                    'A',
                    'B',
                    'C',
                    'D',
                ]),
            ],
        ]);

        $question->update([
            'texte_question' =>
                $validated['texte_question'],

            'choix_a' =>
                $validated['choix_a'],

            'choix_b' =>
                $validated['choix_b'],

            'choix_c' =>
                $validated['choix_c'],

            'choix_d' =>
                $validated['choix_d'],

            'bonne_reponse' =>
                $validated['bonne_reponse'],
        ]);

        return redirect()
            ->route(
                'responsable.quiz.questions',
                $question->id_quiz
            )
            ->with(
                'success',
                'La question a été modifiée avec succès.'
            );
    }

    public function destroyQuestion(
        int $idQuestion
    ): RedirectResponse {
        $this->verifierResponsable();

        $question =
            Question::findOrFail(
                $idQuestion
            );

        $idQuiz =
            $question->id_quiz;

        $question->delete();

        return redirect()
            ->route(
                'responsable.quiz.questions',
                $idQuiz
            )
            ->with(
                'success',
                'La question a été supprimée avec succès.'
            );
    }
}
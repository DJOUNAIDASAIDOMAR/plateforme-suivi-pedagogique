<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Question;
use App\Models\Quiz;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FormateurQuizController extends Controller
{
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

    private function coursDuFormateur(
        int $idCours
    ): Cours {
        $formateur =
            $this->formateurConnecte();

        return Cours::where(
            'id_formateur',
            $formateur->id_formateur
        )->findOrFail($idCours);
    }

    private function quizDuFormateur(
        int $idQuiz
    ): Quiz {
        $formateur =
            $this->formateurConnecte();

        return Quiz::with('cours')
            ->whereHas(
                'cours',
                function ($query) use (
                    $formateur
                ) {
                    $query->where(
                        'id_formateur',
                        $formateur
                            ->id_formateur
                    );
                }
            )
            ->findOrFail($idQuiz);
    }

    public function manage(): View
    {
        $formateur =
            $this->formateurConnecte();

        $cours = Cours::where(
            'id_formateur',
            $formateur->id_formateur
        )
            ->orderBy('titre_cours')
            ->get();

        $idsCours =
            $cours->pluck('id_cours');

        $quiz = Quiz::with('cours')
            ->withCount('questions')
            ->whereIn(
                'id_cours',
                $idsCours
            )
            ->orderByDesc('id_quiz')
            ->get();

        return view(
            'formateur.quiz.manage',
            compact(
                'cours',
                'quiz'
            )
        );
    }

    public function store(
        Request $request
    ): RedirectResponse {
        $formateur =
            $this->formateurConnecte();

        $validated = $request->validate([
            'id_cours' => [
                'required',
                'integer',
            ],

            'titre_quiz' => [
                'required',
                'string',
                'max:100',
            ],
        ]);

        $cours = Cours::where(
            'id_formateur',
            $formateur->id_formateur
        )
            ->where(
                'id_cours',
                $validated['id_cours']
            )
            ->firstOrFail();

        Quiz::create([
            'titre_quiz' =>
                $validated['titre_quiz'],

            'id_cours' =>
                $cours->id_cours,
        ]);

        NotificationService::pourEtudiantsFiliere(
            $cours->id_filier,
            'Nouveau quiz disponible',
            'Le quiz « '
                . $validated['titre_quiz']
                . ' » est maintenant disponible pour le cours « '
                . $cours->titre_cours
                . ' ».',
            'quiz',
            route(
                'etudiant.quiz.index'
            )
        );

        NotificationService::pourResponsables(
            'Nouveau quiz créé',
            'Le formateur '
                . Auth::user()->nom
                . ' a créé le quiz « '
                . $validated['titre_quiz']
                . ' » pour le cours « '
                . $cours->titre_cours
                . ' ».',
            'quiz',
            route(
                'responsable.quiz.index'
            )
        );

        return redirect()
            ->route('formateur.quiz.manage')
            ->with(
                'success',
                'Le quiz a été créé avec succès.'
            );
    }

    public function edit(
        int $idQuiz
    ): View {
        $quiz =
            $this->quizDuFormateur(
                $idQuiz
            );

        $formateur =
            $this->formateurConnecte();

        $cours = Cours::where(
            'id_formateur',
            $formateur->id_formateur
        )
            ->orderBy('titre_cours')
            ->get();

        return view(
            'formateur.quiz.edit',
            compact(
                'quiz',
                'cours'
            )
        );
    }

    public function update(
        Request $request,
        int $idQuiz
    ): RedirectResponse {
        $quiz =
            $this->quizDuFormateur(
                $idQuiz
            );

        $formateur =
            $this->formateurConnecte();

        $validated = $request->validate([
            'id_cours' => [
                'required',
                'integer',
            ],

            'titre_quiz' => [
                'required',
                'string',
                'max:100',
            ],
        ]);

        $cours = Cours::where(
            'id_formateur',
            $formateur->id_formateur
        )
            ->where(
                'id_cours',
                $validated['id_cours']
            )
            ->firstOrFail();

        $quiz->update([
            'titre_quiz' =>
                $validated['titre_quiz'],

            'id_cours' =>
                $cours->id_cours,
        ]);

        return redirect()
            ->route('formateur.quiz.manage')
            ->with(
                'success',
                'Le quiz a été modifié avec succès.'
            );
    }

    public function destroy(
        int $idQuiz
    ): RedirectResponse {
        $quiz =
            $this->quizDuFormateur(
                $idQuiz
            );

        $quiz->loadCount([
            'questions',
            'resultats',
        ]);

        if ($quiz->resultats_count > 0) {
            return back()->with(
                'error',
                'Impossible de supprimer ce quiz car des étudiants ont déjà des résultats.'
            );
        }

        Question::where(
            'id_quiz',
            $quiz->id_quiz
        )->delete();

        $quiz->delete();

        return redirect()
            ->route('formateur.quiz.manage')
            ->with(
                'success',
                'Le quiz a été supprimé avec succès.'
            );
    }

    public function questions(
        int $idQuiz
    ): View {
        $quiz =
            $this->quizDuFormateur(
                $idQuiz
            );

        $questions = Question::where(
            'id_quiz',
            $quiz->id_quiz
        )
            ->orderBy('id_question')
            ->get();

        return view(
            'formateur.quiz.questions',
            compact(
                'quiz',
                'questions'
            )
        );
    }

    public function storeQuestion(
        Request $request,
        int $idQuiz
    ): RedirectResponse {
        $quiz =
            $this->quizDuFormateur(
                $idQuiz
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
                'in:A,B,C,D',
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
                'formateur.quiz.questions',
                $quiz->id_quiz
            )
            ->with(
                'success',
                'La question a été ajoutée avec succès.'
            );
    }

    public function editQuestion(
        int $idQuiz,
        int $idQuestion
    ): View {
        $quiz =
            $this->quizDuFormateur(
                $idQuiz
            );

        $question = Question::where(
            'id_quiz',
            $quiz->id_quiz
        )->findOrFail($idQuestion);

        return view(
            'formateur.quiz.question-edit',
            compact(
                'quiz',
                'question'
            )
        );
    }

    public function updateQuestion(
        Request $request,
        int $idQuiz,
        int $idQuestion
    ): RedirectResponse {
        $quiz =
            $this->quizDuFormateur(
                $idQuiz
            );

        $question = Question::where(
            'id_quiz',
            $quiz->id_quiz
        )->findOrFail($idQuestion);

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
                'in:A,B,C,D',
            ],
        ]);

        $question->update($validated);

        return redirect()
            ->route(
                'formateur.quiz.questions',
                $quiz->id_quiz
            )
            ->with(
                'success',
                'La question a été modifiée avec succès.'
            );
    }

    public function destroyQuestion(
        int $idQuiz,
        int $idQuestion
    ): RedirectResponse {
        $quiz =
            $this->quizDuFormateur(
                $idQuiz
            );

        $question = Question::where(
            'id_quiz',
            $quiz->id_quiz
        )->findOrFail($idQuestion);

        $question->delete();

        return redirect()
            ->route(
                'formateur.quiz.questions',
                $quiz->id_quiz
            )
            ->with(
                'success',
                'La question a été supprimée avec succès.'
            );
    }
}
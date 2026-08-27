<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Lecon;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FormateurLeconController extends Controller
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

        return Cours::with('filier')
            ->where(
                'id_formateur',
                $formateur->id_formateur
            )
            ->findOrFail($idCours);
    }

    private function notifierNouvelleLecon(
        Cours $cours,
        string $titreLecon
    ): void {
        NotificationService::pourEtudiantsFiliere(
            $cours->id_filier,
            'Nouvelle leçon disponible',
            'Une nouvelle leçon « '
                . $titreLecon
                . ' » a été ajoutée au cours « '
                . $cours->titre_cours
                . ' ».',
            'lecon',
            route(
                'etudiant.cours.show',
                $cours->id_cours
            )
        );

        NotificationService::pourResponsables(
            'Nouvelle leçon ajoutée',
            'Le formateur '
                . Auth::user()->nom
                . ' a ajouté la leçon « '
                . $titreLecon
                . ' » au cours « '
                . $cours->titre_cours
                . ' ».',
            'lecon',
            route(
                'responsable.lecons.index'
            )
        );
    }

    public function manage(): View
    {
        $formateur =
            $this->formateurConnecte();

        $cours = Cours::with('filier')
            ->where(
                'id_formateur',
                $formateur->id_formateur
            )
            ->orderBy('titre_cours')
            ->get();

        $idsCours =
            $cours->pluck('id_cours');

        $lecons = Lecon::with('cours')
            ->withCount('contenus')
            ->whereIn(
                'id_cours',
                $idsCours
            )
            ->orderBy('id_cours')
            ->orderBy('ordre')
            ->get();

        return view(
            'formateur.lecons.manage',
            compact(
                'cours',
                'lecons'
            )
        );
    }

    public function storeFromManage(
        Request $request
    ): RedirectResponse {
        $formateur =
            $this->formateurConnecte();

        $validated = $request->validate([
            'id_cours' => [
                'required',
                'integer',
            ],

            'titre_leçon' => [
                'required',
                'string',
                'max:100',
            ],

            'ordre' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $cours = Cours::with('filier')
            ->where(
                'id_formateur',
                $formateur->id_formateur
            )
            ->where(
                'id_cours',
                $validated['id_cours']
            )
            ->firstOrFail();

        Lecon::create([
            'titre_leçon' =>
                $validated['titre_leçon'],

            'ordre' =>
                $validated['ordre'],

            'id_cours' =>
                $cours->id_cours,
        ]);

        $this->notifierNouvelleLecon(
            $cours,
            $validated['titre_leçon']
        );

        return redirect()
            ->route('formateur.lecons.manage')
            ->with(
                'success',
                'La leçon a été ajoutée avec succès.'
            );
    }

    public function index(
        int $idCours
    ): View {
        $cours =
            $this->coursDuFormateur(
                $idCours
            );

        $lecons = Lecon::where(
            'id_cours',
            $cours->id_cours
        )
            ->withCount('contenus')
            ->orderBy('ordre')
            ->get();

        return view(
            'formateur.lecons.index',
            compact(
                'cours',
                'lecons'
            )
        );
    }

    public function create(
        int $idCours
    ): View {
        $cours =
            $this->coursDuFormateur(
                $idCours
            );

        $prochainOrdre = (
            Lecon::where(
                'id_cours',
                $cours->id_cours
            )->max('ordre') ?? 0
        ) + 1;

        return view(
            'formateur.lecons.create',
            compact(
                'cours',
                'prochainOrdre'
            )
        );
    }

    public function store(
        Request $request,
        int $idCours
    ): RedirectResponse {
        $cours =
            $this->coursDuFormateur(
                $idCours
            );

        $validated = $request->validate([
            'titre_leçon' => [
                'required',
                'string',
                'max:100',
            ],

            'ordre' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        Lecon::create([
            'titre_leçon' =>
                $validated['titre_leçon'],

            'ordre' =>
                $validated['ordre'],

            'id_cours' =>
                $cours->id_cours,
        ]);

        $this->notifierNouvelleLecon(
            $cours,
            $validated['titre_leçon']
        );

        return redirect()
            ->route(
                'formateur.lecons.index',
                $cours->id_cours
            )
            ->with(
                'success',
                'La leçon a été ajoutée avec succès.'
            );
    }

    public function edit(
        int $idCours,
        int $idLecon
    ): View {
        $cours =
            $this->coursDuFormateur(
                $idCours
            );

        $lecon = Lecon::where(
            'id_cours',
            $cours->id_cours
        )->findOrFail($idLecon);

        return view(
            'formateur.lecons.edit',
            compact(
                'cours',
                'lecon'
            )
        );
    }

    public function update(
        Request $request,
        int $idCours,
        int $idLecon
    ): RedirectResponse {
        $cours =
            $this->coursDuFormateur(
                $idCours
            );

        $lecon = Lecon::where(
            'id_cours',
            $cours->id_cours
        )->findOrFail($idLecon);

        $validated = $request->validate([
            'titre_leçon' => [
                'required',
                'string',
                'max:100',
            ],

            'ordre' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $lecon->update([
            'titre_leçon' =>
                $validated['titre_leçon'],

            'ordre' =>
                $validated['ordre'],
        ]);

        return redirect()
            ->route('formateur.lecons.manage')
            ->with(
                'success',
                'La leçon a été modifiée avec succès.'
            );
    }

    public function destroy(
        int $idCours,
        int $idLecon
    ): RedirectResponse {
        $cours =
            $this->coursDuFormateur(
                $idCours
            );

        $lecon = Lecon::where(
            'id_cours',
            $cours->id_cours
        )
            ->withCount('contenus')
            ->findOrFail($idLecon);

        if ($lecon->contenus_count > 0) {
            return back()->with(
                'error',
                'Impossible de supprimer cette leçon car elle contient déjà des contenus.'
            );
        }

        $lecon->delete();

        return redirect()
            ->route('formateur.lecons.manage')
            ->with(
                'success',
                'La leçon a été supprimée avec succès.'
            );
    }
}
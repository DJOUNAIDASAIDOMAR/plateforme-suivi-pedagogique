<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Lecon;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ResponsableLeconController extends Controller
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

        $lecons = Lecon::with([
            'cours.formateur.user',
        ])
            ->withCount('contenus')
            ->orderBy('id_cours')
            ->orderBy('ordre')
            ->get();

        return view(
            'responsable.lecons.index',
            compact(
                'cours',
                'lecons'
            )
        );
    }

    public function store(
        Request $request
    ): RedirectResponse {
        $this->verifierResponsable();

        $validated = $request->validate([
            'id_cours' => [
                'required',
                'integer',
                'exists:cours,id_cours',
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

        $cours = Cours::findOrFail(
            $validated['id_cours']
        );

        Lecon::create([
            'id_cours' =>
                $cours->id_cours,

            'titre_leçon' =>
                $validated['titre_leçon'],

            'ordre' =>
                $validated['ordre'],
        ]);

        NotificationService::pourEtudiantsFiliere(
            $cours->id_filier,
            'Nouvelle leçon disponible',
            'La leçon « '
                . $validated['titre_leçon']
                . ' » vient d’être ajoutée au cours « '
                . $cours->titre_cours
                . ' ».',
            'lecon',
            route(
                'etudiant.cours.show',
                $cours->id_cours
            )
        );

        return redirect()
            ->route(
                'responsable.lecons.index'
            )
            ->with(
                'success',
                'La leçon a été ajoutée avec succès.'
            );
    }

    public function edit(
        int $id
    ): View {
        $this->verifierResponsable();

        $lecon =
            Lecon::findOrFail($id);

        $cours = Cours::orderBy(
            'titre_cours'
        )->get();

        return view(
            'responsable.lecons.edit',
            compact(
                'lecon',
                'cours'
            )
        );
    }

    public function update(
        Request $request,
        int $id
    ): RedirectResponse {
        $this->verifierResponsable();

        $lecon =
            Lecon::findOrFail($id);

        $validated = $request->validate([
            'id_cours' => [
                'required',
                'integer',
                'exists:cours,id_cours',
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

        $lecon->update($validated);

        return redirect()
            ->route(
                'responsable.lecons.index'
            )
            ->with(
                'success',
                'La leçon a été modifiée avec succès.'
            );
    }

    public function destroy(
        int $id
    ): RedirectResponse {
        $this->verifierResponsable();

        $lecon = Lecon::withCount(
            'contenus'
        )->findOrFail($id);

        if ($lecon->contenus_count > 0) {
            return back()->with(
                'error',
                'Impossible de supprimer cette leçon car elle contient des contenus.'
            );
        }

        $lecon->delete();

        return redirect()
            ->route(
                'responsable.lecons.index'
            )
            ->with(
                'success',
                'La leçon a été supprimée avec succès.'
            );
    }
}
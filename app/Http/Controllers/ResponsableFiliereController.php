<?php

namespace App\Http\Controllers;

use App\Models\Filier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ResponsableFiliereController extends Controller
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

        $filieres = Filier::withCount([
            'etudiants',
            'cours',
        ])
            ->orderBy('nom_filier')
            ->get();

        return view(
            'responsable.filieres.index',
            compact('filieres')
        );
    }

    public function store(
        Request $request
    ): RedirectResponse {
        $this->verifierResponsable();

        $validated = $request->validate([
            'nom_filier' => [
                'required',
                'string',
                'max:100',
            ],
        ]);

        Filier::create([
            'nom_filier' =>
                $validated['nom_filier'],
        ]);

        return redirect()
            ->route(
                'responsable.filieres.index'
            )
            ->with(
                'success',
                'La filière a été ajoutée avec succès.'
            );
    }

    public function edit(
        int $id
    ): View {
        $this->verifierResponsable();

        $filiere = Filier::findOrFail($id);

        return view(
            'responsable.filieres.edit',
            compact('filiere')
        );
    }

    public function update(
        Request $request,
        int $id
    ): RedirectResponse {
        $this->verifierResponsable();

        $filiere = Filier::findOrFail($id);

        $validated = $request->validate([
            'nom_filier' => [
                'required',
                'string',
                'max:100',
            ],
        ]);

        $filiere->update($validated);

        return redirect()
            ->route(
                'responsable.filieres.index'
            )
            ->with(
                'success',
                'La filière a été modifiée avec succès.'
            );
    }

    public function destroy(
        int $id
    ): RedirectResponse {
        $this->verifierResponsable();

        $filiere = Filier::withCount([
            'etudiants',
            'cours',
        ])->findOrFail($id);

        if (
            $filiere->etudiants_count > 0
            || $filiere->cours_count > 0
        ) {
            return back()->with(
                'error',
                'Impossible de supprimer cette filière car elle contient des étudiants ou des cours.'
            );
        }

        $filiere->delete();

        return redirect()
            ->route(
                'responsable.filieres.index'
            )
            ->with(
                'success',
                'La filière a été supprimée avec succès.'
            );
    }
}
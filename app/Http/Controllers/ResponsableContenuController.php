<?php

namespace App\Http\Controllers;

use App\Models\Contenu;
use App\Models\Cours;
use App\Models\Lecon;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ResponsableContenuController extends Controller
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

        $cours = Cours::orderBy(
            'titre_cours'
        )->get();

        $lecons = Lecon::with('cours')
            ->orderBy('id_cours')
            ->orderBy('ordre')
            ->get();

        $contenus = Contenu::with([
            'lecon.cours',
        ])
            ->orderByDesc('id_contenu')
            ->get();

        return view(
            'responsable.contenus.index',
            compact(
                'cours',
                'lecons',
                'contenus'
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

            'id_lecon' => [
                'required',
                'integer',
            ],

            'titre_contenu' => [
                'required',
                'string',
                'max:100',
            ],

            'type_contenu' => [
                'required',
                'string',
                'max:100',
            ],

            'fichier' => [
                'required',
                'file',
                'max:10240',
            ],
        ]);

        $cours = Cours::findOrFail(
            $validated['id_cours']
        );

        $lecon = Lecon::where(
            'id_cours',
            $cours->id_cours
        )
            ->where(
                'id_leçon',
                $validated['id_lecon']
            )
            ->firstOrFail();

        $chemin = $request
            ->file('fichier')
            ->store(
                'contenus',
                'public'
            );

        Contenu::create([
            'titre_contenu' =>
                $validated['titre_contenu'],

            'type_contenu' =>
                $validated['type_contenu'],

            'fichier' =>
                $chemin,

            'id_lecon' =>
                $lecon->{'id_leçon'},
        ]);

        NotificationService::pourEtudiantsFiliere(
            $cours->id_filier,
            'Nouveau contenu disponible',
            'Le contenu « '
                . $validated['titre_contenu']
                . ' » vient d’être ajouté au cours « '
                . $cours->titre_cours
                . ' ».',
            'contenu',
            route(
                'etudiant.cours.show',
                $cours->id_cours
            )
        );

        return redirect()
            ->route(
                'responsable.contenus.index'
            )
            ->with(
                'success',
                'Le contenu a été ajouté avec succès.'
            );
    }

    public function edit(
        int $id
    ): View {
        $this->verifierResponsable();

        $contenu = Contenu::with([
            'lecon.cours',
        ])->findOrFail($id);

        $cours = Cours::orderBy(
            'titre_cours'
        )->get();

        $lecons = Lecon::with('cours')
            ->orderBy('id_cours')
            ->orderBy('ordre')
            ->get();

        return view(
            'responsable.contenus.edit',
            compact(
                'contenu',
                'cours',
                'lecons'
            )
        );
    }

    public function update(
        Request $request,
        int $id
    ): RedirectResponse {
        $this->verifierResponsable();

        $contenu =
            Contenu::findOrFail($id);

        $validated = $request->validate([
            'id_cours' => [
                'required',
                'integer',
                'exists:cours,id_cours',
            ],

            'id_lecon' => [
                'required',
                'integer',
            ],

            'titre_contenu' => [
                'required',
                'string',
                'max:100',
            ],

            'type_contenu' => [
                'required',
                'string',
                'max:100',
            ],

            'fichier' => [
                'nullable',
                'file',
                'max:10240',
            ],
        ]);

        $lecon = Lecon::where(
            'id_cours',
            $validated['id_cours']
        )
            ->where(
                'id_leçon',
                $validated['id_lecon']
            )
            ->firstOrFail();

        $chemin =
            $contenu->fichier;

        if ($request->hasFile('fichier')) {

            if (
                $contenu->fichier
                && Storage::disk('public')
                    ->exists(
                        $contenu->fichier
                    )
            ) {
                Storage::disk('public')
                    ->delete(
                        $contenu->fichier
                    );
            }

            $chemin = $request
                ->file('fichier')
                ->store(
                    'contenus',
                    'public'
                );
        }

        $contenu->update([
            'titre_contenu' =>
                $validated['titre_contenu'],

            'type_contenu' =>
                $validated['type_contenu'],

            'fichier' =>
                $chemin,

            'id_lecon' =>
                $lecon->{'id_leçon'},
        ]);

        return redirect()
            ->route(
                'responsable.contenus.index'
            )
            ->with(
                'success',
                'Le contenu a été modifié avec succès.'
            );
    }

    public function destroy(
        int $id
    ): RedirectResponse {
        $this->verifierResponsable();

        $contenu =
            Contenu::findOrFail($id);

        if (
            $contenu->fichier
            && Storage::disk('public')
                ->exists(
                    $contenu->fichier
                )
        ) {
            Storage::disk('public')
                ->delete(
                    $contenu->fichier
                );
        }

        $contenu->delete();

        return redirect()
            ->route(
                'responsable.contenus.index'
            )
            ->with(
                'success',
                'Le contenu a été supprimé avec succès.'
            );
    }
}
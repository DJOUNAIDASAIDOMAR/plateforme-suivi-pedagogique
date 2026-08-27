<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Filier;
use App\Models\Formateur;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ResponsableCoursController extends Controller
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
            ->withCount([
                'lecons',
                'quizzes',
            ])
            ->orderBy('titre_cours')
            ->get();

        return view(
            'responsable.cours.index',
            compact('cours')
        );
    }

    public function create(): View
    {
        $this->verifierResponsable();

        $filieres = Filier::orderBy(
            'nom_filier'
        )->get();

        $formateurs = Formateur::with('user')
            ->get()
            ->sortBy(
                fn ($formateur) =>
                    $formateur->user?->nom ?? ''
            );

        return view(
            'responsable.cours.create',
            compact(
                'filieres',
                'formateurs'
            )
        );
    }

    public function store(
        Request $request
    ): RedirectResponse {
        $this->verifierResponsable();

        $validated = $request->validate([
            'titre_cours' => [
                'required',
                'string',
                'max:150',
            ],

            'description' => [
                'required',
                'string',
                'max:2000',
            ],

            'id_filier' => [
                'required',
                'integer',

                Rule::exists(
                    'filier',
                    'id_filier'
                ),
            ],

            'id_formateur' => [
                'required',
                'integer',

                Rule::exists(
                    'formateur',
                    'id_formateur'
                ),
            ],
        ], [
            'titre_cours.required' =>
                'Le titre du cours est obligatoire.',

            'description.required' =>
                'La description du cours est obligatoire.',

            'id_filier.required' =>
                'Veuillez choisir une filière.',

            'id_filier.exists' =>
                'La filière sélectionnée n’existe pas.',

            'id_formateur.required' =>
                'Veuillez choisir un formateur.',

            'id_formateur.exists' =>
                'Le formateur sélectionné n’existe pas.',
        ]);

        $cours = Cours::create([
            'titre_cours' =>
                $validated['titre_cours'],

            'description' =>
                $validated['description'],

            'id_filier' =>
                $validated['id_filier'],

            'id_formateur' =>
                $validated['id_formateur'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | NOTIFICATION AU FORMATEUR
        |--------------------------------------------------------------------------
        */

        $formateur = Formateur::with('user')
            ->find(
                $validated['id_formateur']
            );

        if ($formateur?->user) {

            NotificationService::pourUtilisateur(
                $formateur->user->id_user,
                'Nouveau cours attribué',
                'Le cours « '
                    . $cours->titre_cours
                    . ' » vous a été attribué par le responsable pédagogique.',
                'cours',
                route(
                    'formateur.cours.index'
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | NOTIFICATION AUX ÉTUDIANTS DE LA FILIÈRE
        |--------------------------------------------------------------------------
        */

        NotificationService::pourEtudiantsFiliere(
            $cours->id_filier,
            'Nouveau cours disponible',
            'Un nouveau cours « '
                . $cours->titre_cours
                . ' » vient d’être ajouté dans votre filière.',
            'cours',
            route(
                'etudiant.cours.show',
                $cours->id_cours
            )
        );

        return redirect()
            ->route('responsable.cours.index')
            ->with(
                'success',
                'Le cours a été créé, attribué au formateur et les étudiants de la filière ont été notifiés.'
            );
    }

    public function edit(
        int $id
    ): View {
        $this->verifierResponsable();

        $cours =
            Cours::findOrFail($id);

        $filieres = Filier::orderBy(
            'nom_filier'
        )->get();

        $formateurs = Formateur::with('user')
            ->get()
            ->sortBy(
                fn ($formateur) =>
                    $formateur->user?->nom ?? ''
            );

        return view(
            'responsable.cours.edit',
            compact(
                'cours',
                'filieres',
                'formateurs'
            )
        );
    }

    public function update(
        Request $request,
        int $id
    ): RedirectResponse {
        $this->verifierResponsable();

        $cours =
            Cours::findOrFail($id);

        $ancienFormateur =
            $cours->id_formateur;

        $ancienneFiliere =
            $cours->id_filier;

        $validated = $request->validate([
            'titre_cours' => [
                'required',
                'string',
                'max:150',
            ],

            'description' => [
                'required',
                'string',
                'max:2000',
            ],

            'id_filier' => [
                'required',
                'integer',

                Rule::exists(
                    'filier',
                    'id_filier'
                ),
            ],

            'id_formateur' => [
                'required',
                'integer',

                Rule::exists(
                    'formateur',
                    'id_formateur'
                ),
            ],
        ]);

        $cours->update($validated);

        /*
        |--------------------------------------------------------------------------
        | SI LE FORMATEUR CHANGE
        |--------------------------------------------------------------------------
        */

        if (
            (int) $ancienFormateur
            !==
            (int) $validated['id_formateur']
        ) {
            $nouveauFormateur =
                Formateur::with('user')
                    ->find(
                        $validated['id_formateur']
                    );

            if ($nouveauFormateur?->user) {

                NotificationService::pourUtilisateur(
                    $nouveauFormateur
                        ->user
                        ->id_user,
                    'Nouveau cours attribué',
                    'Le cours « '
                        . $cours->titre_cours
                        . ' » vient de vous être attribué.',
                    'cours',
                    route(
                        'formateur.cours.index'
                    )
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SI LA FILIÈRE CHANGE
        |--------------------------------------------------------------------------
        */

        if (
            (int) $ancienneFiliere
            !==
            (int) $validated['id_filier']
        ) {
            NotificationService::pourEtudiantsFiliere(
                $cours->id_filier,
                'Nouveau cours disponible',
                'Le cours « '
                    . $cours->titre_cours
                    . ' » vient d’être ajouté à votre filière.',
                'cours',
                route(
                    'etudiant.cours.show',
                    $cours->id_cours
                )
            );
        }

        return redirect()
            ->route('responsable.cours.index')
            ->with(
                'success',
                'Le cours a été modifié avec succès.'
            );
    }

    public function destroy(
        int $id
    ): RedirectResponse {
        $this->verifierResponsable();

        $cours = Cours::withCount([
            'lecons',
            'quizzes',
        ])->findOrFail($id);

        if (
            $cours->lecons_count > 0
            || $cours->quizzes_count > 0
        ) {
            return back()->with(
                'error',
                'Impossible de supprimer ce cours car il contient déjà des leçons ou des quiz.'
            );
        }

        $cours->delete();

        return redirect()
            ->route('responsable.cours.index')
            ->with(
                'success',
                'Le cours a été supprimé avec succès.'
            );
    }
}
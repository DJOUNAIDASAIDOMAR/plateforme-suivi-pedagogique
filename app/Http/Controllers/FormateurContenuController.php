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
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FormateurContenuController extends Controller
{
    /**
     * Récupère le formateur actuellement connecté.
     */
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


    /**
     * Vérifie qu'une leçon appartient bien
     * à un cours du formateur connecté.
     */
    private function leconDuFormateur(
        int $idLecon
    ): Lecon {
        $formateur =
            $this->formateurConnecte();

        return Lecon::with('cours')
            ->whereHas(
                'cours',
                function ($query) use ($formateur) {
                    $query->where(
                        'id_formateur',
                        $formateur->id_formateur
                    );
                }
            )
            ->findOrFail($idLecon);
    }


    /**
     * Affiche la page de gestion des contenus.
     */
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


        $lecons = Lecon::with('cours')
            ->whereIn(
                'id_cours',
                $idsCours
            )
            ->orderBy('id_cours')
            ->orderBy('ordre')
            ->get();


        $idsLecons =
            $lecons->pluck('id_leçon');


        $contenus = Contenu::with([
            'lecon.cours',
        ])
            ->whereIn(
                'id_lecon',
                $idsLecons
            )
            ->orderByDesc('id_contenu')
            ->get();


        return view(
            'formateur.contenus.manage',
            compact(
                'cours',
                'lecons',
                'contenus'
            )
        );
    }


    /**
     * Ajoute un nouveau contenu.
     */
    public function store(
        Request $request
    ): RedirectResponse {
        $formateur =
            $this->formateurConnecte();


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            [
                'id_cours' => [
                    'required',
                    'integer',
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

                    Rule::in([
                        'PDF',
                        'Document',
                        'Présentation',
                        'Image',
                        'Vidéo',
                        'Autre',
                    ]),
                ],

                'fichier' => [
                    'required',
                    'file',

                    'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,jpg,jpeg,png,webp,zip,mp4,webm,ogv',

                    'max:102400',
                ],
            ],
            [
                'id_cours.required' =>
                    'Veuillez sélectionner un cours.',

                'id_lecon.required' =>
                    'Veuillez sélectionner une leçon.',

                'titre_contenu.required' =>
                    'Le titre du contenu est obligatoire.',

                'type_contenu.required' =>
                    'Veuillez sélectionner un type de contenu.',

                'type_contenu.in' =>
                    'Le type de contenu sélectionné n’est pas autorisé.',

                'fichier.required' =>
                    'Veuillez sélectionner un fichier.',

                'fichier.file' =>
                    'Le fichier envoyé n’est pas valide.',

                'fichier.mimes' =>
                    'Le format du fichier n’est pas autorisé.',

                'fichier.max' =>
                    'Le fichier ne doit pas dépasser 100 Mo.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | VÉRIFIER LE COURS
        |--------------------------------------------------------------------------
        */

        $cours = Cours::where(
            'id_formateur',
            $formateur->id_formateur
        )
            ->where(
                'id_cours',
                $validated['id_cours']
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | VÉRIFIER LA LEÇON
        |--------------------------------------------------------------------------
        */

        $lecon = Lecon::where(
            'id_cours',
            $cours->id_cours
        )
            ->where(
                'id_leçon',
                $validated['id_lecon']
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | ENREGISTRER LE FICHIER
        |--------------------------------------------------------------------------
        */

        $chemin = $request
            ->file('fichier')
            ->store(
                'contenus',
                'public'
            );


        /*
        |--------------------------------------------------------------------------
        | CRÉER LE CONTENU
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | NOTIFIER LES ÉTUDIANTS
        |--------------------------------------------------------------------------
        */

        NotificationService::pourEtudiantsFiliere(
            $cours->id_filier,

            'Nouveau contenu disponible',

            'Le contenu « '
                . $validated['titre_contenu']
                . ' » a été ajouté au cours « '
                . $cours->titre_cours
                . ' ».',

            'contenu',

            route(
                'etudiant.cours.show',
                $cours->id_cours
            )
        );


        /*
        |--------------------------------------------------------------------------
        | NOTIFIER LES RESPONSABLES
        |--------------------------------------------------------------------------
        */

        NotificationService::pourResponsables(
            'Nouveau contenu ajouté',

            'Le formateur '
                . Auth::user()->nom
                . ' a ajouté le contenu « '
                . $validated['titre_contenu']
                . ' » au cours « '
                . $cours->titre_cours
                . ' ».',

            'contenu',

            route(
                'responsable.contenus.index'
            )
        );


        return redirect()
            ->route(
                'formateur.contenus.manage'
            )
            ->with(
                'success',
                'Le contenu a été ajouté avec succès.'
            );
    }


    /**
     * Affiche le formulaire de modification.
     */
    public function edit(
        int $idContenu
    ): View {
        $formateur =
            $this->formateurConnecte();


        $contenu = Contenu::with([
            'lecon.cours',
        ])
            ->whereHas(
                'lecon.cours',
                function ($query) use ($formateur) {
                    $query->where(
                        'id_formateur',
                        $formateur->id_formateur
                    );
                }
            )
            ->findOrFail($idContenu);


        $cours = Cours::where(
            'id_formateur',
            $formateur->id_formateur
        )
            ->orderBy('titre_cours')
            ->get();


        $idsCours =
            $cours->pluck('id_cours');


        $lecons = Lecon::with('cours')
            ->whereIn(
                'id_cours',
                $idsCours
            )
            ->orderBy('id_cours')
            ->orderBy('ordre')
            ->get();


        return view(
            'formateur.contenus.edit',
            compact(
                'contenu',
                'cours',
                'lecons'
            )
        );
    }


    /**
     * Modifie un contenu.
     */
    public function update(
        Request $request,
        int $idContenu
    ): RedirectResponse {
        $formateur =
            $this->formateurConnecte();


        /*
        |--------------------------------------------------------------------------
        | RÉCUPÉRER LE CONTENU
        |--------------------------------------------------------------------------
        */

        $contenu = Contenu::whereHas(
            'lecon.cours',
            function ($query) use ($formateur) {
                $query->where(
                    'id_formateur',
                    $formateur->id_formateur
                );
            }
        )->findOrFail($idContenu);


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            [
                'id_cours' => [
                    'required',
                    'integer',
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

                    Rule::in([
                        'PDF',
                        'Document',
                        'Présentation',
                        'Image',
                        'Vidéo',
                        'Autre',
                    ]),
                ],

                'fichier' => [
                    'nullable',
                    'file',

                    'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,jpg,jpeg,png,webp,zip,mp4,webm,ogv',

                    'max:102400',
                ],
            ],
            [
                'id_cours.required' =>
                    'Veuillez sélectionner un cours.',

                'id_lecon.required' =>
                    'Veuillez sélectionner une leçon.',

                'titre_contenu.required' =>
                    'Le titre du contenu est obligatoire.',

                'type_contenu.required' =>
                    'Veuillez sélectionner un type de contenu.',

                'type_contenu.in' =>
                    'Le type de contenu sélectionné n’est pas autorisé.',

                'fichier.file' =>
                    'Le fichier envoyé n’est pas valide.',

                'fichier.mimes' =>
                    'Le format du fichier n’est pas autorisé.',

                'fichier.max' =>
                    'Le fichier ne doit pas dépasser 100 Mo.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | VÉRIFIER LE COURS
        |--------------------------------------------------------------------------
        */

        $cours = Cours::where(
            'id_formateur',
            $formateur->id_formateur
        )
            ->where(
                'id_cours',
                $validated['id_cours']
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | VÉRIFIER LA LEÇON
        |--------------------------------------------------------------------------
        */

        $lecon = Lecon::where(
            'id_cours',
            $cours->id_cours
        )
            ->where(
                'id_leçon',
                $validated['id_lecon']
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | FICHIER ACTUEL
        |--------------------------------------------------------------------------
        */

        $chemin =
            $contenu->fichier;


        /*
        |--------------------------------------------------------------------------
        | NOUVEAU FICHIER
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('fichier')) {

            if (
                $contenu->fichier
                &&
                Storage::disk('public')
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


        /*
        |--------------------------------------------------------------------------
        | METTRE À JOUR LE CONTENU
        |--------------------------------------------------------------------------
        */

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
                'formateur.contenus.manage'
            )
            ->with(
                'success',
                'Le contenu a été modifié avec succès.'
            );
    }


    /**
     * Supprime un contenu.
     */
    public function destroy(
        int $idContenu
    ): RedirectResponse {
        $formateur =
            $this->formateurConnecte();


        $contenu = Contenu::whereHas(
            'lecon.cours',
            function ($query) use ($formateur) {
                $query->where(
                    'id_formateur',
                    $formateur->id_formateur
                );
            }
        )->findOrFail($idContenu);


        /*
        |--------------------------------------------------------------------------
        | SUPPRIMER LE FICHIER
        |--------------------------------------------------------------------------
        */

        if (
            $contenu->fichier
            &&
            Storage::disk('public')
                ->exists(
                    $contenu->fichier
                )
        ) {

            Storage::disk('public')
                ->delete(
                    $contenu->fichier
                );
        }


        /*
        |--------------------------------------------------------------------------
        | SUPPRIMER LE CONTENU
        |--------------------------------------------------------------------------
        */

        $contenu->delete();


        return redirect()
            ->route(
                'formateur.contenus.manage'
            )
            ->with(
                'success',
                'Le contenu a été supprimé avec succès.'
            );
    }
}
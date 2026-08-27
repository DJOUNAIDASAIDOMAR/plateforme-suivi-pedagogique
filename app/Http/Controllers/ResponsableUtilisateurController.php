<?php

namespace App\Http\Controllers;

use App\Models\Filier;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ResponsableUtilisateurController extends Controller
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

        $utilisateurs = User::with([
            'etudiant.filier',
            'formateur',
        ])
            ->whereIn(
                'role',
                [
                    'etudiant',
                    'formateur',
                ]
            )
            ->orderBy('nom')
            ->get();

        return view(
            'responsable.utilisateurs.index',
            compact('utilisateurs')
        );
    }

    public function edit(
        int $id
    ): View {
        $this->verifierResponsable();

        $utilisateur = User::with([
            'etudiant.filier',
            'formateur',
        ])
            ->whereIn(
                'role',
                [
                    'etudiant',
                    'formateur',
                ]
            )
            ->findOrFail($id);

        $filieres = Filier::orderBy(
            'nom_filier'
        )->get();

        return view(
            'responsable.utilisateurs.edit',
            compact(
                'utilisateur',
                'filieres'
            )
        );
    }

    public function update(
        Request $request,
        int $id
    ): RedirectResponse {
        $this->verifierResponsable();

        $utilisateur = User::with([
            'etudiant',
            'formateur',
        ])
            ->whereIn(
                'role',
                [
                    'etudiant',
                    'formateur',
                ]
            )
            ->findOrFail($id);

        $rules = [
            'nom' => [
                'required',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:150',

                Rule::unique(
                    'users',
                    'email'
                )->ignore(
                    $utilisateur->id_user,
                    'id_user'
                ),
            ],
        ];

        if ($utilisateur->role === 'etudiant') {
            $rules['id_filier'] = [
                'required',
                'integer',

                Rule::exists(
                    'filier',
                    'id_filier'
                ),
            ];
        }

        if ($utilisateur->role === 'formateur') {
            $rules['specialité'] = [
                'required',
                'string',
                'max:100',
            ];
        }

        $validated = $request->validate(
            $rules
        );

        $utilisateur->update([
            'nom' =>
                $validated['nom'],

            'email' =>
                $validated['email'],
        ]);

        if (
            $utilisateur->role === 'etudiant'
            && $utilisateur->etudiant
        ) {
            $utilisateur->etudiant->update([
                'id_filier' =>
                    $validated['id_filier'],
            ]);
        }

        if (
            $utilisateur->role === 'formateur'
            && $utilisateur->formateur
        ) {
            $utilisateur->formateur->update([
                'specialité' =>
                    $validated['specialité'],
            ]);
        }

        return redirect()
            ->route(
                'responsable.utilisateurs.index'
            )
            ->with(
                'success',
                'L’utilisateur a été modifié avec succès.'
            );
    }
}
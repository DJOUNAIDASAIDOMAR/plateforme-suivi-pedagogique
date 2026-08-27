<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EtudiantProfilController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        abort_unless(
            $user
            && $user->role === 'etudiant'
            && $user->etudiant,
            403,
            'Accès réservé aux étudiants.'
        );

        $user->load(
            'etudiant.filier'
        );

        return view(
            'etudiant.profil.index',
            compact('user')
        );
    }

    public function update(
        Request $request
    ): RedirectResponse {
        $user = Auth::user();

        abort_unless(
            $user
            && $user->role === 'etudiant'
            && $user->etudiant,
            403,
            'Accès réservé aux étudiants.'
        );

        $validated = $request->validate([
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
                    $user->id_user,
                    'id_user'
                ),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'nom.required' =>
                'Le nom est obligatoire.',

            'email.required' =>
                'L’adresse e-mail est obligatoire.',

            'email.email' =>
                'L’adresse e-mail n’est pas valide.',

            'email.unique' =>
                'Cette adresse e-mail est déjà utilisée.',

            'password.min' =>
                'Le mot de passe doit contenir au moins 8 caractères.',

            'password.confirmed' =>
                'La confirmation du mot de passe ne correspond pas.',
        ]);

        $user->nom =
            $validated['nom'];

        $user->email =
            $validated['email'];

        if (! empty($validated['password'])) {
            $user->password =
                $validated['password'];
        }

        $user->save();

        return redirect()
            ->route('etudiant.profil.index')
            ->with(
                'success',
                'Votre profil a été modifié avec succès.'
            );
    }
}
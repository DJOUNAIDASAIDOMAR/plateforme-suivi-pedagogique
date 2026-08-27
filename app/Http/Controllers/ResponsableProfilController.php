<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ResponsableProfilController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        abort_unless(
            $user
            && $user->role === 'responsable_pedagogique'
            && $user->responsablePedagogique,
            403,
            'Accès réservé au responsable pédagogique.'
        );

        return view(
            'responsable.profil.index',
            compact('user')
        );
    }

    public function update(
        Request $request
    ): RedirectResponse {
        $user = Auth::user();

        abort_unless(
            $user
            && $user->role === 'responsable_pedagogique'
            && $user->responsablePedagogique,
            403,
            'Accès réservé au responsable pédagogique.'
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
            ->route(
                'responsable.profil.index'
            )
            ->with(
                'success',
                'Votre profil a été modifié avec succès.'
            );
    }
}
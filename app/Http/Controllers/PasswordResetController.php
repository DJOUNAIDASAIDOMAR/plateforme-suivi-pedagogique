<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    /**
     * Affiche la page "Mot de passe oublié".
     */
    public function showForgotForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Envoie le lien de réinitialisation par e-mail.
     */
    public function sendResetLink(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:150',
            ],
        ], [
            'email.required' =>
                'L’adresse e-mail est obligatoire.',

            'email.email' =>
                'L’adresse e-mail n’est pas valide.',
        ]);

        Password::sendResetLink([
            'email' => $validated['email'],
        ]);

        /*
        | Message volontairement générique :
        | on ne révèle pas si l'adresse existe dans la base.
        */
        return back()->with(
            'success',
            'Si cette adresse e-mail correspond à un compte, un lien de réinitialisation vous a été envoyé.'
        );
    }

    /**
     * Affiche la page permettant
     * de choisir un nouveau mot de passe.
     */
    public function showResetForm(
        Request $request,
        string $token
    ): View {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Enregistre le nouveau mot de passe.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token' => [
                'required',
                'string',
            ],

            'email' => [
                'required',
                'email',
                'max:150',
            ],

            'password' => [
                'required',
                'confirmed',

                PasswordRule::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'email.required' =>
                'L’adresse e-mail est obligatoire.',

            'email.email' =>
                'L’adresse e-mail n’est pas valide.',

            'password.required' =>
                'Le nouveau mot de passe est obligatoire.',

            'password.confirmed' =>
                'La confirmation du mot de passe ne correspond pas.',
        ]);

        $status = Password::reset(
            [
                'email' =>
                    $validated['email'],

                'password' =>
                    $validated['password'],

                'password_confirmation' =>
                    $request->input('password_confirmation'),

                'token' =>
                    $validated['token'],
            ],

            function (User $user, string $password): void {

                $user->forceFill([
                    'password' => Hash::make($password),
                ]);

                $user->setRememberToken(
                    Str::random(60)
                );

                $user->save();

                event(
                    new PasswordReset($user)
                );
            }
        );

        if ($status === Password::PASSWORD_RESET) {

            return redirect()
                ->route('login')
                ->with(
                    'success',
                    'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.'
                );
        }

        return back()
            ->withErrors([
                'email' =>
                    'Le lien de réinitialisation est invalide ou a expiré. Veuillez demander un nouveau lien.',
            ])
            ->withInput(
                $request->only('email')
            );
    }
}
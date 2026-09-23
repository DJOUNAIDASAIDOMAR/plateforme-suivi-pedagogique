<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Filier;
use App\Models\Formateur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTE DES FILIÈRES
    |--------------------------------------------------------------------------
    |
    | Utilisée par le formulaire d'inscription Flutter.
    |
    */
    public function filieres()
    {
        $filieres = Filier::orderBy('nom_filier')
            ->get([
                'id_filier',
                'nom_filier',
            ]);

        return response()->json([
            'filieres' => $filieres,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | INSCRIPTION MOBILE
    |--------------------------------------------------------------------------
    |
    | Inscription publique autorisée uniquement pour :
    | - étudiant
    | - formateur
    |
    | Le responsable pédagogique ne peut pas s'inscrire publiquement.
    |
    */
    public function register(Request $request)
    {
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
                Rule::unique('users', 'email'),
            ],

            'password' => [
                'required',
                'confirmed',

                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],

            'role' => [
                'required',

                Rule::in([
                    'etudiant',
                    'formateur',
                ]),
            ],

            'id_filier' => [
                Rule::requiredIf(
                    fn (): bool =>
                        $request->input('role') === 'etudiant'
                ),

                'nullable',
                'integer',

                Rule::exists(
                    'filier',
                    'id_filier'
                ),
            ],

            'specialite' => [
                Rule::requiredIf(
                    fn (): bool =>
                        $request->input('role') === 'formateur'
                ),

                'nullable',
                'string',
                'max:150',
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

            'password.required' =>
                'Le mot de passe est obligatoire.',

            'password.confirmed' =>
                'La confirmation du mot de passe ne correspond pas.',

            'password.min' =>
                'Le mot de passe doit contenir au moins 8 caractères.',

            'role.required' =>
                'Veuillez choisir un rôle.',

            'role.in' =>
                'Le rôle sélectionné n’est pas autorisé.',

            'id_filier.required' =>
                'Veuillez choisir une filière.',

            'id_filier.exists' =>
                'La filière sélectionnée n’existe pas.',

            'specialite.required' =>
                'La spécialité du formateur est obligatoire.',
        ]);


        $user = DB::transaction(
            function () use ($validated): User {

                $user = User::create([
                    'nom' => $validated['nom'],
                    'email' => $validated['email'],

                    'password' => Hash::make(
                        $validated['password']
                    ),

                    'role' => $validated['role'],

                    'date_inscription' =>
                        now()->toDateString(),
                ]);


                /*
                |--------------------------------------------------------------------------
                | PROFIL ÉTUDIANT
                |--------------------------------------------------------------------------
                */

                if ($validated['role'] === 'etudiant') {
                    Etudiant::create([
                        'id_user' => $user->id_user,
                        'progression' => 0,
                        'id_filier' => $validated['id_filier'],
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | PROFIL FORMATEUR
                |--------------------------------------------------------------------------
                */

                if ($validated['role'] === 'formateur') {
                    Formateur::create([
                        'id_user' => $user->id_user,
                        'specialité' => $validated['specialite'],
                    ]);
                }


                return $user;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | TOKEN MOBILE
        |--------------------------------------------------------------------------
        |
        | Après l'inscription, l'utilisateur est directement connecté
        | dans l'application Flutter.
        |
        */

        $token = $user
            ->createToken('flutter-mobile')
            ->plainTextToken;


        return response()->json([
            'message' => 'Votre compte a été créé avec succès.',

            'token' => $token,

            'user' => [
                'id' => $user->id_user,
                'nom' => $user->nom,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | CONNEXION MOBILE
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);

        $user = User::where(
            'email',
            $request->email
        )->first();

        if (
            !$user ||
            !Hash::check(
                $request->password,
                $user->password
            )
        ) {
            throw ValidationException::withMessages([
                'email' => [
                    'Adresse e-mail ou mot de passe incorrect.',
                ],
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | SUPPRIMER LES ANCIENS TOKENS
        |--------------------------------------------------------------------------
        */

        $user->tokens()->delete();


        /*
        |--------------------------------------------------------------------------
        | CRÉER LE TOKEN FLUTTER
        |--------------------------------------------------------------------------
        */

        $token = $user
            ->createToken('flutter-mobile')
            ->plainTextToken;


        return response()->json([
            'message' => 'Connexion réussie.',

            'token' => $token,

            'user' => [
                'id' => $user->id_user,
                'nom' => $user->nom,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | UTILISATEUR CONNECTÉ
    |--------------------------------------------------------------------------
    */

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id_user,
                'nom' => $user->nom,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | DÉCONNEXION MOBILE
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        $request
            ->user()
            ->currentAccessToken()
            ->delete();

        return response()->json([
            'message' => 'Déconnexion réussie.',
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Filier;
use App\Models\Formateur;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Nombre maximum de tentatives de connexion.
     */
    private const MAX_LOGIN_ATTEMPTS = 5;

    /**
     * Durée du blocage : 5 minutes.
     */
    private const LOGIN_DECAY_SECONDS = 300;


    /**
     * Affiche le formulaire d'inscription.
     */
    public function showRegister(): View
    {
        $filieres = Filier::orderBy(
            'nom_filier'
        )->get();

        return view(
            'auth.register',
            compact('filieres')
        );
    }


    /**
     * Enregistre un nouvel utilisateur.
     *
     * IMPORTANT :
     * L'inscription publique est uniquement
     * autorisée pour :
     *
     * - les étudiants ;
     * - les formateurs.
     *
     * Le responsable pédagogique est un administrateur
     * et ne peut donc pas s'inscrire depuis le site public.
     */
    public function register(
        Request $request
    ): RedirectResponse {

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
                ),
            ],

            /*
            |--------------------------------------------------------------------------
            | MOT DE PASSE SÉCURISÉ
            |--------------------------------------------------------------------------
            */

            'password' => [
                'required',
                'confirmed',

                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],

            /*
            |--------------------------------------------------------------------------
            | RÔLE
            |--------------------------------------------------------------------------
            |
            | IMPORTANT :
            | Le responsable pédagogique n'est PAS autorisé ici.
            |
            */

            'role' => [
                'required',

                Rule::in([
                    'etudiant',
                    'formateur',
                ]),
            ],

            /*
            |--------------------------------------------------------------------------
            | FILIÈRE ÉTUDIANT
            |--------------------------------------------------------------------------
            */

            'id_filier' => [
                Rule::requiredIf(
                    fn (): bool =>
                        $request->input('role')
                        === 'etudiant'
                ),

                'nullable',
                'integer',

                Rule::exists(
                    'filier',
                    'id_filier'
                ),
            ],

            /*
            |--------------------------------------------------------------------------
            | SPÉCIALITÉ FORMATEUR
            |--------------------------------------------------------------------------
            */

            'specialite' => [
                Rule::requiredIf(
                    fn (): bool =>
                        $request->input('role')
                        === 'formateur'
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


        /*
        |--------------------------------------------------------------------------
        | CRÉATION DU COMPTE
        |--------------------------------------------------------------------------
        */

        $user = DB::transaction(
            function () use ($validated): User {

                $user = User::create([
                    'nom' =>
                        $validated['nom'],

                    'email' =>
                        $validated['email'],

                    'password' =>
                        Hash::make(
                            $validated['password']
                        ),

                    'role' =>
                        $validated['role'],

                    'date_inscription' =>
                        now()->toDateString(),
                ]);


                /*
                |--------------------------------------------------------------------------
                | PROFIL ÉTUDIANT
                |--------------------------------------------------------------------------
                */

                if (
                    $validated['role']
                    === 'etudiant'
                ) {

                    Etudiant::create([
                        'id_user' =>
                            $user->id_user,

                        'progression' =>
                            0,

                        'id_filier' =>
                            $validated['id_filier'],
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | PROFIL FORMATEUR
                |--------------------------------------------------------------------------
                */

                if (
                    $validated['role']
                    === 'formateur'
                ) {

                    Formateur::create([
                        'id_user' =>
                            $user->id_user,

                        'specialité' =>
                            $validated['specialite'],
                    ]);
                }


                return $user;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | CONNEXION AUTOMATIQUE
        |--------------------------------------------------------------------------
        */

        Auth::login($user);

        $request
            ->session()
            ->regenerate();


        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                'Votre compte a été créé avec succès.'
            );
    }


    /**
     * Affiche le formulaire de connexion.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }


    /**
     * Connecte un utilisateur.
     */
    public function login(
        Request $request
    ): RedirectResponse {

        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],
        ], [

            'email.required' =>
                'L’adresse e-mail est obligatoire.',

            'email.email' =>
                'L’adresse e-mail n’est pas valide.',

            'password.required' =>
                'Le mot de passe est obligatoire.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | CLÉ DU LIMITEUR
        |--------------------------------------------------------------------------
        */

        $throttleKey =
            Str::lower($credentials['email'])
            . '|'
            . $request->ip();


        /*
        |--------------------------------------------------------------------------
        | VÉRIFIER LE BLOCAGE
        |--------------------------------------------------------------------------
        */

        if (
            RateLimiter::tooManyAttempts(
                $throttleKey,
                self::MAX_LOGIN_ATTEMPTS
            )
        ) {

            $seconds = RateLimiter::availableIn(
                $throttleKey
            );

            $minutes = intdiv(
                $seconds,
                60
            );

            $remainingSeconds =
                $seconds % 60;


            return back()
                ->withErrors([
                    'email' =>
                        'Trop de tentatives de connexion. '
                        . 'Veuillez réessayer dans '
                        . $minutes
                        . ' minute(s) et '
                        . $remainingSeconds
                        . ' seconde(s).',
                ])
                ->onlyInput('email');
        }


        /*
        |--------------------------------------------------------------------------
        | TENTATIVE DE CONNEXION
        |--------------------------------------------------------------------------
        */

        $remember =
            $request->boolean('remember');


        if (
            ! Auth::attempt(
                $credentials,
                $remember
            )
        ) {

            RateLimiter::hit(
                $throttleKey,
                self::LOGIN_DECAY_SECONDS
            );


            $remainingAttempts =
                RateLimiter::remaining(
                    $throttleKey,
                    self::MAX_LOGIN_ATTEMPTS
                );


            if ($remainingAttempts <= 0) {

                return back()
                    ->withErrors([
                        'email' =>
                            'Trop de tentatives incorrectes. '
                            . 'La connexion est maintenant bloquée '
                            . 'pendant 5 minutes.',
                    ])
                    ->onlyInput('email');
            }


            return back()
                ->withErrors([
                    'email' =>
                        'L’adresse e-mail ou le mot de passe est incorrect. '
                        . 'Il vous reste '
                        . $remainingAttempts
                        . ' tentative(s) avant un blocage de 5 minutes.',
                ])
                ->onlyInput('email');
        }


        /*
        |--------------------------------------------------------------------------
        | CONNEXION RÉUSSIE
        |--------------------------------------------------------------------------
        */

        RateLimiter::clear(
            $throttleKey
        );


        $request
            ->session()
            ->regenerate();


        return redirect()->intended(
            route('dashboard')
        );
    }


    /**
     * Affiche le tableau de bord selon le rôle.
     */
    public function dashboard(): View
    {
        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | ÉTUDIANT
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === 'etudiant'
        ) {

            $user->load(
                'etudiant.filier'
            );

            $etudiant = $user->etudiant;

            $nombreCours = 0;
            $quizRealises = 0;
            $totalQuiz = 0;
            $progression = 0;


            if ($etudiant) {

                /*
                |--------------------------------------------------------------------------
                | NOMBRE DE COURS DE LA FILIÈRE
                |--------------------------------------------------------------------------
                */

                $nombreCours = DB::table('cours')
                    ->where(
                        'id_filier',
                        $etudiant->id_filier
                    )
                    ->count();


                /*
                |--------------------------------------------------------------------------
                | QUIZ DIFFÉRENTS RÉALISÉS
                |--------------------------------------------------------------------------
                |
                | Un quiz refait plusieurs fois
                | ne compte qu'une seule fois.
                |
                */

                $quizRealises = DB::table('resultat')
                    ->where(
                        'id_etudiant',
                        $etudiant->id_etudiant
                    )
                    ->distinct()
                    ->count('id_quiz');


                /*
                |--------------------------------------------------------------------------
                | TOTAL DES QUIZ DE LA FILIÈRE
                |--------------------------------------------------------------------------
                */

                $totalQuiz = DB::table('quiz')
                    ->join(
                        'cours',
                        'quiz.id_cours',
                        '=',
                        'cours.id_cours'
                    )
                    ->where(
                        'cours.id_filier',
                        $etudiant->id_filier
                    )
                    ->count();


                /*
                |--------------------------------------------------------------------------
                | PROGRESSION
                |--------------------------------------------------------------------------
                */

                if ($totalQuiz > 0) {

                    $progression = round(
                        (
                            $quizRealises
                            / $totalQuiz
                        ) * 100
                    );
                }
            }


            return view(
                'dashboard-etudiant',
                compact(
                    'user',
                    'nombreCours',
                    'quizRealises',
                    'totalQuiz',
                    'progression'
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FORMATEUR
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === 'formateur'
        ) {

            $user->load(
                'formateur'
            );


            return view(
                'dashboard-formateur',
                compact('user')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | RESPONSABLE PÉDAGOGIQUE
        |--------------------------------------------------------------------------
        |
        | Le responsable peut toujours se connecter.
        | Il ne peut simplement plus créer son compte
        | depuis l'inscription publique.
        |
        */

        abort_unless(
            $user->role
            === 'responsable_pedagogique',
            403
        );


        $user->load(
            'responsablePedagogique'
        );


        return view(
            'dashboard-responsable',
            compact('user')
        );
    }


    /**
     * Déconnexion.
     */
    public function logout(
        Request $request
    ): RedirectResponse {

        Auth::logout();


        $request
            ->session()
            ->invalidate();


        $request
            ->session()
            ->regenerateToken();


        return redirect()
            ->route('login')
            ->with(
                'success',
                'Vous êtes maintenant déconnecté.'
            );
    }
}
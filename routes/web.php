<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\CoursPublicController;

use App\Http\Controllers\EtudiantCoursController;
use App\Http\Controllers\EtudiantProfilController;
use App\Http\Controllers\EtudiantQuizController;
use App\Http\Controllers\EtudiantResultatController;

use App\Http\Controllers\FormateurContenuController;
use App\Http\Controllers\FormateurCoursController;
use App\Http\Controllers\FormateurLeconController;
use App\Http\Controllers\FormateurProfilController;
use App\Http\Controllers\FormateurQuizController;
use App\Http\Controllers\FormateurResultatController;

use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QuizPublicController;

use App\Http\Controllers\ResponsableContenuController;
use App\Http\Controllers\ResponsableCoursController;
use App\Http\Controllers\ResponsableFiliereController;
use App\Http\Controllers\ResponsableLeconController;
use App\Http\Controllers\ResponsableProfilController;
use App\Http\Controllers\ResponsableQuizController;
use App\Http\Controllers\ResponsableResultatController;
use App\Http\Controllers\ResponsableSuiviController;
use App\Http\Controllers\ResponsableUtilisateurController;

use App\Models\Notification;
use App\Models\User;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| PAGES PUBLIQUES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('accueil');
})->name('accueil');


Route::get(
    '/cours',
    [CoursPublicController::class, 'index']
)->name('cours.public');


Route::get(
    '/cours/{id}',
    [CoursPublicController::class, 'show']
)
    ->whereNumber('id')
    ->name('cours.show');


Route::get(
    '/quiz',
    [QuizPublicController::class, 'index']
)->name('quiz.public');


Route::get(
    '/quiz/{id}',
    [QuizPublicController::class, 'show']
)
    ->whereNumber('id')
    ->name('quiz.show');


Route::get('/a-propos', function () {
    return view('apropos');
})->name('apropos');


/*
|--------------------------------------------------------------------------
| CONTACT
|--------------------------------------------------------------------------
*/

Route::get('/contact', function () {
    return view('contact');
})->name('contact');


Route::post(
    '/contact',
    function (Request $request): RedirectResponse {

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
            ],

            'sujet' => [
                'required',
                'string',
                'max:150',
            ],

            'message' => [
                'required',
                'string',
                'max:2000',
            ],
        ], [
            'nom.required' =>
                'Le nom est obligatoire.',

            'email.required' =>
                'L’adresse e-mail est obligatoire.',

            'email.email' =>
                'L’adresse e-mail n’est pas valide.',

            'sujet.required' =>
                'Le sujet est obligatoire.',

            'message.required' =>
                'Le message est obligatoire.',
        ]);


        $responsables = User::where(
            'role',
            'responsable_pedagogique'
        )
            ->whereNotNull('email')
            ->get();


        if ($responsables->isEmpty()) {

            return back()
                ->withInput()
                ->withErrors([
                    'contact' =>
                        'Aucun responsable pédagogique n’est disponible pour recevoir votre message.',
                ]);
        }


        foreach ($responsables as $responsable) {

            /*
            |--------------------------------------------------------------------------
            | NOTIFICATION DANS LA PLATEFORME
            |--------------------------------------------------------------------------
            */

            Notification::create([
                'id_user' =>
                    $responsable->id_user,

                'titre' =>
                    'Nouveau message de contact',

                'message' =>
                    $validated['nom']
                    . ' vous a envoyé un message depuis le formulaire de contact.'
                    . "\n\n"
                    . 'Sujet : '
                    . $validated['sujet']
                    . "\n"
                    . 'E-mail : '
                    . $validated['email']
                    . "\n\n"
                    . 'Message : '
                    . $validated['message'],

                'type' =>
                    'contact',

                'lien' =>
                    null,

                'est_lue' =>
                    false,

                'date_notification' =>
                    now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | E-MAIL
            |--------------------------------------------------------------------------
            */

            $contenuEmail =
                "Bonjour "
                . ($responsable->nom ?? 'Responsable')
                . ",\n\n";

            $contenuEmail .=
                "Vous avez reçu un nouveau message depuis "
                . "le formulaire de contact de la "
                . "Plateforme de Suivi Pédagogique."
                . "\n\n";

            $contenuEmail .=
                "Nom : "
                . $validated['nom']
                . "\n";

            $contenuEmail .=
                "Adresse e-mail : "
                . $validated['email']
                . "\n";

            $contenuEmail .=
                "Sujet : "
                . $validated['sujet']
                . "\n\n";

            $contenuEmail .=
                "Message :"
                . "\n"
                . $validated['message']
                . "\n\n";

            $contenuEmail .=
                "Pour répondre à cette personne, "
                . "vous pouvez simplement utiliser "
                . "le bouton Répondre de votre messagerie."
                . "\n\n";

            $contenuEmail .=
                "Cordialement,"
                . "\n"
                . "Plateforme de Suivi Pédagogique";


            Mail::raw(
                $contenuEmail,
                function ($mail) use (
                    $responsable,
                    $validated
                ) {

                    $mail
                        ->to(
                            $responsable->email,
                            $responsable->nom
                        )
                        ->replyTo(
                            $validated['email'],
                            $validated['nom']
                        )
                        ->subject(
                            'Contact : '
                            . $validated['sujet']
                        );
                }
            );
        }


        return back()->with(
            'contact_success',
            'Votre message a bien été envoyé aux responsables pédagogiques.'
        );
    }
)->name('contact.store');


/*
|--------------------------------------------------------------------------
| AUTHENTIFICATION
|--------------------------------------------------------------------------
*/

Route::get(
    '/inscription',
    [AuthController::class, 'showRegister']
)->name('register');


Route::post(
    '/inscription',
    [AuthController::class, 'register']
)->name('register.store');


Route::get(
    '/connexion',
    [AuthController::class, 'showLogin']
)->name('login');


Route::post(
    '/connexion',
    [AuthController::class, 'login']
)->name('login.store');


/*
|--------------------------------------------------------------------------
| MOT DE PASSE OUBLIÉ
|--------------------------------------------------------------------------
*/

Route::get(
    '/mot-de-passe-oublie',
    [PasswordResetController::class, 'showForgotForm']
)->name('password.request');


Route::post(
    '/mot-de-passe-oublie',
    [PasswordResetController::class, 'sendResetLink']
)->name('password.email');


Route::get(
    '/reinitialiser-mot-de-passe/{token}',
    [PasswordResetController::class, 'showResetForm']
)->name('password.reset');


Route::post(
    '/reinitialiser-mot-de-passe',
    [PasswordResetController::class, 'resetPassword']
)->name('password.update');


/*
|--------------------------------------------------------------------------
| UTILISATEURS CONNECTÉS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function (): void {

    /*
    |--------------------------------------------------------------------------
    | ROUTES COMMUNES
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [AuthController::class, 'dashboard']
    )->name('dashboard');


    Route::post(
        '/deconnexion',
        [AuthController::class, 'logout']
    )->name('logout');


    /*
    |--------------------------------------------------------------------------
    | NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/notifications',
        [NotificationController::class, 'index']
    )->name('notifications.index');


    Route::put(
        '/notifications/tout-lire',
        [NotificationController::class, 'toutLire']
    )->name('notifications.readAll');


    Route::delete(
        '/notifications/tout-supprimer',
        [NotificationController::class, 'destroyAll']
    )->name('notifications.destroyAll');


    Route::put(
        '/notifications/{idNotification}/lire',
        [NotificationController::class, 'lire']
    )
        ->whereNumber('idNotification')
        ->name('notifications.read');


    Route::delete(
        '/notifications/{idNotification}',
        [NotificationController::class, 'destroy']
    )
        ->whereNumber('idNotification')
        ->name('notifications.destroy');


    /*
    |--------------------------------------------------------------------------
    | MESSAGERIE
    | Accessible uniquement aux étudiants et formateurs
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:etudiant,formateur'
    )->group(function (): void {

        Route::get(
            '/messages',
            [MessageController::class, 'index']
        )->name('messages.index');


        Route::get(
            '/messages/nouveau/cours/{idCours}/destinataire/{idDestinataire}',
            [MessageController::class, 'create']
        )
            ->whereNumber('idCours')
            ->whereNumber('idDestinataire')
            ->name('messages.create');


        Route::post(
            '/messages/nouveau/cours/{idCours}/destinataire/{idDestinataire}',
            [MessageController::class, 'store']
        )
            ->whereNumber('idCours')
            ->whereNumber('idDestinataire')
            ->name('messages.store');


        Route::get(
            '/messages/{idMessage}',
            [MessageController::class, 'show']
        )
            ->whereNumber('idMessage')
            ->name('messages.show');


        Route::post(
            '/messages/{idMessage}/repondre',
            [MessageController::class, 'repondre']
        )
            ->whereNumber('idMessage')
            ->name('messages.reply');
    });


    /*
    |--------------------------------------------------------------------------
    | ESPACE ÉTUDIANT
    |--------------------------------------------------------------------------
    |
    | Un formateur ou responsable ne peut pas accéder
    | directement à ces URLs.
    |
    */

    Route::middleware(
        'role:etudiant'
    )->group(function (): void {

        /*
        |--------------------------------------------------------------------------
        | COURS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/etudiant/cours',
            [EtudiantCoursController::class, 'index']
        )->name('etudiant.cours.index');


        Route::get(
            '/etudiant/cours/{idCours}',
            [EtudiantCoursController::class, 'show']
        )
            ->whereNumber('idCours')
            ->name('etudiant.cours.show');


        /*
        |--------------------------------------------------------------------------
        | QUIZ
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/etudiant/quiz',
            [EtudiantQuizController::class, 'index']
        )->name('etudiant.quiz.index');


        Route::get(
            '/etudiant/quiz/{idQuiz}',
            [EtudiantQuizController::class, 'show']
        )
            ->whereNumber('idQuiz')
            ->name('etudiant.quiz.show');


        Route::post(
            '/etudiant/quiz/{idQuiz}',
            [EtudiantQuizController::class, 'submit']
        )
            ->whereNumber('idQuiz')
            ->name('etudiant.quiz.submit');


        /*
        |--------------------------------------------------------------------------
        | RÉSULTATS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/etudiant/resultats',
            [EtudiantResultatController::class, 'index']
        )->name('etudiant.resultats.index');


        /*
        |--------------------------------------------------------------------------
        | PROFIL
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/etudiant/profil',
            [EtudiantProfilController::class, 'index']
        )->name('etudiant.profil.index');


        Route::put(
            '/etudiant/profil',
            [EtudiantProfilController::class, 'update']
        )->name('etudiant.profil.update');
    });


    /*
    |--------------------------------------------------------------------------
    | ESPACE FORMATEUR
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:formateur'
    )->group(function (): void {

        /*
        |--------------------------------------------------------------------------
        | COURS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/formateur/cours',
            [FormateurCoursController::class, 'index']
        )->name('formateur.cours.index');


        /*
        |--------------------------------------------------------------------------
        | LEÇONS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/formateur/lecons',
            [FormateurLeconController::class, 'manage']
        )->name('formateur.lecons.manage');


        Route::post(
            '/formateur/lecons',
            [FormateurLeconController::class, 'storeFromManage']
        )->name('formateur.lecons.manage.store');


        Route::get(
            '/formateur/cours/{idCours}/lecons',
            [FormateurLeconController::class, 'index']
        )
            ->whereNumber('idCours')
            ->name('formateur.lecons.index');


        Route::get(
            '/formateur/cours/{idCours}/lecons/ajouter',
            [FormateurLeconController::class, 'create']
        )
            ->whereNumber('idCours')
            ->name('formateur.lecons.create');


        Route::post(
            '/formateur/cours/{idCours}/lecons',
            [FormateurLeconController::class, 'store']
        )
            ->whereNumber('idCours')
            ->name('formateur.lecons.store');


        Route::get(
            '/formateur/cours/{idCours}/lecons/{idLecon}/modifier',
            [FormateurLeconController::class, 'edit']
        )
            ->whereNumber('idCours')
            ->whereNumber('idLecon')
            ->name('formateur.lecons.edit');


        Route::put(
            '/formateur/cours/{idCours}/lecons/{idLecon}',
            [FormateurLeconController::class, 'update']
        )
            ->whereNumber('idCours')
            ->whereNumber('idLecon')
            ->name('formateur.lecons.update');


        Route::delete(
            '/formateur/cours/{idCours}/lecons/{idLecon}',
            [FormateurLeconController::class, 'destroy']
        )
            ->whereNumber('idCours')
            ->whereNumber('idLecon')
            ->name('formateur.lecons.destroy');


        /*
        |--------------------------------------------------------------------------
        | CONTENUS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/formateur/contenus',
            [FormateurContenuController::class, 'manage']
        )->name('formateur.contenus.manage');


        Route::post(
            '/formateur/contenus',
            [FormateurContenuController::class, 'store']
        )->name('formateur.contenus.store');


        Route::get(
            '/formateur/contenus/{idContenu}/modifier',
            [FormateurContenuController::class, 'edit']
        )
            ->whereNumber('idContenu')
            ->name('formateur.contenus.edit');


        Route::put(
            '/formateur/contenus/{idContenu}',
            [FormateurContenuController::class, 'update']
        )
            ->whereNumber('idContenu')
            ->name('formateur.contenus.update');


        Route::delete(
            '/formateur/contenus/{idContenu}',
            [FormateurContenuController::class, 'destroy']
        )
            ->whereNumber('idContenu')
            ->name('formateur.contenus.destroy');


        /*
        |--------------------------------------------------------------------------
        | QUIZ
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/formateur/quiz',
            [FormateurQuizController::class, 'manage']
        )->name('formateur.quiz.manage');


        Route::post(
            '/formateur/quiz',
            [FormateurQuizController::class, 'store']
        )->name('formateur.quiz.store');


        Route::get(
            '/formateur/quiz/{idQuiz}/modifier',
            [FormateurQuizController::class, 'edit']
        )
            ->whereNumber('idQuiz')
            ->name('formateur.quiz.edit');


        Route::put(
            '/formateur/quiz/{idQuiz}',
            [FormateurQuizController::class, 'update']
        )
            ->whereNumber('idQuiz')
            ->name('formateur.quiz.update');


        Route::delete(
            '/formateur/quiz/{idQuiz}',
            [FormateurQuizController::class, 'destroy']
        )
            ->whereNumber('idQuiz')
            ->name('formateur.quiz.destroy');


        Route::get(
            '/formateur/quiz/{idQuiz}/questions',
            [FormateurQuizController::class, 'questions']
        )
            ->whereNumber('idQuiz')
            ->name('formateur.quiz.questions');


        Route::post(
            '/formateur/quiz/{idQuiz}/questions',
            [FormateurQuizController::class, 'storeQuestion']
        )
            ->whereNumber('idQuiz')
            ->name('formateur.quiz.questions.store');


        Route::get(
            '/formateur/quiz/{idQuiz}/questions/{idQuestion}/modifier',
            [FormateurQuizController::class, 'editQuestion']
        )
            ->whereNumber('idQuiz')
            ->whereNumber('idQuestion')
            ->name('formateur.quiz.questions.edit');


        Route::put(
            '/formateur/quiz/{idQuiz}/questions/{idQuestion}',
            [FormateurQuizController::class, 'updateQuestion']
        )
            ->whereNumber('idQuiz')
            ->whereNumber('idQuestion')
            ->name('formateur.quiz.questions.update');


        Route::delete(
            '/formateur/quiz/{idQuiz}/questions/{idQuestion}',
            [FormateurQuizController::class, 'destroyQuestion']
        )
            ->whereNumber('idQuiz')
            ->whereNumber('idQuestion')
            ->name('formateur.quiz.questions.destroy');


        /*
        |--------------------------------------------------------------------------
        | RÉSULTATS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/formateur/resultats',
            [FormateurResultatController::class, 'index']
        )->name('formateur.resultats.index');


        /*
        |--------------------------------------------------------------------------
        | PROFIL
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/formateur/profil',
            [FormateurProfilController::class, 'index']
        )->name('formateur.profil.index');


        Route::put(
            '/formateur/profil',
            [FormateurProfilController::class, 'update']
        )->name('formateur.profil.update');
    });


    /*
    |--------------------------------------------------------------------------
    | ESPACE RESPONSABLE PÉDAGOGIQUE
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'role:responsable_pedagogique'
    )->group(function (): void {

        /*
        |--------------------------------------------------------------------------
        | UTILISATEURS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/responsable/utilisateurs',
            [ResponsableUtilisateurController::class, 'index']
        )->name('responsable.utilisateurs.index');


        Route::get(
            '/responsable/utilisateurs/{id}/modifier',
            [ResponsableUtilisateurController::class, 'edit']
        )
            ->whereNumber('id')
            ->name('responsable.utilisateurs.edit');


        Route::put(
            '/responsable/utilisateurs/{id}',
            [ResponsableUtilisateurController::class, 'update']
        )
            ->whereNumber('id')
            ->name('responsable.utilisateurs.update');


        /*
        |--------------------------------------------------------------------------
        | FILIÈRES
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/responsable/filieres',
            [ResponsableFiliereController::class, 'index']
        )->name('responsable.filieres.index');


        Route::post(
            '/responsable/filieres',
            [ResponsableFiliereController::class, 'store']
        )->name('responsable.filieres.store');


        Route::get(
            '/responsable/filieres/{id}/modifier',
            [ResponsableFiliereController::class, 'edit']
        )
            ->whereNumber('id')
            ->name('responsable.filieres.edit');


        Route::put(
            '/responsable/filieres/{id}',
            [ResponsableFiliereController::class, 'update']
        )
            ->whereNumber('id')
            ->name('responsable.filieres.update');


        Route::delete(
            '/responsable/filieres/{id}',
            [ResponsableFiliereController::class, 'destroy']
        )
            ->whereNumber('id')
            ->name('responsable.filieres.destroy');


        /*
        |--------------------------------------------------------------------------
        | COURS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/responsable/cours',
            [ResponsableCoursController::class, 'index']
        )->name('responsable.cours.index');


        Route::get(
            '/responsable/cours/ajouter',
            [ResponsableCoursController::class, 'create']
        )->name('responsable.cours.create');


        Route::post(
            '/responsable/cours',
            [ResponsableCoursController::class, 'store']
        )->name('responsable.cours.store');


        Route::get(
            '/responsable/cours/{id}/modifier',
            [ResponsableCoursController::class, 'edit']
        )
            ->whereNumber('id')
            ->name('responsable.cours.edit');


        Route::put(
            '/responsable/cours/{id}',
            [ResponsableCoursController::class, 'update']
        )
            ->whereNumber('id')
            ->name('responsable.cours.update');


        Route::delete(
            '/responsable/cours/{id}',
            [ResponsableCoursController::class, 'destroy']
        )
            ->whereNumber('id')
            ->name('responsable.cours.destroy');


        /*
        |--------------------------------------------------------------------------
        | LEÇONS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/responsable/lecons',
            [ResponsableLeconController::class, 'index']
        )->name('responsable.lecons.index');


        Route::post(
            '/responsable/lecons',
            [ResponsableLeconController::class, 'store']
        )->name('responsable.lecons.store');


        Route::get(
            '/responsable/lecons/{id}/modifier',
            [ResponsableLeconController::class, 'edit']
        )
            ->whereNumber('id')
            ->name('responsable.lecons.edit');


        Route::put(
            '/responsable/lecons/{id}',
            [ResponsableLeconController::class, 'update']
        )
            ->whereNumber('id')
            ->name('responsable.lecons.update');


        Route::delete(
            '/responsable/lecons/{id}',
            [ResponsableLeconController::class, 'destroy']
        )
            ->whereNumber('id')
            ->name('responsable.lecons.destroy');


        /*
        |--------------------------------------------------------------------------
        | CONTENUS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/responsable/contenus',
            [ResponsableContenuController::class, 'index']
        )->name('responsable.contenus.index');


        Route::post(
            '/responsable/contenus',
            [ResponsableContenuController::class, 'store']
        )->name('responsable.contenus.store');


        Route::get(
            '/responsable/contenus/{id}/modifier',
            [ResponsableContenuController::class, 'edit']
        )
            ->whereNumber('id')
            ->name('responsable.contenus.edit');


        Route::put(
            '/responsable/contenus/{id}',
            [ResponsableContenuController::class, 'update']
        )
            ->whereNumber('id')
            ->name('responsable.contenus.update');


        Route::delete(
            '/responsable/contenus/{id}',
            [ResponsableContenuController::class, 'destroy']
        )
            ->whereNumber('id')
            ->name('responsable.contenus.destroy');


        /*
        |--------------------------------------------------------------------------
        | QUIZ
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/responsable/quiz',
            [ResponsableQuizController::class, 'index']
        )->name('responsable.quiz.index');


        Route::post(
            '/responsable/quiz',
            [ResponsableQuizController::class, 'store']
        )->name('responsable.quiz.store');


        Route::get(
            '/responsable/quiz/{id}/modifier',
            [ResponsableQuizController::class, 'edit']
        )
            ->whereNumber('id')
            ->name('responsable.quiz.edit');


        Route::put(
            '/responsable/quiz/{id}',
            [ResponsableQuizController::class, 'update']
        )
            ->whereNumber('id')
            ->name('responsable.quiz.update');


        Route::delete(
            '/responsable/quiz/{id}',
            [ResponsableQuizController::class, 'destroy']
        )
            ->whereNumber('id')
            ->name('responsable.quiz.destroy');


        /*
        |--------------------------------------------------------------------------
        | QUESTIONS DES QUIZ
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/responsable/quiz/{idQuiz}/questions',
            [ResponsableQuizController::class, 'questions']
        )
            ->whereNumber('idQuiz')
            ->name('responsable.quiz.questions');


        Route::post(
            '/responsable/quiz/{idQuiz}/questions',
            [ResponsableQuizController::class, 'storeQuestion']
        )
            ->whereNumber('idQuiz')
            ->name('responsable.quiz.questions.store');


        Route::get(
            '/responsable/questions/{idQuestion}/modifier',
            [ResponsableQuizController::class, 'editQuestion']
        )
            ->whereNumber('idQuestion')
            ->name('responsable.quiz.questions.edit');


        Route::put(
            '/responsable/questions/{idQuestion}',
            [ResponsableQuizController::class, 'updateQuestion']
        )
            ->whereNumber('idQuestion')
            ->name('responsable.quiz.questions.update');


        Route::delete(
            '/responsable/questions/{idQuestion}',
            [ResponsableQuizController::class, 'destroyQuestion']
        )
            ->whereNumber('idQuestion')
            ->name('responsable.quiz.questions.destroy');


        /*
        |--------------------------------------------------------------------------
        | SUIVI DES ÉTUDIANTS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/responsable/suivi',
            [ResponsableSuiviController::class, 'index']
        )->name('responsable.suivi.index');


        /*
        |--------------------------------------------------------------------------
        | RÉSULTATS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/responsable/resultats',
            [ResponsableResultatController::class, 'index']
        )->name('responsable.resultats.index');


        /*
        |--------------------------------------------------------------------------
        | PROFIL
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/responsable/profil',
            [ResponsableProfilController::class, 'index']
        )->name('responsable.profil.index');


        Route::put(
            '/responsable/profil',
            [ResponsableProfilController::class, 'update']
        )->name('responsable.profil.update');
    });
});
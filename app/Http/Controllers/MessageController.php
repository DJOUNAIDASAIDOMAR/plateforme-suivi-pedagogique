<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Message;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MessageController extends Controller
{
    private function utilisateurConnecte(): User
    {
        $user = Auth::user();

        abort_unless(
            $user
            && in_array(
                $user->role,
                [
                    'etudiant',
                    'formateur',
                ],
                true
            ),
            403,
            'Accès réservé aux étudiants et aux formateurs.'
        );

        return $user;
    }


    private function verifierDestinataire(
        User $user,
        Cours $cours,
        User $destinataire
    ): void {
        if ($user->role === 'etudiant') {

            abort_unless(
                $user->etudiant,
                403,
                'Profil étudiant introuvable.'
            );

            abort_unless(
                (int) $user->etudiant->id_filier
                ===
                (int) $cours->id_filier,
                403,
                'Ce cours n’appartient pas à votre filière.'
            );

            abort_unless(
                $cours->formateur
                && $cours->formateur->user
                && (int) $cours->formateur->user->id_user
                ===
                (int) $destinataire->id_user,
                403,
                'Vous pouvez uniquement contacter le formateur de ce cours.'
            );

            return;
        }

        abort_unless(
            $user->formateur,
            403,
            'Profil formateur introuvable.'
        );

        abort_unless(
            (int) $cours->id_formateur
            ===
            (int) $user->formateur->id_formateur,
            403,
            'Ce cours ne vous est pas attribué.'
        );

        abort_unless(
            $destinataire->role === 'etudiant'
            && $destinataire->etudiant,
            403,
            'Le destinataire doit être un étudiant.'
        );

        abort_unless(
            (int) $destinataire->etudiant->id_filier
            ===
            (int) $cours->id_filier,
            403,
            'Cet étudiant n’appartient pas à la filière de ce cours.'
        );
    }


    public function index(): View
    {
        $user = $this->utilisateurConnecte();

        $recus = Message::with([
            'expediteur',
            'cours',
        ])
            ->where(
                'id_destinataire',
                $user->id_user
            )
            ->orderByDesc('date_message')
            ->orderByDesc('id_message')
            ->get();

        $envoyes = Message::with([
            'destinataire',
            'cours',
        ])
            ->where(
                'id_expediteur',
                $user->id_user
            )
            ->orderByDesc('date_message')
            ->orderByDesc('id_message')
            ->get();

        return view(
            'messages.index',
            compact(
                'user',
                'recus',
                'envoyes'
            )
        );
    }


    public function create(
        int $idCours,
        int $idDestinataire
    ): View {
        $user = $this->utilisateurConnecte();

        $cours = Cours::with([
            'filier',
            'formateur.user',
        ])->findOrFail($idCours);

        $destinataire = User::with([
            'etudiant.filier',
            'formateur',
        ])->findOrFail($idDestinataire);

        $this->verifierDestinataire(
            $user,
            $cours,
            $destinataire
        );

        return view(
            'messages.create',
            compact(
                'user',
                'cours',
                'destinataire'
            )
        );
    }


    public function store(
        Request $request,
        int $idCours,
        int $idDestinataire
    ): RedirectResponse {
        $user = $this->utilisateurConnecte();

        $cours = Cours::with([
            'filier',
            'formateur.user',
        ])->findOrFail($idCours);

        $destinataire = User::with([
            'etudiant.filier',
            'formateur',
        ])->findOrFail($idDestinataire);

        $this->verifierDestinataire(
            $user,
            $cours,
            $destinataire
        );

        $validated = $request->validate([
            'sujet' => [
                'required',
                'string',
                'max:150',
            ],

            'message' => [
                'required',
                'string',
                'max:3000',
            ],
        ], [
            'sujet.required' =>
                'Le sujet est obligatoire.',

            'message.required' =>
                'Le message est obligatoire.',
        ]);

        $message = Message::create([
            'id_expediteur' =>
                $user->id_user,

            'id_destinataire' =>
                $destinataire->id_user,

            'id_cours' =>
                $cours->id_cours,

            'sujet' =>
                $validated['sujet'],

            'message' =>
                $validated['message'],

            'est_lu' =>
                false,

            'date_message' =>
                now(),
        ]);

        NotificationService::pourUtilisateur(
            $destinataire->id_user,
            'Nouveau message',
            $user->nom
                . ' vous a envoyé un message concernant le cours « '
                . $cours->titre_cours
                . ' » : « '
                . $validated['sujet']
                . ' ».',
            'message',
            route(
                'messages.show',
                $message->id_message
            )
        );

        return redirect()
            ->route('messages.index')
            ->with(
                'success',
                'Votre message a été envoyé avec succès.'
            );
    }


    public function show(
        int $idMessage
    ): View {
        $user = $this->utilisateurConnecte();

        $message = Message::with([
            'expediteur',
            'destinataire',
            'cours',
        ])
            ->where(function ($query) use ($user) {

                $query
                    ->where(
                        'id_expediteur',
                        $user->id_user
                    )
                    ->orWhere(
                        'id_destinataire',
                        $user->id_user
                    );
            })
            ->findOrFail($idMessage);

        if (
            (int) $message->id_destinataire
            ===
            (int) $user->id_user
            && ! $message->est_lu
        ) {
            $message->update([
                'est_lu' => true,
            ]);
        }

        return view(
            'messages.show',
            compact(
                'message',
                'user'
            )
        );
    }


    public function repondre(
        Request $request,
        int $idMessage
    ): RedirectResponse {
        $user = $this->utilisateurConnecte();

        $messageOriginal = Message::with([
            'expediteur',
            'destinataire',
            'cours',
        ])
            ->where(
                'id_destinataire',
                $user->id_user
            )
            ->findOrFail($idMessage);

        $validated = $request->validate([
            'message' => [
                'required',
                'string',
                'max:3000',
            ],
        ], [
            'message.required' =>
                'Veuillez écrire votre réponse.',
        ]);

        $destinataire =
            $messageOriginal->expediteur;

        $sujet =
            str_starts_with(
                $messageOriginal->sujet,
                'RE:'
            )
                ? $messageOriginal->sujet
                : 'RE: ' . $messageOriginal->sujet;

        $reponse = Message::create([
            'id_expediteur' =>
                $user->id_user,

            'id_destinataire' =>
                $destinataire->id_user,

            'id_cours' =>
                $messageOriginal->id_cours,

            'sujet' =>
                $sujet,

            'message' =>
                $validated['message'],

            'est_lu' =>
                false,

            'date_message' =>
                now(),
        ]);

        NotificationService::pourUtilisateur(
            $destinataire->id_user,
            'Réponse à votre message',
            $user->nom
                . ' a répondu à votre message « '
                . $messageOriginal->sujet
                . ' ».',
            'message',
            route(
                'messages.show',
                $reponse->id_message
            )
        );

        return redirect()
            ->route(
                'messages.show',
                $reponse->id_message
            )
            ->with(
                'success',
                'Votre réponse a été envoyée.'
            );
    }
} 
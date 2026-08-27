<?php

namespace App\Services;

use App\Models\Etudiant;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Crée une notification sur la plateforme
     * ET envoie également un e-mail.
     */
    public static function pourUtilisateur(
        int $idUser,
        string $titre,
        string $message,
        string $type = 'information',
        ?string $lien = null
    ): Notification {

        /*
        |--------------------------------------------------------------------------
        | 1. CRÉER LA NOTIFICATION DANS LA PLATEFORME
        |--------------------------------------------------------------------------
        */

        $notification = Notification::create([
            'id_user' =>
                $idUser,

            'titre' =>
                $titre,

            'message' =>
                $message,

            'type' =>
                $type,

            'lien' =>
                $lien,

            'est_lue' =>
                false,

            'date_notification' =>
                now(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | 2. RÉCUPÉRER L'UTILISATEUR
        |--------------------------------------------------------------------------
        */

        $user = User::find(
            $idUser
        );


        /*
        |--------------------------------------------------------------------------
        | 3. ENVOYER AUSSI LA NOTIFICATION PAR E-MAIL
        |--------------------------------------------------------------------------
        |
        | On vérifie que l'utilisateur existe
        | et possède une adresse e-mail.
        |
        */

        if (
            $user
            && ! empty($user->email)
        ) {

            try {

                $contenuEmail =
                    "Bonjour "
                    . ($user->nom ?? '')
                    . ",\n\n";

                $contenuEmail .=
                    $message
                    . "\n\n";


                /*
                |--------------------------------------------------------------------------
                | Ajouter le lien si la notification en possède un
                |--------------------------------------------------------------------------
                */

                if ($lien) {

                    $contenuEmail .=
                        "Accéder à la plateforme :\n"
                        . $lien
                        . "\n\n";
                }


                $contenuEmail .=
                    "Cordialement,\n"
                    . "Plateforme de Suivi Pédagogique";


                Mail::raw(
                    $contenuEmail,
                    function ($mail) use (
                        $user,
                        $titre
                    ) {

                        $mail
                            ->to(
                                $user->email,
                                $user->nom
                            )
                            ->subject(
                                $titre
                                . ' - Plateforme de Suivi Pédagogique'
                            );
                    }
                );

            } catch (\Throwable $exception) {

                /*
                |--------------------------------------------------------------------------
                | IMPORTANT
                |--------------------------------------------------------------------------
                |
                | Si Gmail rencontre temporairement un problème,
                | on ne bloque PAS la plateforme.
                |
                | La notification reste bien enregistrée dans
                | la table notifications.
                |
                | L'erreur d'e-mail est simplement enregistrée
                | dans les logs Laravel.
                |
                */

                Log::error(
                    'Erreur lors de l’envoi de l’e-mail de notification.',
                    [
                        'id_user' =>
                            $idUser,

                        'email' =>
                            $user->email,

                        'titre' =>
                            $titre,

                        'erreur' =>
                            $exception->getMessage(),
                    ]
                );
            }
        }


        return $notification;
    }


    /**
     * Notifie tous les étudiants
     * appartenant à une filière.
     *
     * Chaque étudiant reçoit :
     *
     * - notification plateforme ;
     * - e-mail.
     */
    public static function pourEtudiantsFiliere(
        int $idFiliere,
        string $titre,
        string $message,
        string $type = 'information',
        ?string $lien = null
    ): void {

        $etudiants = Etudiant::with('user')
            ->where(
                'id_filier',
                $idFiliere
            )
            ->get();


        foreach (
            $etudiants
            as $etudiant
        ) {

            if (! $etudiant->user) {
                continue;
            }


            self::pourUtilisateur(
                $etudiant
                    ->user
                    ->id_user,

                $titre,

                $message,

                $type,

                $lien
            );
        }
    }


    /**
     * Notifie tous les responsables pédagogiques.
     *
     * Chaque responsable reçoit :
     *
     * - notification plateforme ;
     * - e-mail.
     */
    public static function pourResponsables(
        string $titre,
        string $message,
        string $type = 'information',
        ?string $lien = null
    ): void {

        $responsables = User::where(
            'role',
            'responsable_pedagogique'
        )->get();


        foreach (
            $responsables
            as $responsable
        ) {

            self::pourUtilisateur(
                $responsable->id_user,
                $titre,
                $message,
                $type,
                $lien
            );
        }
    }
}
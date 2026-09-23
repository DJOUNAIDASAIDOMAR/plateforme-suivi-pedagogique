<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $table = 'users';


    protected $primaryKey = 'id_user';


    public $incrementing = true;


    protected $keyType = 'int';


    public $timestamps = true;


    protected $fillable = [
        'nom',
        'email',
        'password',
        'role',
        'date_inscription',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'date_inscription' => 'date',
            'password' => 'hashed',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | E-MAIL DE RÉINITIALISATION DU MOT DE PASSE
    |--------------------------------------------------------------------------
    */

    public function sendPasswordResetNotification($token): void
    {
        $url = route(
            'password.reset',
            [
                'token' => $token,
                'email' => $this->email,
            ]
        );


        $this->notify(
            new class($url) extends \Illuminate\Auth\Notifications\ResetPassword
            {
                private string $resetUrl;


                public function __construct(string $resetUrl)
                {
                    $this->resetUrl = $resetUrl;
                }


                public function toMail($notifiable): MailMessage
                {
                    return (new MailMessage)
                        ->subject(
                            'Réinitialisation de votre mot de passe'
                        )
                        ->greeting(
                            'Bonjour ' . $notifiable->nom . ','
                        )
                        ->line(
                            'Nous avons reçu une demande de réinitialisation du mot de passe de votre compte sur la Plateforme de Suivi Pédagogique.'
                        )
                        ->action(
                            'Réinitialiser mon mot de passe',
                            $this->resetUrl
                        )
                        ->line(
                            'Ce lien de réinitialisation expirera dans 60 minutes.'
                        )
                        ->line(
                            'Si vous n’êtes pas à l’origine de cette demande, aucune action n’est nécessaire.'
                        )
                        ->salutation(
                            'Cordialement, Plateforme de Suivi Pédagogique'
                        );
                }
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function etudiant()
    {
        return $this->hasOne(
            Etudiant::class,
            'id_user',
            'id_user'
        );
    }


    public function formateur()
    {
        return $this->hasOne(
            Formateur::class,
            'id_user',
            'id_user'
        );
    }


    public function responsablePedagogique()
    {
        return $this->hasOne(
            ResponsablePedagogique::class,
            'id_user',
            'id_user'
        );
    }
}
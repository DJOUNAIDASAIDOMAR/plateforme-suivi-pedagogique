<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Création de la table messages.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Identifiant du message
            |--------------------------------------------------------------------------
            */

            $table->id('id_message');


            /*
            |--------------------------------------------------------------------------
            | Expéditeur
            |--------------------------------------------------------------------------
            |
            | Référence users.id_user
            |
            */

            $table->unsignedInteger('id_expediteur');


            /*
            |--------------------------------------------------------------------------
            | Destinataire
            |--------------------------------------------------------------------------
            |
            | Référence users.id_user
            |
            */

            $table->unsignedInteger('id_destinataire');


            /*
            |--------------------------------------------------------------------------
            | Cours concerné
            |--------------------------------------------------------------------------
            |
            | IMPORTANT :
            | cours.id_cours est un INT signé dans notre base existante.
            | Il ne faut donc PAS utiliser unsignedInteger ici.
            |
            */

            $table->integer('id_cours')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Message
            |--------------------------------------------------------------------------
            */

            $table->text('message');


            /*
            |--------------------------------------------------------------------------
            | Lecture du message
            |--------------------------------------------------------------------------
            */

            $table->boolean('est_lu')
                ->default(false);


            /*
            |--------------------------------------------------------------------------
            | Date du message
            |--------------------------------------------------------------------------
            */

            $table->timestamp('date_message')
                ->useCurrent();


            /*
            |--------------------------------------------------------------------------
            | Clé étrangère : expéditeur
            |--------------------------------------------------------------------------
            */

            $table->foreign('id_expediteur')
                ->references('id_user')
                ->on('users')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Clé étrangère : destinataire
            |--------------------------------------------------------------------------
            */

            $table->foreign('id_destinataire')
                ->references('id_user')
                ->on('users')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Clé étrangère : cours
            |--------------------------------------------------------------------------
            |
            | Si un cours est supprimé,
            | le message est conservé mais id_cours devient NULL.
            |
            */

            $table->foreign('id_cours')
                ->references('id_cours')
                ->on('cours')
                ->nullOnDelete();
        });
    }


    /**
     * Suppression de la table messages.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
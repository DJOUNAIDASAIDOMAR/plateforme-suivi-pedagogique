<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajoute le sujet aux messages.
     */
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {

            $table->string(
                'sujet',
                150
            )->after('id_cours');

        });
    }

    /**
     * Supprime le sujet en cas de retour arrière.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {

            $table->dropColumn('sujet');

        });
    }
};


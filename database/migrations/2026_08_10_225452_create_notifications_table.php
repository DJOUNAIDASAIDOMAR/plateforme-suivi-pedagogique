<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {

            $table->id('id_notification');

            $table->unsignedInteger('id_user');

            $table->string('titre', 150);

            $table->text('message');

            $table->string('type', 50)
                ->default('information');

            $table->string('lien')
                ->nullable();

            $table->boolean('est_lue')
                ->default(false);

            $table->timestamp('date_notification')
                ->useCurrent();

            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
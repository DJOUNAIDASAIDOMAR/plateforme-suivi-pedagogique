<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    use HasFactory;

    protected $table = 'cours';

    protected $primaryKey = 'id_cours';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'titre_cours',
        'description',
        'id_filier',
        'id_formateur',
    ];

    public function filier()
    {
        return $this->belongsTo(
            Filier::class,
            'id_filier',
            'id_filier'
        );
    }

    public function formateur()
    {
        return $this->belongsTo(
            Formateur::class,
            'id_formateur',
            'id_formateur'
        );
    }

    public function lecons()
    {
        return $this->hasMany(
            Lecon::class,
            'id_cours',
            'id_cours'
        );
    }

    public function quizzes()
    {
        return $this->hasMany(
            Quiz::class,
            'id_cours',
            'id_cours'
        );
    }
}
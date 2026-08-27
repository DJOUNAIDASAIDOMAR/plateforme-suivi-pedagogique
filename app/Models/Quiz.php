<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $table = 'quiz';

    protected $primaryKey = 'id_quiz';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'titre_quiz',
        'id_cours',
    ];

    public function cours()
    {
        return $this->belongsTo(
            Cours::class,
            'id_cours',
            'id_cours'
        );
    }

    public function questions()
    {
        return $this->hasMany(
            Question::class,
            'id_quiz',
            'id_quiz'
        );
    }

    public function resultats()
    {
        return $this->hasMany(
            Resultat::class,
            'id_quiz',
            'id_quiz'
        );
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'question';

    protected $primaryKey = 'id_question';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'texte_question',
        'choix_a',
        'choix_b',
        'choix_c',
        'choix_d',
        'bonne_reponse',
        'id_quiz',
    ];

    public function quiz()
    {
        return $this->belongsTo(
            Quiz::class,
            'id_quiz',
            'id_quiz'
        );
    }
}
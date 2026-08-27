<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resultat extends Model
{
    use HasFactory;

    protected $table = 'resultat';

    protected $primaryKey = 'id_etudiant';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_etudiant',
        'id_quiz',
        'score',
        'date_resultat',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'date_resultat' => 'date',
        ];
    }

    public function etudiant()
    {
        return $this->belongsTo(
            Etudiant::class,
            'id_etudiant',
            'id_etudiant'
        );
    }

    public function quiz()
    {
        return $this->belongsTo(
            Quiz::class,
            'id_quiz',
            'id_quiz'
        );
    }

    protected function setKeysForSelectQuery($query)
    {
        return $query
            ->where('id_etudiant', $this->getAttribute('id_etudiant'))
            ->where('id_quiz', $this->getAttribute('id_quiz'));
    }

    protected function setKeysForSaveQuery($query)
    {
        return $this->setKeysForSelectQuery($query);
    }
}
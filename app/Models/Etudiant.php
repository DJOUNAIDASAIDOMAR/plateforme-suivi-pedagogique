<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory;

    protected $table = 'etudiants';

    protected $primaryKey = 'id_etudiant';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'progression',
        'id_filier',
    ];

    protected function casts(): array
    {
        return [
            'progression' => 'float',
        ];
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id_user'
        );
    }

    public function filier()
    {
        return $this->belongsTo(
            Filier::class,
            'id_filier',
            'id_filier'
        );
    }

    public function resultats()
    {
        return $this->hasMany(
            Resultat::class,
            'id_etudiant',
            'id_etudiant'
        );
    }
}
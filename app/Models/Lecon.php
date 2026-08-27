<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecon extends Model
{
    use HasFactory;

    protected $table = 'leçon';

    protected $primaryKey = 'id_leçon';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'titre_leçon',
        'ordre',
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

    public function contenus()
    {
        return $this->hasMany(
            Contenu::class,
            'id_lecon',
            'id_leçon'
        );
    }
}
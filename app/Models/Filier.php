<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filier extends Model
{
    use HasFactory;

    protected $table = 'filier';

    protected $primaryKey = 'id_filier';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'nom_filier',
    ];

    public function etudiants()
    {
        return $this->hasMany(
            Etudiant::class,
            'id_filier',
            'id_filier'
        );
    }

    public function cours()
    {
        return $this->hasMany(
            Cours::class,
            'id_filier',
            'id_filier'
        );
    }
}
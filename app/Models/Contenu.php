<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contenu extends Model
{
    use HasFactory;


    protected $table = 'contenu';


    protected $primaryKey = 'id_contenu';


    public $incrementing = true;


    protected $keyType = 'int';


    public $timestamps = false;


    protected $fillable = [
        'titre_contenu',
        'type_contenu',
        'fichier',
        'id_lecon',
    ];


    public function lecon()
    {
        return $this->belongsTo(
            Lecon::class,
            'id_lecon',
            'id_leçon'
        );
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formateur extends Model
{
    use HasFactory;

    protected $table = 'formateur';

    protected $primaryKey = 'id_formateur';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'specialité',
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id_user'
        );
    }

    public function cours()
    {
        return $this->hasMany(
            Cours::class,
            'id_formateur',
            'id_formateur'
        );
    }
}
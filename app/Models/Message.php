<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $primaryKey = 'id_message';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_expediteur',
        'id_destinataire',
        'id_cours',
        'sujet',
        'message',
        'est_lu',
        'date_message',
    ];

    protected function casts(): array
    {
        return [
            'est_lu' => 'boolean',
            'date_message' => 'datetime',
        ];
    }

    public function expediteur()
    {
        return $this->belongsTo(
            User::class,
            'id_expediteur',
            'id_user'
        );
    }

    public function destinataire()
    {
        return $this->belongsTo(
            User::class,
            'id_destinataire',
            'id_user'
        );
    }

    public function cours()
    {
        return $this->belongsTo(
            Cours::class,
            'id_cours',
            'id_cours'
        );
    }
}
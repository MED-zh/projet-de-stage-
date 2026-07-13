<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compteur extends Model
{
    use HasFactory;
    protected $fillable = ['contrat_id', 'type', 'matricule'];

    public function contrat() {
        return $this->belongsTo(Contrat::class);
    }

    public function releves() {
        return $this->hasMany(Releve::class);
    }
}

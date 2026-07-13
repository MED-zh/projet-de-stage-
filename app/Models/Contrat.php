<?php

namespace App\Models;

use App\Models\Alert;
use App\Models\Compteur;
use App\Models\Secteur;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrat extends Model
{  
     use HasFactory;

    protected $table = 'contrats';
    protected $fillable = ['contrat_num', 'adresse', 'secteur_id','ordre_tournee', 'date_debut', 'date_fin', 'status']; 

    protected $casts = ['date_debut' => 'datetime', 'date_fin' => 'date'];

    // One contrat belongs to one user (after registration)
    public function secteur() {
        return $this->belongsTo(Secteur::class);
    }

    public function utilisateur() {
        // Link to user by contrat_num
        return $this->hasOne(User::class, 'contrat_num', 'contrat_num');
    }

    public function compteurs() {
        return $this->hasMany(Compteur::class);
    }
    public function paiements()
        {
            return $this->hasMany(Paiement::class, 'contrat_num', 'contrat_num');
        }
    public function alerts() {
        return $this->hasMany(Alert::class, 'contrat_num', 'contrat_num');
    }
}

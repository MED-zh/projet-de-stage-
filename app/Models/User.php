<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
     protected $primaryKey = 'id';

     protected $table = 'users';

     public $incrementing = true;
     
     protected $keyType = 'int';

protected $fillable = ['name', 'email', 'password', 'role', 'phone', 'adresse', 'cin', 'nom_secteur', 'contrat_num'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
     

    // 🔥 ONLY KEEP THIS (useful + simple)
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTechnicien()
    {
        return $this->role === 'technicien';
    }

    public function isClient()
    {
        return $this->role === 'client';
    }
        public function isCaissier()
        {
            return $this->role === 'caissier';
        }
    // relation (keep if you really use contrats)
    public function contrat() {
        return $this->belongsTo(Contrat::class, 'contrat_num', 'contrat_num');
    }
            public function paiementsRecus()
        {
            return $this->hasMany(Paiement::class, 'recu_par');
        }

    public function secteur() {
        return $this->belongsTo(Secteur::class);
    }

    public function alertsReported() {
        return $this->hasMany(Alert::class, 'tech_cin', 'cin');
    }
}  
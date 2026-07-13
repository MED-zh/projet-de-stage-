<?php

namespace App\Models;

use App\Models\Contrat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Secteur extends Model
{
  use HasFactory;

    /**
     * Les attributs qui peuvent être assignés massivement.
     * On inclut tes colonnes personnalisées ici.
     */
    protected $fillable = [
        'number_secteur',
        'nom_secteur',
        'ville',
    ];

    /**
     * Relation : Un secteur possède plusieurs contrats.
     * Note : J'utilise 'nom_secteur' comme clé étrangère car c'est le nom 
     * que tu as choisi dans ta migration de Contrat.
     */
    public function contrats(): HasMany
    {
        return $this->hasMany(Contrat::class, 'nom_secteur');
    }
}

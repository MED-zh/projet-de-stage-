<?php

namespace App\Models;

use App\Models\Facture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Releve extends Model
{
     use HasFactory;
       protected $table = 'RELEVES';

    protected $primaryKey = 'ID';

    public $incrementing = true;

    protected $keyType = 'int';

    
    protected $fillable = [
       'date_releve',
        'type',
        'index_depart',
        'index_fin',
        'consommation',
        'matricule',
        'contrat_num',
        'tech_cin'
    ];

  
  
    public function compteur()
    {
        return $this->belongsTo(Compteur::class);
    }

    /**
     * Link to the contract via contrat_num
     */
    public function contrat()
    {
        return $this->belongsTo(Contrat::class, 'contrat_num', 'contrat_num');
    }

    /**
     * Link to the Technician who did the reading via their CIN
     */
    public function technician()
    {
        return $this->belongsTo(User::class, 'tech_cin', 'cin');
    }

    /**
     * Link to the bill generated from this reading
     */
    public function facture()
    {
        return $this->hasOne(Facture::class);
    }
}

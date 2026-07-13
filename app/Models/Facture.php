<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
  protected $fillable = ['releve_id', 'montant_ht', 'tva', 'montant_ttc', 'date_facturation', 'date_echeance', 'status', 'type'];

    public function releve() {
        return $this->belongsTo(Releve::class);
    }
    public function paiement()
      {
          return $this->hasOne(Paiement::class);
      }
          public function contrat()
    {
        return $this->belongsTo(Contrat::class, 'contrat_num', 'contrat_num');
    }
}

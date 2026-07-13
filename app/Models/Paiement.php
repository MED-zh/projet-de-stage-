<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $table = 'paiements';

    protected $fillable = [
        'numero_recu',
        'facture_id',
        'recu_par',
        'contrat_num',
        'montant_total',
        'date_paiement',
        'notes',
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'montant_total' => 'decimal:2',
    ];

    // ── Relations ─────────────────────────────────────────

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    public function caissier()
    {
        return $this->belongsTo(User::class, 'recu_par');
    }

    public function contrat()
    {
        return $this->belongsTo(Contrat::class, 'contrat_num', 'contrat_num');
    }
}
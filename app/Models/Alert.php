<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{ 
    use HasFactory;
    protected $table = 'ALERTS';

    protected $primaryKey = 'ID';

    public $incrementing = true;

    protected $keyType = 'int';

 
protected $fillable = ['description', 'date_alert', 'status', 'tech_cin', 'contrat_num'];

    public function contrat() {
        return $this->belongsTo(Contrat::class, 'contrat_num', 'contrat_num');
    }

    public function technicien() {
        return $this->belongsTo(User::class, 'tech_cin', 'cin');
    }
}
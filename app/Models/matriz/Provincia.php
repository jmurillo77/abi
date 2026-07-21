<?php

namespace App\Models\matriz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\matriz\Pais;
use App\Models\matriz\Ciudad;

class Provincia extends Model
{
    use HasFactory;

    protected $connection = 'matriz';
    protected $table = 'provincia';
    protected $primaryKey = 'IdProvincia';

    protected $fillable = [
        'Nombre',
        'IdPais',
    ];

    public function pais()
    {
        return $this->belongsTo(Pais::class, 'IdPais', 'IdPais');
    }

    public function ciudades()
    {
        return $this->hasMany(Ciudad::class, 'IdProvincia', 'IdProvincia');
    }
}

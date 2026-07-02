<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function cantones()
    {
        return $this->hasMany(Canton::class, 'IdProvincia', 'IdProvincia');
    }
}

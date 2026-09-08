<?php

namespace App\Models;

use App\Models\matriz\Empresa;
use App\Models\matriz\Persona;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $connection = 'negocio';
    protected $table = 'clientes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'TipoCliente',
        'IdPersona',
        'IdEmpresa',
        'Nombre',
        'Documento',
        'Email',
        'Activo',
    ];

    protected $casts = [
        'Activo' => 'boolean',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'IdPersona', 'IdPersona');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'IdEmpresa', 'IdEmpresa');
    }

    /**
     * Direcciones registradas para el cliente (persona o empresa), ordenadas por
     * antigüedad: la primera direccion registrada se considera la predeterminada.
     */
    public function direccionesEnvio()
    {
        $relacionado = $this->TipoCliente === 'empresa' ? $this->empresa : $this->persona;

        if (! $relacionado) {
            return collect();
        }

        $pivotKey = $this->TipoCliente === 'empresa' ? 'IdEmpresaDireccion' : 'IdPersonaDireccion';
        $pivotTable = $this->TipoCliente === 'empresa' ? 'empresa_direccion' : 'persona_direccion';

        return $relacionado->direcciones()
            ->with(['tipo', 'parroquia.ciudad', 'ruta'])
            ->orderBy("{$pivotTable}.{$pivotKey}")
            ->get();
    }

    public function getIdClientesAttribute()
    {
        return $this->attributes['id'] ?? $this->id;
    }

    public function setIdClientesAttribute($value): void
    {
        $this->attributes['id'] = $value;
    }

    public function getTipoLabelAttribute(): string
    {
        return $this->TipoCliente === 'empresa' ? 'Empresa' : 'Persona';
    }

    public function getNombreRelacionadoAttribute(): string
    {
        $nombre = $this->Nombre;

        if ($nombre) {
            return $nombre;
        }

        if ($this->persona) {
            return trim(($this->persona->Nombres ?? '') . ' ' . ($this->persona->Apellidos ?? '')) ?: '-';
        }

        if ($this->empresa) {
            return $this->empresa->RazonSocial ?? '-';
        }

        return '-';
    }
}

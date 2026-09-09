<?php

namespace App\Models\matriz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\matriz\DireccionTipo;
use App\Models\matriz\Parroquia;
use App\Models\matriz\Persona;
use App\Models\matriz\Empresa;
use App\Models\negocio\Ruta;

class Direccion extends Model
{
    use HasFactory;

    protected $connection = 'matriz';
    protected $table = 'direccion';
    protected $primaryKey = 'IdDireccion';

    protected $fillable = [
        'Nombre',
        'IdDireccionTipo',
        'IdParroquia',
        'Ubicacion',
        'IdRuta',
    ];

    public function tipo()
    {
        return $this->belongsTo(DireccionTipo::class, 'IdDireccionTipo', 'IdDireccionTipo');
    }

    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class, 'IdParroquia', 'IdParroquia');
    }

    /**
     * Recorrido de entrega asignado por defecto a esta dirección: la toma de
     * pedidos lo preselecciona automáticamente al elegirla.
     */
    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'IdRuta');
    }

    public function personas()
    {
        return $this->belongsToMany(Persona::class, 'persona_direccion', 'IdDireccion', 'IdPersona');
    }

    public function empresas()
    {
        return $this->belongsToMany(Empresa::class, 'empresa_direccion', 'IdDireccion', 'IdEmpresa');
    }

    public function getEtiquetaAttribute(): string
    {
        $partes = array_filter([
            $this->Nombre,
            $this->tipo?->Nombre,
            $this->parroquia?->Nombre,
            $this->parroquia?->ciudad?->Nombre,
        ]);

        return $partes ? implode(' - ', $partes) : 'Dirección sin nombre';
    }

    /**
     * URL lista para abrir en Google Maps. Acepta que 'Ubicacion' sea una URL completa
     * o un par de coordenadas "lat,lng"; si no hay ubicación, cae a una búsqueda por texto.
     */
    public function getGoogleMapsUrlAttribute(): ?string
    {
        $ubicacion = trim((string) $this->Ubicacion);

        if ($ubicacion !== '') {
            if (str_starts_with($ubicacion, 'http://') || str_starts_with($ubicacion, 'https://')) {
                return $ubicacion;
            }

            return 'https://www.google.com/maps?q=' . rawurlencode($ubicacion);
        }

        if ($this->Nombre) {
            return 'https://www.google.com/maps/search/' . rawurlencode($this->Etiqueta);
        }

        return null;
    }
}

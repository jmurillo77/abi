<?php

namespace App\Models\negocio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $connection = 'negocio';
    protected $table = 'producto';
    protected $primaryKey = 'IdProducto';

    protected $fillable = [
        'Nombre',
        'Descripcion',
        'TipoProducto',
        'Categoria',
        'UnidadMedida',
        'CostoUnitario',
        'StockActual',
        'PorcentajeMerma',
        'RendimientoCantidad',
        'RendimientoUnidad',
        'UsaReceta',
        'UsaMenu',
        'TipoMenu',
        'Activo',
    ];

    // Categorías sugeridas por nivel (materia prima, receta, menú final).
    public const CATEGORIAS_MATERIA_PRIMA = [
        'PROTEINAS' => 'Proteínas / Cárnicos',
        'FRUTAS_VERDURAS' => 'Frutas y Verduras (Perecederos)',
        'ABARROTES' => 'Abarrotes / Secos',
        'LACTEOS_HUEVOS' => 'Lácteos y Huevos',
        'BEBIDAS_BARRA' => 'Bebidas y Barra',
        'EMPAQUES' => 'Empaques y Desechables',
    ];

    public const CATEGORIAS_RECETA = [
        'SUB_RECETA' => 'Sub-receta (Base)',
        'EMPLATADO' => 'Receta de Emplatado',
    ];

    public const CATEGORIAS_MENU = [
        'ENTRADAS' => 'Entradas / Aperitivos',
        'PLATOS_FUERTES' => 'Platos Fuertes',
        'POSTRES' => 'Postres',
        'BEBIDAS' => 'Bebidas',
        'COMBOS' => 'Combos / Promociones',
    ];

    // Factores de conversión simples (a la unidad base) para poder comparar/convertir
    // cantidades de receta (ej. gramos) contra el inventario de la materia prima (ej. kg).
    private const FACTORES_PESO = ['kg' => 1000, 'g' => 1, 'lb' => 453.592, 'libra' => 453.592];
    private const FACTORES_VOLUMEN = ['l' => 1000, 'litro' => 1000, 'litros' => 1000, 'ml' => 1];

    public function ingredientes()
    {
        return $this->hasMany(ProductoIngrediente::class, 'IdProducto');
    }

    public function usadoComoInsumoEn()
    {
        return $this->hasMany(ProductoIngrediente::class, 'IdInsumo');
    }

    public function getCategoriaLabelAttribute(): ?string
    {
        return match ($this->TipoProducto) {
            'MATERIA_PRIMA' => self::CATEGORIAS_MATERIA_PRIMA[$this->Categoria] ?? null,
            'RECETA' => self::CATEGORIAS_RECETA[$this->Categoria] ?? null,
            'MENU' => self::CATEGORIAS_MENU[$this->Categoria] ?? null,
            default => null,
        };
    }

    public function getTipoProductoLabelAttribute(): string
    {
        return match ($this->TipoProducto) {
            'MATERIA_PRIMA' => 'Materia Prima',
            'RECETA' => 'Receta / Sub-receta',
            'MENU' => 'Menú (Producto Final)',
            default => $this->TipoProducto,
        };
    }

    /**
     * Convierte una cantidad entre unidades de peso o volumen compatibles.
     * Devuelve null si las unidades no son convertibles entre sí (evita cálculos incorrectos).
     */
    public static function convertirCantidad(float $cantidad, ?string $unidadOrigen, ?string $unidadDestino): ?float
    {
        $origen = strtolower(trim((string) $unidadOrigen));
        $destino = strtolower(trim((string) $unidadDestino));

        if ($origen === '' || $destino === '') {
            return null;
        }

        if ($origen === $destino) {
            return $cantidad;
        }

        foreach ([self::FACTORES_PESO, self::FACTORES_VOLUMEN] as $factores) {
            if (isset($factores[$origen], $factores[$destino])) {
                return $cantidad * $factores[$origen] / $factores[$destino];
            }
        }

        return null;
    }
}

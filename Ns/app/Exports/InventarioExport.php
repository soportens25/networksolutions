<?php

namespace App\Exports;

use App\Models\Inventario;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InventarioExport implements FromQuery, WithHeadings, WithMapping
{
    protected $empresaIds;

    public function __construct($empresaIds = null)
    {
        $this->empresaIds = $empresaIds;
    }

    public function query()
    {
        $query = Inventario::query();

        if ($this->empresaIds) {
            $query->whereIn('id_empresa', $this->empresaIds);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Nombre Equipo',
            'Marca Equipo',
            'Tipo Equipo',
            'Sistema Operativo',
            'Número Serial',
            'Idioma',
            'Procesador',
            'Velocidad Procesador',
            'Tipo Conexión',
            'IP',
            'MAC',
            'Memoria RAM',
            'Cantidad Memoria',
            'Slots Memoria',
            'Versión BIOS',
            'Cantidad Discos',
            'Tipo Discos',
            'Espacio Discos',
            'Gráfica',
            'Licencias',
            'Periféricos',
            'Observaciones',
            'Creado',
            'Actualizado',
        ];
    }

    public function map($inventario): array
    {
        return [
            $inventario->nombre_equipo,
            $inventario->marca_equipo,
            $inventario->tipo_equipo,
            $inventario->sistema_operativo,
            $inventario->numero_serial,
            $inventario->idioma,
            $inventario->procesador,
            $inventario->velocidad_procesador,
            $inventario->tipo_conexion,
            $inventario->ip,
            $inventario->mac,
            $inventario->memoria_ram,
            $inventario->cantidad_memoria,
            $inventario->slots_memoria,
            $inventario->version_bios,
            $inventario->cantidad_discos,
            $inventario->tipo_discos,
            $inventario->espacio_discos,
            $inventario->grafica,
            $inventario->licencias,
            $inventario->perifericos,
            $inventario->observaciones,
            $inventario->created_at->format('d/m/Y H:i:s'),
            $inventario->updated_at->format('d/m/Y H:i:s'),
        ];
    }
}

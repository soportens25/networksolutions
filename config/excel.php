<?php

return [
    'exports' => [
        'chunk_size' => 1000, // Tamaño del chunk para exportaciones grandes
        'pre_calculate_formulas' => false, // Precalcular fórmulas en Excel
        'csv' => [
            'delimiter' => ',', // Delimitador para archivos CSV
            'enclosure' => '"', // Carácter de encapsulado para CSV
            'line_ending' => "\n", // Fin de línea para CSV
            'use_bom' => false, // Usar BOM (Byte Order Mark) en CSV
            'include_separator_line' => false, // Incluir línea separadora en CSV
            'excel_compatibility' => false, // Compatibilidad con Excel
        ],
    ],
    'imports' => [
        'read_only' => true, // Leer solo en importaciones
        'ignore_empty' => false, // Ignorar filas vacías en importaciones
        'heading_row' => [
            'formatter' => 'slug', // Formato para los nombres de las columnas
        ],
    ],
    'extension_detector' => [
        'xlsx' => 'Xlsx',
        'xls' => 'Xls',
        'csv' => 'Csv',
        'ods' => 'Ods',
        'html' => 'Html',
    ],
];
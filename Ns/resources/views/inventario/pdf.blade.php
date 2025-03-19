<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Hoja de Vida de Equipo</title>
    <style>
        @page {
            margin: 20mm;
        }

        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
            color: #000;
            line-height: 1.4;
        }

        .container {
            width: 100%;
            padding: 10px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            margin-bottom: 15px;
        }

        .header-table td {
            vertical-align: middle;
            text-align: center;
            padding: 5px;
        }

        .logo-container {
            width: 100px;
        }

        .logo {
            width: 100%;
        }

        .title-container {
            text-align: center;
        }

        .title-container h1 {
            font-size: 18px;
            margin: 0;
            color: #D35400;
        }

        .title-container p {
            margin: 3px 0;
            font-size: 10px;
            color: #000;
        }

        /* Secciones */
        .section {
            margin-bottom: 15px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #D35400;
            border-bottom: 1px solid #D35400;
            margin-bottom: 5px;
            padding-bottom: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            padding: 6px 8px;
            border: 1px solid #444;
            font-size: 11px;
        }

        th {
            background-color: #F0F0F0;
            color: #000;
        }

        /* Tablas sin bordes (para control y mantenimiento) */
        table.no-border {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table.no-border td {
            border: none;
            padding: 5px 8px;
            vertical-align: top;
            font-size: 11px;
            color: #000;
        }

        /* Footer */
        .footer {
            text-align: center;
            font-size: 9px;
            color: #000;
            border-top: 1px solid #D35400;
            padding-top: 5px;
            margin-top: 15px;
        }

        .sec {
            width: 20%;
            margin-left: 79%;
        }

        .sec th {
            height: 10px text-align: center;
        }

        .sec td {
            height: 10px
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Encabezado General -->
        <div class="container">
            <!-- ENCABEZADO -->
            <table class="header-table">
                <tr>
                    <td class="logo-container">
                        <img src="{{ asset('storage/' . $logo) }}" class="logo">
                    </td>
                    <td class="title-container">
                        <h1>HOJA DE VIDA DE EQUIPOS DE CÓMPUTO</h1>
                        <p>Código: FO-SS-01 | Página: 1</p>
                        <p>Fecha de Descarga: {{ \Carbon\Carbon::now()->isoFormat('D [de] MMMM [del] YYYY') }} | Versión
                            No.: 1</p>
                    </td>
                    <td class="logo-container">
                        <img src="{{ asset('storage/image/logo.jpg') }}" class="logo">
                    </td>
                </tr>
            </table>


            <div>
                <table class="sec">
                    <tr>
                        <th> {{ \Carbon\Carbon::now()->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}</th>
                        <th>{{ $inventario->sticker ?? 'Sin Sticker' }}</th>
                    </tr>
                </table>
            </div>

            <!-- INFORMACIÓN DE LA EMPRESA -->
            <div class="section">
                <div class="section-title">1. INFORMACIÓN DE LA EMPRESA</div>
                <table>
                    <tr>
                        <th>ID Empresa</th>
                        <td>{{ $inventario->id_empresa }}</td>
                        <th>Nombre Empresa</th>
                        <td>{{ $inventario->empresa->nombre_empresa ?? 'N/D' }}</td>
                    </tr>
                </table>
            </div>
            <br>
            <br>
            <!-- DATOS DEL EQUIPO -->
            <div class="section">
                <div class="section-title">2. DATOS DEL EQUIPO</div>
                <table>
                    <tr>
                        <th>Nombre del Equipo</th>
                        <td>{{ $inventario->nombre_equipo }}</td>
                        <th>Marca</th>
                        <td>{{ $inventario->marca_equipo }}</td>
                    </tr>
                    <tr>
                        <th>Tipo de Equipo</th>
                        <td>{{ $inventario->tipo_equipo }}</td>
                        <th>Sistema Operativo</th>
                        <td>{{ $inventario->sistema_operativo }}</td>
                    </tr>
                    <tr>
                        <th>Número Serial</th>
                        <td>{{ $inventario->numero_serial }}</td>
                        <th>Idioma</th>
                        <td>{{ $inventario->idioma }}</td>
                    </tr>
                </table>
            </div>
            <br>
            <br>
            <br>
            <!-- CONFIGURACIÓN DE HARDWARE -->
            <div class="section">
                <div class="section-title">3. CONFIGURACIÓN DE HARDWARE</div>
                <table>
                    <tr>
                        <th>Procesador</th>
                        <td>{{ $inventario->procesador }}</td>
                        <th>Velocidad</th>
                        <td>{{ $inventario->velocidad_procesador }}</td>
                    </tr>
                    <tr>
                        <th>Tipo de Conexión</th>
                        <td>{{ $inventario->tipo_conexion }}</td>
                        <th>IP / MAC</th>
                        <td>
                            IP: {{ $inventario->ip }}<br>
                            MAC: {{ $inventario->mac }}
                        </td>
                    </tr>
                    <tr>
                        <th>Memoria RAM</th>
                        <td>{{ $inventario->memoria_ram }}</td>
                        <th>Cantidad</th>
                        <td>{{ $inventario->cantidad_memoria }}</td>
                    </tr>
                    <tr>
                        <th>Slots de Memoria</th>
                        <td>{{ $inventario->slots_memoria }}</td>
                        <th>Versión BIOS</th>
                        <td>{{ $inventario->version_bios }}</td>
                    </tr>
                    <tr>
                        <th>Discos</th>
                        <td colspan="3">
                            Tipo: {{ $inventario->tipo_discos }}<br>
                            Espacio: {{ $inventario->espacio_discos }}<br>
                            Cantidad: {{ $inventario->cantidad_discos }}
                        </td>
                    </tr>
                    <tr>
                        <th>Gráfica</th>
                        <td colspan="3">{{ $inventario->grafica }}</td>
                    </tr>
                </table>
            </div>

            <!-- DETALLES ADICIONALES -->
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>    
            <br>
            <div class="section">
                <div class="section-title">4. DETALLES ADICIONALES</div>
                <table>
                    <tr>
                        <th>Licencias</th>
                        <td>{{ $inventario->licencias }}</td>
                    </tr>
                    <tr>
                        <th>Periféricos</th>
                        <td>{{ $inventario->perifericos }}</td>
                    </tr>
                    <tr>
                        <th>Observaciones</th>
                        <td>{{ $inventario->observaciones }}</td>
                    </tr>
                </table>
            </div>

            <!-- CONTROL Y MANTENIMIENTO (en la parte superior de la sección final) -->

            <!-- SECCIÓN DE MANTENIMIENTOS REALIZADOS -->
            <div class="section">
                <div class="section-title">6. MANTENIMIENTOS REALIZADOS</div>

                <!-- TABLA DE MANTENIMIENTOS -->
                <table>
                    <thead>
                        <tr>
                            <th>Tecnico</th>
                            <th>Tipo de Mantenimiento</th>
                            <th>Fecha de Mantenimiento</th>
                            <th>Descripción de Mantenimiento</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inventario->historialMantenimiento as $mantenimiento)
                            <tr>
                                <td>{{ $mantenimiento->encargado }}</td>
                                <td>{{ $mantenimiento->tipo_mantenimiento }}</td>
                                <td>{{ \Carbon\Carbon::parse($mantenimiento->fecha_mantenimiento)->format('d/m/Y') }}</td>
                                <td>{{ $mantenimiento->descripcion }}</td>
                                <td>{{ $mantenimiento->observaciones }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <br> <!-- Espaciado entre tablas -->

                <!-- TABLA DE ASIGNACIONES -->
                <div class="section-title">7. ASIGNACIONES DEL EQUIPO</div>
                <table>
                    <thead>
                        <tr>
                            <th>Encargado</th>
                            <th>Area de ubicación</th>
                            <th>Fecha de asignación</th>
                            <th>Fecha de devolución</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inventario->asignaciones as $asignacion)
                            <tr>
                                <td>{{ $asignacion->usuario_responsable ?? 'No asignado' }}</td>
                                <td>{{ $asignacion->area_ubicacion ?? 'No asignado' }}</td>
                                <td>{{ \Carbon\Carbon::parse($asignacion->fecha_asignacion)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($asignacion->fecha_devolucion)->format('d/m/Y') }}</td>
                                <td>{{ $asignacion->observacion}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="section">
                <div class="section-title">5. CONTROL Y MANTENIMIENTO</div>
                <table class="no-border">
                    <tr>
                        <td><strong>N° Ficha:</strong> 00{{ $inventario->id }}</td>
                        <td><strong>Fecha:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}</td>
                        <td><strong>Hoja de datos y control de mantenimiento</strong></td>
                        <td><strong>N° de equipo:</strong> 00{{ $inventario->id }}</td>
                        <td><strong>Área asignada:</strong> Comercial</td>
                    </tr>
                </table>
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <div class="section">
                <table class="no-border">
                    <tr>
                        <td><strong>Elaborado o Actualizado por:</strong> ____________________</td>
                        <td><strong>Revisado por:</strong> ____________________</td>
                        <td><strong>Aprobado por:</strong> ____________________</td>
                    </tr>
                </table>
            </div>


            <br>
            <!-- Footer -->
            <div class="footer">
                <p>"DOCUMENTO CONTROLADO" de propiedad de {{ $inventario->empresa->nombre_empresa ?? 'N/D' }} . Se
                    reservan los derechos de Autor. La impresión y/o fotocopia parcial o total de su contenido está
                    restringida.</p>
            </div>
        </div>
</body>

</html>

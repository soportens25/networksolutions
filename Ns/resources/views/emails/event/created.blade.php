<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nueva Tarea Asignada</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f7;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #2d2d2d;
        }

        .wrapper {
            width: 100%;
            padding: 40px 0;
            background-color: #f4f4f7;
        }

        .main {
            width: 100%;
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }

        .header {
            background-color: #1f2937;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: #ffffff;
            text-align: center
        }

        .content {
            padding: 40px 30px;
            text-align: center;
        }

        .content h2 {
            font-size: 20px;
            margin: 0 0 10px;
        }

        .content p {
            font-size: 16px;
            margin: 0 0 30px;
            color: #4b5563;
        }

        .details {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            text-align: left;
            margin: 0 auto 30px;
            max-width: 100%;
        }

        .details p {
            margin: 10px 0;
            font-size: 15px;
            color: #374151;
        }

        .details p strong {
            color: #111827;
        }

        .btn {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff;
            padding: 12px 28px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #1e40af;
        }

        .footer {
            text-align: center;
            padding: 20px;
            font-size: 13px;
            color: #9ca3af;
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="main">

            <!-- Header -->
            <div class="header">
                <h1>🗓️ Nueva Tarea Asignada</h1>
            </div>

            <!-- Content -->
            <div class="content">
                <h2>¡Hola, {{ $event->user->name }}!</h2>
                <p>Se ha asignado una nueva tarea con los siguientes detalles:</p>

                <div class="details">
                    <p><strong>Título:</strong> {{ $event->title }}</p>
                    <p><strong>Tipo:</strong> {{ ucfirst($event->type) }}</p>
                    <p><strong>Inicio:</strong> {{ \Carbon\Carbon::parse($event->start)->format('d/m/Y H:i') }}</p>
                    <p><strong>Fin:</strong> {{ \Carbon\Carbon::parse($event->end)->format('d/m/Y H:i') }}</p>
                </div>

                <a href="{{ url('dashboard?seccion=calendario') }}" class="btn" target="_blank">
                    Ver en el calendario
                </a>
            </div>

            <!-- Footer -->
            <div class="footer">
                Network Solutions IT PBX © {{ now()->year }}<br>
                Este es un mensaje automático. No respondas a este correo.
            </div>
        </div>
    </div>
</body>

</html>

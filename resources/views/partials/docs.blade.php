<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso al Documento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts + Icon -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>

        .mas {
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .card i {
            font-size: 48px;
            color: #4285F4;
            margin-bottom: 20px;
        }

        .card h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 15px;
        }

        .card p {
            color: #666;
            margin-bottom: 30px;
        }

        .btn {
            background-color: #4285F4;
            color: #fff;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #357ae8;
        }

        @media (max-width: 600px) {
            .card {
                padding: 30px 20px;
            }

            .card h2 {
                font-size: 20px;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="mas">
        <div class="card">
            <i class="bi bi-google-drive"></i>
            <h2>Accede a tu documento</h2>
            <p>Haz clic en el botón para abrir el enlace de Google Drive.</p>
            <a href="https://drive.google.com/drive/folders/1nJLYguDGyOt36T_JDn5qHq08x0T6bgqu?usp=sharing" target="_blank" class="btn">
                Ver en Google Drive
            </a>
        </div>
    </div>

</body>
</html>

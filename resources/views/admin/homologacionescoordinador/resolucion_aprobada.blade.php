<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .titulo { text-align: center; font-weight: bold; margin-bottom: 20px; }
        .contenido { margin: 0 30px; text-align: justify; }
        .firma { margin-top: 60px; text-align: center; }
        .linea-firma { margin-top: 60px; border-top: 1px solid #000; width: 200px; margin-left: auto; margin-right: auto; }
    </style>
    <title>Resolución de Homologación</title>
</head>
<body>
    <div class="titulo">
        UNIVERSIDAD AUTÓNOMA DEL CAUCA<br>
        FACULTAD DE INGENIERÍA<br>
        RESOLUCIÓN DE HOMOLOGACIÓN
    </div>

    <div class="contenido">
        <p>La Facultad de Ingeniería de la Universidad Autónoma del Cauca, en uso de sus atribuciones legales, y</p>

        <p><strong>CONSIDERANDO:</strong></p>
        <ul>
            <li>Que el/la estudiante <strong>{{ $homologacion['estudiante']['nombre'] ?? 'Nombre no disponible' }}</strong>, identificado(a) con número de cédula <strong>{{ $homologacion['estudiante']['identificacion'] ?? '---' }}</strong>, ha solicitado la homologación de asignaturas.</li>
            <li>Que los estudios cursados en <strong>{{ $homologacion['universidad_origen'] ?? '---' }}</strong> son susceptibles de homologación.</li>
            <li>Que tras la revisión del comité curricular, se aprueba la homologación de las siguientes asignaturas:</li>
        </ul>

        <table border="1" cellpadding="5" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Asignatura Origen</th>
                    <th>Asignatura Destino</th>
                    <th>Nota</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($homologacion['asignaturas'] as $asig)
                    @if (($asig['estado'] ?? '') === 'aprobada')
                    <tr>
                        <td>{{ $asig['asignatura_origen'] ?? '---' }}</td>
                        <td>{{ $asig['asignatura_destino'] ?? '---' }}</td>
                        <td>{{ $asig['nota'] ?? '---' }}</td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <p>Por lo anterior, se RESUELVE:</p>

        <p>ARTÍCULO PRIMERO: Aprobar la homologación de las asignaturas antes mencionadas al estudiante <strong>{{ $homologacion['estudiante']['nombre'] ?? '' }}</strong>.</p>
        <p>ARTÍCULO SEGUNDO: Esta resolución rige a partir de su expedición.</p>
    </div>

    <div class="firma">
        <p>Cali, {{ \Carbon\Carbon::now()->format('d \d\e F \d\e Y') }}</p>
        <div class="linea-firma"></div>
        <p><strong>{{ $homologacion['decano_nombre'] ?? 'Decano Nombre' }}</strong><br>Decano Facultad de Ingeniería</p>
    </div>
</body>
</html>

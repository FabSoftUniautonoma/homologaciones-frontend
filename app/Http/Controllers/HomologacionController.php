<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Solicitud;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use PDF;

class HomologacionController extends Controller
{
    /**
     * Actualiza el estado de una solicitud.
     */
    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,en revisión,aprobada,rechazada',
        ]);

        $homologacion = Solicitud::findOrFail($id);
        $homologacion->estado = ucfirst($request->estado);
        $homologacion->save();

        // Registrar historial
        $homologacion->historial()->create([
            'accion' => 'Estado actualizado a: ' . ucfirst($request->estado),
            'usuario' => auth()->user()->name ?? 'admin',
        ]);

        return redirect()->back()->with('success', 'Estado actualizado correctamente.');
    }

    /**
     * Muestra información detallada de una solicitud.
     */
    public function verInformacion($id)
    {
        try {
            $solicitud = Solicitud::findOrFail($id);
            return view('admin.homologacionescoordinador.informacionhomologacionusuario', compact('solicitud'));
        } catch (\Exception $e) {
            return view('homologacionesaspirante.error_solicitud_no_encontrada');
        }
    }

    /**
     * Genera y descarga el PDF de una solicitud.
     */
    public function descargarPDF($id)
    {
        $solicitud = Solicitud::findOrFail($id);
        $pdf = PDF::loadView('pdf.homologacion', compact('solicitud'));
        return $pdf->download("Homologacion_{$solicitud->id}.pdf");
    }

    /**
     * Verifica los documentos de una solicitud.
     */
    public function verificarDocumentos($id)
    {
        $homologacion = Solicitud::findOrFail($id);

        $documentos = [
            'Documento de identidad' => $homologacion->documento_identidad,
            'Certificado de notas' => $homologacion->certificado_notas,
            'Contenido programático' => $homologacion->contenido_programatico,
            'Carta de solicitud' => $homologacion->carta_solicitud,
            'Hoja de vida' => $homologacion->hoja_vida,
            'Otro documento' => $homologacion->otro_documento,
        ];

        return view('admin.homologacionescoordinador.documentos', compact('documentos'));
    }

    /**
     * Cambia el estado de una solicitud a "En revisión".
     */
    public function iniciarProceso($id)
    {
        $homologacion = Solicitud::findOrFail($id);
        $homologacion->estado = 'En revisión';
        $homologacion->save();

        return response()->json(['mensaje' => 'Proceso iniciado con éxito.']);
    }

    /**
     * Obtiene datos del backend (solicitudes y usuarios).
     */
    public function obtenerDatosBack()
    {
        try {
            $client = new Client([
                'base_uri' => env('BASE_URL_BACKEND'), // Debe terminar en /api/
                'timeout' => 2.0,
            ]);

            $responseSolicitudes = $client->request('GET', 'solicitudes', [
                'headers' => ['Accept' => 'application/json']
            ]);
            $solicitudes = json_decode($responseSolicitudes->getBody()->getContents(), true);
            $responseUsuarios = $client->request('GET', 'usuarios', [
                'headers' => ['Accept' => 'application/json']
            ]);

            return view('admin.homologacionescoordinador.coordinador', [
                'solicitudes' => $solicitudes,
            ]);

        } catch (RequestException $e) {
            \Log::error('Error al obtener datos del backend: ' . $e->getMessage());

            return view('admin.homologacionescoordinador.coordinador')->withErrors([
                'error' => 'No se pudieron cargar los datos. Verifica que el backend esté corriendo y accesible.'
            ]);
        }
    }
    // HomologacionController.php


    public function verDocumentos($id)
    {
        // Definimos los documentos por estudiante
        $documentosPorEstudiante = [
            'HOM-2025-001' => [
                'nombre' => 'María González',
                'documentos' => [
                    'Documento de identidad' => 'https://www.sancristobal.gov.co/sites/sancristobal.gov.co/files/documentos/tabla_archivos/folleto_cedula_digital_v1.pdf',
                    'Certificado de notas' => 'https://www.uniboyaca.edu.co/sites/default/files/2024-09/EJEMPLO%20CERTIFICADO%20COLEGIO%20Y%20NOTAS%20BECAS%20MEJORES%20BACHILLERES.pdf',
                    'Contenido programático' => 'https://www.unipamplona.edu.co/unipamplona/portalIG/home_129/recursos/archivos-civil/documentos/05112021/cont-resistdemater.pdf',
                    'Carta Solicitud de homologación' => 'https://brisenas.gob.mx/contenidos/brisenas/docs/7_formato-de-carta-peticion_22527130557.pdf',
                    'Certificación de finalización de estudios' => 'https://www.fceqyn.unam.edu.ar/wp-content/uploads/2020/09/modelo-certificado-%C3%BAltimo-a%C3%B1o-2.pdf',
                    'Copia de la visa o pasaporte' => null,
                ],
            ],
            'HOM-2025-002' => [
                'nombre' => 'Carlos Rodríguez',
                'documentos' => [
                    'Documento de identidad' => 'https://www.sancristobal.gov.co/sites/sancristobal.gov.co/files/documentos/tabla_archivos/folleto_cedula_digital_v1.pdf',
                    'Certificado de notas' => 'https://www.uniboyaca.edu.co/sites/default/files/2024-09/EJEMPLO%20CERTIFICADO%20COLEGIO%20Y%20NOTAS%20BECAS%20MEJORES%20BACHILLERES.pdf',
                    'Contenido programático' => 'https://www.unipamplona.edu.co/unipamplona/portalIG/home_129/recursos/archivos-civil/documentos/05112021/cont-resistdemater.pdf',
                    'Carta Solicitud de homologación' => 'https://brisenas.gob.mx/contenidos/brisenas/docs/7_formato-de-carta-peticion_22527130557.pdf',
                    'Certificación de finalización de estudios' => 'https://www.fceqyn.unam.edu.ar/wp-content/uploads/2020/09/modelo-certificado-%C3%BAltimo-a%C3%B1o-2.pdf',
                    'Copia de la visa o pasaporte' => 'docs/carlos_rodriguez/visa_pasaporte.pdf',
                ],
            ],
            'HOM-2025-003' => [
                'nombre' => 'Ana López',
                'documentos' => [
                    'Documento de identidad' => 'https://www.sancristobal.gov.co/sites/sancristobal.gov.co/files/documentos/tabla_archivos/folleto_cedula_digital_v1.pdf',
                    'Certificado de notas' => 'https://www.uniboyaca.edu.co/sites/default/files/2024-09/EJEMPLO%20CERTIFICADO%20COLEGIO%20Y%20NOTAS%20BECAS%20MEJORES%20BACHILLERES.pdf',
                    'Contenido programático' => 'https://www.unipamplona.edu.co/unipamplona/portalIG/home_129/recursos/archivos-civil/documentos/05112021/cont-resistdemater.pdf',
                    'Carta Solicitud de homologación' => 'https://brisenas.gob.mx/contenidos/brisenas/docs/7_formato-de-carta-peticion_22527130557.pdf',
                    'Certificación de finalización de estudios' => 'https://www.fceqyn.unam.edu.ar/wp-content/uploads/2020/09/modelo-certificado-%C3%BAltimo-a%C3%B1o-2.pdf',
                    'Copia de la visa o pasaporte' => null,
                ],
            ],
            'HOM-2025-004' => [
                'nombre' => 'Luis Martínez',
                'documentos' => [
                    'Documento de identidad' => 'https://www.sancristobal.gov.co/sites/sancristobal.gov.co/files/documentos/tabla_archivos/folleto_cedula_digital_v1.pdf',
                    'Certificado de notas' => 'https://www.uniboyaca.edu.co/sites/default/files/2024-09/EJEMPLO%20CERTIFICADO%20COLEGIO%20Y%20NOTAS%20BECAS%20MEJORES%20BACHILLERES.pdf',
                    'Contenido programático' => 'https://www.unipamplona.edu.co/unipamplona/portalIG/home_129/recursos/archivos-civil/documentos/05112021/cont-resistdemater.pdf',
                    'Carta Solicitud de homologación' => 'https://brisenas.gob.mx/contenidos/brisenas/docs/7_formato-de-carta-peticion_22527130557.pdf',
                    'Certificación de finalización de estudios' => null,
                    'Copia de la visa o pasaporte' => 'docs/luis_martinez/visa_pasaporte.pdf',
                ],
            ],
        ];


        // Verificar si existe el ID del estudiante en los documentos
        if (!array_key_exists($id, $documentosPorEstudiante)) {
            abort(404, 'No hay documentos disponibles para este ID.');
        }

        // Obtener los datos del estudiante
        $nombreEstudiante = $documentosPorEstudiante[$id]['nombre'];
        $documentos = $documentosPorEstudiante[$id]['documentos'];

        // Retornar la vista con los datos necesarios
        return view('admin.homologacionescoordinador.documentos', compact('documentos', 'nombreEstudiante', 'id'));
    }
    public function procesarHomologacion($id)
    {
        // Datos simulados para las solicitudes
        $solicitudes = [
            'HOM-2025-001' => [
                'id' => 1,
                'codigo' => 'HOM-2025-001',
                'nombre' => 'María González',
                'instituto' => 'FUP de Popayán',
                'fecha' => '01/04/2025',
                'estado' => 'Pendiente',
                'estado_class' => 'warning',
                'instituto_origen' => 'FUP de Popayán',
                'programa_origen' => 'Tecnología en Sistemas',
                'carrera_interes' => 'Ingeniería de Software',
                'fecha_solicitud' => Carbon::createFromFormat('d/m/Y', '01/04/2025'),
                'estudiante' => (object) [
                    'tipo_identificacion' => 'Cédula de Ciudadanía',
                    'numero_identificacion' => '1234567890',
                    'nombre_completo' => 'María González',
                    'correo' => 'maria@example.com',
                    'telefono' => '3201234567',
                    'nacionalidad' => 'Colombiana',
                    'departamento' => 'Cauca',
                    'municipio' => 'Popayán',
                ],
                'materias_cursadas' => [
                    [
                        'nombre' => 'Programación Funcional',
                        'nota' => '4.0',
                        'descripcion' => 'Paradigma funcional aplicado a resolución de problemas usando Haskell o Scala.',
                        'creditos' => 2,
                        'horas' => 32,
                        'temas' => ['Funciones puras', 'Inmutabilidad', 'Recursión'],
                    ],
                    [
                        'nombre' => 'Machine Learning Básico',
                        'nota' => '4.3',
                        'descripcion' => 'Introducción al aprendizaje automático usando Python y Scikit-learn.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['Regresión', 'Clasificación', 'Entrenamiento', 'Evaluación'],
                    ],
                    [
                        'nombre' => 'DevOps',
                        'nota' => '4.5',
                        'descripcion' => 'Automatización de procesos de desarrollo y despliegue de software.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['CI/CD', 'Docker', 'Git', 'Pipeline'],
                    ],
                    [
                        'nombre' => 'Seguridad Web',
                        'nota' => '4.1',
                        'descripcion' => 'Detección y prevención de vulnerabilidades en aplicaciones web.',
                        'creditos' => 2,
                        'horas' => 32,
                        'temas' => ['XSS', 'SQL Injection', 'HTTPS', 'Tokens'],
                    ],
                    [
                        'nombre' => 'Diseño de Software',
                        'nota' => '4.3',
                        'descripcion' => 'Patrones y buenas prácticas para diseño modular y escalable.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['MVC', 'DRY', 'Patrones de diseño', 'SOLID'],
                    ],
                    [
                        'nombre' => 'Técnicas de Testing',
                        'nota' => '4.2',
                        'descripcion' => 'Metodologías de prueba de software, unitarias y funcionales.',
                        'creditos' => 2,
                        'horas' => 32,
                        'temas' => ['TDD', 'JUnit', 'Casos de prueba'],
                    ],
                    [
                        'nombre' => 'Análisis de Datos',
                        'nota' => '4.4',
                        'descripcion' => 'Procesamiento y análisis de datos aplicados a decisiones empresariales.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['ETL', 'Pandas', 'Visualización'],
                    ],
                    [
                        'nombre' => 'Aplicaciones Híbridas',
                        'nota' => '4.5',
                        'descripcion' => 'Creación de apps móviles multiplataforma con tecnologías como React Native.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['Componentes', 'Hooks', 'Navegación'],
                    ],
                    [
                        'nombre' => 'Inteligencia Artificial',
                        'nota' => '4.3',
                        'descripcion' => 'Bases teóricas y prácticas de sistemas inteligentes.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['Agentes', 'Redes neuronales', 'Heurísticas'],
                    ],
                    [
                        'nombre' => 'Gestión de Proyectos TIC',
                        'nota' => '4.4',
                        'descripcion' => 'Planificación, ejecución y control de proyectos de software.',
                        'creditos' => 2,
                        'horas' => 32,
                        'temas' => ['Alcance', 'Costos', 'Cronograma', 'Riesgos'],
                    ],
                ],

                'historial' => collect([
                    (object) [
                        'accion' => 'Solicitud registrada',
                        'usuario' => 'admin',
                        'created_at' => now()->subDays(3)
                    ],
                    (object) [
                        'accion' => 'Documentos revisados',
                        'usuario' => 'coordinador',
                        'created_at' => now()->subDays(1)
                    ]
                ])
            ],
            'HOM-2025-002' => [
                'id' => 2,
                'codigo' => 'HOM-2025-002',
                'nombre' => 'Carlos Rodríguez',
                'instituto' => 'SENA',
                'fecha' => '31/03/2025',
                'estado' => 'En revisión',
                'estado_class' => 'info',
                'instituto_origen' => 'SENA',
                'programa_origen' => 'Análisis y Desarrollo de Software',
                'carrera_interes' => 'Ingeniería de Software',
                'fecha_solicitud' => Carbon::createFromFormat('d/m/Y', '31/03/2025'),
                'estudiante' => (object) [
                    'tipo_identificacion' => 'Tarjeta de Identidad',
                    'numero_identificacion' => '987654321',
                    'nombre_completo' => 'Carlos Rodríguez',
                    'correo' => 'carlos@example.com',
                    'telefono' => '3124567890',
                    'nacionalidad' => 'Colombiano',
                    'departamento' => 'Valle del Cauca',
                    'municipio' => 'Cali',
                ],
                'materias_cursadas' => [
                    [
                        'nombre' => 'Algoritmos y Lógica de Programación',
                        'nota' => '4.3',
                        'descripcion' => 'Introducción a los fundamentos de la programación estructurada y resolución de problemas.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['Variables y tipos de datos', 'Estructuras de control', 'Funciones', 'Arreglos'],
                    ],
                    [
                        'nombre' => 'Programación Orientada a Objetos',
                        'nota' => '4.5',
                        'descripcion' => 'Profundización en los principios de programación usando objetos en Java.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['Clases y objetos', 'Encapsulamiento', 'Herencia', 'Polimorfismo'],
                    ],
                    [
                        'nombre' => 'Bases de Datos I',
                        'nota' => '4.2',
                        'descripcion' => 'Diseño, modelado e implementación de bases de datos relacionales.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['Modelo entidad-relación', 'Normalización', 'SQL básico', 'Consultas'],
                    ],
                    [
                        'nombre' => 'Estructura de Datos',
                        'nota' => '4.1',
                        'descripcion' => 'Estudio de estructuras de datos y su aplicación en algoritmos eficientes.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['Listas enlazadas', 'Pilas y colas', 'Árboles', 'Grafos'],
                    ],
                    [
                        'nombre' => 'Matemáticas Discretas',
                        'nota' => '4.0',
                        'descripcion' => 'Fundamentos matemáticos esenciales para la computación.',
                        'creditos' => 2,
                        'horas' => 32,
                        'temas' => ['Lógica proposicional', 'Conjuntos', 'Relaciones', 'Grafos'],
                    ],
                    [
                        'nombre' => 'Sistemas Operativos',
                        'nota' => '4.3',
                        'descripcion' => 'Estudio de la estructura y funcionamiento de sistemas operativos.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['Procesos', 'Memoria', 'Sistemas de archivos', 'Sincronización'],
                    ],
                    [
                        'nombre' => 'Redes de Computadores',
                        'nota' => '4.2',
                        'descripcion' => 'Conceptos y fundamentos de redes de datos.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['Modelos OSI y TCP/IP', 'Direcciones IP', 'Enrutamiento', 'Protocolos'],
                    ],
                    [
                        'nombre' => 'Ingeniería de Software I',
                        'nota' => '4.4',
                        'descripcion' => 'Introducción a la ingeniería de software, procesos y ciclo de vida.',
                        'creditos' => 2,
                        'horas' => 32,
                        'temas' => ['Modelos de desarrollo', 'Requerimientos', 'Análisis', 'Diseño'],
                    ],
                    [
                        'nombre' => 'Programación Web',
                        'nota' => '4.5',
                        'descripcion' => 'Desarrollo de aplicaciones web del lado del cliente y servidor.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['HTML/CSS', 'JavaScript', 'PHP', 'Bases de datos web'],
                    ],
                    [
                        'nombre' => 'Electrónica Digital',
                        'nota' => '4.1',
                        'descripcion' => 'Fundamentos de electrónica para comprender el funcionamiento del hardware.',
                        'creditos' => 2,
                        'horas' => 32,
                        'temas' => ['Lógica digital', 'Puertas lógicas', 'Flip-flops', 'Contadores'],
                    ]
                ],
                'historial' => collect([
                    (object) [
                        'accion' => 'Solicitud registrada',
                        'usuario' => 'admin',
                        'created_at' => now()->subDays(3)
                    ],
                    (object) [
                        'accion' => 'Documentos revisados',
                        'usuario' => 'coordinador',
                        'created_at' => now()->subDays(1)
                    ]
                ])
            ],
            'HOM-2025-003' => [
                'id' => 3,
                'codigo' => 'HOM-2025-003',
                'nombre' => 'Ana López',
                'instituto' => 'Colegio Mayor',
                'fecha' => '30/03/2025',
                'estado' => 'Aprobada',
                'estado_class' => 'success',
                'instituto_origen' => 'Colegio Mayor',
                'programa_origen' => 'Tecnología en Desarrollo de Aplicaciones Web',
                'carrera_interes' => 'Ingeniería de Software',
                'fecha_solicitud' => Carbon::createFromFormat('d/m/Y', '30/03/2025'),
                'estudiante' => (object) [
                    'tipo_identificacion' => 'Cédula de Ciudadanía',
                    'numero_identificacion' => '1122334455',
                    'nombre_completo' => 'Ana López',
                    'correo' => 'ana@example.com',
                    'telefono' => '3112233445',
                    'nacionalidad' => 'Colombiana',
                    'departamento' => 'Antioquia',
                    'municipio' => 'Medellín',
                ],
                'materias_cursadas' => [
                    [
                        'nombre' => 'Fundamentos de Programación',
                        'nota' => '4.2',
                        'descripcion' => 'Principios básicos para desarrollar algoritmos y resolver problemas usando pseudocódigo y lenguaje C.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['Algoritmos', 'Condicionales', 'Bucles', 'Funciones'],
                    ],
                    [
                        'nombre' => 'Lógica Computacional',
                        'nota' => '4.0',
                        'descripcion' => 'Estudio de la lógica formal aplicada a la resolución de problemas computacionales.',
                        'creditos' => 2,
                        'horas' => 32,
                        'temas' => ['Lógica proposicional', 'Tablas de verdad', 'Inferencias'],
                    ],
                    [
                        'nombre' => 'Bases de Datos II',
                        'nota' => '4.3',
                        'descripcion' => 'Manejo avanzado de bases de datos, procedimientos almacenados y optimización de consultas.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['Stored Procedures', 'Vistas', 'Índices', 'Subconsultas'],
                    ],
                    [
                        'nombre' => 'Análisis y Diseño de Sistemas',
                        'nota' => '4.5',
                        'descripcion' => 'Estudio de metodologías y herramientas para el análisis y modelado de sistemas de información.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['Casos de uso', 'Diagramas UML', 'Modelado de datos'],
                    ],
                    [
                        'nombre' => 'Desarrollo Web Frontend',
                        'nota' => '4.6',
                        'descripcion' => 'Creación de interfaces gráficas dinámicas utilizando tecnologías web modernas.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['HTML5', 'CSS3', 'JavaScript', 'Responsive Design'],
                    ],
                    [
                        'nombre' => 'Desarrollo Web Backend',
                        'nota' => '4.4',
                        'descripcion' => 'Construcción de servicios y APIs con tecnologías de servidor.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['PHP', 'Laravel básico', 'REST API', 'Autenticación'],
                    ],
                    [
                        'nombre' => 'Metodologías Ágiles',
                        'nota' => '4.1',
                        'descripcion' => 'Aplicación de metodologías ágiles para gestión de proyectos de software.',
                        'creditos' => 2,
                        'horas' => 32,
                        'temas' => ['SCRUM', 'Sprints', 'Product Backlog', 'Roles del equipo'],
                    ],
                    [
                        'nombre' => 'Seguridad Informática',
                        'nota' => '4.0',
                        'descripcion' => 'Fundamentos de seguridad digital y protección de la información.',
                        'creditos' => 2,
                        'horas' => 32,
                        'temas' => ['Autenticación', 'Cifrado', 'Amenazas comunes'],
                    ],
                    [
                        'nombre' => 'Fundamentos de Redes',
                        'nota' => '4.3',
                        'descripcion' => 'Conceptos básicos sobre interconexión de dispositivos y protocolos de red.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['Topologías', 'Protocolos', 'Switches y routers'],
                    ],
                    [
                        'nombre' => 'Matemáticas para Programadores',
                        'nota' => '4.2',
                        'descripcion' => 'Aplicación de conceptos matemáticos al desarrollo de software.',
                        'creditos' => 2,
                        'horas' => 32,
                        'temas' => ['Álgebra', 'Matrices', 'Cálculo básico'],
                    ],
                ],
                'historial' => collect([
                    (object) [
                        'accion' => 'Solicitud registrada',
                        'usuario' => 'admin',
                        'created_at' => now()->subDays(3)
                    ],
                    (object) [
                        'accion' => 'Documentos revisados',
                        'usuario' => 'coordinador',
                        'created_at' => now()->subDays(1)
                    ]
                ])





            ],
            'HOM-2025-004' => [
                'id' => 4,
                'codigo' => 'HOM-2025-004',
                'nombre' => 'Luis Martínez',
                'instituto' => 'SENA',
                'fecha' => '29/03/2025',
                'estado' => 'Rechazada',
                'estado_class' => 'danger',
                'instituto_origen' => 'SENA',
                'programa_origen' => 'Tecnología en Redes y Comunicaciones',
                'carrera_interes' => 'Ingeniería de Software',
                'fecha_solicitud' => Carbon::createFromFormat('d/m/Y', '29/03/2025'),
                'estudiante' => (object) [
                    'tipo_identificacion' => 'Cédula de Extranjería',
                    'numero_identificacion' => '77889900',
                    'nombre_completo' => 'Luis Martínez',
                    'correo' => 'luis@example.com',
                    'telefono' => '3134455667',
                    'nacionalidad' => 'Ecuatoriano',
                    'departamento' => 'Nariño',
                    'municipio' => 'Pasto',
                ],
                'materias_cursadas' => [
                    [
                        'nombre' => 'Introducción a la Ingeniería de Software',
                        'nota' => '4.4',
                        'descripcion' => 'Explora los conceptos fundamentales del ciclo de vida del software.',
                        'creditos' => 2,
                        'horas' => 32,
                        'temas' => ['Modelos de desarrollo', 'Requisitos', 'Calidad del software'],
                    ],
                    [
                        'nombre' => 'Diseño de Interfaces',
                        'nota' => '4.5',
                        'descripcion' => 'Creación de interfaces centradas en el usuario utilizando principios de usabilidad.',
                        'creditos' => 2,
                        'horas' => 32,
                        'temas' => ['UX/UI', 'Prototipado', 'Accesibilidad'],
                    ],
                    [
                        'nombre' => 'Lenguajes de Programación',
                        'nota' => '4.3',
                        'descripcion' => 'Estudio comparativo de lenguajes orientados a objetos, funcionales y estructurados.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['Paradigmas', 'Java', 'Python', 'C++'],
                    ],
                    [
                        'nombre' => 'Arquitectura de Computadores',
                        'nota' => '4.2',
                        'descripcion' => 'Estructura interna y funcionamiento del hardware.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['Memoria', 'Procesadores', 'Buses', 'Ciclo de instrucción'],
                    ],
                    [
                        'nombre' => 'Programación Móvil',
                        'nota' => '4.4',
                        'descripcion' => 'Desarrollo de aplicaciones móviles nativas para Android.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['Android Studio', 'Java/Kotlin', 'Layouts', 'Intents'],
                    ],
                    [
                        'nombre' => 'Taller de Programación',
                        'nota' => '4.1',
                        'descripcion' => 'Curso práctico con ejercicios de lógica y resolución de problemas.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['Recursividad', 'Estructuras condicionales', 'Listas'],
                    ],
                    [
                        'nombre' => 'Desarrollo de Software Empresarial',
                        'nota' => '4.3',
                        'descripcion' => 'Creación de sistemas integrales de gestión empresarial.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['ERP', 'CRUD', 'PHP/MySQL'],
                    ],
                    [
                        'nombre' => 'Ética Profesional en TI',
                        'nota' => '4.5',
                        'descripcion' => 'Reflexión crítica sobre la responsabilidad social del desarrollador.',
                        'creditos' => 2,
                        'horas' => 32,
                        'temas' => ['Privacidad', 'Ciberseguridad', 'Impacto social'],
                    ],
                    [
                        'nombre' => 'Fundamentos de Hardware',
                        'nota' => '4.0',
                        'descripcion' => 'Conocimientos básicos sobre componentes físicos del computador.',
                        'creditos' => 2,
                        'horas' => 32,
                        'temas' => ['Partes del PC', 'Montaje', 'Mantenimiento preventivo'],
                    ],
                    [
                        'nombre' => 'Diseño y Administración de Bases de Datos',
                        'nota' => '4.2',
                        'descripcion' => 'Diseño lógico y físico de BD con administración y optimización.',
                        'creditos' => 3,
                        'horas' => 48,
                        'temas' => ['MySQL avanzado', 'Índices', 'Triggers', 'Backups'],
                    ],
                ],
                'historial' => collect([
                    (object) [
                        'accion' => 'Solicitud rechazada',
                        'usuario' => 'admin',
                        'created_at' => now()->subDays(2)
                    ]
                ])
            ],
        ];


        $pensum_autonoma = [
            1 => [ // Primer semestre
                ['nombre' => 'Matemáticas I', 'descripcion' => 'Curso básico de cálculo diferencial enfocado en funciones reales, límites y derivadas.', 'temas' => ['Funciones reales', 'Límites y continuidad', 'Derivadas', 'Aplicaciones de la derivada'], 'creditos' => 4, 'horas' => 64],
                ['nombre' => 'Física I', 'descripcion' => 'Fundamentos de física clásica con énfasis en mecánica y cinemática.', 'temas' => ['Vectores', 'Cinemática', 'Leyes de Newton', 'Trabajo y energía'], 'creditos' => 3, 'horas' => 48],
                ['nombre' => 'Programación I', 'descripcion' => 'Introducción a la programación con énfasis en lógica y estructuras básicas.', 'temas' => ['Algoritmos', 'Variables y tipos de datos', 'Condicionales', 'Bucles'], 'creditos' => 3, 'horas' => 48],
                ['nombre' => 'Comunicación Oral y Escrita', 'descripcion' => 'Desarrollo de competencias comunicativas en contextos académicos y profesionales.', 'temas' => ['Redacción académica', 'Argumentación', 'Ortografía', 'Presentaciones orales'], 'creditos' => 2, 'horas' => 32],
                ['nombre' => 'Fundamentos de Ingeniería de Software', 'descripcion' => 'Panorama general del desarrollo de software como disciplina de ingeniería.', 'temas' => ['Ciclo de vida del software', 'Modelos de desarrollo', 'Requerimientos', 'Ética profesional'], 'creditos' => 3, 'horas' => 48],
            ],
            2 => [ // Segundo semestre
                ['nombre' => 'Matemáticas II', 'descripcion' => 'Estudio del cálculo integral y sus aplicaciones.', 'temas' => ['Integrales definidas', 'Métodos de integración', 'Aplicaciones de la integral', 'Series y sucesiones'], 'creditos' => 4, 'horas' => 64],
                ['nombre' => 'Física II', 'descripcion' => 'Electricidad, magnetismo y óptica.', 'temas' => ['Electrostática', 'Corriente eléctrica', 'Circuitos', 'Ondas y óptica'], 'creditos' => 3, 'horas' => 48],
                ['nombre' => 'Programación II', 'descripcion' => 'Estructuras de datos básicas y programación orientada a objetos.', 'temas' => ['POO', 'Listas', 'PilasyColas', 'Recursividad'], 'creditos' => 3, 'horas' => 48],
                ['nombre' => 'Lógica y Matemática Discreta', 'descripcion' => 'Base lógica y matemática para la computación.', 'temas' => ['Proposiciones', 'Álgebra booleana', 'Teoría de conjuntos', 'Grafos'], 'creditos' => 3, 'horas' => 48],
                ['nombre' => 'Arquitectura de Computadores', 'descripcion' => 'Principios de hardware, componentes y organización de computadores.', 'temas' => ['CPU y memoria', 'Sistemas de buses', 'Lenguaje ensamblador', 'Arquitectura Von Neumann'], 'creditos' => 4, 'horas' => 64],
            ],
            3 => [ // Tercer semestre
                ['nombre' => 'Estructuras de Datos', 'descripcion' => 'Estudio detallado de estructuras de datos para optimizar algoritmos.', 'temas' => ['Listas enlazadas', 'Árboles', 'Grafos', 'Tablas hash'], 'creditos' => 3, 'horas' => 48],
                ['nombre' => 'Bases de Datos I', 'descripcion' => 'Diseño lógico de bases de datos relacionales.', 'temas' => ['Modelo entidad-relación', 'Normalización', 'SQL básico', 'Restricciones'], 'creditos' => 4, 'horas' => 64],
                ['nombre' => 'Ingeniería de Requisitos', 'descripcion' => 'Técnicas para la recolección y análisis de requerimientos.', 'temas' => ['Elicitación', 'Modelado de requisitos', 'Casos de uso', 'Requisitos no funcionales'], 'creditos' => 3, 'horas' => 48],
                ['nombre' => 'Probabilidad y Estadística', 'descripcion' => 'Análisis probabilístico y estadístico aplicado a ingeniería.', 'temas' => ['Distribuciones', 'Variables aleatorias', 'Muestreo', 'Inferencia estadística'], 'creditos' => 3, 'horas' => 48],
                ['nombre' => 'Sistemas Operativos', 'descripcion' => 'Fundamentos y funcionamiento de sistemas operativos.', 'temas' => ['Procesos e hilos', 'Administración de memoria', 'Sistemas de archivos', 'Planificación de CPU'], 'creditos' => 4, 'horas' => 64],
            ],
            4 => [ // Cuarto semestre
                ['nombre' => 'Bases de Datos II', 'descripcion' => 'Gestión avanzada de bases de datos y transacciones.', 'temas' => ['Transacciones', 'Índices', 'Vistas', 'SQL avanzado'], 'creditos' => 3, 'horas' => 48],
                ['nombre' => 'Diseño de Software', 'descripcion' => 'Modelado y patrones para diseñar sistemas robustos.', 'temas' => ['UML', 'Patrones de diseño', 'Arquitectura en capas', 'Principios SOLID'], 'creditos' => 3, 'horas' => 48],
                ['nombre' => 'Ingeniería de Software I', 'descripcion' => 'Desarrollo de proyectos usando metodologías ágiles.', 'temas' => ['Scrum', 'Kanban', 'Historias de usuario', 'Gestión de backlog'], 'creditos' => 4, 'horas' => 64],
                ['nombre' => 'Análisis y Diseño de Algoritmos', 'descripcion' => 'Técnicas para diseñar y evaluar algoritmos eficientes.', 'temas' => ['Divide y vencerás', 'Programación dinámica', 'Algoritmos voraces', 'Complejidad temporal'], 'creditos' => 4, 'horas' => 64],
                ['nombre' => 'Redes de Computadores', 'descripcion' => 'Fundamentos de redes de datos y protocolos.', 'temas' => ['Modelo OSI', 'TCP/IP', 'Direccionamiento IP', 'Protocolos de red'], 'creditos' => 3, 'horas' => 48],
            ],
            5 => [ // Quinto semestre
                ['nombre' => 'Lenguajes de Programación', 'descripcion' => 'Estudio comparativo de paradigmas de programación.', 'temas' => ['Paradigma funcional', 'Lenguaje Prolog', 'Compiladores', 'Interpretes'], 'creditos' => 3, 'horas' => 48],
                ['nombre' => 'Seguridad Informática', 'descripcion' => 'Principios y técnicas para proteger la información.', 'temas' => ['Criptografía', 'Seguridad en redes', 'Autenticación', 'Ataques comunes'], 'creditos' => 3, 'horas' => 48],
                ['nombre' => 'Gestión de Proyectos de Software', 'descripcion' => 'Planeación y control de proyectos tecnológicos.', 'temas' => ['Planificación', 'Estimación de esfuerzo', 'Gestión de riesgos', 'Seguimiento'], 'creditos' => 3, 'horas' => 48],
                ['nombre' => 'Desarrollo Web', 'descripcion' => 'Creación de aplicaciones web frontend y backend.', 'temas' => ['HTML/CSS/JS', 'PHP o NodeJS', 'Laravel o Express', 'APIs REST'], 'creditos' => 4, 'horas' => 64],
                ['nombre' => 'Electiva Profesional I', 'descripcion' => 'Curso electivo según línea de interés.', 'temas' => ['A determinar por el estudiante y currículo'], 'creditos' => 3, 'horas' => 48],
            ],
            6 => [ // Sexto semestre
                ['nombre' => 'Ingeniería de Software II', 'descripcion' => 'Construcción y aseguramiento de calidad del software.', 'temas' => ['Integración continua', 'Pruebas de software', 'Refactorización', 'Métricas de calidad'], 'creditos' => 4, 'horas' => 64],
                ['nombre' => 'Desarrollo de Aplicaciones Móviles', 'descripcion' => 'Diseño e implementación de apps para Android o iOS.', 'temas' => ['Android Studio', 'Flutter', 'Interfaces móviles', 'Gestión de eventos'], 'creditos' => 3, 'horas' => 48],
                ['nombre' => 'Computación en la Nube', 'descripcion' => 'Uso de servicios cloud para desplegar soluciones.', 'temas' => ['AWS', 'Azure', 'Contenedores', 'Microservicios'], 'creditos' => 4, 'horas' => 64],
                ['nombre' => 'Interacción Humano-Computador', 'descripcion' => 'Diseño centrado en el usuario y usabilidad.', 'temas' => ['UX/UI', 'Prototipado', 'Evaluación de interfaces', 'Diseño accesible'], 'creditos' => 3, 'horas' => 48],
                ['nombre' => 'Electiva Profesional II', 'descripcion' => 'Curso electivo en una línea especializada.', 'temas' => ['Según currículo'], 'creditos' => 3, 'horas' => 48],
            ],
            7 => [ // Séptimo semestre
                ['nombre' => 'Inteligencia Artificial', 'descripcion' => 'Fundamentos y aplicaciones de IA.', 'temas' => ['Agentes inteligentes', 'Aprendizaje automático', 'Redes neuronales', 'Árboles de decisión'], 'creditos' => 4, 'horas' => 64],
                ['nombre' => 'Minería de Datos', 'descripcion' => 'Extracción de patrones útiles de grandes volúmenes de datos.', 'temas' => ['Análisis de datos', 'Algoritmos de minería', 'Data wrangling', 'Visualización'], 'creditos' => 3, 'horas' => 48],
                ['nombre' => 'Blockchain y Criptomonedas', 'descripcion' => 'Estudio de blockchain, criptomonedas y su impacto social.', 'temas' => ['Blockchain', 'Ethereum', 'Bitcoin', 'Smart contracts'], 'creditos' => 3, 'horas' => 48],
                ['nombre' => 'Electiva Profesional III', 'descripcion' => 'Curso electivo de profundización.', 'temas' => ['A determinar'], 'creditos' => 3, 'horas' => 48],
                ['nombre' => 'Desarrollo de Videojuegos', 'descripcion' => 'Creación de videojuegos utilizando motores gráficos como Unity o Unreal.', 'temas' => ['Unity', 'Modelado 3D', 'Programación gráfica', 'Gestión de recursos'], 'creditos' => 4, 'horas' => 64],
            ],
            8 => [ // Octavo semestre
                ['nombre' => 'Tendencias Tecnológicas', 'descripcion' => 'Estudio de las tendencias emergentes en tecnología.', 'temas' => ['IoT', 'Big Data', '5G', 'Realidad Aumentada'], 'creditos' => 3, 'horas' => 48],
                ['nombre' => 'Proyecto Final I', 'descripcion' => 'Desarrollo del proyecto de investigación o de software final.', 'temas' => ['Definición del proyecto', 'Planificación', 'Metodología de desarrollo'], 'creditos' => 6, 'horas' => 96],
                ['nombre' => 'Ética Profesional y Responsabilidad Social', 'descripcion' => 'Ética en el ejercicio profesional y el impacto social de las tecnologías.', 'temas' => ['Ética profesional', 'Responsabilidad social', 'Impacto de la tecnología'], 'creditos' => 3, 'horas' => 48],
                ['nombre' => 'Electiva Profesional IV', 'descripcion' => 'Curso electivo en la línea de interés del estudiante.', 'temas' => ['A determinar'], 'creditos' => 3, 'horas' => 48],
            ],
            9 => [ // Noveno semestre
                ['nombre' => 'Proyecto Final II', 'descripcion' => 'Desarrollo y entrega del proyecto final.', 'temas' => ['Entrega del proyecto', 'Presentación', 'Documentación final'], 'creditos' => 9, 'horas' => 144],
                ['nombre' => 'Práctica Profesional', 'descripcion' => 'Realización de prácticas en empresas del sector tecnológico.', 'temas' => ['Trabajo en equipo', 'Aplicación de conocimientos', 'Desarrollo en un entorno profesional'], 'creditos' => 6, 'horas' => 96],
                ['nombre' => 'Gestión de la Innovación', 'descripcion' => 'Cómo gestionar proyectos tecnológicos innovadores.', 'temas' => ['Creatividad', 'Innovación en productos', 'Gestión del cambio', 'Transformación digital'], 'creditos' => 3, 'horas' => 48],
            ]
        ];



        // Verificar si existe el ID de la solicitud
        if (!array_key_exists($id, $solicitudes)) {
            abort(404, 'Solicitud no encontrada.');
        }

        // Obtener la solicitud correspondiente
        $solicitud = $solicitudes[$id];
        $materias_cursadas = $solicitud['materias_cursadas'];

        // Comparar las materias cursadas con el pensum de la Universidad Autónoma del Cauca
        $materias_homologables = [];
        foreach ($materias_cursadas as $materia_cursada) {
            // Iterar por cada semestre del pensum autónomo
            foreach ($pensum_autonoma as $semestre => $materias) {
                foreach ($materias as $materia_pensum) {
                    // Compara las materias cursadas con las del pensum de cada semestre
                    if ($materia_cursada['nombre'] == $materia_pensum['nombre']) {
                        // Se guarda la materia homologable
                        $materias_homologables[] = [
                            'nombre' => $materia_cursada['nombre'],
                            'nota' => $materia_cursada['nota'],
                            'descripcion' => $materia_cursada['descripcion'],
                            'descripcion_pensum' => $materia_pensum['descripcion'],
                            'semestre' => $semestre, // Guardamos el semestre donde se encuentra la materia
                        ];
                    }
                }
            }
        }

        // Pasar los datos a la vista
        return view('admin.homologacionescoordinador.procesohomologacion', compact('solicitud', 'materias_cursadas', 'pensum_autonoma', 'materias_homologables'));
    }
}

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Homologación - Universidad Autónoma del Cauca</title>
    <link href="{{ asset('css/registro/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


</head>

<body>
    <header>
        <h1>Universidad Autónoma del Cauca</h1>
        <p>Proceso de Homologación Académica</p>
    </header>

    <div class="container">
        <div class="progress-container">
            <div class="step active" data-step="1">
                <div class="step-icon">
                    <i class="fa-solid fa-id-badge"></i>
                </div>
                <div class="step-title">Datos Personales</div>
            </div>
            <div class="step" data-step="2">
                <div class="step-icon">
                    <i class="fa-solid fa-landmark"></i>
                </div>
                <div class="step-title">Universidad de Origen</div>
            </div>
            <div class="step" data-step="3">
                <div class="step-icon">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div class="step-title">Programa Académico</div>
            </div>
            <div class="step" data-step="4">
                <div class="step-icon">
                    <i class="fa-solid fa-file-pdf"></i>
                </div>
                <div class="step-title">Documentos</div>
            </div>
            <div class="step" data-step="5">
                <div class="step-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="step-title">Confirmación</div>
            </div>
        </div>

        <form id="homologacion-form">
            <div class="step-content active" id="step-1">
                <form id="formulario">

                    <!-- Tipo de Identificación y Número -->
                    <div class="row">
                        <div class="form-group">
                            <label>Tipo de Identificación:</label>
                            <select id="tipo_identificacion" required>
                                <option value="">Seleccione</option>
                                <option value="TI">Tarjeta de Identidad (TI)</option>
                                <option value="CC">Cédula de Ciudadanía (CC)</option>
                                <option value="TE">Tarjeta de Extranjería (TE)</option>
                            </select>
                            <span class="error-message"></span>
                        </div>

                        <div class="form-group">
                            <label>Número de Identificación:</label>
                            <input type="text" id="numero_identificacion" required>
                            <span class="error-message"></span>
                        </div>
                    </div>

                    <!-- Nombres -->
                    <div class="row">
                        <div class="form-group">
                            <label>Primer Nombre:</label>
                            <input type="text" id="primer_nombre" required>
                            <span class="error-message"></span>
                        </div>

                        <div class="form-group">
                            <label>Segundo Nombre (Opcional):</label>
                            <input type="text" id="segundo_nombre">
                        </div>
                    </div>

                    <!-- Apellidos -->
                    <div class="row">
                        <div class="form-group">
                            <label>Primer Apellido:</label>
                            <input type="text" id="primer_apellido" required>
                            <span class="error-message"></span>
                        </div>

                        <div class="form-group">
                            <label>Segundo Apellido (Opcional):</label>
                            <input type="text" id="segundo_apellido">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label>Correo Electrónico:</label>
                        <input type="email" id="email" required>
                        <span class="error-message"></span>
                    </div>
                    <!-- Teléfono y direccion -->
                    <div class="row">
                        <div class="form-group">
                            <label>Teléfono:</label>
                            <input type="tel" id="telefono" required>
                            <span class="error-message"></span>
                        </div>

                        <div class="form-group">
                            <label>Dirección:</label>
                            <input type="text" id="direccion" required>
                            <span class="error-message"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label>País:</label>
                            <select id="pais" required disabled onchange="cargarDepartamentos()">
                                <option value="Colombia">Colombia</option>
                            </select><br>
                        </div>
                        <div class="form-group">
                            <label>Departamento:</label>
                            <select id="departamento" required onchange="updateMunicipios()">
                                <option value="">Seleccione un departamento</option>
                            </select>
                            <span class="error-message"></span>
                        </div>

                        <div class="form-group">
                            <label>Municipio:</label>
                            <select id="municipio" required>
                                <option value="">Seleccione un municipio</option>
                            </select>
                            <span class="error-message"></span>
                        </div>
                    </div>
                    <div class="btn-container">
                        <button type="button" class="next-button" onclick="validarFormularioStep1(1)">Siguiente</button>
                    </div>

                </form>
            </div>
            <!--STEP # 2 DONDE CARGAR LAS UNIVERSIDADES QUE HAY EN EL PAIS-->
            <div class="step-content" id="step-2">
                <h3>Universidad de Origen</h3>

                <div class="row">
                    <!-- Selección de País -->
                    <div class="col-md-4 form-group">
                        <label>País:</label>
                        <select id="pais" class="form-control" required disabled onchange="cargarPaises()">
                            <option value="Colombia">Colombia</option>
                        </select>
                    </div>

                    <!-- Selección de Departamento -->
                    <div class="col-md-4 form-group">
                        <label>Departamento:</label>
                        <select id="departamento" class="form-control" required disabled onchange="cargarCiudades()">
                            <option value="Cauca">Cauca</option>
                        </select>
                    </div>

                    <!-- Selección de Ciudad -->
                    <div class="col-md-4 form-group">
                        <label>Ciudad:</label>
                        <select id="ciudad" class="form-control" required disabled onchange="cargarUniversidades()">
                            <option value="Popayán">Popayán</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <!-- Institución de origen -->
                    <div class="col-md-4 form-group">
                        <label>Institución de origen:</label>
                        <select id="institucion" class="form-control" required onchange="updateFormacion()">
                            <option value="">Seleccione</option>
                            <option value="SENA">SENA</option>
                            <option value="FUP">FUP</option>
                            <option value="Colegio Mayor">Colegio Mayor</option>
                        </select>
                    </div>

                    <!-- Tipo de formación -->
                    <div class="col-md-4 form-group">
                        <label>Tipo de formación:</label>
                        <select id="tipo_formacion" class="form-control" required onchange="updateCarreras()">
                            <option value="">Seleccione</option>
                        </select>
                    </div>

                    <!-- Carrera / Programa -->
                    <div class="col-md-4 form-group">
                        <label>Carrera / Programa:</label>
                        <select id="carrera" class="form-control" required>
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <!-- ¿Finalizó estudios? -->
                    <div class="col-md-4 form-group">
                        <label>¿Finalizó sus estudios?</label>
                        <select id="finalizo_estudios" class="form-control" required
                            onchange="toggleFechaFinalizacion()">
                            <option value="">Seleccione</option>
                            <option value="si">Sí</option>
                            <option value="no">No</option>
                        </select>
                    </div>

                    <!-- Fecha de Finalización (Se muestra si selecciona "Sí") -->
                    <div class="col-md-4 form-group" id="fecha_finalizacion_container" style="display: none;">
                        <label>Fecha de Finalización:</label>
                        <input type="date" id="fecha_finalizacion" class="form-control" required>
                    </div>

                    <!-- Fecha del Último Semestre Cursado (Se muestra si selecciona "No") -->
                    <div class="col-md-4 form-group" id="fecha_ultimo_semestre_container" style="display: none;">
                        <label>Fecha del Último Semestre Cursado:</label>
                        <input type="date" id="fecha_ultimo_semestre" class="form-control" required>
                    </div>
                </div>

                <div class="btn-container">
                    <button class="prev-button" onclick="changeStep(-1, event)">Anterior</button>
                    <button class="next-button" onclick="changeStep(1, event)">Siguiente</button>
                </div>
            </div>

            <!--STEP 3-->

            <div class="step-content" id="step-3">
                <h2>Registro de Notas</h2>

                <div class="form-group">
                    <label for="semestre">Semestre:</label>
                    <select id="semestre">
                        <option value="">Seleccione un semestre</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="materia">Materia:</label>
                    <select id="materia">
                        <option value="">Seleccione una materia</option>
                    </select>
                </div>

                <button class="add-button" onclick="agregarMateria()"><i class="fa-solid fa-plus"></i> Agregar
                    Materia</button>

                <h3>Materias Seleccionadas</h3>
                <div id="materias-container"></div>
                <div class="btn-container">

                    <button class="prev-button" onclick="changeStep(-1, event)">Anterior</button>
                    <button class="next-button" onclick="changeStep(1, event)">Siguiente</button>
                </div>
            </div>



            <div class="step-content" id="step-4">
                <h2>Documentos</h2>

                <label>Documento de Identidad:</label>
                <input type="file" id="documento_id" accept=".pdf" required><br>

                <label>Certificado de Notas:</label>
                <input type="file" id="certificado_notas" accept=".pdf" required><br>

                <label>Contenido Programático:</label>
                <input type="file" id="contenido_programatico" accept=".pdf" required><br>

                <label>Carta Solicitud de Homologación:</label>
                <input type="file" id="carta_homologacion" accept=".pdf" required><br>

                <label>Certificación de Finalización de Estudios (Opcional):</label>
                <input type="file" id="certificacion_finalizacion" accept=".pdf"><br>

                <!-- Documentos adicionales para extranjeros (se mostrarán si aplica) -->
                <div id="extra-docs" class="hidden">
                    <h3>Documentos adicionales para extranjeros</h3>

                    <label>Copia de la Visa o Pasaporte:</label>
                    <input type="file" id="visa_pasaporte" accept=".pdf"><br>
                </div>
                <div class="btn-container">
                    <button type="button" class="prev-button" onclick="changeStep(-1)">Anterior</button>
                    <button type="button" class="next-button" onclick="changeStep(1)">Siguiente</button>
                </div>

            </div>


            <div class="step-content" id="step-5">
                <h2>Confirmación</h2>

                <p>Por favor, revisa tus datos antes de enviar la solicitud.</p>
                <div class="btn-container">

                    <button type="button" class="prev-button" onclick="changeStep(-1)">Anterior</button>
                    <button type="submit" class="submit-button" onclick="confirmarDatos()">Enviar</button>
                </div>
            </div>
        </form>
    </div>
    <script src="{{ asset('js/registro/app.js') }}"></script>
</body>

</html>

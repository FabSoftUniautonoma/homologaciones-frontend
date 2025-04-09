<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Homologación - Universidad Autónoma del Cauca</title>
    <link rel="stylesheet" href="{{ asset('css/registro/princi.css') }}">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <!-- Header -->
    <header class="full-width header">
        <div class="container header-container">
            <div class="logo">
                <img src="https://buscacarrera.com.co/public/content/logos/estandar/corporacion-universitaria-autonoma-del-cauca_550x420.jpg"
                    alt="Logo Universidad Autónoma del Cauca">
                <h1>Universidad Autónoma del Cauca</h1>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#proceso">Proceso</a></li>
                    <li><a href="#requisitos">Requisitos</a></li>
                    <li><a href="#contacto">Contacto</a></li>
                    <li><a href="Login.html" id="login-btn">Iniciar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Banner -->
    <section class="banner" id="inicio">
        <div class="banner-content">
            <h2>Sistema de Homologación Académica</h2>
            <p>Tu camino hacia la excelencia académica en la Universidad Autónoma del Cauca</p>
            <a href="homoformulario.html" class="btn">Comenzar Proceso</a>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container">
        <!-- Process Overview -->
        <section id="proceso">
            <h2 class="section-title">Proceso de Homologación</h2>
            <div class="process-steps">
                <div class="step animate-fade-in">
                    <div class="step-number">1</div>
                    <h3>Registro en el Sistema</h3>
                    <p>Complete el formulario de registro para obtener acceso al sistema de homologación.</p>
                    <a href="homoformulario.html" class="btn">Registrarse</a>
                </div>
                <div class="step animate-fade-in">
                    <div class="step-number">2</div>
                    <h3>Activación de Cuenta</h3>
                    <p>Recibirá un correo con sus credenciales de acceso para iniciar sesión.</p>
                </div>
                <div class="step animate-fade-in">
                    <div class="step-number">3</div>
                    <h3>Solicitud de Homologación</h3>
                    <p>Complete el formulario de homologación y cargue los documentos requeridos.</p>
                </div>
                <div class="step animate-fade-in">
                    <div class="step-number">4</div>
                    <h3>Revisión Académica</h3>
                    <p>El comité académico revisará su solicitud y la documentación presentada.</p>
                </div>
                <div class="step animate-fade-in">
                    <div class="step-number">5</div>
                    <h3>Notificación de Resultados</h3>
                    <p>Será notificado sobre la aprobación o rechazo de su solicitud de homologación.</p>
                </div>
                <div class="step animate-fade-in">
                    <div class="step-number">6</div>
                    <h3>Formalización</h3>
                    <p>En caso de aprobación, se formalizará el proceso en su registro académico.</p>
                </div>
            </div>
        </section>


        <!-- Homologation Form (Hidden) -->
        <section id="homologacion-section" class="form-section hidden">
            <h2 class="section-title">Solicitud de Homologación</h2>
            <form id="homologacion-form">
                <div class="form-group">
                    <label for="institucion_origen">Institución de origen:</label>
                    <input type="text" id="institucion_origen" required>
                </div>
                <div class="form-group">
                    <label for="programa_origen">Programa académico de origen:</label>
                    <input type="text" id="programa_origen" required>
                </div>
                <div class="form-group">
                    <label for="nivel_academico">Nivel académico:</label>
                    <select id="nivel_academico" required>
                        <option value="">Seleccione...</option>
                        <option value="tecnico">Técnico</option>
                        <option value="tecnologico">Tecnológico</option>
                        <option value="pregrado">Pregrado</option>
                        <option value="especializacion">Especialización</option>
                        <option value="maestria">Maestría</option>
                        <option value="doctorado">Doctorado</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="semestre_cursado">Último semestre cursado:</label>
                    <select id="semestre_cursado" required>
                        <option value="">Seleccione...</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="estado_academico">Estado académico:</label>
                    <select id="estado_academico" required>
                        <option value="">Seleccione...</option>
                        <option value="en_curso">En curso</option>
                        <option value="graduado">Graduado</option>
                        <option value="retirado">Retirado</option>
                    </select>
                </div>

                <h3>Documentación Requerida</h3>
                <div class="form-group">
                    <label>Documento de Identidad:</label>
                    <input type="file" id="documento_id" accept=".pdf" required>
                </div>
                <div class="form-group">
                    <label>Certificado de calificación:</label>
                    <input type="file" id="certificado_calificacion" accept=".pdf" required>
                </div>
                <div class="form-group">
                    <label>Contenido programático:</label>
                    <input type="file" id="contenido_programatico" accept=".pdf" required>
                </div>
                <div class="form-group">
                    <label>Carta solicitud de homologación:</label>
                    <input type="file" id="carta_homologacion" accept=".pdf" required>
                </div>
                <div class="form-group">
                    <label>Certificación de finalización de estudios:</label>
                    <input type="file" id="certificacion_finalizacion" accept=".pdf">
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn">Enviar Solicitud</button>
                </div>
            </form>
        </section>

        <!-- Requirements Section -->
        <section id="requisitos" class="form-section">
            <h2 class="section-title">Requisitos para la Homologación</h2>
            <div class="animate-fade-in">
                <h3>Documentos Generales</h3>
                <ul>
                    <li>Documento de identidad vigente (cédula, tarjeta de identidad, pasaporte o cédula de extranjería)
                    </li>
                    <li>Certificado de calificaciones oficial de la institución de origen</li>
                    <li>Contenido programático o syllabus de cada asignatura a homologar</li>
                    <li>Carta de solicitud de homologación, especificando las asignaturas a homologar</li>
                    <li>Certificación de finalización de estudios (si aplica)</li>
                </ul>

                <h3>Documentos Adicionales para Estudiantes Extranjeros</h3>
                <ul>
                    <li>Apostilla del certificado de calificaciones</li>
                    <li>Copia de la Visa vigente</li>
                    <li>Copia del pasaporte</li>
                </ul>

                <h3>Condiciones para la Homologación</h3>
                <ul>
                    <li>Los créditos académicos de la asignatura a homologar deben ser iguales o superiores a los de la
                        asignatura destino</li>
                    <li>El contenido programático debe tener al menos un 80% de similitud</li>
                    <li>La calificación mínima para considerar la homologación es de 3.5/5.0 o su equivalente</li>
                    <li>Solo se homologan asignaturas cursadas en los últimos 5 años</li>
                    <li>El máximo de créditos a homologar es el 60% del total del programa destino</li>
                </ul>
            </div>
        </section>



        <!-- Contenido previo omitido por brevedad -->

        <!-- Contact Section (continuación) -->
        <section id="contacto">
            <h2 class="section-title">Contáctenos</h2>
            <div class="contact-container">
                <div class="contact-info animate-fade-in">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Dirección</h4>
                            <p>Calle 5 No. 3-85, Campus Principal</p>
                            <p>Popayán, Cauca, Colombia</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Teléfonos</h4>
                            <p>PBX: (602) 8213000</p>
                            <p>Oficina de Homologaciones: Ext. 1245</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Correo Electrónico</h4>
                            <p><a href="mailto:homologaciones@uniautonoma.edu.co">homologaciones@uniautonoma.edu.co</a>
                            </p>
                            <p><a href="mailto:admisiones@uniautonoma.edu.co">admisiones@uniautonoma.edu.co</a>
                            </p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Horario de Atención</h4>
                            <p>Lunes a Viernes: 8:00 AM - 12:00 PM y 2:00 PM - 6:00 PM</p>
                            <p>Sábados: 8:00 AM - 12:00 PM</p>
                        </div>
                    </div>

                    <div class="social-media">
                        <h4>Síguenos en Redes Sociales</h4>
                        <div class="social-icons">
                            <a href="https://www.facebook.com/UniAutonomaDelCauca" class="social-icon"
                                target="_blank">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/UniautonomaC" class="social-icon" target="_blank">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.instagram.com/uniautonoma/" class="social-icon" target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://www.youtube.com/channel/UC-0xd6W79SeyFaC9MxFQdPg" class="social-icon"
                                target="_blank">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="contact-form animate-fade-in">
                    <h3>Envíenos un Mensaje</h3>
                    <form id="contact-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nombre">Nombre Completo*</label>
                                <input type="text" id="nombre" name="nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Correo Electrónico*</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="tel" id="telefono" name="telefono">
                            </div>
                            <div class="form-group">
                                <label for="asunto">Asunto*</label>
                                <input type="text" id="asunto" name="asunto" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="mensaje">Mensaje*</label>
                            <textarea id="mensaje" name="mensaje" rows="5" required></textarea>
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary">Enviar
                                Mensaje</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="map-container animate-fade-in">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3986.066851383618!2d-76.6063723!3d2.4417889!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e300410eb607c65%3A0x614545787e0ce96f!2sCorporaci%C3%B3n%20Universitaria%20Aut%C3%B3noma%20del%20Cauca!5e0!3m2!1ses!2sco!4v1710704256158!5m2!1ses!2sco"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </section>
    </main>
    <!-- Footer -->
    <footer class="full-width footer">
        <div class="container">
            <p>&copy; 2025 Universidad Autónoma del Cauca - Todos los derechos reservados.</p>
        </div>
    </footer>
    <script>
        // Script para animar elementos cuando aparecen en el viewport
        document.addEventListener('DOMContentLoaded', function() {
            const animateElements = document.querySelectorAll('.animate-fade-in');

            function checkIfInView() {
                animateElements.forEach(function(element) {
                    const elementTop = element.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;

                    if (elementTop < windowHeight - 100) {
                        element.style.opacity = '1';
                        element.style.transform = 'translateY(0)';
                    }
                });
            }

            // Asignar animación con delay para que sea secuencial
            animateElements.forEach(function(element, index) {
                element.style.animationDelay = (index * 0.2) + 's';
            });

            // Verificar posición inicial
            checkIfInView();

            // Verificar al hacer scroll
            window.addEventListener('scroll', checkIfInView);

            // Smooth scrolling para los enlaces internos
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;

                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 80, // Ajuste para el header fijo
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Validación básica de formularios
            const contactForm = document.getElementById('contact-form');
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    // Aquí se añadiría la lógica para enviar el formulario
                    alert(
                        '¡Mensaje enviado con éxito! Nos pondremos en contacto con usted lo antes posible.'
                    );
                    this.reset();
                });
            }
        });
    </script>
</body>

</html>

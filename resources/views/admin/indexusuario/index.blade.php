<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema de Homologación - Autónoma del Cauca</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">


</head>


<body>
    <!-- Header -->
    <header class="full-width header">
        <div class="container header-container">
            <div class="logo">
                <img src="https://brilla.com.co/documents/83088/0/CORPORACION+UNIVERSITARIA+AUTONOMA+DEL+CAUCA.png/1f5d0453-bee9-f6ae-4d78-3f5c8759c0db?t=1669067233102E"
                    alt="Logo Universidad Autónoma del Cauca">
            </div>
            <div class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#proceso">Proceso</a></li>
                    <li><a href="#beneficios">Beneficios</a></li>
                    <li><a href="#requisitos">Requisitos</a></li>
                    <li><a href="#faq">Preguntas Frecuentes</a></li>
                    <li><a href="#contacto">Contacto</a></li>
                    <li><a href="{{ route('login') }}" id="login-btn">Iniciar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Banner -->
    <section class="banner" id="inicio">
        <div class="banner-content">
            <h2>Sistema de Homologación Académica</h2>
            <p>Tu camino hacia la excelencia académica en la Autónoma del Cauca</p>
            <a href="{{ route('register') }}" class="btn">Comenzar Proceso</a>

        </div>

    </section>

    <!-- Main Content -->
    <main class="container">
        <!-- Process Overview -->
        <section id="proceso">
            <h2 class="section-title">Proceso de Homologación</h2>
            <div class="process-steps">
                <div class="step" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-number">1</div>
                    <h3>Registro en el Sistema</h3>
                    <p>Complete el formulario de registro para obtener acceso al sistema de homologación.</p>
                    <a href="{{ route('register') }}" class="btn">Registrarse</a>

                </div>
                <div class="step" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-number">2</div>
                    <h3>Activación de Cuenta</h3>
                    <p>Recibirá un correo con sus credenciales de acceso para iniciar sesión.</p>
                </div>
                <div class="step" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-number">3</div>
                    <h3>Solicitud de Homologación</h3>
                    <p>Complete el formulario de homologación y cargue los documentos requeridos.</p>
                </div>
                <div class="step" data-aos="fade-up" data-aos-delay="400">
                    <div class="step-number">4</div>
                    <h3>Revisión Académica</h3>
                    <p>El comité académico revisará su solicitud y la documentación presentada.</p>
                </div>
                <div class="step" data-aos="fade-up" data-aos-delay="500">
                    <div class="step-number">5</div>
                    <h3>Notificación de Resultados</h3>
                    <p>Será notificado sobre la aprobación o rechazo de su solicitud de homologación.</p>
                </div>
                <div class="step" data-aos="fade-up" data-aos-delay="600">
                    <div class="step-number">6</div>
                    <h3>Formalización</h3>
                    <p>En caso de aprobación, se formalizará el proceso en su registro académico.</p>
                </div>
            </div>
        </section>


        <!-- beneficios  -->
        <section id="beneficios" class="benefits-section">
            <h2 class="section-title">Beneficios de la Homologación</h2>
            <div class="benefits-container">
                <div class="benefit-card" data-aos="flip-left" data-aos-delay="100">
                    <div class="benefit-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Ahorro de Tiempo</h3>
                    <p>Reduce significativamente el tiempo necesario para completar tu programa académico al validar
                        asignaturas ya cursadas.</p>
                </div>
                <div class="benefit-card" data-aos="flip-left" data-aos-delay="200">
                    <div class="benefit-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <h3>Ahorro Económico</h3>
                    <p>Disminuye los costos de tu formación académica al no tener que cursar y pagar nuevamente por
                        asignaturas homologadas.</p>
                </div>
                <div class="benefit-card" data-aos="flip-left" data-aos-delay="300">
                    <div class="benefit-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Continuidad Académica</h3>
                    <p>No pierdas el avance académico logrado previamente y continúa tu formación de manera fluida.</p>
                </div>
                <div class="benefit-card" data-aos="flip-left" data-aos-delay="400">
                    <div class="benefit-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3>Proceso Simplificado</h3>
                    <p>Sistema digital que facilita el trámite sin necesidad de desplazamientos y con seguimiento en
                        tiempo real.</p>
                </div>
                <div class="benefit-card" data-aos="flip-left" data-aos-delay="500">
                    <div class="benefit-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3>Orientación Personalizada</h3>
                    <p>Acompañamiento por parte de asesores académicos durante todo el proceso de homologación.</p>
                </div>
                <div class="benefit-card" data-aos="flip-left" data-aos-delay="600">
                    <div class="benefit-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3>Reconocimiento de Saberes</h3>
                    <p>Validación oficial de los conocimientos y competencias adquiridos en otras instituciones
                        educativas.</p>
                </div>
            </div>
        </section>

        <!-- Requerimientos -->
        <section id="requisitos" class="form-section">
            <h2 class="section-title">Requisitos para la Homologación</h2>
            <div data-aos="fade-up">
                <h3><i class="fas fa-file-alt"></i> Documentos Generales</h3>
                <ul>
                    <li>Documento de identidad vigente (cédula, tarjeta de identidad, pasaporte o cédula de extranjería)
                    </li>
                    <li>Certificado de calificaciones oficial de la institución de origen</li>
                    <li>Contenido programático o syllabus de cada asignatura a homologar</li>
                    <li>Carta de solicitud de homologación, especificando las asignaturas a homologar</li>
                    <li>Certificación de finalización de estudios (si aplica)</li>
                </ul>

                <h3><i class="fas fa-globe"></i> Documentos Adicionales para Estudiantes Extranjeros</h3>
                <ul>
                    <li>Apostilla del certificado de calificaciones</li>
                    <li>Copia de la Visa vigente</li>
                    <li>Copia del pasaporte</li>
                </ul>

                <h3><i class="fas fa-list-check"></i> Condiciones para la Homologación</h3>
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

        <!-- FAQ Section -->
        <section id="faq" class="faq-section">
            <h2 class="section-title">Preguntas Frecuentes</h2>
            <div class="accordion">
                <div class="accordion-item" data-aos="fade-up">
                    <div class="accordion-header">
                        <h3>¿Cuánto tiempo toma el proceso de homologación?</h3>
                        <span class="icon"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="accordion-content">
                        <p>El proceso de homologación generalmente toma entre 15 y 30 días hábiles desde la fecha de
                            solicitud completa. Este tiempo puede variar dependiendo de la complejidad de la solicitud,
                            el volumen de asignaturas a homologar y la disponibilidad del comité académico.</p>
                    </div>
                </div>
                <div class="accordion-item" data-aos="fade-up" data-aos-delay="100">
                    <div class="accordion-header">
                        <h3>¿Puedo homologar asignaturas de cualquier institución educativa?</h3>
                        <span class="icon"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="accordion-content">
                        <p>Sí, siempre y cuando la institución de origen esté reconocida por el Ministerio de Educación
                            Nacional o, en caso de instituciones extranjeras, tenga el reconocimiento oficial en su
                            país. Además, los contenidos programáticos deben ser compatibles con nuestros programas
                            académicos.</p>
                    </div>
                </div>
                <div class="accordion-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="accordion-header">
                        <h3>¿Existe un límite de asignaturas que puedo homologar?</h3>
                        <span class="icon"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="accordion-content">
                        <p>Sí, según el reglamento académico, se puede homologar hasta el 60% de los créditos totales
                            del programa académico al que ingresa. Esto equivale aproximadamente a 6-7 semestres
                            dependiendo del programa.</p>
                    </div>
                </div>
                <div class="accordion-item" data-aos="fade-up" data-aos-delay="300">
                    <div class="accordion-header">
                        <h3>¿Qué sucede si mi solicitud de homologación es rechazada?</h3>
                        <span class="icon"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="accordion-content">
                        <p>Si su solicitud es rechazada, recibirá una notificación con los motivos del rechazo. Puede
                            solicitar una reconsideración dentro de los 10 días hábiles siguientes, aportando
                            información adicional que respalde su solicitud. También puede solicitar una reunión con el
                            coordinador académico del programa para recibir orientación.</p>
                    </div>
                </div>
                <div class="accordion-item" data-aos="fade-up" data-aos-delay="400">
                    <div class="accordion-header">
                        <h3>¿Puedo homologar asignaturas cursadas hace varios años?</h3>
                        <span class="icon"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="accordion-content">
                        <p>Como regla general, se consideran para homologación las asignaturas cursadas en los últimos 5
                            años. Sin embargo, en casos especiales donde pueda demostrar experiencia profesional
                            continua en el área, el comité académico puede evaluar excepciones a esta regla.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contacto">
            <h2 class="section-title">Contáctenos</h2>
            <div class="contact-container">
                <div class="contact-info" data-aos="fade-right">
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
                            <p><a href="mailto:admisiones@uniautonoma.edu.co">admisiones@uniautonoma.edu.co</a></p>
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
                            <a href="https://x.com/uniautonomadc" class="social-icon" target="_blank">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.instagram.com/uniautonomadelcauca/?hl=es-la" class="social-icon"
                                target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://www.youtube.com/@autonomadelcauca4125/videos" class="social-icon"
                                target="_blank">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="contact-form" data-aos="fade-left">
                    <h3>Envíenos un Mensaje</h3>
                    <form id="contact-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nombre">Nombre Completo*</label>
                                <input type="text" name="nombre" id="nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Correo Electrónico*</label>
                                <input type="email" name="email" id="email" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="tel" name="telefono" id="telefono">
                            </div>
                            <div class="form-group">
                                <label for="asunto">Asunto*</label>
                                <input type="text" name="asunto" id="asunto" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="mensaje">Mensaje*</label>
                            <textarea name="mensaje" id="mensaje" rows="5" required></textarea>
                        </div>

                        {{-- Campo honeypot oculto --}}
                        <input type="text" name="empresa" id="empresa" style="display:none">

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                <span class="btn-text">Enviar Mensaje</span>
                                <span class="btn-loading" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i> Enviando...
                                </span>
                            </button>
                        </div>
                    </form>

                    <!-- Mensajes de respuesta -->
                    <div id="form-messages" style="margin-top: 20px;"></div>
                </div>

            </div>


            <div class="map-container" data-aos="zoom-in">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3986.066851383618!2d-76.6063723!3d2.4417889!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e300410eb607c65%3A0x614545787e0ce96f!2sCorporaci%C3%B3n%20Universitaria%20Aut%C3%B3noma%20del%20Cauca!5e0!3m2!1ses!2sco!4v1710704256158!5m2!1ses!2sco"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="full-width footer">
        <div class="container">
            <p>&copy; 2025 Autónoma del Cauca - Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
       document.addEventListener('DOMContentLoaded', function() {
   // Obtener token CSRF - definido al inicio para que esté disponible globalmente
   const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

   // Inicializar AOS (Animate on Scroll)
   AOS.init({
       duration: 800,
       easing: 'ease-in-out',
       once: true,
       mirror: false
   });

   // Efecto al hacer scroll en el header
   const header = document.querySelector('.header');
   if (header) {
       window.addEventListener('scroll', function() {
           if (window.scrollY > 100) {
               header.classList.add('scrolled');
           } else {
               header.classList.remove('scrolled');
           }
       });
   }

   // Menú móvil
   const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
   const navLinks = document.querySelector('.nav-links');
   if (mobileMenuBtn && navLinks) {
       mobileMenuBtn.addEventListener('click', function() {
           navLinks.classList.toggle('active');
       });
   }

   // Desplazamiento suave para enlaces internos
   document.querySelectorAll('a[href^="#"]').forEach(anchor => {
       anchor.addEventListener('click', function(e) {
           e.preventDefault();
           const targetId = this.getAttribute('href');
           if (targetId === '#') return;
           const targetElement = document.querySelector(targetId);
           if (targetElement) {
               if (navLinks && navLinks.classList.contains('active')) {
                   navLinks.classList.remove('active');
               }
               window.scrollTo({
                   top: targetElement.offsetTop - 80,
                   behavior: 'smooth'
               });
           }
       });
   });

   // Acordeón de preguntas frecuentes (FAQ)
   const accordionHeaders = document.querySelectorAll('.accordion-header');
   accordionHeaders.forEach(header => {
       header.addEventListener('click', function() {
           this.classList.toggle('active');
           const content = this.nextElementSibling;
           if (this.classList.contains('active')) {
               content.classList.add('active');
               content.style.height = content.scrollHeight + 'px';
           } else {
               content.classList.remove('active');
               content.style.height = '0';
           }

           // Cerrar otros acordeones
           accordionHeaders.forEach(otherHeader => {
               if (otherHeader !== this) {
                   otherHeader.classList.remove('active');
                   otherHeader.nextElementSibling.classList.remove('active');
                   otherHeader.nextElementSibling.style.height = '0';
               }
           });
       });
   });

   // Función para mostrar mensajes
   function showMessage(type, message) {
       const formMessages = document.getElementById('form-messages');
       if (!formMessages) return;

       const messageClass = type === 'success' ? 'alert-success' : 'alert-danger';
       formMessages.innerHTML = `
           <div class="alert ${messageClass}" style="
               padding: 15px;
               border-radius: 8px;
               margin-top: 15px;
               background-color: ${type === 'success' ? '#d4edda' : '#f8d7da'};
               border: 1px solid ${type === 'success' ? '#c3e6cb' : '#f5c6cb'};
               color: ${type === 'success' ? '#155724' : '#721c24'};
               animation: fadeIn 0.3s ease-in;
           ">
               <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'}"></i>
               ${message}
           </div>
       `;

       formMessages.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

       if (type === 'success') {
           setTimeout(() => {
               if (formMessages) formMessages.innerHTML = '';
           }, 5000);
       }
   }

   // Manejo del formulario de contacto con AJAX
   const contactForm = document.getElementById('contact-form');

   if (contactForm) {
       contactForm.addEventListener('submit', async function(e) {
           e.preventDefault();

           const submitBtn = document.getElementById('submit-btn');
           const btnText = document.querySelector('.btn-text');
           const btnLoading = document.querySelector('.btn-loading');
           const formMessages = document.getElementById('form-messages');

           // Mostrar estado de carga
           if (submitBtn) {
               submitBtn.disabled = true;
               if (btnText) btnText.style.display = 'none';
               if (btnLoading) btnLoading.style.display = 'inline-block';
           }
           if (formMessages) formMessages.innerHTML = '';

           // Recopilar datos del formulario
           const formData = new FormData();

           const nombre = document.getElementById('nombre');
           const email = document.getElementById('email');
           const telefono = document.getElementById('telefono');
           const asunto = document.getElementById('asunto');
           const mensaje = document.getElementById('mensaje');
           const empresa = document.getElementById('empresa');

           if (nombre) formData.append('nombre', nombre.value);
           if (email) formData.append('email', email.value);
           if (telefono) formData.append('telefono', telefono.value);
           if (asunto) formData.append('asunto', asunto.value);
           if (mensaje) formData.append('mensaje', mensaje.value);
           if (empresa) formData.append('empresa', empresa.value);

           try {
               const response = await fetch('http://127.0.0.1:8000/api/contacto', {
                   method: 'POST',
                   body: formData,
                   headers: {
                       'X-Requested-With': 'XMLHttpRequest',
                       ...(csrfToken && { 'X-CSRF-TOKEN': csrfToken })
                   }
               });

               const data = await response.json();

               if (response.ok) {
                   showMessage('success', data.message || '¡Mensaje enviado con éxito! Nos pondremos en contacto con usted lo antes posible.');
                   contactForm.reset();
               } else {
                   if (data.errors) {
                       let errorMessages = '';
                       for (const field in data.errors) {
                           errorMessages += `<li>${data.errors[field][0]}</li>`;
                       }
                       showMessage('error', `<ul style="margin: 0; padding-left: 20px;">${errorMessages}</ul>`);
                   } else {
                       showMessage('error', data.error || 'Ha ocurrido un error. Por favor, inténtelo de nuevo.');
                   }
               }
           } catch (error) {
               console.error('Error completo:', error);
               showMessage('error', 'Error de conexión. Por favor, verifique su conexión a internet e inténtelo de nuevo.');
           } finally {
               // Restaurar estado del botón
               if (submitBtn) {
                   submitBtn.disabled = false;
                   if (btnText) btnText.style.display = 'inline-block';
                   if (btnLoading) btnLoading.style.display = 'none';
               }
           }
       });
   }
});
    </script>

</body>

</html>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Homologación -  Autónoma del Cauca</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

</head>
<style>
    /* Estilos principales - Sistema de Homologación Universidad Autónoma del Cauca */
    /* Estilo avanzado con animaciones, transiciones y efectos de scroll */

    /* ===== FUENTES Y VARIABLES GLOBALES ===== */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap');

    :root {
        --primary: #003366;
        --primary-light: #0055a4;
        --secondary: #f39c12;
        --secondary-light: #f8c471;
        --accent: #e74c3c;
        --text-dark: #2c3e50;
        --text-light: #ecf0f1;
        --bg-light: #f9f9f9;
        --bg-dark: #34495e;
        --success: #27ae60;
        --warning: #f1c40f;
        --danger: #c0392b;
        --white: #ffffff;
        --gray-100: #f8f9fa;
        --gray-200: #e9ecef;
        --gray-300: #dee2e6;
        --gray-400: #ced4da;
        --gray-500: #adb5bd;
        --gray-600: #6c757d;
        --gray-700: #495057;
        --gray-800: #343a40;
        --gray-900: #212529;
        --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        --shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        --transition: all 0.3s ease;
        --transition-slow: all 0.5s ease;
        --border-radius: 0.5rem;
        --border-radius-sm: 0.25rem;
        --border-radius-lg: 1rem;
    }

    /* ===== RESET Y ESTILOS BASE ===== */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html {
        scroll-behavior: smooth;
        font-size: 16px;
        scroll-padding-top: 80px;
    }

    body {
        font-family: 'Poppins', sans-serif;
        line-height: 1.6;
        color: var(--text-dark);
        background-color: var(--bg-light);
        overflow-x: hidden;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        line-height: 1.3;
        color: var(--primary);
        margin-bottom: 1rem;
    }

    a {
        text-decoration: none;
        color: var(--primary);
        transition: var(--transition);
    }

    a:hover {
        color: var(--primary-light);
    }

    p {
        margin-bottom: 1.5rem;
    }

    img {
        max-width: 100%;
        height: auto;
    }

    button,
    .btn {
        cursor: pointer;
        outline: none;
        border: none;
    }

    ul {
        list-style: none;
    }

    .container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    .full-width {
        width: 100%;
    }

    .section-title {
        text-align: center;
        font-size: 2.5rem;
        margin-bottom: 3rem;
        position: relative;
        padding-bottom: 1rem;
        background: linear-gradient(to right, var(--primary), var(--primary-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0 5px 15px rgba(0, 51, 102, 0.1);
    }

    .section-title::after {
        content: '';
        position: absolute;
        left: 50%;
        bottom: 0;
        width: 100px;
        height: 4px;
        background: linear-gradient(to right, var(--primary), var(--secondary));
        transform: translateX(-50%);
        border-radius: 2px;
    }

    section {
        padding: 5rem 0;
        overflow: hidden;
        position: relative;
    }

    section:nth-child(even) {
        background-color: var(--gray-100);
    }

    /* ===== EFECTOS DE PARTÍCULAS Y FONDO ===== */
    .particles-js {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: -1;
        opacity: 0.5;
    }

    /* ===== HEADER Y NAVEGACIÓN ===== */
    .header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background-color: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        z-index: 1000;
        padding: 1rem 0;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
    }

    .header-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header.scrolled {
        padding: 0.5rem 0;
        background-color: rgba(255, 255, 255, 0.95);
        box-shadow: var(--shadow);
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .logo img {
        height: 60px;
        width: auto;
        transition: var(--transition);
        filter: drop-shadow(0 4px 4px rgba(0, 0, 0, 0.1));
    }

    .header.scrolled .logo img {
        height: 50px;
    }

    .logo h1 {
        font-size: 1.5rem;
        margin-bottom: 0;
        transition: var(--transition);
        background: linear-gradient(to right, var(--primary), var(--primary-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .header.scrolled .logo h1 {
        font-size: 1.3rem;
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .nav-links li a {
        position: relative;
        color: var(--text-dark);
        font-weight: 500;
        padding: 0.25rem 0;
    }

    .nav-links li a::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -2px;
        width: 0;
        height: 2px;
        background-color: var(--primary);
        transition: var(--transition);
    }

    .nav-links li a:hover::after,
    .nav-links li a:focus::after {
        width: 100%;
    }

    .mobile-menu-btn {
        display: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--primary);
        transition: var(--transition);
    }

    .mobile-menu-btn:hover {
        color: var(--primary-light);
    }

    /* Botón de inicio de sesión moderno y limpio */
    #login-btn {
        display: inline-block;
        padding: 0.75rem 2rem;
        font-size: 1rem;
        font-weight: 600;
        color: var(--white);
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border: none;
        border-radius: 8px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        z-index: 1;
        transition: all 0.3s ease-in-out;
        text-align: center;
        line-height: 1.5;
    }

    /* Fondo animado en hover */
    #login-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--primary-light), var(--primary));
        transition: left 0.4s ease-in-out;
        z-index: -1;
    }

    /* Efecto hover que desplaza el fondo */
    #login-btn:hover::before {
        left: 0;
    }

    /* Sombra sutil en hover */
    #login-btn:hover {
        box-shadow: 0 8px 16px rgba(0, 51, 102, 0.2);
        transform: translateY(-2px);
    }


    /* ===== BANNER ===== */
    .banner {
        height: 100vh;
        min-height: 600px;
        background: linear-gradient(135deg, rgba(0, 51, 102, 0.9), rgba(0, 85, 164, 0.8)), url('/api/placeholder/1920/1080') center/cover no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: var(--white);
        position: relative;
        overflow: hidden;
        padding-top: 80px;
    }

    /* Efecto de onda en el banner */
    .wave-separator {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        overflow: hidden;
        line-height: 0;
    }

    .wave-separator svg {
        position: relative;
        display: block;
        width: calc(100% + 1.3px);
        height: 150px;
    }

    .wave-separator .shape-fill {
        fill: var(--white);
    }

    /* Contenido del banner */
    .banner-content {
        max-width: 800px;
        padding: 2rem;
        z-index: 1;
        animation: fadeInUp 1s ease-out;
    }

    .banner-content h2 {
        font-size: 3.5rem;
        margin-bottom: 1.5rem;
        color: var(--white);
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    .banner-content p {
        font-size: 1.5rem;
        margin-bottom: 2rem;
        text-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    /* Botón principal con efecto */
    .btn {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--secondary), var(--secondary-light));
        color: var(--text-dark);
        font-weight: 600;
        border-radius: var(--border-radius);
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        z-index: 1;
        box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3);
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--secondary-light), var(--secondary));
        transition: var(--transition-slow);
        z-index: -1;
    }

    .btn:hover::before {
        left: 0;
    }

    .btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(243, 156, 18, 0.4);
    }

    /* Flecha de desplazamiento */
    .scroll-down {
        position: absolute;
        bottom: 170px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 2;
        animation: bounce 2s infinite;
    }

    .scroll-down a {
        color: var(--white);
        font-size: 2rem;
        display: block;
        transition: var(--transition);
    }

    .scroll-down a:hover {
        color: var(--secondary);
        transform: scale(1.2);
    }

    /* ===== PROCESO DE HOMOLOGACIÓN ===== */
    .process-steps {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .step {
        background-color: var(--white);
        border-radius: var(--border-radius);
        padding: 2rem;
        text-align: center;
        box-shadow: var(--shadow);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .step::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(to right, var(--primary), var(--primary-light));
        transition: var(--transition);
    }

    .step:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-lg);
    }

    .step:hover::before {
        height: 10px;
    }

    .step-number {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: var(--white);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 auto 1.5rem;
        position: relative;
        z-index: 1;
        box-shadow: 0 5px 15px rgba(0, 51, 102, 0.3);
    }

    .step-number::before {
        content: '';
        position: absolute;
        top: -5px;
        left: -5px;
        right: -5px;
        bottom: -5px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary));
        z-index: -1;
        border-radius: 50%;
        opacity: 0.3;
        animation: pulse 2s infinite;
    }

    /* ===== CAROUSEL ===== */
    .carousel-section {
        padding: 5rem 0;
        background: linear-gradient(135deg, var(--gray-100), var(--gray-200));
    }

    .carousel-container {
        position: relative;
        overflow: hidden;
        padding: 2rem 1rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .carousel-track {
        display: flex;
        gap: 1.5rem;
        transition: transform 0.5s ease-in-out;
        height: auto;
    }

    .carousel-slide {
        flex: 0 0 100%;
        max-width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        text-align: center;
        background-color: var(--white);
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow);
        transition: var(--transition);
    }

    .carousel-slide:hover {
        transform: scale(1.02);
        box-shadow: var(--shadow-lg);
    }

    .carousel-slide img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--white);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    .carousel-slide h3 {
        font-size: 1.25rem;
        margin-bottom: 0.3rem;
        font-weight: 600;
    }

    .student-program {
        color: var(--primary);
        font-weight: 600;
        margin-bottom: 1rem;
        font-style: italic;
    }

    .carousel-slide p {
        font-size: 0.95rem;
        line-height: 1.5;
        color: var(--gray-700);
    }

    /* Botones */
    .carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: var(--white);
        color: var(--primary);
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        box-shadow: var(--shadow);
        transition: var(--transition);
        z-index: 2;
    }

    .carousel-btn:hover {
        background-color: var(--primary);
        color: var(--white);
        transform: translateY(-50%) scale(1.1);
    }

    .carousel-btn.prev {
        left: -15px;
    }

    .carousel-btn.next {
        right: -15px;
    }

    /* Indicadores */
    .carousel-indicators {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        margin-top: 2rem;
    }

    .indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: var(--gray-400);
        cursor: pointer;
        transition: 0.3s ease;
    }

    .indicator.active {
        background-color: var(--primary);
        transform: scale(1.2);
    }

    /* RESPONSIVE MULTISLIDE */
    @media (min-width: 768px) {
        .carousel-slide {
            flex: 0 0 calc(50% - 1rem);
            max-width: calc(50% - 1rem);
        }
    }

    @media (min-width: 1024px) {
        .carousel-slide {
            flex: 0 0 calc(33.333% - 1rem);
            max-width: calc(33.333% - 1rem);
        }
    }


    /* ===== BENEFICIOS ===== */
    .benefits-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .benefit-card {
        background-color: var(--white);
        border-radius: var(--border-radius);
        padding: 2rem;
        text-align: center;
        box-shadow: var(--shadow);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .benefit-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 0;
        background: linear-gradient(135deg, var(--primary-light), var(--primary));
        opacity: 0.03;
        transition: var(--transition);
        z-index: -1;
    }

    .benefit-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-lg);
    }

    .benefit-card:hover::before {
        height: 100%;
    }

    .benefit-icon {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: var(--white);
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 1.5rem;
        position: relative;
        z-index: 1;
        box-shadow: 0 5px 15px rgba(0, 51, 102, 0.3);
        transition: var(--transition);
    }

    .benefit-card:hover .benefit-icon {
        transform: rotateY(360deg);
        background: linear-gradient(135deg, var(--secondary), var(--secondary-light));
    }

    /* ===== REQUISITOS ===== */
    .form-section {
        background-color: var(--white);
        border-radius: var(--border-radius-lg);
        padding: 3rem;
        box-shadow: var(--shadow);
    }

    .form-section h3 {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 1.5rem;
        margin-top: 2rem;
        color: var(--primary);
        border-bottom: 2px solid var(--primary-light);
        padding-bottom: 0.5rem;
    }

    .form-section h3 i {
        color: var(--primary);
        font-size: 1.75rem;
    }

    .form-section ul {
        margin: 1.5rem 0 2rem;
        padding-left: 2rem;
    }

    .form-section li {
        position: relative;
        margin-bottom: 1rem;
        padding-left: 1.5rem;
        line-height: 1.6;
    }

    .form-section li::before {
        content: '•';
        color: var(--primary);
        font-size: 1.5rem;
        position: absolute;
        left: 0;
        top: -0.2rem;
    }

    /* ===== FAQ ===== */
    .faq-section {
        background: linear-gradient(135deg, var(--gray-100), var(--gray-200));
    }

    .accordion {
        max-width: 900px;
        margin: 0 auto;
    }

    .accordion-item {
        background-color: var(--white);
        border-radius: var(--border-radius);
        margin-bottom: 1rem;
        overflow: hidden;
        box-shadow: var(--shadow);
    }

    .accordion-header {
        padding: 1.5rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: var(--white);
        transition: var(--transition);
    }

    .accordion-header:hover {
        background-color: var(--gray-100);
    }

    .accordion-header h3 {
        font-size: 1.25rem;
        margin-bottom: 0;
    }

    .accordion-header .icon {
        font-size: 1.25rem;
        transition: var(--transition);
        color: var(--primary);
    }

    .accordion-header.active .icon {
        transform: rotate(180deg);
    }

    .accordion-content {
        height: 0;
        overflow: hidden;
        transition: height 0.3s ease-in-out;
    }

    .accordion-content p {
        padding: 0 1.5rem 1.5rem;
    }

    /* ===== CONTACTO ===== */
    .contact-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-bottom: 3rem;
    }

    .contact-info {
        background-color: var(--white);
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow);
    }

    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .contact-icon {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: var(--white);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .contact-details h4 {
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }

    .contact-details p {
        margin-bottom: 0.5rem;
    }

    .social-media {
        margin-top: 2rem;
    }

    .social-icons {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .social-icon {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: var(--white);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        transition: var(--transition);
    }

    .social-icon:hover {
        transform: translateY(-5px) rotate(10deg);
        filter: brightness(1.2);
    }

    /* Contact Form */
    .contact-form {
        background-color: var(--white);
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow);
    }

    .contact-form h3 {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--gray-300);
        border-radius: var(--border-radius-sm);
        outline: none;
        transition: var(--transition);
    }

    .form-group input:focus,
    .form-group textarea:focus {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 2px rgba(0, 85, 164, 0.2);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: var(--white);
    }

    .map-container {
        border-radius: var(--border-radius);
        overflow: hidden;
        height: 400px;
        box-shadow: var(--shadow);
    }

    .map-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    /* ===== FOOTER ===== */
    .footer {
        background-color: var(--bg-dark);
        color: var(--text-light);
        padding: 1.5rem 0;
        text-align: center;
    }

    /* ===== ANIMACIONES ===== */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 0.5;
        }

        50% {
            transform: scale(1.1);
            opacity: 0.3;
        }

        100% {
            transform: scale(1);
            opacity: 0.5;
        }
    }

    @keyframes bounce {

        0%,
        20%,
        50%,
        80%,
        100% {
            transform: translateY(0) translateX(-50%);
        }

        40% {
            transform: translateY(-20px) translateX(-50%);
        }

        60% {
            transform: translateY(-10px) translateX(-50%);
        }
    }

    /* Animación para elementos cuando aparecen en viewport */
    [data-aos="custom-fade-up"] {
        opacity: 0;
        transform: translateY(50px);
        transition: opacity 0.8s ease, transform 0.8s ease;
    }

    [data-aos="custom-fade-up"].aos-animate {
        opacity: 1;
        transform: translateY(0);
    }

    /* Efecto de desplazamiento de fondo */
    .bg-animated {
        background: linear-gradient(-45deg, var(--primary), var(--primary-light), var(--secondary), var(--secondary-light));
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
    }

    @keyframes gradientBG {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    /* ===== EFECTOS DE SCROLL ===== */
    .reveal-on-scroll {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease;
    }

    .reveal-on-scroll.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Efecto typing */
    .typing-effect {
        overflow: hidden;
        border-right: 0.15em solid var(--primary);
        white-space: nowrap;
        animation: typing 3.5s steps(40, end), blink-caret 0.75s step-end infinite;
    }

    @keyframes typing {
        from {
            width: 0
        }

        to {
            width: 100%
        }
    }

    @keyframes blink-caret {

        from,
        to {
            border-color: transparent
        }

        50% {
            border-color: var(--primary)
        }
    }

    /* Zoom Effect */
    .zoom-on-hover {
        transition: transform 0.5s ease;
    }

    .zoom-on-hover:hover {
        transform: scale(1.05);
    }

    /* Floating Animation */
    .floating {
        animation: floating 3s ease-in-out infinite;
    }

    @keyframes floating {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }

        100% {
            transform: translateY(0px);
        }
    }

    /* Shine Effect */
    .shine-effect {
        position: relative;
        overflow: hidden;
    }

    .shine-effect::before {
        content: '';
        position: absolute;
        top: 0;
        left: -75%;
        width: 50%;
        height: 100%;
        background: linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.3) 100%);
        transform: skewX(-25deg);
        transition: all 0.75s;
    }

    .shine-effect:hover::before {
        animation: shine 1s;
    }

    @keyframes shine {
        100% {
            left: 125%;
        }
    }

    /* Particle Effect */
    .particle-background {
        position: relative;
        overflow: hidden;
    }

    .particle {
        position: absolute;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.3);
        animation: particle 15s infinite linear;
    }

    @keyframes particle {
        0% {
            transform: translateY(0) translateX(0);
            opacity: 0;
        }

        10% {
            opacity: 1;
        }

        90% {
            opacity: 1;
        }

        100% {
            transform: translateY(-100vh) translateX(100vw);
            opacity: 0;
        }
    }

    /* ===== RESPONSIVE STYLES ===== */
    @media (max-width: 1200px) {
        .container {
            max-width: 960px;
        }

        .banner-content h2 {
            font-size: 3rem;
        }
    }

    @media (max-width: 992px) {
        html {
            font-size: 15px;
        }

        .container {
            max-width: 720px;
        }

        .banner-content h2 {
            font-size: 2.5rem;
        }

        .contact-container {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        html {
            font-size: 14px;
        }

        .container {
            max-width: 540px;
        }

        .banner-content h2 {
            font-size: 2.2rem;
        }

        .nav-links {
            position: fixed;
            top: 80px;
            left: -100%;
            width: 100%;
            height: calc(100vh - 80px);
            background-color: var(--white);
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-top: 2rem;
            gap: 2rem;
            transition: var(--transition);
            box-shadow: var(--shadow);
            z-index: 999;
        }

        .nav-links.active {
            left: 0;
        }

        .mobile-menu-btn {
            display: block;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .carousel-btn.prev {
            left: 10px;
        }

        .carousel-btn.next {
            right: 10px;
        }
    }

    @media (max-width: 576px) {
        .container {
            padding: 0 1rem;
        }

        .logo h1 {
            font-size: 1.2rem;
        }

        .banner-content {
            padding: 1rem;
        }

        .banner-content h2 {
            font-size: 1.8rem;
        }

        .banner-content p {
            font-size: 1.2rem;
        }

        .section-title {
            font-size: 2rem;
        }
    }

    /* ===== SCROLL REVEAL ANIMATIONS ===== */
    /* Fade Up */
    .sr-fade-up {
        visibility: hidden;
    }

    /* Fade Right */
    .sr-fade-right {
        visibility: hidden;
    }

    /* Fade Left */
    .sr-fade-left {
        visibility: hidden;
    }

    /* Zoom In */
    .sr-zoom-in {
        visibility: hidden;
    }

    /* Flip X */
    .sr-flip-x {
        visibility: hidden;
    }

    /* Flip Y */
    .sr-flip-y {
        visibility: hidden;
    }

    /* ===== ADVANCED ANIMATIONS ===== */
    /* Parallax Scrolling Effect */
    .parallax-bg {
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        position: relative;
        min-height: 400px;
    }

    /* Mouse Follow Effect */
    .mouse-follow {
        position: fixed;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: rgba(0, 51, 102, 0.2);
        pointer-events: none;
        transform: translate(-50%, -50%);
        transition: transform 0.1s ease;
        z-index: 9999;
        backdrop-filter: blur(5px);
        display: none;
    }

    /* 3D Button */
    .btn-3d {
        transform-style: preserve-3d;
        perspective: 1000px;
    }

    .btn-3d:hover {
        transform: rotateX(10deg) translateY(-5px);
    }

    /* Text Glitch Effect */
    .glitch {
        position: relative;
    }

    .glitch::before,
    .glitch::after {
        content: attr(data-text);
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0.8;
    }

    .glitch::before {
        color: #00ffff;
        animation: glitch-effect 0.3s infinite;
        clip: rect(44px, 450px, 56px, 0);
        transform: skew(0.1deg);
    }

    .glitch::after {
        color: #ff00ff;
        animation: glitch-effect 0.3s infinite alternate-reverse;
        clip: rect(44px, 450px, 56px, 0);
        transform: skew(0.1deg);
    }

    @keyframes glitch-effect {
        0% {
            clip: rect(44px, 450px, 56px, 0);
        }

        5% {
            clip: rect(6px, 450px, 71px, 0);
        }

        10% {
            clip: rect(57px, 450px, 16px, 0);
        }

        15% {
            clip: rect(60px, 450px, 78px, 0);
        }

        20% {
            clip: rect(79px, 450px, 70px, 0);
        }

        25% {
            clip: rect(25px, 450px, 11px, 0);
        }

        30% {
            clip: rect(27px, 450px, 45px, 0);
        }

        35% {
            clip: rect(5px, 450px, 34px, 0);
        }

        40% {
            clip: rect(66px, 450px, 76px, 0);
        }

        45% {
            clip: rect(67px, 450px, 65px, 0);
        }

        50% {
            clip: rect(30px, 450px, 84px, 0);
        }

        55% {
            clip: rect(6px, 450px, 63px, 0);
        }

        60% {
            clip: rect(46px, 450px, 3px, 0);
        }

        65% {
            clip: rect(46px, 450px, 30px, 0);
        }

        70% {
            clip: rect(39px, 450px, 30px, 0);
        }

        75% {
            clip: rect(38px, 450px, 93px, 0);
        }

        80% {
            clip: rect(44px, 450px, 93px, 0);
        }

        85% {
            clip: rect(82px, 450px, 61px, 0);
        }

        90% {
            clip: rect(67px, 450px, 75px, 0);
        }

        95% {
            clip: rect(5px, 450px, 69px, 0);
        }

        100% {
            clip: rect(8px, 450px, 2px, 0);
        }
    }

    /* Liquid Button Effect */
    .liquid-btn {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .liquid-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 300px;
        height: 300px;
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 40%;
        transform: translate3d(-50%, -50%, 0) scale(0);
        transition: transform 0.5s ease;
    }

    .liquid-btn:hover::before {
        transform: translate3d(-50%, -50%, 0) scale(1.5);
    }

    /* Magnetic Button */
    .magnetic-btn {
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    /* Tilt Card Effect */
    .tilt-card {
        transform-style: preserve-3d;
        transform: perspective(1000px);
        transition: transform 0.1s;
    }

    .tilt-card-inner {
        transform-style: preserve-3d;
    }

    .tilt-card-content {
        transform: translateZ(20px);
    }

    /* Bubble Button */
    .bubble-btn {
        position: relative;
        overflow: hidden;
    }

    .bubble-btn::before {
        content: '';
        position: absolute;
        left: var(--x, 50%);
        top: var(--y, 50%);
        width: 0;
        height: 0;
        background: radial-gradient(circle closest-side, rgba(255, 255, 255, 0.3), transparent);
        transform: translate(-50%, -50%);
        transition: width 0.5s ease, height 0.5s ease;
    }

    .bubble-btn:hover::before {
        width: 200%;
        height: 200%;
    }

    /* Text Wave Animation */
    .text-wave span {
        display: inline-block;
        animation: textWave 1s infinite;
        animation-delay: calc(0.1s * var(--i));
    }

    @keyframes textWave {

        0%,
        40%,
        100% {
            transform: translateY(0);
        }

        20% {
            transform: translateY(-15px);
        }
    }

    /* Scrolling Text */
    .scroll-text-container {
        width: 100%;
        overflow: hidden;
        white-space: nowrap;
    }

    .scroll-text {
        display: inline-block;
        padding-left: 100%;
        animation: scrollText 20s linear infinite;
    }

    @keyframes scrollText {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-100%);
        }
    }

    /* Neon Glow Effect */
    .neon-text {
        text-shadow: 0 0 5px var(--primary),
            0 0 10px var(--primary),
            0 0 15px var(--primary),
            0 0 20px var(--primary);
        animation: neonGlow 1.5s ease-in-out infinite alternate;
    }

    @keyframes neonGlow {
        from {
            text-shadow: 0 0 5px var(--primary),
                0 0 10px var(--primary),
                0 0 15px var(--primary),
                0 0 20px var(--primary);
        }

        to {
            text-shadow: 0 0 10px var(--primary),
                0 0 20px var(--primary),
                0 0 30px var(--primary),
                0 0 40px var(--primary);
        }
    }

    /* Hover Split Effect */
    .hover-split {
        position: relative;
        overflow: hidden;
    }

    .hover-split::before,
    .hover-split::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 50%;
        background-color: rgba(0, 51, 102, 0.8);
        transition: transform 0.5s ease;
        z-index: -1;
    }

    .hover-split::before {
        top: 0;
        transform: translateY(-100%);
    }

    .hover-split::after {
        bottom: 0;
        transform: translateY(100%);
    }

    .hover-split:hover::before {
        transform: translateY(0);
    }

    .hover-split:hover::after {
        transform: translateY(0);
    }

    /* Progress Bar On Scroll */
    .progress-container {
        position: fixed;
        top: 0;
        width: 100%;
        height: 5px;
        z-index: 2000;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(to right, var(--primary), var(--secondary));
        width: 0%;
        transition: width 0.1s ease;
    }

    /* Custom Cursor */
    .custom-cursor {
        position: fixed;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: var(--primary);
        pointer-events: none;
        mix-blend-mode: difference;
        transform: translate(-50%, -50%);
        transition: transform 0.1s;
        z-index: 9999;
        opacity: 0.7;
    }

    /* Background Reveal */
    .bg-reveal {
        position: relative;
        overflow: hidden;
    }

    .bg-reveal::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        transform: scaleX(0);
        transform-origin: 0 50%;
        transition: transform 0.5s ease-out;
        z-index: -1;
    }

    .bg-reveal:hover::before {
        transform: scaleX(1);
    }

    /* Shake Animation */
    .shake:hover {
        animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        20%,
        60% {
            transform: translateX(-5px);
        }

        40%,
        80% {
            transform: translateX(5px);
        }
    }

    /* Floating Elements */
    .float-animation {
        animation: floatAnimation 6s ease-in-out infinite;
    }

    @keyframes floatAnimation {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-20px);
        }
    }

    /* Spotlight Effect */
    .spotlight {
        position: relative;
        overflow: hidden;
    }

    .spotlight::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -60%;
        width: 20%;
        height: 200%;
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(30deg);
        pointer-events: none;
    }

    .spotlight:hover::before {
        animation: spotlightSweep 1s ease-in-out;
    }

    @keyframes spotlightSweep {
        0% {
            left: -60%;
        }

        100% {
            left: 150%;
        }
    }

    /* Letter Animation */
    .animate-letters span {
        display: inline-block;
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.3s ease, transform 0.3s ease;
        transition-delay: calc(0.05s * var(--i));
    }

    .animate-letters:hover span {
        opacity: 1;
        transform: translateY(0);
    }

    /* Clock Hand Animation */
    .clock-animation {
        position: relative;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 2px solid var(--primary);
        margin: 0 auto;
    }

    .hour-hand,
    .minute-hand,
    .second-hand {
        position: absolute;
        bottom: 50%;
        left: 50%;
        transform-origin: 50% 100%;
        background: var(--primary);
    }

    .hour-hand {
        width: 4px;
        height: 25px;
        transform: translateX(-50%) rotate(0deg);
        animation: hourRotate 43200s linear infinite;
    }

    .minute-hand {
        width: 3px;
        height: 35px;
        transform: translateX(-50%) rotate(0deg);
        animation: minuteRotate 3600s linear infinite;
    }

    .second-hand {
        width: 2px;
        height: 40px;
        background: var(--accent);
        transform: translateX(-50%) rotate(0deg);
        animation: secondRotate 60s linear infinite;
    }

    @keyframes hourRotate {
        from {
            transform: translateX(-50%) rotate(0deg);
        }

        to {
            transform: translateX(-50%) rotate(360deg);
        }
    }

    @keyframes minuteRotate {
        from {
            transform: translateX(-50%) rotate(0deg);
        }

        to {
            transform: translateX(-50%) rotate(360deg);
        }
    }

    @keyframes secondRotate {
        from {
            transform: translateX(-50%) rotate(0deg);
        }

        to {
            transform: translateX(-50%) rotate(360deg);
        }
    }

    /* Rain Effect */
    .rain-container {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 1;
        opacity: 0.5;
        pointer-events: none;
    }

    .rain-drop {
        position: absolute;
        background: linear-gradient(to bottom, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.7));
        width: 2px;
        height: 20px;
        top: -20px;
        animation: rain-fall linear infinite;
    }

    @keyframes rain-fall {
        to {
            transform: translateY(calc(100vh + 20px));
        }
    }

    /* Perspective Card Flip */
    .flip-card {
        perspective: 1000px;
        width: 300px;
        height: 200px;
    }

    .flip-card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        transition: transform 0.6s;
        transform-style: preserve-3d;
    }

    .flip-card:hover .flip-card-inner {
        transform: rotateY(180deg);
    }

    .flip-card-front,
    .flip-card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        border-radius: var(--border-radius);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .flip-card-front {
        background-color: var(--white);
        box-shadow: var(--shadow);
    }

    .flip-card-back {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: var(--white);
        transform: rotateY(180deg);
        box-shadow: var(--shadow);
    }

    /* Animated Background Gradient */
    .animated-bg {
        background: linear-gradient(-45deg, var(--primary), var(--primary-light), var(--secondary), var(--secondary-light));
        background-size: 400% 400%;
        animation: gradient 15s ease infinite;
    }

    @keyframes gradient {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    /* JS Required Hooks */
    .js-reveal-on-scroll,
    .js-parallax,
    .js-tilt-effect,
    .js-magnetic-button,
    .js-bubble-effect,
    .js-particle-effect,
    .js-bg-parallax {
        /* These classes are hooks for JavaScript to add interactive effects */
    }

    /* Print Styles */
    @media print {

        header,
        footer,
        .scroll-down,
        .wave-separator,
        .social-media,
        .contact-form {
            display: none !important;
        }

        body {
            background: white !important;
            color: black !important;
        }

        .container {
            width: 100% !important;
            max-width: none !important;
            padding: 0 !important;
        }

        .step,
        .benefit-card,
        .accordion-item {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        /* Reset all animations and effects */
        * {
            animation: none !important;
            transition: none !important;
            transform: none !important;
            box-shadow: none !important;
            text-shadow: none !important;
        }
    }
</style>

<body>
    <!-- Header -->
    <header class="full-width header">
        <div class="container header-container">
            <div class="logo">
                <img src="https://buscacarrera.com.co/public/content/logos/estandar/corporacion-universitaria-autonoma-del-cauca_550x420.jpg"
                    alt="Logo Universidad Autónoma del Cauca">
                <h1> Autónoma del Cauca</h1>
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
                    <li><a href="Login.html" id="login-btn">Iniciar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Banner -->
    <section class="banner" id="inicio">
        <div class="banner-content">
            <h2>Sistema de Homologación Académica</h2>
            <p>Tu camino hacia la excelencia académica en la  Autónoma del Cauca</p>
            <a href="homoformulario.html" class="btn">Comenzar Proceso</a>
        </div>
        <div class="scroll-down">
            <a href="#proceso"><i class="fas fa-chevron-down"></i></a>
        </div>
        <div class="wave-separator">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"
                preserveAspectRatio="none">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"
                    class="shape-fill"></path>
            </svg>
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
                    <a href="homoformulario.html" class="btn">Registrarse</a>
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

        <!-- Student Testimonials Carousel -->
        <section class="carousel-section">
            <h2 class="section-title">Experiencias de Estudiantes</h2>
            <div class="carousel-container">
                <div class="carousel-track" id="carouselTrack">
                    <div class="carousel-slide">
                        <img src="{{ asset('img/quimecara.png') }}" alt="Estudiante 1">
                        <h3>Ana María Rodríguez</h3>
                        <p class="student-program">Ingeniería de Sistemas</p>
                        <p>"El proceso de homologación fue muy sencillo y rápido. Pude avanzar en mi carrera sin perder
                            los créditos que ya había cursado. El apoyo del personal fue excelente."</p>
                    </div>
                    <div class="carousel-slide">
                        <img src="{{ asset('img/quimecara.png') }}" alt="Estudiante 2">
                        <h3>Carlos Mendoza</h3>
                        <p class="student-program">Administración de Empresas</p>
                        <p>"Gracias al sistema de homologación pude transferirme sin problemas y continuar mi carrera en
                            la UAC. El proceso online facilitó todo."</p>
                    </div>
                    <div class="carousel-slide">
                        <img src="{{ asset('img/quimecara.png') }}" alt="Estudiante 3">
                        <h3>Laura Valencia</h3>
                        <p class="student-program">Derecho</p>
                        <p>"La plataforma es muy intuitiva y el seguimiento de mi solicitud fue transparente. Recibí
                            notificaciones en cada paso del proceso."</p>
                    </div>
                    <div class="carousel-slide">
                        <img src="{{ asset('img/quimecara.png') }}" alt="Estudiante 4">
                        <h3>Juan Camilo Ruiz</h3>
                        <p class="student-program">Psicología</p>
                        <p>"No imaginé que homologar fuera tan simple. Todo el proceso se hizo en línea y siempre
                            estuvieron atentos a resolver mis dudas."</p>
                    </div>
                </div>
        </section>

        <!-- Benefits Section -->
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

        <!-- Requirements Section -->
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

                <div class="contact-form" data-aos="fade-left">
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
                            <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
                        </div>
                    </form>
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
            <p>&copy; 2025  Autónoma del Cauca - Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS (Animate on Scroll)
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true,
                mirror: false
            });

            // Header Scroll Effect
            const header = document.querySelector('.header');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 100) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });

            // Mobile Menu Toggle
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const navLinks = document.querySelector('.nav-links');
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    navLinks.classList.toggle('active');
                });
            }

            // Smooth scrolling for internal links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;

                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        // Close mobile menu if open
                        if (navLinks.classList.contains('active')) {
                            navLinks.classList.remove('active');
                        }

                        window.scrollTo({
                            top: targetElement.offsetTop - 80, // Adjust for fixed header
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // FAQ Accordion
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

                    // Close other accordions
                    accordionHeaders.forEach(otherHeader => {
                        if (otherHeader !== this) {
                            otherHeader.classList.remove('active');
                            otherHeader.nextElementSibling.classList.remove('active');
                            otherHeader.nextElementSibling.style.height = '0';
                        }
                    });
                });
            });
            document.addEventListener('DOMContentLoaded', () => {
                const track = document.getElementById('carouselTrack');
                const slides = track.querySelectorAll('.carousel-slide');
                const slideWidth = slides[0].offsetWidth + 24; // Considera el margen horizontal
                let index = 0;

                const moveCarousel = () => {
                    index++;
                    if (index >= slides.length) {
                        index = 0;
                    }
                    track.style.transform = `translateX(-${index * slideWidth}px)`;
                };

                setInterval(moveCarousel, 4000); // Cambia de slide cada 4 segundos
            });
            // Testimonial Carousel
            const track = document.querySelector('.carousel-track');
            const slides = Array.from(track.children);
            const nextButton = document.querySelector('.carousel-btn.next');
            const prevButton = document.querySelector('.carousel-btn.prev');
            const indicators = document.querySelectorAll('.indicator');

            let currentIndex = 0;
            const slideWidth = slides[0].getBoundingClientRect().width;

            // Set slides position
            slides.forEach((slide, index) => {
                slide.style.left = slideWidth * index + 'px';
            });

            // Update indicators
            function updateIndicators(index) {
                indicators.forEach(indicator => indicator.classList.remove('active'));
                indicators[index].classList.add('active');
            }

            // Move to slide
            function moveToSlide(index) {
                track.style.transform = 'translateX(-' + slides[index].style.left + ')';
                currentIndex = index;
                updateIndicators(index);
            }

            // Next slide
            nextButton.addEventListener('click', () => {
                if (currentIndex === slides.length - 1) {
                    moveToSlide(0);
                } else {
                    moveToSlide(currentIndex + 1);
                }
            });

            // Previous slide
            prevButton.addEventListener('click', () => {
                if (currentIndex === 0) {
                    moveToSlide(slides.length - 1);
                } else {
                    moveToSlide(currentIndex - 1);
                }
            });

            // Indicator click
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    moveToSlide(index);
                });
            });
            setInterval(() => {
                index = (index + 1) % slideCount;
                updateCarousel();
            }, 1000);


            // Form validation
            const contactForm = document.getElementById('contact-form');
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    // Here you would add the logic to send the form
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

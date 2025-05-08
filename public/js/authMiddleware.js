
// public/js/authMiddleware.js
document.addEventListener('DOMContentLoaded', function() {
    // Proteger la ruta actual
    if (!AuthService.middleware()) {
        return; // La redirección ya ocurrió en el middleware
    }

    // Opcional: Mostrar información del usuario en la página
    const user = window.AuthService && new AuthService().getUser();
    if (user) {
        console.log('Usuario autenticado:', user);
        // Puedes mostrar el nombre del usuario en algún lugar de la página
        const userNameElement = document.querySelector('.user-name');
        if (userNameElement) {
            userNameElement.textContent = user.name;
        }
    }
});

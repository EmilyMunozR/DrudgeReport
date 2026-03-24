document.addEventListener("DOMContentLoaded", () => {
    // Toggle suscripción
    // Mostrar formulario y ocultar botón
    document.getElementById("toggleSubscribe").addEventListener("click", () => {
        document.getElementById("subscribeBox").classList.remove("hidden");
        document.getElementById("toggleSubscribe").style.display = "none";
    });

    // Validación suscripción estricta
    document.getElementById("subscribeForm").addEventListener("submit", e => {
        e.preventDefault();
        const name = document.getElementById("subName").value.trim();
        const email = document.getElementById("subEmail").value.trim();
        const subMessage = document.getElementById("subMessage");

        const nameRegex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{3,}$/;
        const emailRegex = /^[^\s@]+@(gmail\.com|hotmail\.com|outlook\.com|yahoo\.com)$/;

        if (!nameRegex.test(name)) {
            subMessage.textContent = "El nombre solo puede contener letras y espacios (mínimo 3 caracteres).";
            subMessage.style.color = "red";
        } else if (!emailRegex.test(email)) {
            subMessage.textContent = "Correo inválido. Solo se aceptan dominios: gmail.com, hotmail.com, outlook.com, yahoo.com.";
            subMessage.style.color = "red";
        } else {
            subMessage.textContent = `¡Gracias ${name}, te has suscrito exitosamente!`;
            subMessage.style.color = "green";
            e.target.reset();
        }
    });

    // Switch modo oscuro/claro
    document.getElementById("themeSwitch").addEventListener("click", () => {
      document.body.classList.toggle("dark-mode");
    });

});

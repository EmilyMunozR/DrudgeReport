document.addEventListener("DOMContentLoaded", () => {
  const users = {
    "admin": { password: "admin123", role: "admin", subscribed: true },
    "cliente": { password: "1234", role: "cliente", subscribed: false },
    "suscrito": { password: "suscrito123", role: "cliente", subscribed: true }
  };

  let generatedCode = "";

  // Login
  document.getElementById("loginForm").addEventListener("submit", e => {
    e.preventDefault();
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();
    const loginMessage = document.getElementById("loginMessage");

    if (users[username] && users[username].password === password) {
      // Generar MFA simulado
      generatedCode = Math.floor(100000 + Math.random() * 900000).toString();
      loginMessage.textContent = `Usuario válido (${users[username].role}). Ingresa el código MFA.`;
      loginMessage.style.color = "green";
      document.getElementById("mfaSection").classList.remove("hidden");
      console.log("Código MFA generado:", generatedCode); // Simulación
    } else {
      loginMessage.textContent = "Usuario o contraseña incorrectos.";
      loginMessage.style.color = "red";
    }
  });

  // MFA
  document.getElementById("mfaSubmit").addEventListener("click", () => {
    const code = document.getElementById("mfaCode").value.trim();
    const mfaMessage = document.getElementById("mfaMessage");

    if (code === generatedCode) {
      mfaMessage.textContent = "Acceso concedido ✅";
      mfaMessage.style.color = "green";
    } else {
      mfaMessage.textContent = "Código incorrecto ❌";
      mfaMessage.style.color = "red";
    }
  });

  // Recuperación de contraseña
  document.getElementById("forgotPassword").addEventListener("click", e => {
    e.preventDefault();
    document.getElementById("recoverSection").classList.toggle("hidden");
  });

  document.getElementById("recoverForm").addEventListener("submit", e => {
    e.preventDefault();
    const email = document.getElementById("recoverEmail").value.trim();
    const recoverMessage = document.getElementById("recoverMessage");

    if (email === "") {
      recoverMessage.textContent = "Ingresa un correo válido.";
      recoverMessage.style.color = "red";
    } else {
      recoverMessage.textContent = "Se envió un enlace de recuperación a " + email;
      recoverMessage.style.color = "green";
    }
  });
});

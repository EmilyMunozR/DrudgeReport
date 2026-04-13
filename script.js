document.addEventListener("DOMContentLoaded", () => {
    console.log("DOM cargado - UN SOLO SCRIPT");

    // ==========================================
    // 1. TOGGLE SUSCRIPCIÓN
    // ==========================================
    const toggleBtn = document.getElementById("toggleSubscribe");
    const subscribeBox = document.getElementById("subscribeBox");
    const subscribeForm = document.getElementById("subscribeForm");
    const subMessage = document.getElementById("subMessage");

    if (toggleBtn) {
        toggleBtn.addEventListener("click", (e) => {
            e.preventDefault();
            if (subscribeBox) {
                subscribeBox.classList.remove("hidden");
                toggleBtn.style.display = "none";
            }
        });
    }

    if (subscribeForm) {
        subscribeForm.addEventListener("submit", e => {
            e.preventDefault();
            const name = document.getElementById("subName").value.trim();
            const email = document.getElementById("subEmail").value.trim();

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
                subscribeForm.reset();
            }
        });
    }

    // ==========================================
    // 2. TOGGLE CONTÁCTANOS (Integrado aquí)
    // ==========================================
    const toggleContactBtn = document.getElementById("toggleContact");
    const contactBox = document.getElementById("contactBox");

    if (toggleContactBtn && contactBox) {
        toggleContactBtn.addEventListener("click", function(e) {
            e.preventDefault(); // Evita que la página haga saltos extraños
            contactBox.classList.remove("hidden"); // Muestra el formulario
            toggleContactBtn.style.display = "none"; // Oculta el botón para que quede igual que suscripción
        });
    }

    // ==========================================
    // 3. SWITCH DE TEMA
    // ==========================================
    const themeSwitch = document.querySelector('.theme-switch');
    if (themeSwitch) {
        const newThemeSwitch = themeSwitch.cloneNode(true);
        themeSwitch.parentNode.replaceChild(newThemeSwitch, themeSwitch);
        
        newThemeSwitch.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            document.body.classList.toggle('dark-mode');
            
            const isDark = document.body.classList.contains('dark-mode');
            if (isDark) {
                this.classList.add('dark-active');
            } else {
                this.classList.remove('dark-active');
            }
        });
    }

    // ==========================================
    // 4. SWITCH DE IDIOMA
    // ==========================================
    const langSwitch = document.getElementById('langSwitch');
    if (langSwitch) {
        const newLangSwitch = langSwitch.cloneNode(true);
        langSwitch.parentNode.replaceChild(newLangSwitch, langSwitch);
        
        let currentLang = 'es';
        
        newLangSwitch.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (currentLang === 'es') {
                loadLanguage('en');
                currentLang = 'en';
                this.classList.add('en-mode');
            } else {
                loadLanguage('es');
                currentLang = 'es';
                this.classList.remove('en-mode');
            }
        });
    }

    // ==========================================
    // 5. BÚSQUEDA DINÁMICA
    // ==========================================
    const searchInput = document.getElementById("searchInput");
    if (searchInput) {
        searchInput.addEventListener("input", function() {
            const filter = this.value.toLowerCase();
            
            const sideNewsItems = document.querySelectorAll(".news-list li");
            sideNewsItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(filter) ? "" : "none";
            });

            const mainStory = document.querySelector(".main-story-col");
            if (mainStory) {
                const mainText = mainStory.textContent.toLowerCase();
                mainStory.style.display = mainText.includes(filter) ? "" : "none";
            }
        });
    }

    // ==========================================
    // 6. ACCESIBILIDAD (Aumento de tamaño de texto)
    // ==========================================
    const textSizeBtn = document.getElementById("textSizeBtn");
    if (textSizeBtn) {
        let isLargeText = false;
        textSizeBtn.addEventListener("click", () => {
            isLargeText = !isLargeText;
            document.body.classList.toggle("large-text", isLargeText);
            textSizeBtn.textContent = isLargeText ? "A-" : "A+";
            textSizeBtn.title = isLargeText ? "Reducir tamaño de texto" : "Aumentar tamaño de texto";
        });
    }
});

// Función para cargar idioma (Se queda fuera del DOMContentLoaded porque es una función reutilizable)
async function loadLanguage(lang) {
    try {
        const response = await fetch(`lang/${lang}.json`);
        const translations = await response.json();

        document.querySelectorAll("[data-translate]").forEach(el => {
            const key = el.getAttribute("data-translate");
            if (translations[key]) {
                if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                    el.placeholder = translations[key];
                } else {
                    el.textContent = translations[key];
                }
            }
        });
    } catch (err) {
        console.error("Error cargando idioma:", err);
    }
}
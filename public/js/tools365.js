/* =============================================
   tools365.js — Dark Mode + Zoom
   APIs usadas:
     · localStorage API  → persistencia
     · matchMedia API    → detecta preferencia del SO
     · CSS Custom Properties API → zoom sin recargar
   ============================================= */

(function () {

    /* ---- DARK MODE ---- */
    const html      = document.documentElement;
    const THEME_KEY = 'tools365-theme';

    // 1. Detectar preferencia del sistema operativo con matchMedia API
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');

    // 2. Usar lo guardado, o la preferencia del SO si no hay nada guardado
    const saved = localStorage.getItem(THEME_KEY);
    const theme = saved ?? (prefersDark.matches ? 'dark' : 'light');

    applyTheme(theme);

    // 3. Si el usuario cambia el tema del SO en tiempo real, adaptarse
    prefersDark.addEventListener('change', function (e) {
        if (!localStorage.getItem(THEME_KEY)) {          // solo si no eligió manualmente
            applyTheme(e.matches ? 'dark' : 'light');
        }
    });

    // 4. Botón toggle
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('darkToggle');
        if (btn) {
            btn.addEventListener('click', function () {
                const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                applyTheme(next);
                localStorage.setItem(THEME_KEY, next);
            });
        }
    });

    function applyTheme(t) {
        html.setAttribute('data-theme', t);
        const icon  = document.getElementById('darkIcon');
        const label = document.getElementById('darkLabel');
        if (icon)  icon.className    = t === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
        if (label) label.textContent = t === 'dark' ? 'Claro' : 'Oscuro';
    }


    /* ---- ZOOM (CSS Custom Properties API) ---- */
    const ZOOM_KEY   = 'tools365-zoom';
    const ZOOM_SIZES = { sm: '0.9rem', md: '1rem', lg: '1.1rem', xl: '1.2rem' };

    const savedZoom = localStorage.getItem(ZOOM_KEY) || 'md';
    applyZoom(savedZoom);

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-zoom]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const z = this.getAttribute('data-zoom');
                applyZoom(z);
                localStorage.setItem(ZOOM_KEY, z);
            });
        });
    });

    function applyZoom(z) {
        // CSS Custom Properties API: setProperty en el elemento raíz
        document.documentElement.style.setProperty('font-size', ZOOM_SIZES[z] || '1rem');
        document.querySelectorAll('[data-zoom]').forEach(function (btn) {
            btn.classList.toggle('active', btn.getAttribute('data-zoom') === z);
        });
    }

})();
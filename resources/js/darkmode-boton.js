function setupDarkModeToggle() {
    const toggle = document.getElementById('toggle-dark');
    const html = document.documentElement;
    const moonIcon = document.getElementById('moon-icon');
    const sunIcon = document.getElementById('sun-icon');

    function updateIcons() {
        if (html.classList.contains('dark')) {
            moonIcon?.classList.add('hidden');
            sunIcon?.classList.remove('hidden');
        } else {
            moonIcon?.classList.remove('hidden');
            sunIcon?.classList.add('hidden');
        }
    }

    if (toggle) {
        toggle.onclick = () => {
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
            updateIcons();
        };
        updateIcons();
    }
}

// Ejecutar al cargar y tras cada navegación Livewire SPA
document.addEventListener('DOMContentLoaded', setupDarkModeToggle);
document.addEventListener('livewire:navigated', setupDarkModeToggle);
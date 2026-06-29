import './bootstrap';

window.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-copy-target]').forEach(button => {
        button.addEventListener('click', async () => {
            const targetSelector = button.getAttribute('data-copy-target');
            const input = document.querySelector(targetSelector);

            if (! input) {
                return;
            }

            try {
                await navigator.clipboard.writeText(input.value);
                button.textContent = 'Copié !';
                setTimeout(() => {
                    button.textContent = 'Copier';
                }, 2000);
            } catch (error) {
                console.error(error);
                button.textContent = 'Erreur';
                setTimeout(() => {
                    button.textContent = 'Copier';
                }, 2000);
            }
        });
    });
});

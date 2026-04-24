document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-confirm]').forEach((element) => {
        element.addEventListener('click', (event) => {
            const message = element.getAttribute('data-confirm') || 'Are you sure?';
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('.template-selector input[type="radio"]').forEach((input) => {
        input.addEventListener('change', () => {
            document.querySelectorAll('.template-option').forEach((card) => card.classList.remove('active'));
            input.closest('.template-option')?.classList.add('active');
        });
    });
});

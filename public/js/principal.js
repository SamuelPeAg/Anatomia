document.addEventListener('DOMContentLoaded', () => {
    console.log('Página de inicio de Anatomía MEDAC cargada.');

    // Aquí puedes añadir interacciones futuras
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', () => {
            btn.style.transform = 'translateY(-2px)';
        });
        btn.addEventListener('mouseleave', () => {
            btn.style.transform = 'translateY(0)';
        });
    });
});

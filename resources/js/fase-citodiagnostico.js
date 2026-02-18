
window.validarFase4 = function () {
    const zooms = ['x4', 'x10', 'x40', 'x100'];
    let faltantes = [];

    zooms.forEach(zoom => {
        const input = document.querySelector(`input[name="micro_${zoom}_img[]"]`);
        if (input) {
            const container = input.closest('.subtarjeta-cuerpo');
            if (container) {
                const count = container.querySelectorAll('.imagen-card, .nueva-imagen-fila').length;
                if (count === 0) faltantes.push(zoom);
            }
        }
    });

    if (faltantes.length > 0) {
        Swal.fire({
            title: 'Imágenes faltantes',
            text: 'Es obligatorio adjuntar imágenes para los aumentos: ' + faltantes.join(', ') + '.',
            icon: 'error',
            confirmButtonColor: '#0234AB'
        });
        return false;
    }
    return true;
};

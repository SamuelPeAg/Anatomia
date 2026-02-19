
/**
 * Validación obligatoria de imágenes para la Fase 4
 */
window.validarFase4 = function () {
    const aumentos = ['x4', 'x10', 'x40', 'x100'];
    let faltantes = [];

    aumentos.forEach(zoom => {
        const input = document.querySelector(`input[name="micro_${zoom}_img[]"]`);
        if (input) {
            const contenedor = input.closest('.subtarjeta-cuerpo');
            if (contenedor) {
                const total = contenedor.querySelectorAll('.imagen-card, .nueva-imagen-fila').length;
                if (total === 0) faltantes.push(zoom);
            }
        }
    });

    if (faltantes.length > 0) {
        Swal.fire({
            title: 'Imágenes faltantes',
            text: 'Es obligatorio adjuntar al menos una imagen para los aumentos: ' + faltantes.join(', ') + '.',
            icon: 'error',
            confirmButtonColor: '#0234AB'
        });
        return false;
    }
    return true;
};

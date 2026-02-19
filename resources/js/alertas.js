
document.addEventListener('DOMContentLoaded', () => {
    const datos = document.body.dataset;

    // Notificación de éxito (Toast)
    if (datos.success) {
        Swal.fire({
            toast: true,
            position: 'top-start',
            icon: 'success',
            title: datos.success,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    }

    // Modal de Error
    if (datos.error) {
        Swal.fire({
            icon: 'error',
            title: datos.errorTitle || 'Revisa los campos',
            text: datos.error,
            confirmButtonColor: '#0234AB'
        });
    }
});

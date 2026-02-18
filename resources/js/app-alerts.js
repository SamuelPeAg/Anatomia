
document.addEventListener('DOMContentLoaded', () => {
    const config = document.body.dataset;

    // Toast Success
    if (config.success) {
        Swal.fire({
            toast: true,
            position: 'top-start',
            icon: 'success',
            title: config.success,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    }

    // Error Modal
    if (config.error) {
        Swal.fire({
            icon: 'error',
            title: config.errorTitle || 'Revisa los campos',
            text: config.error,
            confirmButtonColor: '#0234AB'
        });
    }
});

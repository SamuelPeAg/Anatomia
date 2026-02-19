
document.addEventListener('DOMContentLoaded', () => {
    // Confirmación para VALIDAR INFORME
    document.querySelectorAll('.form-revisar').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Validar este informe?',
                text: "Una vez validado, el informe quedará cerrado y no podrá ser editado.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0234AB',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Sí, validar ahora',
                cancelButtonText: 'Cancelar'
            }).then((resultado) => {
                if (resultado.isConfirmed) form.submit();
            });
        });
    });

    // Confirmación para BORRAR INFORME
    document.querySelectorAll('.form-borrar').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Eliminar informe?',
                text: "Esta acción no se puede deshacer.",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#DC2626',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
            }).then((resultado) => {
                if (resultado.isConfirmed) form.submit();
            });
        });
    });
});

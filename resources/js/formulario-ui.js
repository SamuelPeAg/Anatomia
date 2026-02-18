/**
 * Muestra notificaciones tipo Toast con SweetAlert2 desde la izquierda
 */
export function mostrarToast(mensaje, tipo = 'info') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-start',
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        customClass: {
            container: 'swal2-toast-left'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: tipo,
        title: mensaje
    });
}

/**
 * Modal de confirmación estilizado con SweetAlert2
 */
export async function pedirConfirmacion(titulo, mensaje) {
    const result = await Swal.fire({
        title: titulo,
        text: mensaje,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0234AB',
        cancelButtonColor: '#64748B',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
    });

    return result.isConfirmed;
}

const titulosPorFase = {
    1: "Fase 1 — Recepción",
    2: "Fase 2 — Procesamiento",
    3: "Fase 3 — Tinción",
    4: "Fase 4 — Citodiagnóstico"
};

export function cambiarAFase(n) {
    const botonesPasos = document.querySelectorAll(".paso");
    const seccionesFase = document.querySelectorAll(".fase");
    const tituloFase = document.getElementById("tituloFase");

    seccionesFase.forEach(s => s.classList.remove("fase-activa"));
    botonesPasos.forEach(b => b.classList.remove("paso-activo"));

    const el = document.getElementById(`fase-${n}`);
    const btn = document.querySelector(`.paso[data-paso="${n}"]`);

    if (el) el.classList.add("fase-activa");
    if (btn) btn.classList.add("paso-activo");
    if (tituloFase) tituloFase.textContent = titulosPorFase[n] || "Cargando...";
}

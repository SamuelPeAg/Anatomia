
/**
 * Muestra notificaciones tipo Toast
 */
export function mostrarToast(mensaje, tipo = 'info') {
    const contenedor = document.getElementById('toast-container');
    if (!contenedor) return;

    const toast = document.createElement('div');
    toast.className = `toast toast--${tipo}`;

    const iconos = {
        info: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#0234AB" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        warning: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#f59e0b" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>',
        error: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#ef4444" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        success: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#10b981" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>'
    };

    toast.innerHTML = `<div class="toast-icon">${iconos[tipo] || iconos.info}</div>
                       <div class="toast-content"><span class="toast-title">${tipo.toUpperCase()}</span><span class="toast-msg">${mensaje}</span></div>`;

    contenedor.appendChild(toast);
    setTimeout(() => toast.classList.add('active'), 10);
    setTimeout(() => { toast.classList.remove('active'); setTimeout(() => toast.remove(), 400); }, 4500);
}

/**
 * Modal de confirmación estilizado
 */
export async function pedirConfirmacion(titulo, mensaje) {
    const modal = document.getElementById('modal-confirm');
    if (!modal) return confirm(mensaje);

    return new Promise(resolve => {
        const btnCancel = document.getElementById('modal-btn-cancel');
        const btnConfirm = document.getElementById('modal-btn-confirm');
        document.getElementById('modal-confirm-title').textContent = titulo;
        document.getElementById('modal-confirm-msg').textContent = mensaje;

        modal.classList.remove('oculto');

        const cerrar = (res) => {
            modal.classList.add('oculto');
            btnConfirm.removeEventListener('click', onOk);
            btnCancel.removeEventListener('click', onCancel);
            resolve(res);
        };
        const onOk = () => cerrar(true);
        const onCancel = () => cerrar(false);

        btnConfirm.addEventListener('click', onOk);
        btnCancel.addEventListener('click', onCancel);
    });
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

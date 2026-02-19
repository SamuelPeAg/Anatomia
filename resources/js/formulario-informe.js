
/**
 * =============================================================================
 * FORMULARIO DE INFORME - LÓGICA UNIFICADA
 * =============================================================================
 * Este archivo centraliza toda la interactividad del registro de informes:
 * 1. Utilidades de Interfaz (Toasts, Modals, Navegación)
 * 2. Gestión de Eventos y Cambios
 * 3. Autocompletado de Pacientes
 * 4. Validación de Fases (Especialmente Fase 4)
 * =============================================================================
 */

// --- 1. UTILIDADES DE INTERFAZ ---

/**
 * Muestra notificaciones tipo Toast con SweetAlert2
 */
function mostrarToast(mensaje, tipo = 'info') {
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
 * Modal de confirmación estilizado
 */
async function pedirConfirmacion(titulo, mensaje) {
    const resultado = await Swal.fire({
        title: titulo,
        text: mensaje,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0234AB',
        cancelButtonColor: '#64748B',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
    });

    return resultado.isConfirmed;
}

const TITULOS_FASES = {
    1: "Fase 1 — Recepción",
    2: "Fase 2 — Procesamiento",
    3: "Fase 3 — Tinción",
    4: "Fase 4 — Citodiagnóstico"
};

/**
 * Gestiona el cambio visual de fases
 */
function cambiarAFase(n) {
    const botonesPasos = document.querySelectorAll(".paso");
    const seccionesFase = document.querySelectorAll(".fase");
    const tituloFase = document.getElementById("tituloFase");

    seccionesFase.forEach(s => s.classList.remove("fase-activa"));
    botonesPasos.forEach(b => b.classList.remove("paso-activo"));

    const el = document.getElementById(`fase-${n}`);
    const btn = document.querySelector(`.paso[data-paso="${n}"]`);

    if (el) el.classList.add("fase-activa");
    if (btn) btn.classList.add("paso-activo");
    if (tituloFase) tituloFase.textContent = TITULOS_FASES[n] || "Cargando...";
}


// --- 2. LÓGICA PRINCIPAL DEL FORMULARIO ---

document.addEventListener("DOMContentLoaded", () => {
    const configEl = document.getElementById("informe-config");
    if (!configEl) return; // No estamos en la página del formulario

    const configuracion = {
        faseInicial: parseInt(configEl.dataset.faseInicial || 1),
        esModoEdicion: configEl.dataset.esEdicion === "true"
    };

    let hayCambiosSinGuardar = false;

    // Auxiliares de estado
    const marcarCambios = () => { hayCambiosSinGuardar = true; };
    const limpiarCambios = () => { hayCambiosSinGuardar = false; };

    // Escuchar cambios en cualquier campo del formulario
    document.querySelectorAll('.fase input, .fase textarea, .fase select').forEach(el => {
        el.addEventListener('change', marcarCambios);
        el.addEventListener('input', marcarCambios);
    });

    document.querySelectorAll('form').forEach(f => f.addEventListener('submit', limpiarCambios));

    // --- Navegación por clicks ---
    document.addEventListener("click", e => {
        const btnPaso = e.target.closest(".paso");
        if (btnPaso) cambiarAFase(parseInt(btnPaso.dataset.paso));

        const btnVolver = e.target.closest('[data-volver-paso]');
        if (btnVolver) cambiarAFase(parseInt(btnVolver.dataset.volverPaso));

        const btnSubir = e.target.closest('[data-trigger-upload]');
        if (btnSubir) document.getElementById(btnSubir.dataset.triggerUpload)?.click();

        const btnBorrar = e.target.closest('[data-borrar-imagen-url]');
        if (btnBorrar) window.borrarImagen(e, btnBorrar.dataset.borrarImagenUrl);
    });

    // --- Generación Automática de Código de Muestra ---
    const selMuestra = document.getElementById("tipo_muestra");
    const inpCodigo = document.getElementById("codigo_identificador");

    if (selMuestra && inpCodigo) {
        selMuestra.addEventListener("change", async () => {
            const prefijo = selMuestra.value;
            const divOrg = document.getElementById("campoOrgano");
            const inpOrg = document.getElementById("organo");

            if (divOrg) divOrg.classList.toggle("oculto", prefijo !== "B");
            if (inpOrg) inpOrg.required = (prefijo === "B");

            if (!prefijo) { inpCodigo.value = ""; return; }

            try {
                inpCodigo.value = "Generando...";
                const url = selMuestra.dataset.urlPatron.replace('PREFIX', prefijo);
                const res = await fetch(url);
                if (res.ok) {
                    const datos = await res.json();
                    inpCodigo.value = datos.codigo;
                    inpCodigo.dispatchEvent(new Event('change'));
                }
            } catch (e) {
                console.error("Error al generar código:", e);
                inpCodigo.value = "";
            }
        });
    }

    // --- Gestión de Autocompletado de Pacientes ---
    const entradaNombre = document.getElementById('paciente_nombre');
    const listaAuto = document.getElementById('autocomplete-list');

    if (entradaNombre && listaAuto) {
        const entradaCorreo = document.getElementById('paciente_correo');
        const cajaAviso = document.getElementById('similarity-warning');
        const sugerenciaNombre = document.getElementById('suggested-name');
        let temporizadorEspera;

        const seleccionarPaciente = (nombre, correo) => {
            entradaNombre.value = nombre;
            if (correo && entradaCorreo && !entradaCorreo.value) {
                entradaCorreo.value = correo;
            }
            listaAuto.style.display = 'none';
            if (cajaAviso) cajaAviso.style.display = 'none';
        };

        sugerenciaNombre?.addEventListener('click', () => {
            seleccionarPaciente(sugerenciaNombre.textContent, '');
        });

        entradaNombre.addEventListener('input', () => {
            const termino = entradaNombre.value;
            if (cajaAviso) cajaAviso.style.display = 'none';

            if (termino.length < 2) {
                listaAuto.style.display = 'none';
                return;
            }

            clearTimeout(temporizadorEspera);
            temporizadorEspera = setTimeout(() => {
                fetch(`/api/expedientes/search?term=${encodeURIComponent(termino)}`)
                    .then(res => res.json())
                    .then(datos => {
                        listaAuto.innerHTML = '';
                        if (datos.length) {
                            datos.forEach(item => {
                                const li = document.createElement('li');
                                li.innerHTML = item.nombre.replace(new RegExp(`(${termino})`, "gi"), "<strong>$1</strong>");
                                li.onclick = () => seleccionarPaciente(item.nombre, item.correo);
                                listaAuto.appendChild(li);
                            });
                            listaAuto.style.display = 'block';
                        } else {
                            listaAuto.style.display = 'none';
                        }
                    });
            }, 300);
        });
    }

    // --- Gestión de Previsualización de Imágenes ---
    document.addEventListener('change', e => {
        if (!e.target.matches('.input-previsualizable')) return;

        const input = e.target;
        const contenedor = document.getElementById(`container-${input.dataset.fase}-${input.dataset.zoom}`);
        if (!input.files?.length || !contenedor) return;

        const padre = contenedor.closest('.subtarjeta-cuerpo');
        const totalActual = padre.querySelectorAll('.imagen-card, .nueva-imagen-fila').length;

        if (totalActual + input.files.length > 6) {
            Swal.fire({
                icon: 'error',
                title: 'Límite alcanzado',
                text: 'Máximo 6 imágenes por sección.',
                confirmButtonColor: '#0234AB'
            });
            input.value = '';
            return;
        }

        const loteId = `lote-${Date.now()}`;
        Array.from(input.files).forEach(archivo => {
            const lector = new FileReader();
            lector.onload = ev => {
                const div = document.createElement('div');
                div.className = 'nueva-imagen-fila';
                div.dataset.lote = loteId;
                div._inputOrigen = input;
                div.innerHTML = `
                    <div class="preview-thumb"><img src="${ev.target.result}"></div>
                    <div class="preview-inputs">
                        <span class="badge-nueva">NUEVA</span>
                        <input type="text" name="${input.dataset.nameDesc}" placeholder="Nombre" class="control-campo control-sm">
                        <span class="nombre-archivo">${archivo.name}</span>
                    </div>
                    <button type="button" class="boton-icono btn-eliminar-lote">✕</button>
                `;
                contenedor.appendChild(div);
            };
            lector.readAsDataURL(archivo);
        });

        const nuevoInput = input.cloneNode(true);
        nuevoInput.value = '';
        input.style.display = 'none';
        input.classList.remove('input-previsualizable');
        input.parentNode.insertBefore(nuevoInput, input.nextSibling);

        actualizarContador(contenedor);
        marcarCambios();
    });

    // Eliminar lotes de subida
    document.addEventListener('click', e => {
        const btn = e.target.closest('.btn-eliminar-lote');
        if (!btn) return;

        const fila = btn.closest('.nueva-imagen-fila');
        pedirConfirmacion('¿Eliminar esta imagen?', 'Se eliminará de la lista de subida.').then(ok => {
            if (ok) {
                const contenedor = fila.parentNode;
                document.querySelectorAll(`.nueva-imagen-fila[data-lote="${fila.dataset.lote}"]`).forEach(el => el.remove());
                fila._inputOrigen?.remove();
                actualizarContador(contenedor);
                marcarCambios();
            }
        });
    });

    // Botón Finalizar e Iniciar Fase
    document.querySelector('.btn-finalizar-informe')?.addEventListener('click', e => {
        if (!validarFase4()) e.preventDefault();
    });

    cambiarAFase(configuracion.faseInicial);
});

// --- 3. FUNCIONES GLOBALES ---

/**
 * Validación obligatoria de imágenes para Fase 4
 */
function validarFase4() {
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
}

/**
 * Borrado físico de imágenes (POST con _method DELETE)
 */
window.borrarImagen = (e, url) => {
    e?.preventDefault();
    pedirConfirmacion('¿Borrar permanentemente?', 'Esta acción no se puede deshacer.').then(ok => {
        if (!ok) return;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        form.innerHTML = `
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    });
};

/**
 * Actualiza el contador visual de imágenes
 */
function actualizarContador(contenedor) {
    const padre = contenedor?.closest('.subtarjeta');
    if (!padre) return;

    const total = padre.querySelectorAll('.imagen-card, .nueva-imagen-fila').length;
    const elContador = padre.querySelector('.contador-imagenes');
    const btnSubir = padre.querySelector('[data-trigger-upload]');

    if (elContador) {
        elContador.textContent = `${total} / 6`;
        elContador.classList.toggle('limite-alcanzado', total >= 6);
    }
    if (btnSubir) btnSubir.disabled = (total >= 6);
}

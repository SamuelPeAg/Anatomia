
import { pedirConfirmacion, cambiarAFase, mostrarToast } from './informe-interfaz.js';

document.addEventListener("DOMContentLoaded", () => {
    const configEl = document.getElementById("informe-config");
    const configuracion = {
        faseInicial: parseInt(configEl?.dataset.faseInicial || 1),
        esModoEdicion: configEl?.dataset.esEdicion === "true"
    };

    let hayCambiosSinGuardar = false;

    // --- Auxiliares UI ---
    const marcarCambios = () => { hayCambiosSinGuardar = true; };
    const limpiarCambios = () => { hayCambiosSinGuardar = false; };

    // --- Inicialización de escuchadores ---
    document.querySelectorAll('.fase input, .fase textarea, .fase select').forEach(el => {
        el.addEventListener('change', marcarCambios);
        el.addEventListener('input', marcarCambios);
    });

    document.querySelectorAll('form').forEach(f => f.addEventListener('submit', limpiarCambios));

    // --- Navegación y Acciones ---
    document.addEventListener("click", e => {
        const btnPaso = e.target.closest(".paso");
        if (btnPaso) cambiarAFase(parseInt(btnPaso.dataset.paso));

        const btnVolver = e.target.closest('[data-volver-paso]');
        if (btnVolver) cambiarAFase(parseInt(btnVolver.dataset.volverPaso));

        // Disparo de subida de archivos
        const btnSubir = e.target.closest('[data-trigger-upload]');
        if (btnSubir) document.getElementById(btnSubir.dataset.triggerUpload)?.click();

        // Borrado de imágenes existentes
        const btnBorrar = e.target.closest('[data-borrar-imagen-url]');
        if (btnBorrar) window.borrarImagen(e, btnBorrar.dataset.borrarImagenUrl);
    });

    // --- Generación Automática de Código ---
    const selMuestra = document.getElementById("tipo_muestra");
    const inpCodigo = document.getElementById("codigo_identificador");

    if (selMuestra && inpCodigo) {
        selMuestra.addEventListener("change", async () => {
            const prefijo = selMuestra.value;
            const divOrg = document.getElementById("campoOrgano");
            const inpOrg = document.getElementById("organo");

            // Mostrar/Ocultar órgano si es Biopsia (B)
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

    // --- Gestión de Previsualización de Imágenes ---
    document.addEventListener('change', e => {
        if (!e.target.matches('.input-previsualizable')) return;

        const input = e.target;
        const contenedor = document.getElementById(`container-${input.dataset.fase}-${input.dataset.zoom}`);
        if (!input.files?.length || !contenedor) return;

        const padre = contenedor.closest('.subtarjeta-cuerpo');
        const totalActual = padre.querySelectorAll('.imagen-card, .nueva-imagen-fila').length;

        // Límite de seguridad
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

        // Clonar input para permitir subidas adicionales en la misma sesión
        const nuevoInput = input.cloneNode(true);
        nuevoInput.value = '';
        input.style.display = 'none';
        input.classList.remove('input-previsualizable');
        input.parentNode.insertBefore(nuevoInput, input.nextSibling);

        actualizarContador(contenedor);
        marcarCambios();
    });

    // Eliminar grupos de subida nuevos
    document.addEventListener('click', e => {
        const btn = e.target.closest('.btn-eliminar-lote');
        if (!btn) return;

        const fila = btn.closest('.nueva-imagen-fila');
        pedirConfirmacion('¿Eliminar esta imagen?', 'Se eliminará el grupo de subida seleccionado.').then(ok => {
            if (ok) {
                const contenedor = fila.parentNode;
                document.querySelectorAll(`.nueva-imagen-fila[data-lote="${fila.dataset.lote}"]`).forEach(el => el.remove());
                fila._inputOrigen?.remove();
                actualizarContador(contenedor);
                marcarCambios();
            }
        });
    });

    // Validación antes de finalizar
    document.querySelector('.btn-finalizar-informe')?.addEventListener('click', e => {
        if (typeof window.validarFase4 === 'function' && !window.validarFase4()) {
            e.preventDefault();
        }
    });

    // Iniciar en la fase que indique el sistema
    cambiarAFase(configuracion.faseInicial);
});

/**
 * Lógica global para borrar imágenes mediante formulario dinámico
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
 * Actualiza el indicador visual de imágenes (X / 6)
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
    if (btnSubir) {
        btnSubir.disabled = (total >= 6);
    }
}

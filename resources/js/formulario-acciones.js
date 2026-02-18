
import { pedirConfirmacion, cambiarAFase, mostrarToast } from './formulario-ui.js';

document.addEventListener("DOMContentLoaded", () => {
    const configEl = document.getElementById("informe-config");
    const config = {
        faseInicial: parseInt(configEl?.dataset.faseInicial || 1),
        esModoEdicion: configEl?.dataset.esEdicion === "true"
    };

    let hayCambiosSinGuardar = false;

    // --- Helpers UI ---
    const markChanges = () => { hayCambiosSinGuardar = true; };
    const resetChanges = () => { hayCambiosSinGuardar = false; };

    // --- Inicialización ---
    document.querySelectorAll('.fase input, .fase textarea, .fase select').forEach(el => {
        el.addEventListener('change', markChanges);
        el.addEventListener('input', markChanges);
    });

    document.querySelectorAll('form').forEach(f => f.addEventListener('submit', resetChanges));

    // --- Navegación ---
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

    // --- Generación de Código ---
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
                    const data = await res.json();
                    inpCodigo.value = data.codigo;
                    inpCodigo.dispatchEvent(new Event('change'));
                }
            } catch (e) { console.error("Error generation:", e); }
        });
    }

    // --- Gestión de Imágenes ---
    document.addEventListener("click", e => {
        const add = e.target.closest("[data-anadir-fila]");
        const del = e.target.closest("[data-eliminar-fila]");

        if (add) {
            const lista = document.querySelector(`[data-lista-imagenes="${add.dataset.anadirFila}"]`);
            const tpl = document.getElementById(`plantilla-${add.dataset.anadirFila}`);
            if (lista && tpl) {
                lista.appendChild(tpl.content.firstElementChild.cloneNode(true));
                markChanges();
            }
        }

        if (del) {
            const fila = del.closest(".fila-imagen");
            if (fila && !fila.classList.contains("fila-obligatoria")) {
                fila.querySelectorAll("input, select").forEach(i => i.value = "");
                if (fila.closest("[data-lista-imagenes]").querySelectorAll(".fila-imagen").length > 1) fila.remove();
                markChanges();
            }
        }
    });

    document.addEventListener('change', e => {
        if (!e.target.matches('.input-previsualizable')) return;

        const input = e.target;
        const container = document.getElementById(`container-${input.dataset.fase}-${input.dataset.zoom}`);
        if (!input.files?.length || !container) return;

        const parent = container.closest('.subtarjeta-cuerpo');
        const currentCount = parent.querySelectorAll('.imagen-card, .nueva-imagen-fila').length;

        if (currentCount + input.files.length > 6) {
            Swal.fire({ icon: 'error', title: 'Límite alcanzado', text: 'Máximo 6 imágenes.', confirmButtonColor: '#0234AB' });
            input.value = '';
            return;
        }

        const loteId = `lote-${Date.now()}`;
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = ev => {
                const div = document.createElement('div');
                div.className = 'nueva-imagen-fila';
                div.dataset.lote = loteId;
                div._inputSource = input;
                div.innerHTML = `
                    <div class="preview-thumb"><img src="${ev.target.result}"></div>
                    <div class="preview-inputs">
                        <span class="badge-nueva">NUEVA</span>
                        <input type="text" name="${input.dataset.nameDesc}" placeholder="Nombre" class="control-campo control-sm">
                        <span class="nombre-archivo">${file.name}</span>
                    </div>
                    <button type="button" class="boton-icono btn-eliminar-lote">✕</button>`;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });

        // Rotar input para permitir más subidas
        const newInput = input.cloneNode(true);
        newInput.value = '';
        input.style.display = 'none';
        input.classList.remove('input-previsualizable');
        input.parentNode.insertBefore(newInput, input.nextSibling);
        actualizarContador(container);
        markChanges();
    });

    // Eliminar lotes nuevos
    document.addEventListener('click', e => {
        const btn = e.target.closest('.btn-eliminar-lote');
        if (!btn) return;

        const fila = btn.closest('.nueva-imagen-fila');
        pedirConfirmacion('¿Eliminar esta imagen?', 'Se eliminará el grupo de subida.').then(ok => {
            if (ok) {
                const container = fila.parentNode;
                document.querySelectorAll(`.nueva-imagen-fila[data-lote="${fila.dataset.lote}"]`).forEach(el => el.remove());
                fila._inputSource?.remove();
                actualizarContador(container);
                markChanges();
            }
        });
    });

    // Finalizar
    document.querySelector('.btn-finalizar-informe')?.addEventListener('click', e => {
        if (typeof window.validarFase4 === 'function' && !window.validarFase4()) e.preventDefault();
    });

    cambiarAFase(config.faseInicial);
});

window.borrarImagen = (e, url) => {
    e?.preventDefault();
    pedirConfirmacion('¿Borrar permanentemente?', 'No se puede deshacer.').then(ok => {
        if (!ok) return;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        form.innerHTML = `<input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}"><input type="hidden" name="_method" value="DELETE">`;
        document.body.appendChild(form);
        form.submit();
    });
};

function actualizarContador(container) {
    const parent = container?.closest('.subtarjeta');
    if (!parent) return;

    const total = parent.querySelectorAll('.imagen-card, .nueva-imagen-fila').length;
    const countEl = parent.querySelector('.contador-imagenes');
    const btn = parent.querySelector('[data-trigger-upload]');

    if (countEl) {
        countEl.textContent = `${total} / 6`;
        countEl.classList.toggle('limite-alcanzado', total >= 6);
    }
    if (btn) btn.disabled = (total >= 6);
}

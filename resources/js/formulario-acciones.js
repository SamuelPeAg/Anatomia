
import { pedirConfirmacion, cambiarAFase, mostrarToast } from './formulario-ui.js';

document.addEventListener("DOMContentLoaded", () => {
    // Configuración desde el DOM
    const configEl = document.getElementById("informe-config");
    const config = {
        faseInicial: parseInt(configEl?.dataset.faseInicial || 1),
        esModoEdicion: configEl?.dataset.esEdicion === "true",
        fasesCompletas: JSON.parse(configEl?.dataset.fasesCompletas || "{}")
    };

    let hayCambiosSinGuardar = false;

    // Seguimiento de cambios para avisar antes de salir
    document.querySelectorAll('.fase input, .fase textarea, .fase select').forEach(el => {
        el.addEventListener('change', () => { hayCambiosSinGuardar = true; });
        el.addEventListener('input', () => { hayCambiosSinGuardar = true; });
    });

    // Navegación entre pasos con protección de cambios
    const botonesPasos = document.querySelectorAll(".paso");
    botonesPasos.forEach(btn => {
        btn.addEventListener("click", (e) => {
            const n = parseInt(btn.dataset.paso);
            cambiarAFase(n);
        });
    });

    // Resetear cambios al enviar formularios
    document.querySelectorAll('form').forEach(f => {
        f.addEventListener('submit', () => {
            hayCambiosSinGuardar = false;
        });
    });


    // Autogeneración de Código ID según tipo de muestra
    const selMuestra = document.getElementById("tipo_muestra");
    const inpCodigo = document.getElementById("codigo_identificador");

    if (selMuestra && inpCodigo) {
        selMuestra.addEventListener("change", async () => {
            const prefijo = selMuestra.value;
            const divOrg = document.getElementById("campoOrgano");
            const inpOrg = document.getElementById("organo");

            // Mostrar órgano solo si es Biopsia (B)
            if (divOrg) divOrg.classList.toggle("oculto", prefijo !== "B");
            if (inpOrg) inpOrg.required = (prefijo === "B");

            if (!prefijo) {
                inpCodigo.value = "";
                return;
            }

            try {
                inpCodigo.value = "Generando...";
                inpCodigo.disabled = true;

                // Usar la ruta generada por Laravel para evitar problemas de subdirectorios
                const urlPatron = selMuestra.dataset.urlPatron;
                if (!urlPatron) throw new Error("Ruta de API no definida");

                const url = urlPatron.replace('PREFIX', prefijo);

                const res = await fetch(url);
                if (res.ok) {
                    const data = await res.json();
                    inpCodigo.value = data.codigo;
                    // También disparamos evento change por si acaso hay validación
                    inpCodigo.dispatchEvent(new Event('change'));

                    // También disparamos evento change por si acaso hay validación
                    inpCodigo.dispatchEvent(new Event('change'));
                } else {
                    inpCodigo.value = "Error";
                    console.error("Error al obtener código", res.status);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Generación',
                        text: 'No se pudo generar el código automáticamente. Por favor recarga la página.',
                        confirmButtonColor: '#0234AB'
                    });
                }
            } catch (e) {
                console.error("Excepción al obtener código", e);
                inpCodigo.value = "Error";
            } finally {
                inpCodigo.disabled = false;
            }
        });
    }

    // Gestión de filas de imágenes dinámicas
    document.addEventListener("click", e => {
        const add = e.target.closest("[data-anadir-fila]");
        const del = e.target.closest("[data-eliminar-fila]");

        if (add) {
            const clave = add.dataset.anadirFila;
            const lista = document.querySelector(`[data-lista-imagenes="${clave}"]`);
            const tpl = document.getElementById(`plantilla-${clave}`);
            if (lista && tpl) {
                lista.appendChild(tpl.content.firstElementChild.cloneNode(true));
                hayCambiosSinGuardar = true;
            }
        }

        if (del) {
            const fila = del.closest(".fila-imagen");
            if (fila && !fila.classList.contains("fila-obligatoria")) {
                const lista = fila.closest("[data-lista-imagenes]");
                if (lista?.querySelectorAll(".fila-imagen").length > 1) {
                    fila.remove();
                } else {
                    // Limpiar inputs si es la última fila
                    fila.querySelectorAll("input, select").forEach(i => i.value = "");
                    // Limpiar preview si existe
                    const preview = fila.querySelector(".img-preview");
                    if (preview) preview.remove();
                }
                hayCambiosSinGuardar = true;
            }
        }
    });

    // Manejo de botones con acciones específicas
    document.addEventListener("click", e => {
        // Botón "Volver" (navegación entre pasos)
        const btnVolver = e.target.closest('[data-volver-paso]');
        if (btnVolver) {
            const pasoDestino = btnVolver.dataset.volverPaso;
            const btnPaso = document.querySelector(`.paso[data-paso="${pasoDestino}"]`);
            if (btnPaso) btnPaso.click();
        }

        // Trigger de input file oculto
        const btnSubir = e.target.closest('[data-trigger-upload]');
        if (btnSubir) {
            const targetId = btnSubir.dataset.triggerUpload;
            const input = document.getElementById(targetId);
            if (input) input.click();
        }

        // Botón de borrar imagen (el que antes era onclick="borrarImagen...")
        const btnBorrar = e.target.closest('[data-borrar-imagen-url]');
        if (btnBorrar) {
            window.borrarImagen(e, btnBorrar.dataset.borrarImagenUrl);
        }
    });

    // Previsualización y gestión de subida de imágenes (Antes en upload-imagenes.blade.php)
    document.addEventListener('change', (e) => {
        if (e.target.matches('.input-previsualizable')) {
            const input = e.target;
            const { fase, zoom, nameDesc } = input.dataset;
            const containerId = `container-${fase}-${zoom}`;
            const container = document.getElementById(containerId);

            if (input.files && input.files.length > 0 && container) {
                const parent = container.closest('.subtarjeta-cuerpo');
                const existingCount = parent.querySelectorAll('.imagen-card').length;
                const newQueuedCount = container.querySelectorAll('.nueva-imagen-fila').length;
                const totalCurrent = existingCount + newQueuedCount;

                if (totalCurrent + input.files.length > 6) {
                    const disponibles = 6 - totalCurrent;

                    if (disponibles <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Límite alcanzado',
                            text: 'Ya tienes el máximo de 6 imágenes permitidas para esta sección.',
                            confirmButtonColor: '#0234AB'
                        });
                        input.value = '';
                        return;
                    }

                    // Si hay hueco pero seleccionó de más, avisamos y recortamos
                    Swal.fire({
                        icon: 'warning',
                        title: 'Demasiadas imágenes',
                        text: `Has seleccionado ${input.files.length} fotos, pero el límite es 6. Solo se añadirán las primeras ${disponibles} que caben para no superar el tope.`,
                        confirmButtonColor: '#0234AB'
                    });

                    // Filtrar archivos usando DataTransfer (necesario para modificar input.files)
                    const dataTransfer = new DataTransfer();
                    Array.from(input.files).slice(0, disponibles).forEach(file => {
                        dataTransfer.items.add(file);
                    });
                    input.files = dataTransfer.files;
                }

                const loteId = 'lote-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
                actualizarContador(container); // Actualizar tras añadir

                // Generar previews
                Array.from(input.files).forEach((file) => {
                    const reader = new FileReader();
                    reader.onload = function (ev) {
                        const div = document.createElement('div');
                        div.className = 'nueva-imagen-fila';
                        div.dataset.lote = loteId;
                        div._inputSource = input; // Vinculamos para poder borrar el input luego

                        div.innerHTML = `
                            <div class="preview-thumb">
                                <img src="${ev.target.result}" title="${file.name}">
                            </div>
                            <div class="preview-inputs">
                                <span class="badge-nueva">NUEVA</span>
                                <input type="text" 
                                       name="${nameDesc}" 
                                       placeholder="Nombre" 
                                       class="control-campo control-sm">
                                <span class="nombre-archivo">${file.name}</span>
                            </div>
                            <button type="button" class="boton-icono btn-eliminar-lote" title="Eliminar imagen (y su lote de subida)">
                                ✕
                            </button>
                        `;
                        container.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });

                // ROTACIÓN DE INPUTS (Para permitir multiselección sucesiva)
                const newInput = input.cloneNode(true);
                newInput.value = '';

                input.removeAttribute('id');
                input.style.display = 'none';
                input.classList.remove('input-previsualizable'); // Evitar que el viejo re-dispare
                input.dataset.lote = loteId;

                input.parentNode.insertBefore(newInput, input.nextSibling);
                hayCambiosSinGuardar = true;
            }
        }
    });

    // Delegación para eliminar lotes de imágenes nuevas
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-eliminar-lote');
        if (btn) {
            const fila = btn.closest('.nueva-imagen-fila');
            const loteId = fila?.dataset.lote;
            const input = fila?._inputSource;

            pedirConfirmacion('¿Eliminar esta imagen?', 'Si subiste varias a la vez se eliminarán todas las de ese grupo.').then(confirmado => {
                if (confirmado) {
                    // Borrar visualmente todas las del lote
                    document.querySelectorAll(`.nueva-imagen-fila[data-lote="${loteId}"]`).forEach(el => el.remove());
                    // Borrar el input oculto asociado
                    if (input) input.remove();
                    hayCambiosSinGuardar = true;
                }
            });
        }
    });

    // Confirmación al finalizar informe (Actualizado para no depender de onclick)
    const btnFinalizar = document.querySelector('.btn-finalizar-informe');
    if (btnFinalizar) {
        btnFinalizar.addEventListener('click', (e) => {
            const form = btnFinalizar.closest('form');
            if (!form) return;

            // Validar si es fase 4
            if (typeof window.validarFase4 === 'function') {
                if (!window.validarFase4()) {
                    e.preventDefault();
                    return;
                }
            }

            // Ya no pedimos confirmación, enviamos directamente
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'stay';
            hiddenInput.value = '0';
            form.appendChild(hiddenInput);
            // El submit ocurrirá de forma natural tras el click si no prevenimos el default
        });
    }

    // Inicializar fase
    cambiarAFase(config.faseInicial);
});

// Función global para borrar imágenes (forma tradicional con recarga)
window.borrarImagen = function (e, url) {
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }

    pedirConfirmacion('¿Borrar imagen permanentemente?', 'Esta acción no se puede deshacer.').then(confirmado => {
        if (!confirmado) return;

        // Crear un formulario dinámico para realizar la petición DELETE
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        form.style.display = 'none';

        // Token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (csrfToken) {
            const inputCsrf = document.createElement('input');
            inputCsrf.type = 'hidden';
            inputCsrf.name = '_token';
            inputCsrf.value = csrfToken;
            form.appendChild(inputCsrf);
        }

        // Método spoofing para DELETE
        const inputMethod = document.createElement('input');
        inputMethod.type = 'hidden';
        inputMethod.name = '_method';
        inputMethod.value = 'DELETE';
        form.appendChild(inputMethod);

        document.body.appendChild(form);
        form.submit();
    });
};

/**
 * Helper para actualizar el contador visual y deshabilitar botones si se llega al límite
 */
function actualizarContador(container) {
    if (!container) return;
    const parent = container.closest('.subtarjeta');
    if (!parent) return;

    const existingCount = parent.querySelectorAll('.imagen-card').length;
    const newQueuedCount = container.querySelectorAll('.nueva-imagen-fila').length;
    const total = existingCount + newQueuedCount;

    const counterEl = parent.querySelector('.contador-imagenes');
    const btnUpload = parent.querySelector('[data-trigger-upload]');

    if (counterEl) {
        counterEl.textContent = `${total} / 6`;
        if (total >= 6) {
            counterEl.classList.add('limite-alcanzado');
        } else {
            counterEl.classList.remove('limite-alcanzado');
        }
    }

    if (btnUpload) {
        if (total >= 6) {
            btnUpload.classList.add('boton-deshabilitado');
            btnUpload.disabled = true;
        } else {
            btnUpload.classList.remove('boton-deshabilitado');
            btnUpload.disabled = false;
        }
    }
}

// Escuchar eliminación de filas nuevas para actualizar contador
document.addEventListener('click', (e) => {
    const btnEliminar = e.target.closest('.btn-eliminar-lote');
    if (btnEliminar) {
        // Esperar un pequeño momento para que la fila se elimine del DOM
        setTimeout(() => {
            const container = document.querySelector('.nuevas-imagenes-container');
            if (container) actualizarContador(container);
        }, 50);
    }
});


import { pedirConfirmacion, cambiarAFase } from './formulario-ui.js';

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

    // Navegación entre pasos
    const botonesPasos = document.querySelectorAll(".paso");
    botonesPasos.forEach(btn => {
        btn.addEventListener("click", () => {
            const n = parseInt(btn.dataset.paso);
            cambiarAFase(n);
        });
    });

    // Autogeneración de Código ID según tipo de muestra
    const selMuestra = document.getElementById("tipo_muestra");
    const inpCodigo = document.getElementById("codigo_identificador");

    if (selMuestra) {
        selMuestra.addEventListener("change", async () => {
            const prefijo = selMuestra.value;
            const divOrg = document.getElementById("campoOrgano");
            const inpOrg = document.getElementById("organo");

            // Mostrar órgano solo si es Biopsia (B)
            if (divOrg) divOrg.classList.toggle("oculto", prefijo !== "B");
            if (inpOrg) inpOrg.required = (prefijo === "B");

            if (!prefijo) { inpCodigo.value = ""; return; }

            try {
                const res = await fetch(`/tipos/${prefijo}/siguiente-codigo`);
                if (res.ok) {
                    const data = await res.json();
                    inpCodigo.value = data.codigo;
                }
            } catch (e) {
                console.error("Error al obtener código", e);
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

    // Previsualización de imágenes
    document.addEventListener('change', (e) => {
        if (e.target.matches('input[type="file"]')) {
            const input = e.target;
            const file = input.files[0];
            const contenedor = input.closest('.archivo-imagen');

            // Eliminar preview anterior si existe
            let oldPreview = contenedor.querySelector('.img-preview');
            if (oldPreview) oldPreview.remove();

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-preview';
                    // Nota: Estilos ahora estarán en CSS
                    img.classList.add('imagen-previsualizada');
                    contenedor.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        }
    });

    // Confirmación al finalizar informe
    const btnFinalizar = document.querySelector('button[onclick*="stayFase4"][onclick*="0"]');
    if (btnFinalizar) {
        btnFinalizar.addEventListener('click', async (e) => {
            e.preventDefault();
            document.getElementById('stayFase4').value = '0';

            const confirmado = await pedirConfirmacion(
                '¿Finalizar informe?',
                'Revisa que todos los datos e imágenes sean correctos. Una vez finalizado pasará a revisión.'
            );

            if (confirmado) {
                btnFinalizar.closest('form').submit();
            }
        });
    }

    // Inicializar fase
    cambiarAFase(config.faseInicial);
});

// Función global para borrar imágenes sin recargar forms
window.borrarImagen = async function (e, url) {
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }

    if (!confirm('¿Seguro que quieres borrar esta imagen permanentemente?')) return;

    try {
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const token = tokenMeta ? tokenMeta.content : '';
        const respuesta = await fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        if (respuesta.ok) {
            const btn = e.target.closest('button');
            if (btn) {
                const item = btn.closest('.imagen-item, .img-preview-container');
                if (item) {
                    item.style.transition = 'opacity 0.3s, transform 0.3s';
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.9)';
                    setTimeout(() => item.remove(), 300);
                } else {
                    window.location.reload();
                }
            } else {
                window.location.reload();
            }
        } else {
            console.error('Error del servidor', respuesta);
            alert('No se pudo eliminar la imagen. Inténtalo de nuevo.');
        }
    } catch (error) {
        console.error('Error de red:', error);
        alert('Error de conexión al intentar borrar.');
    }
};

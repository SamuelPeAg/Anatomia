
document.addEventListener('DOMContentLoaded', () => {
    const entradaNombre = document.getElementById('paciente_nombre');
    const lista = document.getElementById('autocomplete-list');
    if (!entradaNombre || !lista) return;

    const entradaCorreo = document.getElementById('paciente_correo');
    const cajaAviso = document.getElementById('similarity-warning');
    const sugerenciaNombre = document.getElementById('suggested-name');
    let temporizadorEspera;

    const seleccionarPaciente = (nombre, correo) => {
        entradaNombre.value = nombre;
        if (correo && entradaCorreo && !entradaCorreo.value) {
            entradaCorreo.value = correo;
        }
        lista.style.display = 'none';
        if (cajaAviso) cajaAviso.style.display = 'none';
    };

    document.addEventListener('click', e => {
        if (e.target !== entradaNombre && e.target !== lista) {
            lista.style.display = 'none';
        }
    });

    sugerenciaNombre?.addEventListener('click', () => {
        seleccionarPaciente(sugerenciaNombre.textContent, '');
    });

    entradaNombre.addEventListener('input', () => {
        const termino = entradaNombre.value;
        if (cajaAviso) cajaAviso.style.display = 'none';

        if (termino.length < 2) {
            lista.style.display = 'none';
            return;
        }

        clearTimeout(temporizadorEspera);
        temporizadorEspera = setTimeout(() => {
            fetch(`/api/expedientes/search?term=${encodeURIComponent(termino)}`)
                .then(res => res.json())
                .then(datos => {
                    lista.innerHTML = '';
                    if (datos.length) {
                        datos.forEach(item => {
                            const li = document.createElement('li');
                            li.innerHTML = item.nombre.replace(new RegExp(`(${termino})`, "gi"), "<strong>$1</strong>");
                            li.onclick = () => seleccionarPaciente(item.nombre, item.correo);
                            lista.appendChild(li);
                        });
                        lista.style.display = 'block';
                    } else {
                        lista.style.display = 'none';
                    }
                });
        }, 300);
    });
});

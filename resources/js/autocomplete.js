
document.addEventListener('DOMContentLoaded', () => {
    const inputNombre = document.getElementById('paciente_nombre');
    const list = document.getElementById('autocomplete-list');
    if (!inputNombre || !list) return;

    const inputCorreo = document.getElementById('paciente_correo');
    const warningBox = document.getElementById('similarity-warning');
    const suggestedNameParams = document.getElementById('suggested-name');
    let debounceTimer;

    const selectPatient = (name, email) => {
        inputNombre.value = name;
        if (email && inputCorreo && !inputCorreo.value) inputCorreo.value = email;
        list.style.display = 'none';
        if (warningBox) warningBox.style.display = 'none';
    };

    document.addEventListener('click', e => {
        if (e.target !== inputNombre && e.target !== list) list.style.display = 'none';
    });

    suggestedNameParams?.addEventListener('click', () => {
        selectPatient(suggestedNameParams.textContent, '');
    });

    inputNombre.addEventListener('input', () => {
        const term = inputNombre.value;
        if (warningBox) warningBox.style.display = 'none';
        if (term.length < 2) { list.style.display = 'none'; return; }

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            fetch(`/api/expedientes/search?term=${encodeURIComponent(term)}`)
                .then(res => res.json())
                .then(data => {
                    list.innerHTML = '';
                    if (data.length) {
                        data.forEach(item => {
                            const li = document.createElement('li');
                            li.innerHTML = item.nombre.replace(new RegExp(`(${term})`, "gi"), "<strong>$1</strong>");
                            li.onclick = () => selectPatient(item.nombre, item.correo);
                            list.appendChild(li);
                        });
                        list.style.display = 'block';
                    } else list.style.display = 'none';
                });
        }, 300);
    });
});

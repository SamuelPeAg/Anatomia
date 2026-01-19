document.addEventListener("DOMContentLoaded", () => {

  const botonesPasos = document.querySelectorAll(".paso");
  const seccionesFase = document.querySelectorAll(".fase");
  const tituloFase = document.getElementById("tituloFase");

  const titulosPorFase = {
    1: "Fase 1 — Recepción",
    2: "Fase 2 — Procesamiento",
    3: "Fase 3 — Tinción",
    4: "Fase 4 — Citodiagnóstico",
  };

  function cambiarAFase(numeroFase) {

    seccionesFase.forEach((fase) => fase.classList.remove("fase-activa"));
    botonesPasos.forEach((paso) => paso.classList.remove("paso-activo"));

    const faseActual = document.getElementById(`fase-${numeroFase}`);
    const botonPasoActual = document.querySelector(`.paso[data-paso="${numeroFase}"]`);

    if (faseActual) faseActual.classList.add("fase-activa");
    if (botonPasoActual) botonPasoActual.classList.add("paso-activo");

    if (tituloFase) {
      tituloFase.textContent = titulosPorFase[numeroFase] || "Nuevo informe";
    }
  }

  // Click en los pasos de arriba
  botonesPasos.forEach((boton) => {
    boton.addEventListener("click", () => {
      const numero = Number(boton.dataset.paso);
      cambiarAFase(numero);
    });
  });

  // Botones siguiente / volver
  const botonIrFase2 = document.getElementById("botonIrFase2");
  const botonIrFase3 = document.getElementById("botonIrFase3");
  const botonIrFase4 = document.getElementById("botonIrFase4");

  const botonVolverFase1 = document.getElementById("botonVolverFase1");
  const botonVolverFase2 = document.getElementById("botonVolverFase2");
  const botonVolverFase3 = document.getElementById("botonVolverFase3");

  if (botonIrFase2) botonIrFase2.addEventListener("click", () => cambiarAFase(2));
  if (botonIrFase3) botonIrFase3.addEventListener("click", () => cambiarAFase(3));
  if (botonIrFase4) botonIrFase4.addEventListener("click", () => cambiarAFase(4));

  if (botonVolverFase1) botonVolverFase1.addEventListener("click", () => cambiarAFase(1));
  if (botonVolverFase2) botonVolverFase2.addEventListener("click", () => cambiarAFase(2));
  if (botonVolverFase3) botonVolverFase3.addEventListener("click", () => cambiarAFase(3));

  // =========================
  // CAMPOS CONDICIONALES
  // =========================
  const tipoMuestra = document.getElementById("tipo_muestra");
  const campoOrgano = document.getElementById("campoOrgano");
  const inputOrgano = document.getElementById("organo");

  function mostrarOcultarOrgano() {
    const esBiopsia = tipoMuestra && tipoMuestra.value === "B";

    if (campoOrgano) {
      // si NO es biopsia -> oculto
      campoOrgano.classList.toggle("oculto", !esBiopsia);
    }
    if (inputOrgano) inputOrgano.required = !!esBiopsia;
  }

  if (tipoMuestra) {
    tipoMuestra.addEventListener("change", mostrarOcultarOrgano);
    mostrarOcultarOrgano();
  }

  const tipoProcesamiento = document.getElementById("tipo_procesamiento");
  const campoProcesamientoOtro = document.getElementById("campoProcesamientoOtro");
  const inputProcesamientoOtro = document.getElementById("procesamiento_otro");

  function mostrarOcultarProcesamientoOtro() {
    const esOtro = tipoProcesamiento && tipoProcesamiento.value === "OTRO";

    if (campoProcesamientoOtro) {
      campoProcesamientoOtro.classList.toggle("oculto", !esOtro);
    }
    if (inputProcesamientoOtro) inputProcesamientoOtro.required = !!esOtro;
  }

  if (tipoProcesamiento) {
    tipoProcesamiento.addEventListener("change", mostrarOcultarProcesamientoOtro);
    mostrarOcultarProcesamientoOtro();
  }
array.forEach(element => {
  
});
  // =========================
  // AÑADIR / ELIMINAR FILAS DE IMÁGENES (delegación)
  // Para: recepcion, procesamiento, tincion, micro-extra
  // =========================
  function anadirFila(clave) {
    const lista = document.querySelector(`[data-lista-imagenes="${clave}"]`);
    const plantilla = document.getElementById(`plantilla-${clave}`);

    if (!lista || !plantilla) {
      console.warn("No se encuentra data-lista-imagenes/plantilla para:", clave);
      return;
    }

    const nuevaFila = plantilla.content.firstElementChild.cloneNode(true);
    lista.appendChild(nuevaFila);
  }

  function limpiarFila(fila) {
    fila.querySelectorAll("input").forEach((input) => {
      input.value = "";
    });
    fila.querySelectorAll("select").forEach((select) => {
      select.value = "";
    });
  }

  function eliminarFila(boton) {
    const fila = boton.closest(".fila-imagen");
    if (!fila) return;

    // No se permite borrar las filas obligatorias (x4,x10,x40,x100)
    if (fila.classList.contains("fila-obligatoria")) return;

    const lista = fila.closest("[data-lista-imagenes]");
    if (!lista) return;

    const filas = lista.querySelectorAll(".fila-imagen");

    // Si solo queda 1 fila, no la borramos: la limpiamos
    if (filas.length <= 1) {
      limpiarFila(fila);
      return;
    }

    fila.remove();
  }

  document.addEventListener("click", (evento) => {
    const botonAnadir = evento.target.closest("[data-anadir-fila]");
    if (botonAnadir) {
      anadirFila(botonAnadir.dataset.anadirFila);
      return;
    }

    const botonEliminar = evento.target.closest("[data-eliminar-fila]");
    if (botonEliminar) {
      eliminarFila(botonEliminar);
      return;
    }
  });

  // Inicial
  cambiarAFase(1);
});

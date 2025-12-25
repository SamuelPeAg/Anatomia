document.addEventListener("DOMContentLoaded", () => {
  // =========================
  // FASES (wizard)
  // =========================
  const steps = document.querySelectorAll(".step");
  const phases = document.querySelectorAll(".phase");
  const faseTitle = document.getElementById("faseTitle");

  const titles = {
    1: "Fase 1 — Recepción",
    2: "Fase 2 — Procesamiento",
    3: "Fase 3 — Tinción",
    4: "Fase 4 — Citodiagnóstico",
  };

  function setStep(n) {
    phases.forEach((p) => p.classList.remove("phase--active"));
    steps.forEach((s) => s.classList.remove("step--active"));

    const phase = document.getElementById(`phase-${n}`);
    const stepBtn = document.querySelector(`.step[data-step="${n}"]`);
    if (phase) phase.classList.add("phase--active");
    if (stepBtn) stepBtn.classList.add("step--active");
    if (faseTitle) faseTitle.textContent = titles[n] || "Nuevo informe";
  }

  steps.forEach((btn) => {
    btn.addEventListener("click", () => setStep(Number(btn.dataset.step)));
  });

  // Botones next/back
  const toPhase2 = document.getElementById("toPhase2");
  const toPhase3 = document.getElementById("toPhase3");
  const toPhase4 = document.getElementById("toPhase4");
  const backTo1 = document.getElementById("backTo1");
  const backTo2 = document.getElementById("backTo2");
  const backTo3 = document.getElementById("backTo3");

  if (toPhase2) toPhase2.addEventListener("click", () => setStep(2));
  if (toPhase3) toPhase3.addEventListener("click", () => setStep(3));
  if (toPhase4) toPhase4.addEventListener("click", () => setStep(4));
  if (backTo1) backTo1.addEventListener("click", () => setStep(1));
  if (backTo2) backTo2.addEventListener("click", () => setStep(2));
  if (backTo3) backTo3.addEventListener("click", () => setStep(3));

  // =========================
  // CAMPOS CONDICIONALES
  // =========================
  const tipoMuestra = document.getElementById("tipo_muestra");
  const organoField = document.getElementById("organoField");
  const organoInput = document.getElementById("organo");

  function toggleOrgano() {
    const isBiopsia = tipoMuestra && tipoMuestra.value === "B";
    if (organoField) organoField.classList.toggle("field--hidden", !isBiopsia);
    if (organoInput) organoInput.required = !!isBiopsia;
  }

  if (tipoMuestra) {
    tipoMuestra.addEventListener("change", toggleOrgano);
    toggleOrgano();
  }

  const tipoProcesamiento = document.getElementById("tipo_procesamiento");
  const procOtroField = document.getElementById("procesamientoOtroField");
  const procOtroInput = document.getElementById("procesamiento_otro");

  function toggleProcOtro() {
    const isOtro = tipoProcesamiento && tipoProcesamiento.value === "OTRO";
    if (procOtroField) procOtroField.classList.toggle("field--hidden", !isOtro);
    if (procOtroInput) procOtroInput.required = !!isOtro;
  }

  if (tipoProcesamiento) {
    tipoProcesamiento.addEventListener("change", toggleProcOtro);
    toggleProcOtro();
  }

  // =========================
  // AÑADIR / ELIMINAR FILAS DE IMÁGENES (delegación)
  // Works for: recepcion, procesamiento, tincion, micro-extra
  // =========================
  function addRow(key) {
    const list = document.querySelector(`[data-img-list="${key}"]`);
    const tpl = document.getElementById(`tpl-${key}`);

    if (!list || !tpl) {
      console.warn("No se encuentra data-img-list/template para:", key);
      return;
    }

    const node = tpl.content.firstElementChild.cloneNode(true);
    list.appendChild(node);
  }

  function clearRow(row) {
    row.querySelectorAll("input").forEach((i) => {
      if (i.type === "file") i.value = "";
      else i.value = "";
    });
    row.querySelectorAll("select").forEach((s) => (s.value = ""));
  }

  function removeRow(btn) {
    const row = btn.closest(".img-row");
    if (!row) return;

    // No se permite borrar las filas obligatorias (x4,x10,x40,x100)
    if (row.classList.contains("img-row--required")) return;

    const list = row.closest("[data-img-list]");
    if (!list) return;

    const rows = list.querySelectorAll(".img-row");
    if (rows.length <= 1) {
      // si es la última fila, la vaciamos (para permitir "0 imágenes" sin romper la UI)
      clearRow(row);
      return;
    }

    row.remove();
  }

  document.addEventListener("click", (e) => {
    const addBtn = e.target.closest("[data-add-row]");
    if (addBtn) {
      addRow(addBtn.dataset.addRow);
      return;
    }

    const removeBtn = e.target.closest("[data-remove-row]");
    if (removeBtn) {
      removeRow(removeBtn);
      return;
    }
  });

  // Inicial
  setStep(1);
});

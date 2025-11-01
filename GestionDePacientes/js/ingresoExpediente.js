async function cargarExpedientes() {
  const resp = await fetch("php/listarExpedientes.php");
  const expedientes = await resp.json();

  const tbody = document.querySelector("#tablaExpedientes tbody");
  tbody.innerHTML = "";

  expedientes.forEach(exp => {
    const fila = document.createElement("tr");
    fila.innerHTML = `
      <td>${exp.id_expediente}</td>
      <td>${exp.id_paciente}</td>
      <td>${exp.grupo_sanguineo}</td>
      <td>${exp.motivo_consulta}</td>
      <td>${exp.diagnostico}</td>
      <td>${exp.tipo_atencion}</td>
    `;
    tbody.appendChild(fila);
  });
}

window.addEventListener("DOMContentLoaded", cargarExpedientes);

document.getElementById("btnGuardarExpediente").addEventListener("click", async () => {
  const campos = [
    "id_paciente", "grupo_sanguineo", "alergias", "enfermedades_cronicas", "medicamentos_actuales",
    "antecedentes_familiares", "antecedentes_personales", "vacunas", "motivo_consulta", "diagnostico",
    "tratamiento", "medico_responsable", "area_atencion", "tipo_atencion", "observaciones_medicas"
  ];

  const expediente = {};
  campos.forEach(id => {
    expediente[id] = document.getElementById(id).value.trim();
  });

  expediente.fecha_creacion = new Date().toISOString().slice(0, 19).replace("T", " ");
  expediente.fecha_ultima_actualizacion = expediente.fecha_creacion;

  const resp = await fetch("php/guardarExpediente.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(expediente)
  });

  const data = await resp.json();
  if (data.success) {
    alert("✅ Expediente guardado");
    document.getElementById("formExpediente").reset();
    cargarExpedientes();
  } else {
    alert("❌ Error: " + (data.error || "Desconocido"));
  }
});

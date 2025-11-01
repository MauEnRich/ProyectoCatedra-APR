async function cargarCitas() {
  const resp = await fetch("php/listarCitas.php");
  const citas = await resp.json();

  const tbody = document.querySelector("#tablaCitas tbody");
  tbody.innerHTML = ""; 

  citas.forEach(cita => {
    const fila = document.createElement("tr");
    fila.innerHTML = `
      <td>${cita.id}</td>
      <td>${cita.paciente_id}</td>
      <td>${cita.fecha}</td>
      <td>${cita.hora}</td>
      <td>${cita.motivo}</td>
      <td>${cita.estado}</td>
    `;
    tbody.appendChild(fila);
  });
}

window.addEventListener("DOMContentLoaded", cargarCitas);

document.getElementById("btnCrearCita").addEventListener("click", async () => {
  const paciente_id = document.getElementById("paciente_id").value.trim();
  const fecha = document.getElementById("fecha").value;
  const hora = document.getElementById("hora").value;
  const motivo = document.getElementById("motivo").value.trim();
  const estado = document.getElementById("estado").value;

  if (!paciente_id || !fecha || !hora || !motivo || !estado) {
    alert("❌ Todos los campos son obligatorios");
    return;
  }

  const resp = await fetch("php/crearCita.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ paciente_id, fecha, hora, motivo, estado })
  });

  const data = await resp.json();
  if (data.success) {
    alert("✅ Cita registrada");
    document.getElementById("formCita").reset();
    cargarCitas();
  } else {
    alert("❌ Error: " + (data.error || "Desconocido"));
  }
});

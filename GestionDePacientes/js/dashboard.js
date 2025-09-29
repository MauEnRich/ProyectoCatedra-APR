document.addEventListener("DOMContentLoaded", () => {
  const rol = localStorage.getItem("rol");
  const mensaje = document.getElementById("mensaje");
 

  
  if (rol === "usuario") {
    document.getElementById("formAgregarPaciente").style.display = "none";
  }

 
  async function cargarPacientes() {
    const resp = await fetch("php/obtenerPacientes.php");
    const pacientes = await resp.json();
    const lista = document.getElementById("listaPacientes");
    lista.innerHTML = "";
    pacientes.forEach(p => {
      const li = document.createElement("li");
      li.textContent = `${p.nombre} ${p.apellido} - ${p.edad || "Edad no registrada"} años`;
      lista.appendChild(li);
    });
  }

  cargarPacientes();
if (rol === "admin") {
  const btnAgregar = document.getElementById("btnAgregar");
  btnAgregar.addEventListener("click", async () => {
    const nombre = document.getElementById("nombre").value.trim();
    const apellido = document.getElementById("apellido").value.trim();
    const edad = document.getElementById("edad").value.trim();
    const genero = document.getElementById("genero").value;
    const telefono = document.getElementById("telefono").value.trim();
    const direccion = document.getElementById("direccion").value.trim();

    if (!nombre || !apellido) {
      alert("❌ Nombre y apellido son obligatorios");
      return;
    }

    const resp = await fetch("php/agregarPaciente.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ nombre, apellido, edad, genero, telefono, direccion })
    });

    const data = await resp.json();
    if (data.success) {
      alert("Paciente agregado ✅");
      document.getElementById("nombre").value = "";
      document.getElementById("apellido").value = "";
      document.getElementById("edad").value = "";
      document.getElementById("genero").value = "";
      document.getElementById("telefono").value = "";
      document.getElementById("direccion").value = "";
      cargarPacientes();
    } else {
      alert("❌ Error al agregar paciente: " + (data.error || "Desconocido"));
    }
  });
}

});

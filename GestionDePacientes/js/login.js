document.getElementById("formLogin").addEventListener("submit", async (e) => {
  e.preventDefault();

  const usuario = document.getElementById("usuario").value.trim();
  const password = document.getElementById("password").value.trim();


  const respuesta = await fetch("php/login.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ usuario, password })
  });

  const data = await respuesta.json();

  if (data.success) {
    
    localStorage.setItem("rol", data.rol);
    localStorage.setItem("usuario", usuario);

  
    window.location.href = "dashboard.html";
  } else {
    alert("❌ Usuario o contraseña incorrectos");
  }
});

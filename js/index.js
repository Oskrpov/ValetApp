import { enivarAjax } from "./tools.js";

document.addEventListener("click", (e) => {
    if (e.target.matches("#nuevocliente")) location.href = "nuevo_cliente.html"
    if (e.target.matches("#buscarcliente")) location.href = "busqueda_cliente.html"
}
)
let modoEdicion = false;

document.addEventListener("submit", (e) => {
  if (e.target.matches("#form_busqueda")) {
    e.preventDefault();

    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());

    enivarAjax({
      url: "../api/registros/buscar_clientemod.php",
      method: "GET",
      param: data,
      fresp: (resp) => {
        if (resp.code === 200) {
          const contenedor = document.querySelector("#infocliente");
          contenedor.innerHTML = `
            <div class="form-group">
              <label>Nombres</label>
              <input type="text" class="form-control" id="nombres" value="${resp.datos[0].Nombres_Usu}" disabled readonly>
            </div>
            <div class="form-group">
              <label>Apellidos</label>
              <input type="text" class="form-control" id="apellidos" value="${resp.datos[0].Apellidos_Usu}" disabled readonly>
            </div>`;

          // Mostrar botones
          document.getElementById("editar").style.display = "inline-block";
          document.getElementById("eliminar").style.display = "inline-block";
          document.getElementById("continuar").style.display = "inline-block";
          document.getElementById("buscador").style.display = "none";

          // Ahora que los inputs existen, agregamos el listener de editar
          modoEdicion = false; // global o definida arriba
          document.getElementById("editar").onclick = async function () {
            const nombresInput = document.getElementById("nombres");
            const apellidosInput = document.getElementById("apellidos");
            const documentoInput = document.querySelector("input[name='documento']");

            if (!modoEdicion) {
              nombresInput.disabled = false;
              apellidosInput.disabled = false;
              nombresInput.removeAttribute("readonly");
              apellidosInput.removeAttribute("readonly");

              if (documentoInput) {
                documentoInput.disabled = false;
                documentoInput.removeAttribute("readonly");
              }

              let mensajeEdicion = document.getElementById("mensaje-edicion");
              if (!mensajeEdicion) {
                mensajeEdicion = document.createElement("p");
                mensajeEdicion.id = "mensaje-edicion";
                mensajeEdicion.style.color = "orange";
                mensajeEdicion.innerText = "Realiza los cambios y vuelve a pulsar 'Editar' para guardar.";
                contenedor.appendChild(mensajeEdicion);
              }

              modoEdicion = true;
            } else {
              const nuevosDatos = {
                nombres: nombresInput.value.trim(),
                apellidos: apellidosInput.value.trim(),
                documento: documentoInput?.value.trim() || ""
              };

              try {
                const resp = await fetch("../api/registros/actualizar_datos.php", {
                  method: "POST",
                  headers: { "Content-Type": "application/json" },
                  body: JSON.stringify(nuevosDatos)
                });

                const resultado = await resp.json();
                if (resultado.code === 200) {
                  alert("Datos actualizados correctamente");

                  nombresInput.disabled = true;
                  apellidosInput.disabled = true;
                  nombresInput.setAttribute("readonly", true);
                  apellidosInput.setAttribute("readonly", true);
                  if (documentoInput) {
                    documentoInput.disabled = true;
                    documentoInput.setAttribute("readonly", true);
                  }

                  const mensaje = document.getElementById("mensaje-edicion");
                  if (mensaje) mensaje.remove();

                  modoEdicion = false;
                } else {
                  alert("Error: " + resultado.msg);
                }
              } catch (error) {
                console.error("Error al actualizar", error);
                alert("Fallo al actualizar los datos");
              }
            }
          };
        } else {
          alert("No se encontraron resultados");
        }
      }
    });
  }
});
document.addEventListener("DOMContentLoaded", () => {
  const continuarBtn = document.querySelector("#form_busqueda #continuar");
  if (continuarBtn) {
    continuarBtn.addEventListener("click", () => {
      // Solo redirige si no estamos en modo edición
      if (!modoEdicion) {
        window.location.href = "novedades_vehiculo.html";
      } else {
        alert("Termina de editar y guarda los cambios antes de continuar.");
      }
    });
  }
});

document.addEventListener("click", (e) => {
    if (e.target.matches("#botonbuscar")) location.href = "datos_cliente.html"
}
)
document.addEventListener("click", (e) => {
    if (e.target.matches("#continuarnovedades")) location.href = "datos_cliente.html"
}
)
document.addEventListener("click", (e) => {
    if (e.target.matches("#cierre")) location.href = "inicio_sesion.html"
}
)
document.addEventListener("click", (e) => {
    if (e.target.matches("#regresar")) location.href = "index.html"
}
)
document.addEventListener("click", (e) => {
    if (e.target.matches("#Continuarclientenuevo")) location.href = "novedades_vehiculo.html"
}
)
document.addEventListener("click", (e) => {
    if (e.target.matches("#ubicarvehiculo")) location.href = "busqueda_vehiculo.html"
}
)
document.addEventListener("click", (e) => {
    if (e.target.matches("#buscarregistro")) location.href = "buscar_registro.html"
}
)
document.addEventListener("click", (e) => {
    if (e.target.matches("#Guardarclientenuevo")) location.href = "index.html"
}
)
document.addEventListener("click", (e) => {
    if (e.target.matches("#exitoso")) location.href = "index.html"
}
)

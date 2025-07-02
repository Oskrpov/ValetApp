import { enivarAjax } from "./tools.js";

document.addEventListener("click", (e) => {
  if (e.target.matches("#nuevocliente")) location.href = "nuevo_cliente.html"
  if (e.target.matches("#buscarcliente")) location.href = "busqueda_cliente.html"
  if (e.target.matches("#botonbuscar")) location.href = "datos_cliente.html"
  if (e.target.matches("#continuarnovedades")) location.href = "datos_cliente.html"
  if (e.target.matches("#cierre")) location.href = "inicio_sesion.html"
  if (e.target.matches("#regresar")) location.href = "index.html"
  if (e.target.matches("#Continuarclientenuevo")) location.href = "novedades_vehiculo.html"
  if (e.target.matches("#ubicarvehiculo")) location.href = "busqueda_vehiculo.html"
  if (e.target.matches("#buscarregistro")) location.href = "buscar_registro.html"
  if (e.target.matches("#Guardarclientenuevo")) location.href = "index.html"
  if (e.target.matches("#historial")) location.href = "historial.html"
  if (e.target.matches("#exitoso")) location.href = "index.html"
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

          // Listener para el botón Eliminar
          document.getElementById("eliminar").onclick = async function () {
            const confirmacion = confirm("¿Estás seguro de que deseas eliminar este cliente y todos sus registros?");
            if (!confirmacion) return;

            const idCliente = resp.datos[0].IdUsuario;

            try {
              const respuesta = await fetch("../api/registros/eliminar_cliente.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id_cliente: idCliente })
              });

              const resultado = await respuesta.json();

              if (resultado.status === "ok") {
                alert("Cliente y registros eliminados correctamente");
                document.getElementById("form_busqueda").reset();
                document.getElementById("infocliente").innerHTML = "";
                document.getElementById("editar").style.display = "none";
                document.getElementById("eliminar").style.display = "none";
                document.getElementById("continuar").style.display = "none";
                document.getElementById("buscador").style.display = "inline-block";
              } else {
                alert("Error: " + resultado.mensaje);
              }
            } catch (error) {
              console.error("Error al eliminar:", error);
              alert("Hubo un problema al intentar eliminar.");
            }
          };

          // listener de editar
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
//consulta de registro
document.addEventListener("DOMContentLoaded", () => {
  const buscarBtn = document.getElementById("buscarregistro1");
  const seccionNovedades = document.getElementById("novedadesVehiculo");
  const asignarBtn = document.getElementById("asignarSalida");

  if (buscarBtn && seccionNovedades) {
    buscarBtn.addEventListener("click", async () => {
      const documentoInput = document.getElementById("documento");
      const documento = documentoInput?.value.trim();

      if (!documento) {
        alert("Por favor ingresa un número de documento.");
        return;
      }

      try {
        const respuesta = await fetch(`../api/registros/buscar_clientemod.php?documento=${encodeURIComponent(documento)}`);
        const resultado = await respuesta.json();

        if (resultado.code === 200 && resultado.datos.length > 0) {
          const cliente = resultado.datos[0];

          // Rellenar y bloquear campos
          document.getElementById("nombres").value = cliente.Nombres_Usu;
          document.getElementById("apellidos").value = cliente.Apellidos_Usu;
          document.getElementById("documento").value = cliente.Identificacion_Usu;
          document.getElementById("nombres").readOnly = true;
          document.getElementById("apellidos").readOnly = true;

          const idCliente = cliente.IdUsuario;

          // Buscar novedades del vehículo
          try {
            const respuestaNovedades = await fetch(`../api/registros/buscar_novedades.php?id_cliente=${encodeURIComponent(idCliente)}`);
            const novedades = await respuestaNovedades.json();

            if (novedades.code === 200 && novedades.datos.length > 0) {
              const novedad = novedades.datos[0];

              document.getElementById("observaciones").value = novedad.observaciones || "Sin observaciones";
              document.getElementById("elementos").value = novedad.Objet_Valor || "Sin elementos";
              document.getElementById("ubicacion").value = novedad.Ubicacion_veh || "Sin ubicación";
              document.getElementById("placaNovedad").value = novedad.Placa || "Sin placa";
              document.getElementById("entrada").value = novedad.Entrada || "Sin registro";
              document.getElementById("salida").value = novedad.Salida || "Sin registro";
              document.getElementById("imagenNovedad").src = novedad.Imagen_novedad || "";

              seccionNovedades.style.display = "block";
              seccionNovedades.scrollIntoView({ behavior: "smooth" });
            } else {
              alert("No se encontraron novedades para este cliente.");
              seccionNovedades.style.display = "none";
            }
          } catch (error) {
            console.error("Error al buscar novedades:", error);
            alert("Ocurrió un error al buscar las novedades del vehículo.");
            seccionNovedades.style.display = "none";
          }
        } else {
          alert("Cliente no encontrado.");
          seccionNovedades.style.display = "none";
        }
      } catch (error) {
        console.error("Error al buscar cliente:", error);
        alert("Ocurrió un error al buscar el cliente.");
        seccionNovedades.style.display = "none";
      }
    });
  }

  // Botón Asignar Salida
  if (asignarBtn) {
    asignarBtn.addEventListener("click", async () => {
      const documento = document.getElementById("documento").value.trim();

      if (!documento) {
        alert("Por favor ingresa el documento para identificar al cliente.");
        return;
      }

      try {
        const respuestaCliente = await fetch(`../api/registros/buscar_clientemod.php?documento=${encodeURIComponent(documento)}`);
        const datosCliente = await respuestaCliente.json();

        if (datosCliente.code === 200 && datosCliente.datos.length > 0) {
          const idCliente = datosCliente.datos[0].IdUsuario;

          const confirmar = confirm("¿Estás seguro de asignar la hora de salida?");
          if (!confirmar) return;

          const respuesta = await fetch(`../api/registros/asignar_salida.php?id_cliente=${encodeURIComponent(idCliente)}`);
          const resultado = await respuesta.json();

          if (resultado.code === 200) {
            document.getElementById("salida").value = resultado.salida || "Registrado";
            alert("Hora de salida asignada correctamente.");
          } else {
            alert("No se pudo asignar la salida: " + resultado.msg);
          }
        } else {
          alert("Cliente no encontrado.");
        }
      } catch (error) {
        console.error("Error al asignar salida:", error);
        alert("Ocurrió un error al procesar la solicitud.");
      }
    });
  }
});
// Historial de visitas de cliente (solo si estamos en historial.html)
document.addEventListener("DOMContentLoaded", () => {
  const formHistorial = document.getElementById("form_busqueda");
  const contenedorHistorial = document.getElementById("resultado_historial");

  if (formHistorial && contenedorHistorial) {
    formHistorial.addEventListener("submit", async (e) => {
      e.preventDefault();
      const documento = document.getElementById("documento").value.trim();

      try {
        const response = await fetch("../api/registros/buscar_historial_cliente.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `documento=${encodeURIComponent(documento)}`
        });

        const data = await response.json();
        console.log("Respuesta del servidor:", data);

        // ⚠️ Si es objeto con error
        if (!Array.isArray(data)) {
          console.error("Error del servidor:", data.msg || "Respuesta inesperada");
          contenedorHistorial.innerHTML = `<div class="alert alert-danger">Hubo un problema: ${data.msg || "respuesta no válida."}</div>`;
          return;
        }

        // ✅ Si no hay visitas
        if (data.length === 0) {
          contenedorHistorial.innerHTML = `<div class="alert alert-warning">No se encontraron visitas para este cliente.</div>`;
          return;
        }

        // ✅ Construir tabla
        let tabla = `
          <h4>Historial de Visitas</h4>
          <table class="table table-bordered table-striped">
            <thead class="thead-dark">
              <tr>
                <th>Placa</th>
                <th>Ubicación</th>
                <th>Observaciones</th>
                <th>Valor</th>
                <th>Entrada</th>
                <th>Salida</th>
              </tr>
            </thead>
            <tbody>
        `;

        data.forEach(visita => {
          tabla += `
            <tr>
              <td>${visita.Placa}</td>
              <td>${visita.Ubicacion_veh}</td>
              <td>${visita.observaciones}</td>
              <td>${visita.Objet_Valor}</td>
              <td>${visita.Entrada || ''}</td>
              <td>${visita.Salida || ''}</td>
            </tr>
          `;
        });

        tabla += `</tbody></table>`;
        contenedorHistorial.innerHTML = tabla;

      } catch (error) {
        console.error("Error de conexión:", error);
        contenedorHistorial.innerHTML = `<div class="alert alert-danger">Error de conexión con el servidor.</div>`;
      }
    });
  }
});

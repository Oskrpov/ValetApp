console.log("📊 informes.js cargado");

document.addEventListener("DOMContentLoaded", () => {
  const formRango = document.getElementById("form_rango");
  const contenedorTabla = document.getElementById("resultado_rango");
  const contenedorScroll = document.querySelector(".resultado-scroll");
  const limpiarBtn = document.getElementById("limpiar");

  if (formRango && contenedorTabla) {
    formRango.addEventListener("submit", async (e) => {
      e.preventDefault();

      const fechaInicio = document.getElementById("fecha_inicio").value;
      const fechaFin = document.getElementById("fecha_fin").value;

      if (!fechaInicio || !fechaFin) {
        alert("Por favor selecciona ambas fechas.");
        return;
      }

      try {
        const response = await fetch("../api/registros/buscar_por_rango.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          },
          body: `fecha_inicio=${encodeURIComponent(fechaInicio)}&fecha_fin=${encodeURIComponent(fechaFin)}`
        });

        const raw = await response.text();

        try {
          const data = JSON.parse(raw);

          if (!Array.isArray(data)) {
            contenedorTabla.innerHTML = `<div class="alert alert-danger">Respuesta inválida: ${data.msg || "Error desconocido."}</div>`;
            contenedorScroll.style.display = "block";
            return;
          }

          if (data.length === 0) {
            alert("❌ No se encontraron registros para el rango de fechas.");
            contenedorScroll.style.display = "none";
            return;
          }

          let tabla = `
            <h4>Resultados</h4>
            <table class="table table-bordered table-striped">
              <thead class="thead-dark">
                <tr>
                  <th>Nombre</th>
                  <th>Apellido</th>
                  <th>Documento</th>
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

          data.forEach(reg => {
            tabla += `
              <tr>
                <td>${reg.Nombres_Usu}</td>
                <td>${reg.Apellidos_Usu}</td>
                <td>${reg.Identificacion_Usu}</td>
                <td>${reg.Placa}</td>
                <td>${reg.Ubicacion_veh}</td>
                <td>${reg.observaciones}</td>
                <td>${reg.Objet_Valor}</td>
                <td>${reg.Entrada}</td>
                <td>${reg.Salida}</td>
              </tr>
            `;
          });

          tabla += `</tbody></table>`;
          contenedorTabla.innerHTML = tabla;
          contenedorScroll.style.display = "block";

        } catch (errJSON) {
          console.error("Error al parsear JSON:", raw);
          contenedorTabla.innerHTML = `<div class="alert alert-danger">Respuesta no válida del servidor.</div>`;
          contenedorScroll.style.display = "block";
        }

      } catch (errorFetch) {
        console.error("Error al consultar:", errorFetch);
        contenedorTabla.innerHTML = `<div class="alert alert-danger">Error de conexión con el servidor.</div>`;
        contenedorScroll.style.display = "block";
      }
    });
  }

  if (limpiarBtn) {
    limpiarBtn.addEventListener("click", () => {
      document.getElementById("fecha_inicio").value = "";
      document.getElementById("fecha_fin").value = "";
      contenedorTabla.innerHTML = "";
      contenedorScroll.style.display = "none";
    });
  }
});


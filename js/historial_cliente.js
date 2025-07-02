console.log("✅ historial_cliente.js cargado correctamente");

document.addEventListener("DOMContentLoaded", () => {
    const formHistorial = document.getElementById("form_historial");
    const contenedorHistorial = document.getElementById("resultado_historial");

    // Ocultar por defecto al cargar
    if (contenedorHistorial) {
        contenedorHistorial.style.display = "none";
    }

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

                const rawText = await response.text();

                try {
                    const data = JSON.parse(rawText);

                    if (!Array.isArray(data)) {
                        console.warn("Respuesta inesperada:", data);
                        contenedorHistorial.style.display = "none";
                        contenedorHistorial.innerHTML = `<div class="alert alert-danger">Error del servidor: ${data.msg || "respuesta no válida."}</div>`;
                        return;
                    }

                    if (data.length === 0) {
                        contenedorHistorial.style.display = "none";
                        // contenedorHistorial.innerHTML = `<div class="alert alert-warning">No se encontraron visitas para este cliente.</div>`;
                        $('#modalSinResultados').modal('show'); // <- Usa jQuery y Bootstrapreturn;
                    }

                    // Si hay resultados, mostrar tabla
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

                    // Mostrar y rellenar contenedor
                    contenedorHistorial.style.display = "block";
                    contenedorHistorial.innerHTML = tabla;

                } catch (errorParse) {
                    console.error("Error al parsear JSON:", rawText);
                    contenedorHistorial.style.display = "none";
                    contenedorHistorial.innerHTML = `<div class="alert alert-danger">La respuesta del servidor no es válida.</div>`;
                }

            } catch (errorFetch) {
                console.error("Error de conexión:", errorFetch);
                contenedorHistorial.style.display = "none";
                contenedorHistorial.innerHTML = `<div class="alert alert-danger">Error de conexión con el servidor.</div>`;
            }
        });
    }
});
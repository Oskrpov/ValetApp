import {enivarAjax} from "./tools.js";

document.addEventListener("click", (e)=>{
    if(e.target.matches("#nuevocliente")) location.href="nuevo_cliente.html"
    if(e.target.matches("#buscarcliente")) location.href="busqueda_cliente.html"
}
)
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
                    const cliente = resp.datos[0];
                    const contenedor = document.querySelector("#infocliente");
                    contenedor.innerHTML = `
                        <div class="form-group">
                            <label>Nombres</label>
                            <input type="text" class="form-control" value="${cliente.Nombres_Usu}" disabled readonly>
                        </div>
                        <div class="form-group">
                            <label>Apellidos</label>
                            <input type="text" class="form-control" value="${cliente.Apellidos_Usu}" disabled readonly>
                        </div>

                        <div class="form-actions d-flex justify-content-between mt-3">
                            <button type="button" id="btnNuevaPlaca" class="btn btn-warning">Agregar Vehículo</button>
                            <button type="button" id="btnPlacaExistente" class="btn btn-success">Placa Existente</button>
                        </div>
                        <div class="form-group mt-2" id="selectPlacas" style="display: none;">
                            <label>Selecciona una Placa:</label>
                            <select class="form-control" id="placaExistente">
                                ${resp.placas.map(p => `<option value="${p}">${p}</option>`).join("")}
                            </select>
                        </div>
                    `;

                    // Evento para nueva placa
                    document.getElementById("btnNuevaPlaca").addEventListener("click", () => {
                        const nuevaPlaca = prompt("Ingrese la nueva placa del vehículo:");
                        if (nuevaPlaca) {
                            sessionStorage.setItem("placa_actual", nuevaPlaca);
                            window.location.href = "novedades_vehiculo.html";
                        }
                    });

                    // Mostrar selector de placas si existen
                    if (resp.placas.length > 0) {
                        document.getElementById("selectPlacas").style.display = "block";
                        document.getElementById("btnPlacaExistente").addEventListener("click", () => {
                            const placaSeleccionada = document.getElementById("placaExistente").value;
                            sessionStorage.setItem("placa_actual", placaSeleccionada);
                            window.location.href = "novedades_vehiculo.html";
                        });
                    }
                } else {
                    alert(resp.msg || "No se encontraron resultados");
                }
            }
        });
    }
});
document.addEventListener("click", (e)=>{
    if(e.target.matches("#botonbuscar")) location.href="datos_cliente.html"
}
)
document.addEventListener("click", (e)=>{
    if(e.target.matches("#continuarnovedades")) location.href="datos_cliente.html"
}
)
document.addEventListener("click", (e)=>{
    if(e.target.matches("#cierre")) location.href="inicio_sesion.html"
}
)
document.addEventListener("click", (e)=>{
    if(e.target.matches("#regresar")) location.href="index.html"
}
)
document.addEventListener("click", (e)=>{
    if(e.target.matches("#Continuarclientenuevo")) location.href="novedades_vehiculo.html"
}
)
document.addEventListener("click", (e)=>{
    if(e.target.matches("#ubicarvehiculo")) location.href="busqueda_vehiculo.html"
}
)
document.addEventListener("click", (e)=>{
    if(e.target.matches("#buscarregistro")) location.href="buscar_registro.html"
}
)
document.addEventListener("click", (e)=>{
    if(e.target.matches("#exitoso")) location.href="index.html"
}
)

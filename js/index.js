import {enivarAjax} from "./tools.js";

document.addEventListener("click", (e)=>{
    if(e.target.matches("#nuevocliente")) location.href="nuevo_cliente.html"
}
)
document.addEventListener("submit", (e)=>{
    if(e.target.matches("#form_busqueda")) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());
        console.log(data);
        enivarAjax({
            url:"../api/registros/buscar_clientemod.php",
            method:"GET",
            param:data,
            fresp:(resp)=>{
                if(resp.code===200){
                    console.log(resp.datos.Nombres_Usu);
                    const contenedor = document.querySelector("#infocliente");
                    contenedor.innerHTML =`<div class="form-group">
                            <label>Nombres</label>
                            <input type="text" class="form-control" id="nombres" value="${resp.datos[0].Nombres_Usu}" disabled readonly>
                        </div>
                        <div class="form-group">
                            <label>Apellidos</label>
                            <input type="text" class="form-control" id="apellidos" value="${resp.datos[0].Apellidos_Usu}" disabled readonly>
                        </div>`;
                }else{
                    alert("No se encontraron resultados");
                }
            }
        });
    }
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
    if(e.target.matches("#Guardarclientenuevo")) location.href="index.html"
}
)
document.addEventListener("click", (e)=>{
    if(e.target.matches("#exitoso")) location.href="index.html"
}
)

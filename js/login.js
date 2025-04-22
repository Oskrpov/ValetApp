import { enivarAjax, url } from "./tools.js"
export function validarlogin() {
    let ExReg_mail = /^[^@]+@[^@]+\.[a-zA-Z]{2,}$/
    let ExRegContraseña = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{4,18}$/

    let $div_msg = document.querySelector("#div_msg"), msg = ""
    $div_msg.innerHTML = "Procesando..."

    let usuario = username.value, pass = password.value
    // console.log("user: "+usuario,"password: "+pass)
    if (!ExReg_mail.test(usuario)) {
        msg = "Correo Invalido"
        if (!ExRegContraseña.test(pass)) {
            msg = "Correo y Contraseña Invalidos"
        }
    } else if (!ExRegContraseña.test(pass)) {
        msg = "Contraseña Invalida"
    }
    if (!msg == "") {
        $div_msg.innerHTML = "<b class='text-red'>" + msg + "</b>";
        setTimeout(() => {
            $div_msg.innerHTML = "";
        }, 3000);
        return false;
    }
    let info={
        url: "../api/configdb/login.php",
        method: "POST",
        param: {
            usuario: usuario,
            contrasena: pass
        },
        fresp: (data) => {
            console.log("RESPUESTA DEL SERVIDOR:", data);
            if (data.code==200) 
                url("index.html?id=" +data.ID_Funcionario)
             else 
                $div_msg.innerHTML = data.msg
        }
    }   
    enivarAjax(info);
}
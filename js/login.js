export function validarlogin() {  
  let ExReg_mail = /^[^@]+@[^@]+\.[a-zA-Z]{2,}$/
  let ExRegContraseña = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{4,18}$/

  let $div_msg = document.querySelector("#div_msg"),msg=""
  $div_msg.innerHTML="Procesando..."

  let usuario = username.value ,pass = password.value
 // console.log("user: "+usuario,"password: "+pass)
  if(!ExReg_mail.test(usuario)){
      msg="Correo Invalido"
      if(!ExRegContraseña.test(pass)){
          msg="Correo y Contraseña Invalidos"
  }
} else if(!ExRegContraseña.test(pass)){
      msg="Contraseña Invalida"
  }
  if(!msg=="") {$div_msg.innerHTML=msg;return false;}
  let parametros
  enivarAjax(usuario,pass)
}
function enivarAjax(user, pass){
    
//console.log(user, pass)
let $div_msg = document.querySelector("#div_msg")
//fetch
let header={
    headers:{
        "Content-Type": "application/json"
    },
    method:"POST",
    body: JSON.stringify({
        "usuario":user,
        "contrasena":pass
    })
    
}
fetch("../api/configdb/login.php",header)
.then(resp=> resp.json())
.then((data)=>{
    console.log(data)
    if(data.code==200){
        url("../src/index.html")
    }else{
        $div_msg.innerHTML = data.msg
    }
})
.catch((error)=>{})
}
document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    if (username.trim() === "" || password.trim() === "") {
      alert("Por favor, completa todos los campos.");
      return;
    }
    
    console.log("Credenciales ingresadas:", {username, password});

  });
  function url(destino){
    location.href.destino
  }

  document.addEventListener("submit", (e)=>{
    e.preventDefault()
    if(e.target.matches("#loginForm")) validarlogin()
  })

  function validarlogin() {  

      let ExReg_mail = /^[^@]+@[^@]+\.[a-zA-Z]{2,}$/
    let ExRegContraseña = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{4,18}$/

    let $div_msg = document.querySelector("#div_msg"),msg=""
    $div_msg.innerHTML="Procesando..."

    let usuario = username.value ,pass = password.value
    console.log("user: "+usuario,"password: "+pass)
    if(!ExReg_mail.test(usuario)){
        msg="Correo invalido"
        if(!ExRegContraseña.test(pass)){
            msg="Correo y Contraseña invalidos"
    }
  } else if(!ExRegContraseña.test(pass)){
        msg="Contraseña invalida"
    }
    if(!msg=="") {$div_msg.innerHTML="<b>"+msg+"</b>";return false;}
    $div_msg.innerHTML="datos ingrsados correctamente";
}
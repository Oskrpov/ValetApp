import {validarlogin}from"./login.js"
document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();
    
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    if (username.trim() === "" || password.trim() === "") {
      alert("Por favor, completa todos los campos.");
      return;
    }
    
    //console.log("Credenciales ingresadas:", {username, password});

  });
  function url(destino){
    location.href.destino
  }

  document.addEventListener("submit", (e)=>{
    e.preventDefault()
    if(e.target.matches("#loginForm")) validarlogin()
  })

  
import {validarlogin}from"./login.js"


  document.addEventListener("submit", (e)=>{
    e.preventDefault()
    if(e.target.matches("#loginForm")) validarlogin()
  })

  
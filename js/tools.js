
export async function enivarAjax(info){
    let {url , method , param , fresp} = info
    if(param!==undefined && method==="GET") url+="?"+new URLSearchParams(param).toString()

    if(method==="POST"||method==="PUT"||method==="DELETE")method={method,headers:{"Content-Type": "application/json"},body:JSON.stringify(param)}
    else method={method,headers:{"Content-Type": "application/json"}}

    try{
        let resp = await fetch(url,method)
        if(!resp.ok) throw new Error("Error en la respuesta del servidor")
            fresp(await resp.json())
    } catch(error){
        fresp({code:500,msg:"Error en la solicitud"})
    }
}

export function url(destino){
    location.href = destino
  }
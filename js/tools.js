
export async function enivarAjax(info){
    let {url,method,param,fresp} = info
    
    //fetch
    let header={
    headers:{
        "Content-Type": "application/json"
    },
    method:"POST",
    body: JSON.stringify(param)  
    }

await fetch(url,header)
.then(resp=> resp.json())
.then((data)=>{
    fresp(data)
})
.catch((error)=>{})
console.log("prueba de await")
}
/// <reference path="ajax.ts" />
/// <reference path="auto.ts" />

// window.addEventListener("load", ():void => {
//     Mendoza.Manejadora.MostrarEmpleados();
// }); 

namespace Mendoza{
    export class  Manejadora{

    static URL_API : string = "./"; 
    static AJAX : Ajax = new Ajax();

    public static AgregarAutoJSON()
    {
        let patente:string = (<HTMLInputElement>document.getElementById("patente")).value;
        let marca:string = (<HTMLInputElement>document.getElementById("marca")).value;
        let color:string = (<HTMLInputElement>document.getElementById("color")).value;        
        let precio:string = (<HTMLInputElement>document.getElementById("precio")).value;        
        let form : FormData = new FormData()
        form.append('patente', patente);
        form.append('marca', marca);
        form.append('color', color);
        form.append('precio', precio);
        Manejadora.AJAX.Post(Manejadora.URL_API + "./backend/altaAutoJSON.php", 
                    Manejadora.AgregarSuccessJSON, 
                    form, 
                    Manejadora.Fail); 
    }
    public static AgregarSuccessJSON(retorno:string):void {
        let respuesta = JSON.parse(retorno);
        console.log("Agregar: ", retorno);        
        Manejadora.AgregarAutoJSON();
        alert("Agregar:"+retorno);
    }
    public static Fail(retorno:string):void {

        console.error(retorno);
        alert("Ha ocurrido un ERROR!!!");
    }
    public static ListarAutosJSON()
    {       
        Manejadora.AJAX.Get(Manejadora.URL_API + "./backend/listarAutosJSON.php", 
                    Manejadora.MostrarListadoSuccess, 
                    "", 
                    Manejadora.Fail); 
    }
    public static MostrarListadoSuccess(data:string):void {

        let obj_array: any[] = JSON.parse(data);

        console.log("Mostrar: ", obj_array);
        let div = <HTMLDivElement>document.getElementById("divTabla");
        let tabla = `<table class="table table-hover">
                        <tr>
                            <th>PATENTE</th><th>MARCA</th><th>COLOR</th><th>PRECIO</th>
                        </tr>`;
                    if(obj_array.length < 1){
                        tabla += `<tr><td>---</td><td>---</td><td>---</td><td>---</td>
                            <td>---</td></tr>`;
                    }
                    else {
                        for (let index = 0; index < obj_array.length; index++) {
                            const dato = obj_array[index];
                            tabla += `<tr><td>${dato.patente}</td><td>${dato.marca}</td><td>${dato.color}</td><td>${dato.precio}</td></tr>`;
                        }  
                    }
        tabla += `</table>`;
        div.innerHTML = tabla;            
    }
    
    public static VerificarAutoJSON(){
        let patente:string = (<HTMLInputElement>document.getElementById("patente")).value;
        let info :string = '{"patente":"'+patente+'"}';
        let form : FormData = new FormData()
        form.append('auto_json', info);
        Manejadora.AJAX.Post(Manejadora.URL_API + "./backend/verificarAutoJSON.php", 
                    Manejadora.VerificarSuccess, 
                    form, 
                    Manejadora.Fail); 
    }
    public static VerificarSuccess(retorno:string):void {
        let respuesta = JSON.parse(retorno);
        console.log("Verificar: ", respuesta.mensaje);      
        alert("Verificar:"+respuesta.mensaje);
    }     
}
}
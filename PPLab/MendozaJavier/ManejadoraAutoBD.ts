/// <reference path="ajax.ts" />
//import Manejadora = Mendoza.Manejadora;
import Ajax = Mendoza.Ajax;
/// <reference path="Manejadora.ts" />

window.addEventListener("load", ():void => {
    PrimerParcial.ManejadoraAutoBD.ListarAutosBD();
       
}); 

namespace PrimerParcial{
    export class  ManejadoraAutoBD{

    static URL_API : string = "./"; 
    static AJAX : Ajax = new Ajax();

    public static Fail(retorno:string):void{
        console.error(retorno);
        alert("Ha ocurrido un ERROR!!!");
    }  
    public static AgregarAutoBD(){
        let patente:string = (<HTMLInputElement>document.getElementById("patente")).value;
        let marca:string = (<HTMLInputElement>document.getElementById("marca")).value;
        let color:string = (<HTMLInputElement>document.getElementById("color")).value;      
        let precio:string = (<HTMLInputElement>document.getElementById("precio")).value;      
        //let foto : any = (<HTMLInputElement> document.getElementById("foto"));            
        let info :string = '{"marca":"'+marca+'","color":"'+color+'","precio":"'+precio+'"}';
        let form : FormData = new FormData()
        form.append('patente', patente);
        form.append('marca', marca);
        form.append('color', color);
        form.append('precio', precio);
        ManejadoraAutoBD.AJAX.Post(ManejadoraAutoBD.URL_API + "backend/agregarAutoSinFoto.php",
        ManejadoraAutoBD.AgregarSuccess, 
                form, 
                ManejadoraAutoBD.Fail); 
    }
    public static AgregarSuccess(retorno:string):void{
        let respuesta = JSON.parse(retorno);
        console.log("Agregar: ", respuesta.mensaje);        
        ManejadoraAutoBD.ListarAutosBD();
        alert("Agregar:"+respuesta.mensaje);
    }
    public static ListarAutosBD(){
        ManejadoraAutoBD.AJAX.Get(ManejadoraAutoBD.URL_API + "backend/listadoAutosBD.php",
                    ManejadoraAutoBD.ListarAutosBDSuccess, 
                    "tabla=mostrar", 
                    ManejadoraAutoBD.Fail);         
    }
    public static ListarAutosBDSuccess(retorno:string):void {        
        let div = <HTMLDivElement>document.getElementById("divTabla");        
        div.innerHTML = retorno;         
        console.log(retorno);        
        alert(retorno);
    }
    
    public static EliminarAutoBD() {
        let patente: string = (<HTMLInputElement>document.getElementById("patente")).value;
        let form: FormData = new FormData();
        form.append('patente', patente);
        ManejadoraAutoBD.AJAX.Post(ManejadoraAutoBD.URL_API + "backend/eliminarAutoBD.php",
            ManejadoraAutoBD.EliminarAutoBDSuccess,
            form,
            ManejadoraAutoBD.Fail
        );
    }

    public static EliminarAutoBDSuccess(retorno: string): void {
        console.log(retorno);
        let respuesta = JSON.parse(retorno);
        console.log("Eliminar: ", respuesta.mensaje);
        alert("Eliminar: " + respuesta.mensaje);
    }

    public static ModificarAutoBD() {
        let patente: string = (<HTMLInputElement>document.getElementById("patente")).value;
        let marca: string = (<HTMLInputElement>document.getElementById("marca")).value;
        let color: string = (<HTMLInputElement>document.getElementById("color")).value;
        let precio: string = (<HTMLInputElement>document.getElementById("precio")).value;
        let foto: any = (<HTMLInputElement>document.getElementById("foto"));
        let form: FormData = new FormData();
        let auto_json: string = '{"patente":"' + patente + '","marca":"' + marca + '","color":"' + color + '","precio":"' + precio + '"}';
        form.append('auto_json', auto_json);
        if (foto.files && foto.files[0]) {
            form.append('foto', foto.files[0]);
            ManejadoraAutoBD.AJAX.Post(ManejadoraAutoBD.URL_API + "backend/modificarAutoBDFoto.php",
                ManejadoraAutoBD.ModificarAutoBDSuccess,
                form,
                ManejadoraAutoBD.Fail);
        } else {
            ManejadoraAutoBD.AJAX.Post(ManejadoraAutoBD.URL_API + "backend/modificarAutoBD.php",
                ManejadoraAutoBD.ModificarAutoBDSuccess,
                form,
                ManejadoraAutoBD.Fail);
        }
    }

    public static ModificarAutoBDSuccess(retorno: string): void {
        console.log(retorno);
        let respuesta = JSON.parse(retorno);
        console.log("Modificar: ", respuesta.mensaje);
        alert("Modificar: " + respuesta.mensaje);
    }

}



}
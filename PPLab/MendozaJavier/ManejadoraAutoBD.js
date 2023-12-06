"use strict";
/// <reference path="ajax.ts" />
//import Manejadora = Mendoza.Manejadora;
var Ajax = Mendoza.Ajax;
/// <reference path="Manejadora.ts" />
window.addEventListener("load", () => {
    PrimerParcial.ManejadoraAutoBD.ListarAutosBD();
});
var PrimerParcial;
(function (PrimerParcial) {
    class ManejadoraAutoBD {
        static Fail(retorno) {
            console.error(retorno);
            alert("Ha ocurrido un ERROR!!!");
        }
        static AgregarAutoBD() {
            let patente = document.getElementById("patente").value;
            let marca = document.getElementById("marca").value;
            let color = document.getElementById("color").value;
            let precio = document.getElementById("precio").value;
            //let foto : any = (<HTMLInputElement> document.getElementById("foto"));            
            let info = '{"marca":"' + marca + '","color":"' + color + '","precio":"' + precio + '"}';
            let form = new FormData();
            form.append('patente', patente);
            form.append('marca', marca);
            form.append('color', color);
            form.append('precio', precio);
            ManejadoraAutoBD.AJAX.Post(ManejadoraAutoBD.URL_API + "backend/agregarAutoSinFoto.php", ManejadoraAutoBD.AgregarSuccess, form, ManejadoraAutoBD.Fail);
        }
        static AgregarSuccess(retorno) {
            let respuesta = JSON.parse(retorno);
            console.log("Agregar: ", respuesta.mensaje);
            ManejadoraAutoBD.ListarAutosBD();
            alert("Agregar:" + respuesta.mensaje);
        }
        static ListarAutosBD() {
            ManejadoraAutoBD.AJAX.Get(ManejadoraAutoBD.URL_API + "backend/listadoAutosBD.php", ManejadoraAutoBD.ListarAutosBDSuccess, "tabla=mostrar", ManejadoraAutoBD.Fail);
        }
        static ListarAutosBDSuccess(retorno) {
            let div = document.getElementById("divTabla");
            div.innerHTML = retorno;
            console.log(retorno);
            alert(retorno);
        }
        static EliminarAutoBD() {
            let patente = document.getElementById("patente").value;
            let form = new FormData();
            form.append('patente', patente);
            ManejadoraAutoBD.AJAX.Post(ManejadoraAutoBD.URL_API + "backend/eliminarAutoBD.php", ManejadoraAutoBD.EliminarAutoBDSuccess, form, ManejadoraAutoBD.Fail);
        }
        static EliminarAutoBDSuccess(retorno) {
            console.log(retorno);
            let respuesta = JSON.parse(retorno);
            console.log("Eliminar: ", respuesta.mensaje);
            alert("Eliminar: " + respuesta.mensaje);
        }
        static ModificarAutoBD() {
            let patente = document.getElementById("patente").value;
            let marca = document.getElementById("marca").value;
            let color = document.getElementById("color").value;
            let precio = document.getElementById("precio").value;
            let foto = document.getElementById("foto");
            let form = new FormData();
            let auto_json = '{"patente":"' + patente + '","marca":"' + marca + '","color":"' + color + '","precio":"' + precio + '"}';
            form.append('auto_json', auto_json);
            if (foto.files && foto.files[0]) {
                form.append('foto', foto.files[0]);
                ManejadoraAutoBD.AJAX.Post(ManejadoraAutoBD.URL_API + "backend/modificarAutoBDFoto.php", ManejadoraAutoBD.ModificarAutoBDSuccess, form, ManejadoraAutoBD.Fail);
            }
            else {
                ManejadoraAutoBD.AJAX.Post(ManejadoraAutoBD.URL_API + "backend/modificarAutoBD.php", ManejadoraAutoBD.ModificarAutoBDSuccess, form, ManejadoraAutoBD.Fail);
            }
        }
        static ModificarAutoBDSuccess(retorno) {
            console.log(retorno);
            let respuesta = JSON.parse(retorno);
            console.log("Modificar: ", respuesta.mensaje);
            alert("Modificar: " + respuesta.mensaje);
        }
    }
    ManejadoraAutoBD.URL_API = "./";
    ManejadoraAutoBD.AJAX = new Ajax();
    PrimerParcial.ManejadoraAutoBD = ManejadoraAutoBD;
})(PrimerParcial || (PrimerParcial = {}));
//# sourceMappingURL=ManejadoraAutoBD.js.map

$(()=>{

    VerificarJWT();

    ListarJuguetes();
    AltaJuguetes();

 });

function VerificarJWT() {
    
    //RECUPERO DEL LOCALSTORAGE
    let jwt = localStorage.getItem("jwt");

    $.ajax({
        type: 'GET',
        url: URL_API + "login",//voy a verificar para ver si esta OK
        dataType: "json",
        data: {},
        headers : {'Authorization': 'Bearer ' + jwt},//teniendo en cuenta que lo pase por bearer
        async: true
    })
    .done(function (obj_rta:any) {

        console.log(obj_rta);

        if(obj_rta.exito){//si el token fue exito true

            let usuario = obj_rta.jwt.usuario;

            let alerta:string = ArmarAlert("<br>" + JSON.stringify(usuario) + "<br>");

            $("#nombre_usuario").html(usuario.nombre_usuario);
        }
        else{//si fue false lo devuelvo al index para que loguee

            let alerta:string = ArmarAlert(obj_rta.mensaje, "danger");

            setTimeout(() => {
                $(location).attr('href', URL_BASE + "/login.html");
            }, 1500);
        }
    })
    .fail(function (jqXHR:any, textStatus:any, errorThrown:any) {
        
        let retorno = JSON.parse(jqXHR.responseText);

        let alerta:string = ArmarAlert(retorno.mensaje, "danger");

    });    
}


function AltaJuguetes() {
 
    $("#alta_juguete").on("click", function() {
        let formulario : string = MostrarForm("alta");
        $("#divTablaDer").html(formulario);
        $("#btnAceptar").on("click", function(e:any) {
            e.preventDefault();
            let jwt = localStorage.getItem('jwt');
            let marca:any = $("#marca").val();
            let precio:any = $("#precio").val();
            let foto:any = $("#foto")[0];
        
            let frm = new FormData();
            frm.append("juguete_json", JSON.stringify({"marca": marca, "precio": precio}));
            frm.append("foto", foto.files[0]);
            console.log(frm);
            
            $.ajax({
                type: 'POST',
                url: URL_API + "agregarJugueteBD",
                dataType: "json",
                data: frm,
                cache: false,
                processData: false,
                contentType: false,
                headers: {'Authorization': 'Bearer ' + jwt},
                async: true
            })
            .done(function (obj_ret:any) {
                console.log(obj_ret);
                alert(obj_ret.mensaje);
                $("#listado_juguetes").click();
            })
            .fail( (jqXHR : any, textStatus : any, errorThrown : any) => {
                let retorno = JSON.parse(jqXHR.responseText);
                alert(retorno.mensaje);
            });
        });
    });
 }
 function MostrarForm(accion : string, obj_prod : any = null) : string 
    {
        let encabezado : string = "";
        let solo_lectura : string = "";
        let solo_lectura_pk : string = "readonly";

        switch (accion) {
            case "alta":
                encabezado = 'AGREGAR PRODUCTO';
                solo_lectura_pk = "";
                break;

            case "baja":
                encabezado = 'ELIMINAR PRODUCTO';
                solo_lectura = "readonly";
                break;
        
            case "modificacion":
                encabezado = 'MODIFICAR PRODUCTO';
                break;
        }

        let id : any = "";
        let marca : string = "";
        let precio : string = "";
        let path : string = URL_BASE + "/img/producto_default.png";

        if (obj_prod !== null) 
        {
            id = obj_prod.id;
            marca = obj_prod.marca;
            precio = obj_prod.precio;
            path = URL_API + obj_prod.path_foto;       
        }

        let form:string = '<h3 style="padding-top:1em;">'+encabezado+'</h3>\
                            <div class="row justify-content-center">\
                                <div class="col-md-8">\
                                    <form class="was-validated">\
                                        <div class="form-group">\
                                            <label for="codigo">Marca:</label>\
                                            <input type="text" class="form-control" id="marca" placeholder="Ingresar marca"\
                                                value="'+marca+'" '+solo_lectura_pk+' required>\
                                        </div>\
                                        <div class="form-group">\
                                            <label for="precio">Precio:</label>\
                                            <input type="number" class="form-control" id="precio" placeholder="Ingresar precio" name="precio"\
                                                value="'+precio+'" '+solo_lectura+' required>\
                                            <div class="valid-feedback">OK.</div>\
                                            <div class="invalid-feedback">Valor requerido.</div>\
                                        </div>\
                                        <div class="form-group">\
                                            <label for="foto">Foto:</label>\
                                            <input type="file" class="form-control" id="foto" name="foto" '+solo_lectura+' required>\
                                            <div class="valid-feedback">OK.</div>\
                                            <div class="invalid-feedback">Valor requerido.</div>\
                                        </div>\
                                        <div class="row justify-content-between"><img id="img_prod" src="'+path+'" width="400px" height="200px"></div><br>\
                                        <div class="row justify-content-between">\
                                            <input type="button" class="btn btn-danger" data-dismiss="modal" value="Cerrar">\
                                            <button id="btnAceptar" data-id="' + id +'" type="submit" class="btn btn-primary" data-dismiss="modal">Aceptar</button>\
                                        </div>\
                                    </form>\
                                </div>\
                            </div>';
        return form;
    }
 
 function ObtenerListadoJuguetes() {
    
     $("#divTablaIzq").html("");
 
     let jwt = localStorage.getItem("jwt");
 
     $.ajax({
         type: 'GET',
         url: URL_API + "listarJuguetesBD",
         dataType: "json",
         data: {},
         headers : {'Authorization': 'Bearer ' + jwt},
         async: true
     })
     .done(function (resultado:any) {
 
         if(resultado.exito)
         {
             let tabla:string = ArmarTablaJuguetes(resultado.dato);
             $("#divTablaIzq").html(tabla).show(1000);
         }
         else
         {
             console.log("Token invalido");
             alert("Token invalido");
 
             setTimeout(() => {
                 $(location).attr("href", "./login.html");
               }, 2000);
         }       
     })
     .fail(function (jqXHR:any, textStatus:any, errorThrown:any) {
 
         //let retorno = JSON.parse(jqXHR.responseText);
        //let alerta:string = ArmarAlert(retorno.mensaje, "danger");
 
        console.log("Token invalido");
        alert("Token invalido");
 
        setTimeout(() => {
            $(location).attr("href", "./login.html");
          }, 2000);
 
         
     });    
 }
 function ListarJuguetes() {
 
    $("#listado_juguetes").on("click", ()=>{
        ObtenerListadoJuguetes();
    });
}
 
 function ArmarTablaJuguetes(juguetes:[]) : string 
 {   
     let tabla:string = '<table class="table table-success table-striped table-hover">';
     tabla += '<tr><th>MARCA</th><th>PRECIO</th><th>FOTO</th></tr>';
 
     if(juguetes.length == 0)
     {
         tabla += '<tr><td>---</td><td>---</td><td>---</td><td>---</td><th>---</td></tr>';
     }
     else
     {
         juguetes.forEach((toy : any) => {
             tabla += "<tr>";
             for (const key in toy) {
                 if(key != "path_foto") {
                     tabla += "<td>"+toy[key]+"</td>";
                 } else if(key == "path_foto"){
                     tabla += "<td><img src='"+URL_API+ toy.path_foto+"' width='50px' height='50px'></td>";
                 }
             }
            //  tabla += "<td><a href='#' class='btn' data-action='modificar-usuario' data-obj_user='"+JSON.stringify(juguetes)+"' title='Modificar'"+
            //  " data-toggle='modal' data-target='#ventana_modal_prod' ><span class='fas fa-edit'></span></a>";
            //  tabla += "<a href='#' class='btn' data-action='eliminar-usuario' data-obj_user='"+JSON.stringify(juguetes)+"' title='Eliminar'"+
            //  " data-toggle='modal' data-target='#ventana_modal_prod' ><span class='fas fa-times'></span></a>";
            //  tabla += "</td>";
            //  tabla += "</tr>";   
            tabla += "<a href='#' class='btn' data-action='modificar' data-obj_prod='"+JSON.stringify(toy)+"' title='Modificar'"+
            " data-toggle='modal' data-target='#ventana_modal_prod'><span class='fas fa-edit'></span></a>"+
            "<a href='#' class='btn' data-action='eliminar' data-obj_prod='"+JSON.stringify(toy)+"' title='Eliminar'"+
            " data-toggle='modal' data-target='#ventana_modal_prod'><span class='fas fa-times'></span></a>"+
            "</td></tr>";
              
         });

        //  juguetes.forEach((jug : any) => {

        //     tabla += "<tr><td>"+jug.codigo+"</td><td>"+jug.marca+"</td><td>"+jug.precio+"</td>"+
        //     "<td><img src='"+URL_API+jug.path+"' width='50px' height='50px'></td><th>"+
        //     "<a href='#' class='btn' data-action='modificar' data-obj_prod='"+JSON.stringify(jug)+"' title='Modificar'"+
        //     " data-toggle='modal' data-target='#ventana_modal_prod'><span class='fas fa-edit'></span></a>"+
        //     "<a href='#' class='btn' data-action='eliminar' data-obj_prod='"+JSON.stringify(jug)+"' title='Eliminar'"+
        //     " data-toggle='modal' data-target='#ventana_modal_prod'><span class='fas fa-times'></span></a>"+
        //     "</td></tr>";
        // });
     }
 
     tabla += "</table>";
 
     return tabla;
 }
 function Eliminar() {
    $("#btnAceptar").on("click", function(e:any) {
        // e.preventDefault();
        // let jwt = localStorage.getItem('jwt');
        // let marca:any = $("#marca").val();
        // let precio:any = $("#precio").val();
        // let foto:any = $("#foto")[0];
    
        // let frm = new FormData();
        // frm.append("juguete_json", JSON.stringify({"marca": marca, "precio": precio}));
        // frm.append("foto", foto.files[0]);
        // console.log(frm);
        
        // $.ajax({
        //     type: 'POST',
        //     url: URL_API + "agregarJugueteBD",
        //     dataType: "json",
        //     data: frm,
        //     cache: false,
        //     processData: false,
        //     contentType: false,
        //     headers: {'Authorization': 'Bearer ' + jwt},
        //     async: true
        // })
        // .done(function (obj_ret:any) {
        //     console.log(obj_ret);
        //     alert(obj_ret.mensaje);
        //     $("#listado_juguetes").click();
        // })
        // .fail( (jqXHR : any, textStatus : any, errorThrown : any) => {
        //     let retorno = JSON.parse(jqXHR.responseText);
        //     alert(retorno.mensaje);
        // });
    });
}

//? #################################################################################################################################################
//? # MODIFICAR # MODIFICAR # MODIFICAR # MODIFICAR # MODIFICAR # MODIFICAR # MODIFICAR # MODIFICAR # MODIFICAR # MODIFICAR # MODIFICAR # MODIFICAR #
//? #################################################################################################################################################
function Modificar() {
    // $("#btnAceptar").on("click", function(e:any) {
    //     e.preventDefault();
    //     let jwt = localStorage.getItem('jwt');
    //     let marca:any = $("#marca").val();
    //     let precio:any = $("#precio").val();
    //     let foto:any = $("#foto")[0];
    
    //     let frm = new FormData();
    //     frm.append("juguete_json", JSON.stringify({"marca": marca, "precio": precio}));
    //     frm.append("foto", foto.files[0]);
    //     console.log(frm);
        
    //     $.ajax({
    //         type: 'POST',
    //         url: URL_API + "agregarJugueteBD",
    //         dataType: "json",
    //         data: frm,
    //         cache: false,
    //         processData: false,
    //         contentType: false,
    //         headers: {'Authorization': 'Bearer ' + jwt},
    //         async: true
    //     })
    //     .done(function (obj_ret:any) {
    //         console.log(obj_ret);
    //         alert(obj_ret.mensaje);
    //         $("#listado_juguetes").click();
    //     })
    //     .fail( (jqXHR : any, textStatus : any, errorThrown : any) => {
    //         let retorno = JSON.parse(jqXHR.responseText);
    //         alert(retorno.mensaje);
    //     });
    // });
}

 
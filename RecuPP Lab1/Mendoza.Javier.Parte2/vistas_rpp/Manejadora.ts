
let xhttp: XMLHttpRequest = new XMLHttpRequest();

namespace RecPrimerParcial 
{

    export class Manejadora
    {
        public static MostrarPlantasFotosBD(): void {
            $.ajax({
                type: "GET",
                url: "http://localhost:2023/listarPlantasFotosBD",
                dataType: "json" 
            })
            .done((objJSON: any) => {
                //MUESTRO EL RESULTADO DE LA PETICION
                console.log(objJSON);
        
                let tabla:string = `<table class="table table-hover">
                                    <tr>
                                    <th>Código</th><th>Nombre</th><th>Color Flor</th><th>Precio</th><th>Foto</th><th>Acciones</th>
                                    </tr>`;
                objJSON.forEach((item:any) => {
                    tabla +=`<tr>
                                <td>${item.codigo}</td>
                                <td>${item.nombre}</td>
                                <td>${item.color}</td>
                                <td>${item.precio}</td>
                                <td> <img src="http://localhost:2023/${item.foto}" width=50 height=50> </td>
                                <td>
                                    <input type="button" value="modificar" data-obj='${JSON.stringify(item)}' data-action="modificar">        
                                    <input type="button" value="eliminar" data-obj='${JSON.stringify(item)}' data-action="eliminar">
                                </td>
                            </tr>`;
                });
        
                tabla +=`</table>`;
                $("#divTablaPlantaFotos").html(tabla);
        
                $('[data-action="modificar"]').on("click", (function(){
                    let objJSON:any = $(this).attr("data-obj");
                    let obj = JSON.parse(objJSON);
        
                    // Llenar campos con la información del producto
                    $("#codigo").val(obj.codigo);
                    $("#nombre").val(obj.nombre);
                    $("#color_flor").val(obj.color_flor);
                    $("#precio").val(obj.precio);
                    $("#imgFoto").attr("src", `http://localhost:2023/${obj.foto}`);
        
                    // Adjuntar el objeto al botón "btnModificar"
                    $("#btnModificar").data("producto", obj);
                }));
        
                $('[data-action="eliminar"]').on("click", (function(){
                    let objJSON:any = $(this).attr("data-obj");
                    let obj = JSON.parse(objJSON);
        
                    console.log(obj);
        
                    $.ajax({
                        type: 'DELETE',
                        url: "http://localhost:2023/eliminarPlantaFotoBD",
                        data: objJSON,
                        dataType: "text",
                        contentType: 'application/json'
                    })
                    .done((mensaje:any)=>{
                        alert(mensaje);
                    })
                    .fail(function (jqXHR:any, textStatus:any, errorThrown:any) {
                        alert(jqXHR.responseText + "\n" + textStatus + "\n" + errorThrown);
                    }); 
                }));
            });
        }

        

        public static AgregarPlantaFotoBD()
        {
            let codigo:any = $("#codigo").val();
            let nombre:any = $("#nombre").val();
            let color_flor:any = $("#color_flor").val();
            let precio:any = $("#precio").val();
            let foto:any = $("#foto")[0];
            let form:FormData = new FormData();
            
            form.append("codigo", codigo);
            form.append("nombre", nombre);
            form.append("color_flor", color_flor);
            form.append("precio", precio);
            form.append("foto", foto.files[0]);

            if(codigo && nombre && color_flor && precio && foto)
            {
                let xhr : XMLHttpRequest = new XMLHttpRequest();
                xhr.open('POST', "http://localhost:2023/agregarPlantaFotoBD", true);
                
                xhr.send(form);
                
                xhr.onreadystatechange = function()
                {
                    if (xhr.readyState === XMLHttpRequest.DONE)
                    {
                        if (xhr.status === 200) 
                        {
                            console.log(xhr.responseText);
                            alert(xhr.responseText);
                            RecPrimerParcial.Manejadora.MostrarPlantasFotosBD();
                        } 
                        else 
                        {
                            console.error('Error: ', xhr.status);
                            alert('Error: '+ xhr.status);
                        }
                    }
                };
            }
            else
            {
                alert("Formulario incompleto.");
            }
        
        }

        public static MostrarInfoDePlantaFoto(planta: any): void {
            // Mostrar la información de la planta en el formulario
            $("#codigo").val(planta.codigo);
            $("#nombre").val(planta.nombre);
            $("#color_flor").val(planta.color_flor);
            $("#precio").val(planta.precio);
            $("#imgFoto").attr("src", `http://localhost:2023/${planta.foto}`);
      
            // Hacer el campo de código de solo lectura
            $("#codigo").prop("readonly", true);
      
            // Agregar un evento al botón "Modificar"
            $("#btnModificar").off("click"); // Desvincular eventos anteriores
            $("#btnModificar").on("click", function () {
              // Llamar a ModificarPlantaFotoBD con el objeto planta
              RecPrimerParcial.Manejadora.ModificarPlantaFotoBD(planta);
            });
          }
          public static ModificarPlantaFotoBD(planta: any): void {
            let codigo: any = $("#codigo").val();
            let nombre: any = $("#nombre").val();
            let color_flor: any = $("#color_flor").val();
            let precio: any = $("#precio").val();
            let foto: any = $("#foto")[0];
      
            let form: FormData = new FormData();
            form.append("codigo", codigo);
            form.append("nombre", nombre);
            form.append("color_flor", color_flor);
            form.append("precio", precio);
            form.append("foto", foto.files[0]);
      
            if (nombre && color_flor && precio && foto) {
              $.ajax({
                type: "POST",
                url: "http://localhost:2023/modificarPlantaFotoBD",
                data: form,
                processData: false,
                contentType: false,
              })
                .done((mensaje: any) => {
                  alert(mensaje);
                  RecPrimerParcial.Manejadora.MostrarPlantasFotosBD(); // Refrescar el listado
                })
                .fail(function (jqXHR: any, textStatus: any, errorThrown: any) {
                  alert(jqXHR.responseText + "\n" + textStatus + "\n" + errorThrown);
                });
            } else {
              alert("Formulario incompleto.");
            }
          }

          public static EliminarPlantaFotoBD(planta: any): void {
            // Mostrar un mensaje de confirmación
            const confirmacion = confirm(`¿Seguro que deseas eliminar la planta ${planta.codigo} - ${planta.nombre}?`);
      
            if (confirmacion) {
              // Enviar solicitud POST para eliminar la planta
              $.ajax({
                type: 'POST',
                url: "http://localhost:2023/eliminarPlantaFotoBD",
                data: JSON.stringify(planta),
                dataType: "json",
                contentType: 'application/json'
              })
              .done((mensaje: any) => {
                alert(mensaje);
                console.log(mensaje);
                RecPrimerParcial.Manejadora.MostrarPlantasFotosBD(); // Refrescar el listado
              })
              .fail((jqXHR: any, textStatus: any, errorThrown: any) => {
                alert(jqXHR.responseText + "\n" + textStatus + "\n" + errorThrown);
                console.error(jqXHR.responseText + "\n" + textStatus + "\n" + errorThrown);
              });
            } else {
              console.log("Operación de eliminación cancelada.");
            }
          }

          public static FiltrarPlantasFotoBD(codigo: string, nombre: string, color_flor: string, precio: number): void {
            // Realizar la solicitud GET para obtener el listado filtrado de plantas
            $.ajax({
              type: "GET",
              url: "http://localhost:2023/listarPlantasFiltradasFotosBD",
              data: { codigo, nombre, color_flor, precio },
              dataType: "json"
            })
            .done((plantasFiltradas: any[]) => {
              // Generar la tabla con el listado filtrado y mostrarla en la página
              let tabla: string = `<table class="table table-hover">
                                    <tr>
                                      <th>Código</th><th>Nombre</th><th>Color Flor</th><th>Precio</th><th>Foto</th><th>Acciones</th>
                                    </tr>`;
      
              plantasFiltradas.forEach((item: any) => {
                tabla += `<tr>
                            <td>${item.codigo}</td>
                            <td>${item.nombre}</td>
                            <td>${item.color}</td>
                            <td>${item.precio}</td>
                            <td><img src="http://localhost:2036/${item.foto}" width=50 height=50></td>
                            <td>
                              <input type="button" value="modificar" data-obj='${JSON.stringify(item)}' data-action="modificar">        
                              <input type="button" value="eliminar" data-obj='${JSON.stringify(item)}' data-action="eliminar">
                            </td>
                          </tr>`;
              });
      
              tabla += `</table>`;
              $("#divTablaPlantaFotos").html(tabla);
      
              // Agregar eventos a los botones de modificar y eliminar
              $('[data-action="modificar"]').on("click", function () {
                let objJSON: any = $(this).attr("data-obj");
                let obj = JSON.parse(objJSON);
      
                // Llenar campos con la información del producto
                $("#codigo").val(obj.codigo);
                $("#nombre").val(obj.nombre);
                $("#color_flor").val(obj.color_flor);
                $("#precio").val(obj.precio);
                $("#imgFoto").attr("src", `http://localhost:2036/${obj.foto}`);
      
                // Adjuntar el objeto al botón "btnModificar"
                $("#btnModificar").data("producto", obj);
              });
      
              $('[data-action="eliminar"]').on("click", function () {
                let objJSON: any = $(this).attr("data-obj");
                let obj = JSON.parse(objJSON);
      
                console.log(obj);
      
                $.ajax({
                  type: 'DELETE',
                  url: "http://localhost:2023/eliminarPlantaFotoBD",
                  data: objJSON,
                  dataType: "text",
                  contentType: 'application/json'
                })
                  .done((mensaje: any) => {
                    alert(mensaje);
                  })
                  .fail(function (jqXHR: any, textStatus: any, errorThrown: any) {
                    alert(jqXHR.responseText + "\n" + textStatus + "\n" + errorThrown);
                  });
              });
            })
            .fail(function (jqXHR: any, textStatus: any, errorThrown: any) {
              alert(jqXHR.responseText + "\n" + textStatus + "\n" + errorThrown);
            });
          }













    }
}

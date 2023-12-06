<?php
require_once("./clases/autoBD.php");
use MendozaJavier\AutoBD;
if(count($_POST) > 0 ){

    $auto_json = isset($_POST["auto_json"]) ? $_POST["auto_json"] : "sin json"; 
    $autoObj = json_decode($auto_json);

    function ObtenerPathViejo (AutoBD $auto) : null | string{
        $retorno = null;
        $autos = AutoBD::Traer();
        foreach($autos as $item){
            if($auto->Patente() == $item->Patente()){
                $retorno = $item->Pathfoto();
            }
        }
        return $retorno;
    }

    $autoViejo = new AutoBD($autoObj->patente,$autoObj->marca,$autoObj->color,$autoObj->precio);
    $pathAMover="";
    if(ObtenerPathViejo($autoViejo)!=="sinFoto"){
        $pathAMover= "./autos/imagenes/" . ObtenerPathViejo($autoViejo);
        }
    $extencionM = pathinfo($pathAMover, PATHINFO_EXTENSION);

    $destinoModificado = "./autosModificados/" .$autoObj->patente.'.'. $autoObj->marca.".modificado.".date("His").".".$extencionM;

    //region validacion foto
    $foto_name = $_FILES['foto']['name'];
    $foto_tmp_name = $_FILES['foto']['tmp_name'];
    $foto_extension = pathinfo($foto_name, PATHINFO_EXTENSION);
    $hora = date('His');
    $new_foto_name = $autoViejo->Patente() . '.' . $autoViejo->Marca() . 'modificado' . $hora . '.' . $foto_extension;

    $destinoFoto = "./autos/imagenes/" . $new_foto_name;

    $uploadOk = TRUE;
    if (file_exists($destinoFoto)) {
        //echo "El archivo ya existe. Verifique!!!";
        $uploadOk = FALSE;
    }
    if ($_FILES["foto"]["size"] > 5000000 ) {
        //echo "El archivo es demasiado grande. Verifique!!!";
        $uploadOk = FALSE;
    }
    $tipoArchivo = pathinfo($destinoFoto, PATHINFO_EXTENSION);
    if($tipoArchivo != "jpg" && $tipoArchivo != "jpeg" && $tipoArchivo != "gif"
        && $tipoArchivo != "png") {
        //echo "Solo son permitidas imagenes con extension JPG, JPEG, PNG o GIF.";
        $uploadOk = FALSE;
    }

    $retorno ='{"exito" : false,"mensaje": "auto no modificado"}';
    $neumatico = new AutoBD($autoObj->patente,$autoObj->marca,$autoObj->color,$autoObj->precio,$new_foto_name);

    if($neumatico->Modificar()){  
        
        if($neumatico->GuardarEnArchivo("./archivos/autosbd_modificados.txt")){

            move_uploaded_file($foto_tmp_name, $destinoFoto);

            if($pathAMover!=""){        
                rename($pathAMover,$destinoModificado);   
            } 

            $retorno ='{"exito" : true,"mensaje": "auto modificado"}'; 
        } 
    }

    echo $retorno;
}
else{

    if(file_exists("./archivos/autosbd_modificados.txt")){
    
        echo "
        <table >
            <thead>
                <tr>
                    <th>patente</th>
                    <th>marca</th>
                    <th>color</th>
                    <th>precio</th>
                    <th>path</th>
                    <th>foto</th>
                </tr>
            </thead>"; 
            $tabla = "";
            $contenido = file_get_contents('./archivos/autosbd_modificados.txt');
            $lineas = explode("\n", $contenido);
            foreach ($lineas as $linea) {
                // Dividir la línea en campos usando la coma como separador
                $campos = explode(',', $linea);
              
                // Crear una fila de la tabla con los datos
                echo '<tr>';
                foreach ($campos as $campo) {
                  // Dividir el campo en clave y valor usando el dos puntos como separador
                  $datos = explode(':', $campo);
                  if (count($datos) > 1) {
                    $clave = trim($datos[0]);
                    $valor = trim($datos[1]);
                    if ($clave == "foto") {
                        //$valor .= "</td><td><img src='./autosModificados/'. urlencode($valor).' width='200' height='200'></td>'";
                        $valor .= "</td><td><img src='autosModificados/" . urlencode($valor) . "' width='200' height='200'></td>";

                        // echo "<td><img src='./autos/imagenes/{$auto->Pathfoto()}' alt='Foto del auto' width='100' height='100'></td>";
                
                    }
                    } else {
                        // Manejo de error: $datos no contiene al menos dos elementos
                        $clave = "N/A";
                        $valor = "N/A";
                    }
                  // Mostrar el valor en la celda correspondiente
                  echo '<td>' . $valor . '</td>';
                }
                echo '</tr>';
            }
            $tabla .= "</table>";
        
            echo $tabla;
        }
}

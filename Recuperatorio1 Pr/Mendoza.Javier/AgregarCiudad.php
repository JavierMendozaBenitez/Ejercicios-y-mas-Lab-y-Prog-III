<?php
// require_once("./clases/autoBD.php");
// use MendozaJavier\AutoBD;
require_once './clases/Ciudad.php';
use Mendoza\Javier\Ciudad;

$patente = isset($_POST["nombre"]) ? $_POST["nombre"] : "sin nombre"; 
$marca = isset($_POST["poblacion"]) ? $_POST["poblacion"] : "sin poblacion"; 
$color = isset($_POST["pais"]) ? $_POST["pais"] : "sin pais"; 

//region validacion foto
$foto_name = $_FILES['pathFoto']['name'];
$foto_tmp_name = $_FILES['pathFoto']['tmp_name'];
$foto_extension = pathinfo($foto_name, PATHINFO_EXTENSION);
$hora = date('His');
$new_foto_name = $nombre.'.'.$pais .'.'.$hora . '.' . $foto_extension;

$destinoFoto = "./ciudades/fotos/" . $new_foto_name;

$uploadOk = TRUE;
if (file_exists($destinoFoto)) {
    //echo "El archivo ya existe. Verifique!!!";
    $uploadOk = FALSE;
}
if ($_FILES["foto"]["size"] > 5000000 ) {
   // echo "El archivo es demasiado grande. Verifique!!!";
    $uploadOk = FALSE;
}
$tipoArchivo = pathinfo($destinoFoto, PATHINFO_EXTENSION);
if($tipoArchivo != "jpg" && $tipoArchivo != "jpeg" && $tipoArchivo != "gif"
    && $tipoArchivo != "png") {
 //   echo "Solo son permitidas imagenes con extension JPG, JPEG, PNG o GIF.";
    $uploadOk = FALSE;
}
if($uploadOk){    
    $ciudad  = new Ciudad($id,$nombre,$poblacion,$pais,$new_foto_name);
    $array = Ciudad::Traer();
    if(!$ciudad->Existe($array)){  
        if($ciudad->Agregar()){
            if(move_uploaded_file($foto_tmp_name, $destinoFoto)){            
                echo '{"exito" : true,"mensaje": "agregado con foto"}';
            }else{            
                echo '{"exito" : true,"mensaje": "agregado sin foto porq hubo un error"}';
            }        
        }else{
            echo '{"exito" : false,"mensaje": "NO agregado hubo un error en el agregar"}';
         }
    }else{
        echo '{"exito" : false,"mensaje": "NO agregado, porque ya existe en la Base de datos"}'; 
    }
}else{
    echo '{"exito" : false,"mensaje": "NO agregado, porque hubo un problema con la carga del archivo"}';
}
<?php
require_once("./clases/autoBD.php");
use MendozaJavier\AutoBD;

$patente = isset($_POST["patente"]) ? $_POST["patente"] : "sin patente"; 
$marca = isset($_POST["marca"]) ? $_POST["marca"] : "sin marca"; 
$color = isset($_POST["color"]) ? $_POST["color"] : "sin color"; 
$precio = isset($_POST["precio"]) ? $_POST["precio"] : 0; 

//region validacion foto
$foto_name = $_FILES['foto']['name'];
$foto_tmp_name = $_FILES['foto']['tmp_name'];
$foto_extension = pathinfo($foto_name, PATHINFO_EXTENSION);
$hora = date('His');
$new_foto_name = $patente.'.'.$hora . '.' . $foto_extension;

$destinoFoto = "./autos/imagenes/" . $new_foto_name;

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
    $neumatico  = new AutoBD($patente,$marca,$color,$precio,$new_foto_name);
    $array = AutoBD::Traer();
    if(!$neumatico->Existe($array)){  
        if($neumatico->Agregar()){
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
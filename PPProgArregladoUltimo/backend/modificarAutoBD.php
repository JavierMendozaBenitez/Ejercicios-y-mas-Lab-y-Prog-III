<?php
require_once("./clases/autoBD.php");
use MendozaJavier\AutoBD;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el JSON de la solicitud
    $jsonData = file_get_contents("php://input");
    $auto_decode = json_decode($jsonData);
  
  

// $auto_json = isset($_POST["auto_json"]) ? $_POST["auto_json"] : "no hay";
// $auto_decode = json_decode($auto_json);
$auto  = new AutoBD($auto_decode->patente,$auto_decode->marca,$auto_decode->color,(float)$auto_decode->precio);

if($auto->Modificar()){
    
     
    echo '{"exito" : true,"mensaje": "modificado sin foto "}';

}else{echo '{"exito" : false,"mensaje": "NO modificado sin foto"}';}
}
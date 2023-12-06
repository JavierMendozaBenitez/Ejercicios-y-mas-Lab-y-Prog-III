<?php
require_once("./clases/autoBD.php");
use MendozaJavier\AutoBD;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el JSON de la solicitud
    $jsonData = file_get_contents("php://input");
    $auto_decode = json_decode($jsonData);
  
  
// $obj_auto = isset($_POST["obj_auto"]) ? $_POST["obj_auto"] : "sin obj_auto";
// $auto_bd = json_decode($obj_auto);
$auto = new AutoBD($auto_decode->patente);


$array_autos = AutoBD::Traer();
$retorno = "{}";
if($auto->Existe($array_autos)){
    $item = $auto->traerUno();
    if($item != null){        
        $retorno = $item->ToJSON();
    }
}

echo $retorno;
}
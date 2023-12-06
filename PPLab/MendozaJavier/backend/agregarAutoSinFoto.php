<?php
require_once("./clases/autoBD.php");
use MendozaJavier\AutoBD;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el JSON de la solicitud
    $jsonData = file_get_contents("php://input");
    $auto_decode = json_decode($jsonData);

// $auto_json = isset($_POST["auto_json"]) ? $_POST["auto_json"] : "sin auto"; 
// $auto_decode = json_decode($auto_json);

//var_dump($auto_decode);
if ($auto_decode !== null) {
$auto = new AutoBD($auto_decode->patente,$auto_decode->marca,$auto_decode->color,(float)$auto_decode->precio);

if($auto->agregar()){

    echo '{"exito" : true,"mensaje": "auto sin foto agregado"}';

}else{
    
    echo '{"exito" : false,"mensaje": "auto sin foto NO agregado"}'; 
}
} else {
    // Si no se pudo decodificar el JSON, devolver un error
    http_response_code(400); // Bad Request
    echo json_encode(['exito' => false, 'mensaje' => 'Error en los datos JSON.']);
}
} else {
    // Si la solicitud no es POST, devolver un error
    http_response_code(405); // Method Not Allowed
    echo json_encode(['exito' => false, 'mensaje' => 'Método no permitido.']);
}
?>
<?php
require_once "./clases/Ciudadano.php";

use Tignino_Christian\Ciudadano;

if(isset($_POST['email']) && isset($_POST['clave'])){
    $ciudadano = new Ciudadano($_POST['email'], $_POST['clave']);
    $resultado = Ciudadano::verificarExistencia($ciudadano, "./clases/archivos/ciudadanos.json");

    echo $resultado;
} else {
    $resultado = json_encode(array('éxito' => false, 'mensaje' => 'No se recibieron los datos necesarios'));
    echo $resultado;
}

?>
<?php
require_once './clases/Ciudadano.php';
use Mendoza\Javier\Ciudadano;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $clave = $_POST['clave'];
    $path = './archivos/ciudadanos.json';
        $ciudadano= new Ciudadano($email,$clave,"");
    $result = Ciudadano::verificarExistencia($ciudadano, $path);

    echo json_encode($result);
}
<?php
require_once './clases/Ciudadano.php';
use Mendoza\Javier\Ciudadano;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ciudad = $_POST['ciudad'];
    $email = $_POST['email'];
    $clave = $_POST['clave'];

    $ciudadano = new Ciudadano($email, $clave, $ciudad);
    $path = './archivos/ciudadanos.json';

    $result = $ciudadano->guardarEnArchivo($path);

    echo json_encode($result);
}

<?php
require_once './clases/Ciudadano.php';
use Mendoza\Javier\Ciudadano;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $path = './archivos/ciudadanos.json';

    $ciudadanos = Ciudadano::traerTodos($path);

    echo json_encode($ciudadanos);
}

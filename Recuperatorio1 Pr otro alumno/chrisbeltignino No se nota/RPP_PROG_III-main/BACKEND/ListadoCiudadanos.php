<?php
use Tignino_Christian\Ciudadano;
require_once './clases/Ciudadano.php';

$accion = isset($_GET["accion"]) ? $_GET["accion"] : "sin accion";
$resultado = Ciudadano::traerTodos('clases/archivos/ciudadanos.json');

if ($resultado !== null) 
{
    echo json_encode($resultado);
} 
else 
{
    echo "No se pudo cargar el archivo JSON.";
}
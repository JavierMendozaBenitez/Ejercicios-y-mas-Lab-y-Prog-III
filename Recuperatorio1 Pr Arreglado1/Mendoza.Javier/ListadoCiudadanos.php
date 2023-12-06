<?php
require_once './clases/Ciudadano.php';
use Mendoza\Javier\Ciudadano;

$pathArchivos = "./archivos/ciudadanos.json";


try 
{
    $array_ciudadanos = Ciudadano::TraerTodos($pathArchivos);
    $json_ciudadanos = json_encode($array_ciudadanos);

    echo $json_ciudadanos;   
    
} catch (Exception $ex) 
{
    echo "ERROR : " . $ex -> getMessage();    
}
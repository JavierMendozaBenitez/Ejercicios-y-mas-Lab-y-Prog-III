<?php
require_once './clases/Ciudadano.php';
use Mendoza\Javier\Ciudadano;

$pathArchivos = "./archivos/ciudadanos.json";

try 
{
    $ciudad = isset($_POST["ciudad"]) == true && empty($_POST["ciudad"]) == false ? (string)$_POST["ciudad"] : throw new Exception("La ciudad no fue enviado como parametro"); 
    $clave = isset($_POST["clave"]) == true && empty($_POST["clave"]) == false ? (string)$_POST["clave"] : throw new Exception("La clave no fue enviado como parametro"); 
    $email = isset($_POST["email"]) == true  && empty($_POST["email"]) == false ? (string)$_POST["email"] : throw new Exception("El email no fue enviada como parametro");    

    $ciudadano = new Ciudadano($email,$clave,$ciudad);

    $retornoJson = $ciudadano -> GuardarEnArchivo($pathArchivos);

    $objRetorno = json_decode($retornoJson);

    if($objRetorno -> exito = true)
    {
        echo $objRetorno -> mensaje;
    }
    else
    {
        throw new Exception($objRetorno -> mensaje);
    }
    
} catch (Exception $ex) 
{
    echo "ERROR : " . $ex -> getMessage();
    
}
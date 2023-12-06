<?php
require_once './clases/Ciudadano.php';
use Mendoza\Javier\Ciudadano;

$pathArchivos = "./archivos/ciudadanos.json";

try 
{
    $retorno = new stdClass();
    $retorno -> exito = false;
    $retorno -> mensaje = "";

    $clave = isset($_POST["clave"]) == true && empty($_POST["clave"]) == false ? (string)$_POST["clave"] : throw new Exception("La clave no fue enviado como parametro"); 
    $email = isset($_POST["email"]) == true  && empty($_POST["email"]) == false ? (string)$_POST["email"] : throw new Exception("El email no fue enviada como parametro");   

    $ciudadano = new Ciudadano($email,$clave);

    $retornoJson = $ciudadano -> VerificarExistencia($ciudadano, $pathArchivos);

    $objRetorno = json_decode($retornoJson);

    if($objRetorno -> exito === true)
    {        
        $retorno -> exito = true;
        $retorno -> mensaje = $objRetorno -> mensaje;   
    }
    else
    {
        throw new Exception($objRetorno -> mensaje);
    }
    
} catch (Exception $ex) 
{
    $retorno -> mensaje =  "ERROR : " . $ex -> getMessage();    
}
finally
{
    echo json_encode($retorno);
}
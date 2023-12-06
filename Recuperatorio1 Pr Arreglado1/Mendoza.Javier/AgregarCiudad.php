<?php
// require_once("./clases/autoBD.php");
// use MendozaJavier\AutoBD;
require_once './clases/Ciudad.php';
use Mendoza\Javier\Ciudad;

try {

    $retorno = new stdClass();
    $retorno->exito = false;
    $retorno->mensaje = "";

    $poblacion = isset($_POST["poblacion"]) == true ? (int) $_POST["poblacion"] : throw new Exception("La poblacion no fue enviado como parametro");
    $nombre = isset($_POST["nombre"]) == true && empty($_POST["nombre"]) == false ? (string) $_POST["nombre"] : throw new Exception("El Nombre no fue enviado como parametro");
    $pais = isset($_POST["pais"]) == true && empty($_POST["pais"]) == false ? (string) $_POST["pais"] : throw new Exception("El pais no fue enviado como parametro");
    $foto = isset($_FILES["foto"]) == true ? (array) $_FILES["foto"] : throw new Exception("La foto no fue enviado como parametro");

    $pathFoto = Ciudad::ValidarFoto($foto, $nombre, $pais);

    $retornoPath_obj = json_decode($pathFoto);

    if ($retornoPath_obj->exito === true) 
    {
        $ciudad = new Ciudad($nombre, $pais, $poblacion, $retornoPath_obj->mensaje);
        $array_ciudades = Ciudad::Traer();

        $retorno_existencia = $ciudad->Existe($array_ciudades);

        if ($retorno_existencia == false) {

            if ($ciudad->Agregar()) 
            {
                if ($ciudad->GuardarFoto($foto)) {

                    $retorno->exito = true;
                    $retorno->mensaje = "Se agrego correctamente la ciudad con foto";
                } else {

                    $ciudad -> pathFoto = NULL;
                    throw new Exception("Se agrego la ciudad pero no se pudo subir la foto");
                }
            } else {
                throw new Exception("No se pudo agregar la ciudad");
            }

        } else {
            throw new Exception("la ciudad ya existe en el sistema");
        }
    } else {

        throw new Exception($retornoPath_obj->mensaje);
    }

} catch (Exception $ex) 
{
    $retorno->mensaje = "ERROR : " . $ex->getMessage();
} finally {
    echo json_encode($retorno);
}
<?php
// require_once("./clases/autoBD.php");
// use MendozaJavier\AutoBD;


require_once './clases/Ciudad.php';
use Mendoza\Javier\Ciudad;

try {

    $retorno = new stdClass();
    $retorno->exito = false;
    $retorno->mensaje = "";

    $ciudad_json = isset($_POST["ciudad_json"]) == true && empty($_POST["ciudad_json"]) == false ? (string)$_POST["ciudad_json"] : throw new Exception("La ciudad en formato JSON no fue enviado como parametro");

    $ciudad_obj = json_decode($ciudad_json);

    $id = isset($ciudad_obj->id) == true && empty($ciudad_obj->id) == false ? $ciudad_obj->id : throw new Exception("la Ciudad no contiene ID o es 0");    
    $poblacion = isset($ciudad_obj->poblacion) == true ? $ciudad_obj->poblacion : throw new Exception("la Ciudad no contiene poblacion");
    $nombre = isset($ciudad_obj->nombre) == true && empty($ciudad_obj->nombre) == false ? $ciudad_obj->nombre : throw new Exception("la Ciudad no contiene Nombre");
    $pais = isset($ciudad_obj->pais) == true && empty($ciudad_obj->pais) == false ? $ciudad_obj->pais : throw new Exception("la Ciudad no contiene pais");

    if(isset($ciudad_obj -> pathFoto) === true)
    {
        if(empty($ciudad_obj -> pathFoto) === false)
        {
            if(file_exists($ciudad_obj -> pathFoto))
            {
                $pathFoto = $ciudad_obj -> pathFoto;          
            }
            else
            {            
                throw new Exception("El pathFoto pasado no existe en el sistema");
            }
        }
        else
        {
            throw new Exception("La ciudad no contiene un PathFoto");
        }
    }
    else
    {
        $pathFoto = null;
    }          

    $foto = isset($_FILES["foto"]) == true ? (array) $_FILES["foto"] : throw new Exception("La foto no fue enviado como parametro");

    $retorno_validacion = Ciudad::ValidarFoto($foto, $nombre, $pais);

    $retorno_path = json_decode($retorno_validacion);

    if ($retorno_path->exito === true) {        

        $array_ciudades = Ciudad::Traer();
        $ciudad_nueva = new Ciudad($nombre, $pais, $poblacion,$retorno_path->mensaje, $id);

        $existencia =  $ciudad_nueva -> ExisteID($id);      
        
        if($existencia === true)
        {
            if ($ciudad_nueva-> Modificar()) {      
                
                if(isset($pathFoto))
                {
                    MoverArchivoModificado($pathFoto, $nombre, $pais);
                }                

                if ($ciudad_nueva->GuardarFoto($foto)) {

                    $retorno->exito = true;
                    $retorno->mensaje = "Se modifico correctamente la Ciudad con foto";
                    
                } else {
                    throw new Exception("Se modifico la Ciudad pero no se pudo subir la foto");
                }

            } else {                
                throw new Exception("No se pudo modificar la Ciudad");
            }   

        }
        else
        {
            throw new Exception("La ciudad que se quiere modificar no existe en el sistema");
        }

             
        
    } else {

        throw new Exception($retorno_path->mensaje);
    }
} catch (Exception $ex) {

    $retorno->mensaje = "ERROR : " . $ex->getMessage();
} finally {

    echo json_encode($retorno);
}


    /**  
     *
     * Mueve el archivo a la carpeta ciudades/modificados
     * 
     * @param string $path path del archivo que se quiere mover
     * @param string nombre que se le quiere poner al archivo
     * @param string pais que se le quiere poner al archivo
     * 
     * @return bool retorna true si se pudo mover el archivo, false caso contrario
     * 
     **/
    function MoverArchivoModificado(string $path, string $nombre, string $pais): bool
    {
        $retorno = false;
        
        if(file_exists($path))
        {

            $nombre_sin_espacios = str_replace(" ","",$nombre);
            $pais_sin_espacios = str_replace(" ","",$pais);

            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $nombreArchivo = $nombre_sin_espacios . "." . $pais_sin_espacios . "." . "modificado" . "." . date("his") . "." . $extension;
    
            $pathOriginal = $path;
            $pathDestino = "./ciudades/modificadas/" . $nombreArchivo;
    
            if (rename($pathOriginal, $pathDestino)) {
                $retorno = true;
            }
        }       

        return $retorno;
    }
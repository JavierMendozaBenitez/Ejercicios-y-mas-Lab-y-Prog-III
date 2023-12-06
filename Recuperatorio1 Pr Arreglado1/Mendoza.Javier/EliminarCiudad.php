<?php
require_once './clases/Ciudad.php';
use Mendoza\Javier\Ciudad;


if (isset($_POST["ciudad_json"]) && isset($_POST["accion"]) && $_POST["accion"] === "borrar") 
{
    try {

    $retorno = new stdClass();
    $retorno->exito = false;
    $retorno->mensaje = "";

    $ciudad_json = empty($_POST["ciudad_json"]) == false ? (string)$_POST["ciudad_json"] : throw new Exception("La ciudad en formato JSON esta vacio");

    $ciudad_obj = json_decode($ciudad_json);

    $id = isset($ciudad_obj->id) == true && empty($ciudad_obj->id) == false ? $ciudad_obj->id : throw new Exception("la Ciudad no contiene ID o es 0");        
    $nombre = isset($ciudad_obj->nombre) == true && empty($ciudad_obj->nombre) == false ? $ciudad_obj->nombre : throw new Exception("la Ciudad no contiene Nombre");
    $pais = isset($ciudad_obj->pais) == true && empty($ciudad_obj->pais) == false ? $ciudad_obj->pais : throw new Exception("la Ciudad no contiene pais");

    $ciudad = new Ciudad($nombre, $pais, -1, NULL,$id);

        if ($ciudad -> Eliminar() === true) 
        {
            Ciudad::GuardarEnArchivo($ciudad);
            $retorno->exito = true;
            $retorno->mensaje = "El producto fue eliminado correctamente del sistema";

        } else 
        {
            throw new Exception("No existe ninguna ciudad con el nombre y pais pasado");
        }

    } catch (Exception $ex)
     {
        $retorno->mensaje = "ERROR : " . $ex->getMessage();
    } 
    finally 
    {
        echo json_encode($retorno);
    }

} else if (isset($_GET["nombre"])) 
{
    if(empty($_GET["nombre"]) == false)
    {
        $nombre = $_GET["nombre"];
        $ciudad_obj = new Ciudad($nombre);
        $array_ciudades = Ciudad::Traer();

        if($ciudad_obj -> ExisteNombre($array_ciudades))
        {
            echo "La ciudad con el nombre pasado por GET existe en el sistema";

        }
        else
       {
         echo "La ciudad con el nombre pasado por GET no existe en el sistema";
       }
    }   
}
else
{
    $array_ciudadesBorradas = Ciudad::TraerCiudadesBorradas("./archivos/ciudades_borradas.txt");    

    var_dump($array_ciudadesBorradas);
    $tablaHTML = '<html>
        <head><title>Listado de Ciudades Eliminadas</title></head>
        <body>
        
        <h1>Listado de Ciudades Eliminadas</h1>
        
        <table>
        <tr>
          <th style="padding:0 15px 0 15px;"><strong>ID</strong></th>      
          <th style="padding:0 15px 0 15px;"><strong>NOMBRE </strong></th>
          <th style="padding:0 15px 0 15px;"><strong>PAIS </strong></th>
          <th style="padding:0 15px 0 15px;"><strong>POBLACION </strong></th>
        </tr>
        
        ';

    foreach ($array_ciudadesBorradas as $ciudad) {
        $string_ciudad = '<tr>
            <td style="padding:0 15px 0 15px;"><strong>' . $ciudad->id . '</strong></td>
            <td style="padding:0 15px 0 15px;"><strong>' . $ciudad->nombre . '</strong></td>
            <td style="padding:0 15px 0 15px;"><strong>' . $ciudad->pais . '</strong></td>
            <td style="padding:0 15px 0 15px;"><strong>' . $ciudad->poblacion . '</strong></td>
            </tr>
            
            ';

        $tablaHTML .= $string_ciudad;
    }

    $tablaHTML .= "</table>
    
        </body>
        </html>";

    echo $tablaHTML;
}
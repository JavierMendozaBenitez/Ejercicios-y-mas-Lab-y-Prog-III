<?php

namespace Mendoza\Javier;

use stdClass;
use Exception;

class Ciudadano
{

    public string $ciudad;
    public string $email;
    public string $clave;


    public function __construct(string $email, string $clave, string $ciudad = "No_Asignado")
    {
        $this->ciudad = $ciudad;
        $this->email = $email;
        $this->clave = $clave;
    }

    /**  
     *
     * Convierte los datos del ciudadano en formato JSON
     *     
     * @return string Datos del ciudadano en formato JSON
     **/
    public function ToJSON(): string
    {
        $retorno = new stdClass();
        $retorno->ciudad = $this->ciudad;
        $retorno->email = $this->email;
        $retorno->clave = $this->clave;

        return json_encode($retorno);
    }

    /**
     *
     * Guarda los datos del ciudadano en un archivo.
     *
     * @param string $path Direccion del Archivo en donde se desea guardar los datos.
     * @return string Retorna un JSON que contiene : 
     *  exito(true|false) = Indicando si la se pudo guardar el ciudadano  y
     *  mensaje(string) = Contiene el mensaje correspondiente a lo ocurrido
     **/
    public function GuardarEnArchivo(string $path): string
    {
        $retorno = new stdClass();
        $retorno->exito = true;
        $retorno->mensaje = "El ciudadano fue guardado en el archivo correctamente";

        try {
            //ABRO EL ARCHIVO
            $ar = fopen($path, "a"); //A - append

            //ESCRIBO EN EL ARCHIVO
            $cant = fwrite($ar, $this->ToJSON() . ",\r\n");

            if ($cant <= 0) {
                throw new Exception("Ocurrio un error al escribir el archivo y no fue guardado el ciudadano");
            }
        } catch (Exception $ex) {

            $retorno->exito = false;
            $retorno->mensaje = "GuardarEnArchivo : " . $ex->getMessage();
        } finally {
            fclose($ar);
            return json_encode($retorno);
        }
    }

    /**
     *
     * Obtiene los ciudadanos del archivo pasado por parametro
     *
     * @param string $path Direccion del Archivo en donde se desea recuperar los datos.
     * @return array Retorna un array de ciudadanos, si el archivo esta vacio retorna un array vacio.
     **/
    public static function TraerTodos(string $path): array
    {
        $array_ciudadanos = array();
        $contenido = "";

        //ABRO EL ARCHIVO
        $ar = fopen($path, "r");

        //LEO LINEA X LINEA DEL ARCHIVO 
        while (!feof($ar)) {
            $contenido .= fgets($ar);
        }

        //CIERRO EL ARCHIVO
        fclose($ar);

        $array_contenido = explode(",\r\n", $contenido);

        for ($i = 0; $i < count($array_contenido); $i++) {

            if ($array_contenido[$i] != "") {

                $ciudadano =  json_decode($array_contenido[$i]);

                if ($ciudadano != null) {
                    $email = $ciudadano->email;
                    $clave = $ciudadano->clave;
                    $ciudad = $ciudadano->ciudad;

                    if ($email !== null && $clave !== null && $ciudad !== null) {
                        $ciudadano_array = new Ciudadano($email, $clave, $ciudad);
                        array_push($array_ciudadanos, $ciudadano_array);
                    }
                }                
            }
        }

        return $array_ciudadanos;
    }

    /**
     *
     * Verifica si el ciudadano existe en el archivo "ciudadanos.JSON"
     *
     * @param Ciudadano ciudadano que se desea verificar si existe
     * @param string $path Direccion del Archivo en donde se desea recuperar los datos.
     * 
     * @return string Retorna un JSON que contiene : 
     *  exito(true|false) = Indicando si la se pudo guardar el ciudadano  y
     *  mensaje(string) = Contiene el mensaje correspondiente a lo ocurrido
     **/
    public static function VerificarExistencia(ciudadano $ciudadano, string $path): string
    {
        $retorno = new stdClass();
        $retorno->exito = false;
        $retorno->mensaje = "El ciudadano no esta registrado en el sistema";

        $contadorExactIguales = 0;

        try {

            $array_ciudadanos = ciudadano::TraerTodos($path);

            if ($ciudadano != null) {

                if (count($array_ciudadanos) != 0) {


                    foreach ($array_ciudadanos as $ciudadano_array) {

                        if($ciudadano -> email === $ciudadano_array -> email && $ciudadano -> clave === $ciudadano_array -> clave )
                        {
                            $retorno -> exito = true;
                            $contadorExactIguales++;
                        }

                    }

                    if ($retorno->exito == true) {

                        $retorno->mensaje = "El ciudadano existe en el archivo y tiene una cantidad de {$contadorExactIguales} ciudadanos con la misma ciudad";
                    } 
                    
                } else {
                    $retorno->mensaje = "Se debe agregar minimo 1 ciudadano para verificar si existe";
                }
            } else {
                throw new Exception("El objeto ciudadano pasado es NULL");
            }
        } catch (Exception $ex) {
            $retorno->mensaje = " Verificar Existencia :" . $ex->getMessage();
        }

        return json_encode($retorno);
    }
}
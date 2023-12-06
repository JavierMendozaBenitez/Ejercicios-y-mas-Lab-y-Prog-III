<?php

namespace Mendoza\Javier;
require_once("./clases/IParte1.php");
require_once("./clases/IParte2.php");

use stdClass;
use Exception;
use PDO;
use PDOException;

class Ciudad implements IParte1, IParte2
{

    public int $id;
    public int $poblacion;
    public string $nombre;
    public string $pais;
    public string | null $pathFoto;

    public function __construct(string $nombre = "No_Asignado", string $pais = "No_Asignado", int $poblacion = -1, string $pathFoto = NULL, int $id = 0)
    {
        $this->id = $id;
        $this->pais = $pais;
        $this->poblacion = $poblacion;
        $this->nombre = $nombre;
        $this->pathFoto = $pathFoto;
    }

    /**  
     *
     * Convierte los datos de la ciudad en formato JSON
     *     
     * @return string Datos de la ciudad en formato JSON
     **/
    public function toJSON(): string
    {
        $retorno = new stdClass();

        $retorno->nombre = $this->nombre;
        $retorno->pais = $this->pais;
        $retorno->id = $this->id;
        $retorno->poblacion = $this->poblacion;
        $retorno->pathFoto = $this->pathFoto;

        return json_encode($retorno);
    }

    //--------------------------------------------------------------------- Funciones de la BD ---------------------------------------------------------//

    /**  
     *
     * Agrega la ciudad a la base de datos
     *     
     * @return bool Retorna true si se pudo agregar la ciudad, false en caso contrario
     **/
    public function Agregar(): bool
    {
        $retorno = true;

        try {

            $pdo = new PDO('mysql:host=localhost;dbname=ciudades_bd;charset=utf8', "root", "");

            $consulta = $pdo->prepare('INSERT INTO ciudades (poblacion, nombre, pais, path_foto)
             VALUES(:poblacion, :nombre, :pais, :pathFoto)');

            $consulta->bindValue(':poblacion', $this->poblacion, PDO::PARAM_INT);
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':pais', $this->pais, PDO::PARAM_STR);
            $consulta->bindValue(':pathFoto', $this->pathFoto, PDO::PARAM_STR);

            $consulta->execute();
        } catch (Exception $ex) {
            $retorno = false;
        }

        return $retorno;
    }

    /**  
     *
     * Retorna un array con todas los ciudades de la Base de Datos
     *     
     * @return array Retorna un array de ciudades, si el archivo esta vacio retorna un array vacio.
     **/
    public static function Traer(): array
    {
        $retornoBD = array();
        $retornoArray = array();

        try {

            $pdo = new PDO('mysql:host=localhost;dbname=ciudades_bd;charset=utf8', "root", "");

            $consulta = $pdo->prepare('SELECT id, poblacion,nombre,pais,path_foto AS pathFoto FROM ciudades ');

            $consulta->execute();

            $retornoBD = $consulta->fetchAll(PDO::FETCH_OBJ);

            foreach ($retornoBD as $ciudadBD) {
                $id_ciudad = $ciudadBD->id;
                $poblacion_ciudad = $ciudadBD->poblacion;
                $nombre_ciudad = $ciudadBD->nombre;
                $pais_ciudad = $ciudadBD->pais;
                $pathFoto_ciudad = $ciudadBD->pathFoto;

                $ciudad_obj = new Ciudad($nombre_ciudad, $pais_ciudad, $poblacion_ciudad, $pathFoto_ciudad, $id_ciudad);

                array_push($retornoArray, $ciudad_obj);
            }
        } catch (Exception $th) {
            $retornoArray = array();
            //En caso de un error retorno un array vacio          
        }

        return $retornoArray;
    }

    /**  
     *
     * Retorna un ciudad Envasado con el mismo ID del parametro
     *    
     * @param int $id ID del que se desea obtener el ciudad 
     * @return ciudadEnvasado|false Retorna un ciudad envasado si el id coincide con alguno de la BD,caso contrario retorna false
     **/
    /*public static function TraerUno(int $id): Ciudad | false
    {
        try {

            $pdo = new PDO('mysql:host=localhost;dbname=ciudads_bd;charset=utf8', "root", "");

            $consulta = $pdo->prepare('SELECT id, codigo_barra AS poblacion, nombre AS nombre, pais AS pais,
            nombre AS nombre, foto AS pathFoto FROM ciudads WHERE id = :id');

            $consulta->bindValue(':id', $id, PDO::PARAM_INT);

            $consulta->execute();

            if ($consulta->rowCount() > 0) {

                $retornociudad = $consulta->fetch(PDO::FETCH_OBJ);

                $idciudad = $retornociudad->id;
                $poblacionciudad = $retornociudad->poblacion;
                $nombreciudad = $retornociudad->nombre;
                $paisciudad = $retornociudad->pais;
                $nombreciudad = $retornociudad->nombre;

                if (isset($retornociudad->pathFoto)) {
                    $pathFotociudad = $retornociudad->pathFoto;
                } else {
                    $pathFotociudad = "NULL";
                }

                $retorno = new Ciudad($nombreciudad, $paisciudad, $poblacionciudad, $nombreciudad, $pathFotociudad, $idciudad);
            } else {
                throw new Exception("El ciudad no existe en la BD");
            }
        } catch (Exception $th) {

            $retorno = false;
        }

        return $retorno;
    }*/

    /**  
     *
     * Elimina un ciudad que contenga el mismo nombre y pais del objeto
     *    
     * @param int $id ID de la ciudad que se desea eliminar de la BD
     * @return bool Retorna true si la ciudad fue eliminado de la BD, caso contrario retorna false
     **/
    public function Eliminar(): bool
    {
        $retorno = true;

        try {

            $pdo = new PDO('mysql:host=localhost;dbname=ciudades_bd;charset=utf8', "root", "");

            $consulta = $pdo->prepare('DELETE FROM ciudades WHERE nombre = :nombre AND pais = :pais');

            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':pais', $this->pais, PDO::PARAM_STR);

            $consulta->execute();

            if ($consulta->rowCount() === 0) {
                throw new Exception("No se elimino ningun ciudad con ese ID");
            }
        } catch (Exception $ex) {
            $retorno = false;
        }

        return $retorno;
    }

    /**  
     *
     * Modifica la ciudad que contiene el mismo ID
     * 
     * @return bool Retorna true si la ciudad fue modificada de la BD, caso contrario retorna false
     **/
    public function Modificar(): bool
    {
        $retorno = true;

        try {
            $pdo = new PDO('mysql:host=localhost;dbname=ciudades_bd;charset=utf8', "root", "");

            $consulta = $pdo->prepare("UPDATE ciudades SET poblacion = :poblacion, nombre = :nombre, 
            pais = :pais, path_foto = :pathFoto WHERE id = :id");

            $consulta->bindValue(':poblacion', $this->poblacion, PDO::PARAM_INT);
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':pais', $this->pais, PDO::PARAM_STR);
            $consulta->bindValue(':pathFoto', $this->pathFoto, PDO::PARAM_STR);
            $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);

            $consulta->execute();

            if ($consulta->rowCount() === 0) {
                throw new Exception("No se modifico ningun ciudad");
            }
        } catch (Exception $ex) {
            $retorno = false;
        }

        return $retorno;
    }

    //--------------------------------------------------------------------- Funciones de Fotos ---------------------------------------------------------

    /**  
     *
     * Valida que un archivo sea una foto y que no sea demasiado grande.
     * 
     * @return string Retorna un JSON que contiene : 
     *  exito(true|false) = Indicando si la foto es valida o no
     *  mensaje(string) = Contiene el error en caso de false o retorna el path completo del archivo en caso de true
     * 
     **/
    public static function ValidarFoto(array $foto, string $nombre, string $pais): string
    {
        try {

            $retorno = new stdClass();
            $retorno->exito = true;
            $retorno->mensaje = "";

            if ($foto != NULL) {

                $nombre_sin_espacios = str_replace(" ", "", $nombre);
                $pais_sin_espacios = str_replace(" ", "", $pais);

                $foto_nombre = $foto["name"];
                $extension = pathinfo($foto_nombre, PATHINFO_EXTENSION);
                $pathFoto = $nombre_sin_espacios . "." . $pais_sin_espacios . "." . date("his") . "." . $extension;
                $retorno->mensaje = "./ciudades/fotos/" . $pathFoto;

                //VERIFICO EL TAMAÑO MAXIMO QUE PERMITO SUBIR

                if ($foto["size"] > 2000000) {
                    throw new Exception("El archivo es demasiado grande, inserte uno mas chico");
                }

                //OBTIENE EL TAMAÑO DE UNA IMAGEN, SI EL ARCHIVO NO ES UNA		
                //IMAGEN, RETORNA FALSE       

                $esImagen = getimagesize($foto["tmp_name"]);

                if ($esImagen != false) {
                    if (
                        $extension != "jpg" && $extension != "jpeg" && $extension != "gif"
                        && $extension != "png"
                    ) {
                        throw new Exception("Solo son permitidas imagenes con extension JPG, JPEG, PNG o GIF.");
                    }
                } else {
                    throw new Exception("El archivo no es una imagen,POR FAVOR inserte una imagen");
                }
            } else {
                throw new Exception("Es necesario cargar una foto para poder realizar el ALTA");
            }
        } catch (Exception $ex) {

            $retorno->exito = false;
            $retorno->mensaje = "Error : " . $ex->getMessage();
        } finally {
            return json_encode($retorno);
        }
    }

    /**  
     *
     * Guarda una foto en el path deseado
     * 
     * @param array $arrayFoto array foto que se quiere guardar
     * @return bool retorna true si se pudo subir la foto, false caso contrario
     * 
     **/
    public function GuardarFoto(array $arrayFoto): bool
    {
        $retorno = false;

        if (move_uploaded_file($arrayFoto["tmp_name"], $this->pathFoto)) {
            $retorno = true;
        }

        return $retorno;
    }

    //--------------------------------------------------------------------- Funciones de Archivos ---------------------------------------------------------

    /**  
     *
     * Guarda los datos de la ciudad en el archivo ciudades_borradas.txt
     * 
     * @param Ciudad $ciudad Ciudad de la cual se desea guardar los datos
     * 
     * @return bool Retorna true si se pudo guardar los datos, false caso contrario
     **/
    public static function GuardarEnArchivo(Ciudad $ciudad): bool
    {
        $retorno = false;

        $datos_ciudad = $ciudad->toJSON();

        //ABRO EL ARCHIVO
        $ar = fopen(__DIR__ . "/../archivos/ciudades_borradas.txt", "a"); //A - append

        //ESCRIBO EN EL ARCHIVO
        $cant = fwrite($ar, $datos_ciudad . ",\r\n");

        if ($cant > 0) {
            $retorno = true;
        }

        //CIERRO EL ARCHIVO
        fclose($ar);

        return $retorno;
    }


    /**  
     *
     * Muestra las fotos de la carpeta Fotos Modificadas
     * 
     * @return string retorna la tabla html con las imagenes de la carpeta
     * 
     **/
    public static function MostrarModificadas(): string
    {

        $rutaImagenes = "./ciudades/modificadas/";
        $manejadorArchivos = opendir($rutaImagenes);

        $tablaHTML = '<html>
        <head><title> Imagenes de Ciudades Modificadas </title></head>
        <body>
        
        <h1>Imagenes de ciudades Modificados</h1>
        
        <table>
        <tr>          
          <th style="padding:0 15px 0 15px;"><strong>FOTOS</strong></th>
        </tr>
        
        ';

        while ($file = readdir($manejadorArchivos)) 
        {

            if ($file != "." && $file != "..") {

                $rutaCompleta = $rutaImagenes . $file;
                $stringciudad = '<tr>            
            <td style="padding:0 15px 0 15px;"><img src="' . $rutaCompleta . '" width="50" height="50"></td>
            </tr>            
            ';

                $tablaHTML .= $stringciudad;
            }
        }

        $tablaHTML .= "</table>
    
        </body>
        </html>";

        return $tablaHTML;
    }

    //--------------------------------------------------------------------- Funciones de ciudades ---------------------------------------------------------

    /**  
     *
     * Chequea si el ciudad existe en el array ciudades pasado por parametro
     * 
     * @param array $ciudades array donde se quiere chequear la existencia
     * @return bool true si existe el ciudad,caso contrario false
     * 
     **/
    public function Existe(array $ciudades): bool
    {
        $retorno = false;

        foreach ($ciudades as $ciudad) {

            if ($ciudad->nombre === $this->nombre && $ciudad->pais === $this->pais) {

                $retorno = true;
                break;
            }
        }

        return $retorno;
    }

    public function ExisteNombre(array $ciudades): bool
    {
        $retorno = false;

        foreach ($ciudades as $ciudad) {

            if ($ciudad->nombre === $this->nombre) {

                $retorno = true;
                break;
            }
        }

        return $retorno;
    }

    public static function ExisteID(int $id): bool
    {
        $retorno = true;

        try {
            $pdo = new PDO('mysql:host=localhost;dbname=ciudades_bd;charset=utf8', "root", "");

            $consulta = $pdo->prepare('SELECT id, poblacion, nombre, pais,path_foto AS pathFoto FROM ciudades WHERE id = :id');

            $consulta->bindValue(':id', $id, PDO::PARAM_INT);

            $consulta->execute();

            if ($consulta->rowCount() === 0) {
                throw new Exception("La ciudad no existe");
            }
        } catch (Exception $ex) {
            $retorno = false;
        }


        return $retorno;
    }

    /**
     * Obtiene las ciudades eliminadas del archivo pasado por parámetro.
     *
     * @param string $path Direccion del archivo en donde se encuentran las ciudades borradas.
     * @return array Retorna un array de ciudades eliminadas, si el archivo está vacío retorna un array vacío.
     */
    public static function TraerCiudadesBorradas(string $path): array
    {
        $array_ciudades = array();
        $contenido = "";

        // ABRO EL ARCHIVO
        $ar = fopen($path, "r");

        // LEO LINEA X LINEA DEL ARCHIVO
        while (!feof($ar)) {
            $contenido .= fgets($ar);
        }

        // CIERRO EL ARCHIVO
        fclose($ar);

        $array_contenido = explode(",\r\n", $contenido);

        for ($i = 0; $i < count($array_contenido); $i++) {

            if ($array_contenido[$i] != "") {

                $ciudad = json_decode($array_contenido[$i]);

                // Asegúrate de tener los atributos adecuados en tu clase Ciudad
                $id = $ciudad->id;
                $nombre = $ciudad->nombre;
                $pais = $ciudad->pais;
                $poblacion = $ciudad->poblacion;

                // Crea una instancia de Ciudad con los datos recuperados
                $ciudad_obj = new Ciudad($nombre, $pais, $poblacion, NULL, $id);

                array_push($array_ciudades, $ciudad_obj);
            }
        }

        return $array_ciudades;
    }
}
<?php
namespace Tignino_Christian
{
    require_once "AccesoDatos.php";
    require_once "IParte1.php";
    require_once "IParte2.php";
    //require_once "IParte3.php";

    use Tignino_Christian\IParte1;
    use Tignino_Christian\IParte2;
    //use Tignino_Christian\IParte3;
    use POO\AccesoDatos;
    use stdClass;
    use PDO;

    class Ciudad implements IParte1
    {
        public int $id;
        public $nombre;
        public int $poblacion;
        public $pais;
        public $pathFoto;

        public function __construct(int $id = 0, string $nombre = "", int $poblacion = 0, string $pais = "", string $pathFoto = "") {
            $this->id = $id;
            $this->nombre = $nombre;
            $this->poblacion = $poblacion;
            $this->pais = $pais;
            $this->pathFoto = $pathFoto;
        }

        public function toJSON(): string {
            return json_encode(get_object_vars($this));
        }

        public function agregar(): bool
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $query = $objetoAccesoDato->retornarConsulta("INSERT INTO ciudades(id, nombre, poblacion, pais, path_foto) 
                                                        VALUES(:id, :nombre, :poblacion, :pais, :path_foto)");
            $query->bindValue(':id', $this->id, PDO::PARAM_INT);
            $query->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $query->bindValue(':poblacion', $this->poblacion, PDO::PARAM_INT);
            $query->bindValue(':pais', $this->pais, PDO::PARAM_STR);
            $query->bindValue(':path_foto', $this->pathFoto, PDO::PARAM_STR);

            $query->execute();

            $retorno = false; // Inicializar la variable de retorno

            if($query->rowCount() != 0)
            {
                $retorno = true;
            }

            return $retorno;
        }

        public static function traer():array
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $ciudades  = [];

            $query = $objetoAccesoDato->retornarConsulta("SELECT * FROM ciudades");

            $query->execute();

            while ($row = $query->fetch(PDO::FETCH_ASSOC))
            {
                $ciudad = new Ciudad($row["id"], $row["nombre"], $row["poblacion"], $row["pais"]);

                if ($row["path_foto"] != null) 
                {
                    $ciudad->pathFoto = $row["path_foto"];
                }

                $ciudades[] = $ciudad;
            }
            return $ciudades;
        }

        public function existe($ciudades) : bool
        {
            $retorno = false;

            $array = Ciudad::traer();

            foreach($array as $comprobar)
            {
                if($this->nombre === $comprobar->nombre && $this->pais === $comprobar->pais)
                {
                    $retorno = true;
                    break;
                }
            }

            return $retorno;
        }

        /////////////////////////////////////////----PARTE 2----/////////////////////////////////////////

        public function eliminar(): bool
        {
            $retorno = false;

            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->retornarConsulta("DELETE FROM ciudades WHERE nombre = :nombre");

            $consulta->bindValue(":nombre", $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(":pais", $this->pais, PDO::PARAM_STR);

            $consulta->execute();

            if ($consulta->rowCount() != 0) {
                $retorno = true;
            }

            return $retorno;
        }

        public static function guardarEnArchivo(Ciudad $ciudad) : bool
        {
            $path = 'clases/archivos/ciudades_borradas.txt';
            $carpetaImagenes = './ciudadesBorradas/';

            $archivo = fopen($path, "a");
            $exito = false;
            $mensaje = "No se pudo guardar en el archivo";

            if($archivo != false)
            {
                fwrite($archivo, $ciudad->id."." .  $ciudad->nombre ."." . $ciudad->poblacion."." . $ciudad->pais."." . $ciudad->pathFoto . "\r\n");
                $exito = true;
                $mensaje = "Se guardó correctamente en el archivo";

                // Mover la foto al subdirectorio ciudadesBorradas con el nuevo nombre
                $nuevaUbicacionFoto = $carpetaImagenes . $ciudad->id . ".borrado." . date("His") . ".jpg";
                if (file_exists($ciudad->pathFoto)) 
                {
                    rename($ciudad->pathFoto, $nuevaUbicacionFoto);
                }
            }
            // Invocar al método guardarJSON con './archivos/ciudades_eliminadas.json'
            $ciudad->guardarJSON('clases/archivos/ciudades_eliminadas.json');

            fclose($archivo);            
            
            return json_encode(array('exito' => $exito, 'mensaje' => $mensaje));
        }

        public function guardarJSON($path) : string {
            $objRespuesta = new stdClass();
            $objRespuesta->exito = false;
            $objRespuesta->mensaje = "Ocurrio un error. No se pudo guardar el archivo";

            $ar = fopen($path,"a");

            $cant = fwrite($ar,$this->toJSON()."\r\n");

            if($cant > 0)
            {
                $objRespuesta->exito = true;
                $objRespuesta->mensaje = "Registro guardado con exito!";
            }

            fclose($ar);

            return json_encode(get_object_vars($objRespuesta));
            
        }

        /*
        public function modificar(): bool
        {
            $retorno = false;
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->retornarConsulta("UPDATE autos SET marca = :marca, color = :color, precio = :precio WHERE patente = :patente");
    
            $consulta->bindValue(':patente', $this->patente, PDO::PARAM_STR);
            $consulta->bindValue(':marca', $this->marca, PDO::PARAM_STR);
            $consulta->bindValue(':color', $this->color, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
          
            
            $consulta->execute();

            if($consulta->rowCount() != 0)
            {
                $retorno = true;
            }
    
            return $retorno;
        }
        */

        /////////////////////////////////////////----PARTE 3----/////////////////////////////////////////
    }
}
?>
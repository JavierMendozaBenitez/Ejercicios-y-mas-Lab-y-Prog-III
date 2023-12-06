<?php 
namespace Tignino_Christian
{
    use stdClass;

    class Ciudadano
    {
        public string $ciudad;
        public string $email;
        public int $clave;

        public function __construct(string $ciudad = "", string $email = "", int $clave = 0) {
            $this->ciudad = $ciudad;
            $this->email = $email;
            $this->clave = $clave;
        }

        public function toJSON():string{
            return json_encode(get_object_vars($this));
        }

        public function guardarEnArchivo($path) : string {
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

        public static function verificarExistencia(Ciudadano $ciudadano, $path): string
        {
            $objRta = new stdClass();
            $objRta->exito = false;
            $objRta->mensaje = "No se encontro al ciudadano";

            $arrCiudadanos = Ciudadano::traerTodos($path);
            foreach ($arrCiudadanos as $ciudadanoAComprobar) {
                if ($ciudadano->email == $ciudadanoAComprobar->email && $ciudadano->clave == $ciudadanoAComprobar->clave) {
                    $objRta->exito = true;
                    $objRta->mensaje = 'El ciudadano esta registrado.';
                }
            }

            return json_encode($objRta);
        }

        public static function traerTodos(string $path){
            $retorno = array();
            $str = "";
            $ar = fopen($path, "r");

            while(!feof($ar))
            {
                $str = fgets($ar);

                if($str != "")
                {
                    array_push($retorno, json_decode($str));
                }
            }

            fclose($ar);

            return $retorno;
        }

        public static function TraerTodosJSON(string $path)
        {
            $autos = [];
            $ar = fopen($path, "r");

            while (!feof($ar)) 
            {
                $linea = fgets($ar);
                $autos = json_decode($linea);
        
                if (isset($autos)) 
                {
                    $autos[] = $autos;
                }
            }

            fclose($ar);

            return json_encode($autos, JSON_PRETTY_PRINT);
        }
    }
}
?>
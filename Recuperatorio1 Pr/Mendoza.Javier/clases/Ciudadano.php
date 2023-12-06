<?php

namespace Mendoza\Javier;

class Ciudadano {
    public $ciudad;
    public $email;
    public $clave;

    public function __construct($email, $clave, $ciudad = null) {
        $this->ciudad = $ciudad;
        $this->email = $email;
        $this->clave = $clave;
    }

    public function ToJSON(): string{
        $json= array(
            "ciudad"=>$this->ciudad,
            "email"=>$this->email,
            "clave"=>$this->clave);
        return json_encode($json);
    }

    public function guardarEnArchivo(string $path): string //"./archivos/autosbd_borrados.txt"
    {
        $retorno = "";		
		$ar = fopen($path, "a");
		$cant = fwrite($ar,$this->MostrarDatos() ."\r\n");		
		if($cant > 0)
		{
			$retorno= '{"exito" : true,"mensaje": "auto agregado"}';
		}
		else{
			$retorno= '{"exito" : false,"mensaje": "hubo un problema con el archivo"}';
		}
		fclose($ar);
		return $retorno;
    }
    public function mostrarDatos():string{

        return "ciudad:{$this->ciudad},email:{$this->email},clave:{$this->clave}";
    }

    public static function traerTodos(string $path){//carpeta ubicada en ./archivos/usuarios.json
        
        $ciudadanos_obtenidos = [];
        $array_retorno = array();
        if (file_exists($path))
        {
            $ciudadanos_obtenidos = json_decode((file_get_contents($path)), true);
            foreach ($ciudadanos_obtenidos as $ciudadano) 
            {
                $ciudadanoAhora = new Ciudadano($ciudadano['ciudad'],$ciudadano['email'], $ciudadano['clave']);
                $array_retorno[] = $ciudadanoAhora;
            }
        }
        return $array_retorno;

    }

    public static function verificarExistencia(Ciudadano $ciudadano,string $path){

        $ciudadanos=Ciudadano::traerTodos(($path));
        $retorno = '{"exito" : false,"mensaje": "el auto no existe en el json"}';

        foreach($ciudadanos as $item){

                if($item->email == $ciudadano->email && $item->clave == $ciudadano->clave){
                    $retorno  = '{"exito" : true,"mensaje": "Existe"}'; 
                }
        }
        return $retorno;
    }
}

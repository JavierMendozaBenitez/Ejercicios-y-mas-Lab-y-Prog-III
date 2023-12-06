<?php
namespace MendozaJavier;

class Auto{

    protected string $patente;
    protected string $marca;
    protected string $color;
    protected int $precio;
    public function Patente(){

        return $this->patente;
    }

    public function Marca(){

        return $this->marca;
    }
    public function Color(){

        return $this->color;
    }
    public function Precio(){

        return $this->precio;
    }


    public function __construct(string $patente,string $marca,string $color,int $precio) {
        
        $this->patente=$patente;
        $this->marca=$marca;
        $this->color=$color;
        $this->precio=$precio;
    }

    public function ToJSON(){
        $json= array(
            "patente"=>$this->patente,
            "marca"=>$this->marca,
            "color"=>$this->color,
            "precio"=>$this->precio);
        return json_encode($json);
    }

    public function guardarJSON(string $path){

        $nemuaticos_obtenidos = [];
        $retorno ="";
        if (file_exists($path)) {
            $nemuaticos_obtenidos = json_decode(file_get_contents($path), true);
        }

        $neumatico_nuevo = $this->toJSON(); 
        $nemuaticos_obtenidos[] = json_decode($neumatico_nuevo, true); 
        $neumaticosJSON = json_encode($nemuaticos_obtenidos);

        if (file_put_contents($path, $neumaticosJSON)) {

            $retorno= '{"exito" : true,"mensaje": "usuario agregado al archivo json"}';
        } else {

            $retorno= '{"exito" : false,"mensaje": "hubo un problema con el archivo"}';
        }
        return $retorno;
    }
    public static function TraerTodosJSON(string $path){//carpeta ubicada en ./archivos/usuarios.json
        
        $nemuaticos_obtenidos = [];
        $array_retorno = array();
        if (file_exists($path))
        {
            $nemuaticos_obtenidos = json_decode((file_get_contents($path)), true);
            foreach ($nemuaticos_obtenidos as $neumatico) 
            {
                $auto = new Auto($neumatico['patente'],$neumatico['marca'], $neumatico['color'], $neumatico['precio']);
                $array_retorno[] = $auto;
            }
        }
        return $array_retorno;

    }

    public static function verificarNeumaticoJSON(Auto $auto,string $path){

        $autos=Auto::TraerTodosJSON(($path));
        $retorno = '{"exito" : false,"mensaje": "el auto no existe en el json"}';

        foreach($autos as $item){

                if($item->patente == $auto->patente){
                    $retorno  = '{"exito" : true,"mensaje": "Existe"}'; 

                }
        }
        return $retorno;
    }
}
<?php

namespace Mendoza\Javier;
require_once("./clases/IParte1.php");

use PDO;
use PDOException;

class Ciudad implements IParte1, IParte2 {
    public $id;
    public $nombre;
    public $poblacion;
    public $pais;
    public $pathFoto;

    public function __construct($id = null, $nombre = null, $poblacion = null, $pais = null, $pathFoto = null) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->poblacion = $poblacion;
        $this->pais = $pais;
        $this->pathFoto = $pathFoto;
    }

    public function toJSON(): string {
        $ciudadData = [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'poblacion' => $this->poblacion,
            'pais' => $this->pais,
            'pathFoto' => $this->pathFoto,
        ];

        return json_encode($ciudadData);
    }

    public function agregar():bool
    {
        $exito=false;
        try{
            $pdo= new PDO("mysql:host=localhost;dbname=ciudades_bd","root","");
            $sql=$pdo->prepare("INSERT INTO `ciudades`(`id`,`nombre`, `poblacion`, `pais`, `pathFoto`) 
                                VALUES (:id,:nombre,:poblacion,:pais,:pathFoto)");
            
            $sql->bindParam(':id',$this->id,PDO::PARAM_STR,30);
            $sql->bindParam(':nombre',$this->nombre,PDO::PARAM_STR,30);
            $sql->bindParam(':poblacion',$this->poblacion,PDO::PARAM_STR,15);
            $sql->bindParam(':pais',$this->pais,PDO::PARAM_INT);
            $sql->bindParam(':pathFoto',$this->pathFoto,PDO::PARAM_STR,50);

            $sql->execute();
            $exito=true;
        }catch(PDOException $e){

            echo $e->getMessage();
            $exito=false;

        }
        return $exito;
    }
    public static function traer():array
    {
        try{
            $ciudades=array();
            $pdo= new PDO("mysql:host=localhost;dbname=ciudades_bd","root","");
            $sql=$pdo->query("SELECT * FROM ciudades ");

            $sql->execute();
            
            while($fila=$sql->fetch())
            {
                $id =  $fila["id"];   
                $nombre = $fila["nombre"];
                $poblacion = $fila["poblacion"];
                $pais = $fila["pais"];
                $pathFoto = $fila["pathFoto"];
                if($pathFoto != null){
                    $item= new Ciudad($id,$nombre,$poblacion,$pais,$pathFoto); 
                }else{
                    $item= new Ciudad($id,$nombre,$poblacion,$pais,"sin foto");
                }
                array_push($ciudades, $item);
            }
            return $ciudades;         		
    
        }catch(PDOException $e){

            echo $e->getMessage();

        }
    }
    public function existe($ciudades): bool
    {
        $retorno = false;
        if(count($ciudades) > 0){           
            foreach($ciudades as $item) {
                if($this->nombre == $item->nombre && $this->pais == $item->pais){
                    $retorno = true;
                    break;
                }
            }
        }
        return $retorno;  
    }

    public function modificar(): bool
    {
        $exito=false;
        try{
            $pdo= new PDO("mysql:host=localhost;dbname=ciudades_bd","root","");
            $sql=$pdo->prepare("UPDATE ciudades SET id=:id,nombre=:nombre,poblacion=:poblacion,
                                pais=:pais,pathFoto=:pathFoto WHERE id = :id");
            $sql->bindParam(':id',$this->id,PDO::PARAM_STR,30);
            $sql->bindParam(':nombre',$this->nombre,PDO::PARAM_STR,30);
            $sql->bindParam(':poblacion',$this->poblacion,PDO::PARAM_STR,15);
            $sql->bindParam(':pais',$this->pais,PDO::PARAM_INT);
            $sql->bindParam(':pathFoto',$this->pathFoto,PDO::PARAM_STR,50);
            $sql->execute();
            $exito=true;
        }catch(PDOException $e){

            echo $e->getMessage();
            $exito=false;

        }
        return $exito;
    }
    public function eliminar(): bool
    {
        $exito=false;
        try
        {
            $pdo= new PDO("mysql:host=localhost;dbname=ciudades_bd","root","");

            $sql=$pdo->prepare("DELETE FROM `ciudades` WHERE id=:id");
            $sql->bindParam(':id',$id,PDO::PARAM_STR,30);
            $sql->execute();

            $exito=true;

        }catch(PDOException $e)
        {
            echo $e->getMessage();
            $exito=false;
        }

        return $exito;
    }
    public static function GuardarEnArchivo(Ciudad $ciudad): string //"./archivos/autosbd_borrados.txt"
    {
        $retorno = "";		
		$ar = fopen("../archivos/ciudades_borradas.txt", "a");
		$cant = fwrite($ar,Ciudad::MostrarDatos() ."\r\n");		
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
    public function MostrarDatos():string{

        return "id:{$this->id},nombre:{$this->nombre},poblacion:{$this->poblacion},pais:{$this->pais},pathFoto:{$this->pathFoto}";
    }
    public static function MostrarModificadas() {
        $rutaDirectorio = '../ciudades/modificadas/';
        $archivos = scandir($rutaDirectorio);
    
        $imagenes = array_filter($archivos, function ($archivo) {
            $extension = pathinfo($archivo, PATHINFO_EXTENSION);
            $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
            return in_array($extension, $extensionesPermitidas);
        });
    
        return $imagenes;
    }
    
}

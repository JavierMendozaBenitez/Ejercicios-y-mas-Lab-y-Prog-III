<?php
namespace MendozaJavier;
require_once("./clases/auto.php");
require_once("./clases/IParte1.php");
require_once("./clases/IParte2.php");
require_once("./clases/IParte3.php");


use PDO;
use PDOException;

class AutoBD extends Auto implements IParte1,IParte2,IParte3{

    protected string $pathFoto;

    public function PathFoto(){

        return $this->pathFoto;
        
    }

    public function __construct(string $patente="",string $marca="", string $color="",float $precio=-1,string $pathFoto=""){
        parent::__construct($patente,$marca,$color,$precio);
        $this->pathFoto = $pathFoto != null ? $pathFoto : "sin foto";
    }

    public function ToJSON()
    {
        $json= array(
            "patente"=>$this->patente,
            "marca"=>$this->marca,
            "color"=>$this->color,
            "precio"=>$this->precio,
            "pathFoto"=>$this->pathFoto);
        return json_encode($json);
    }
    
    public function Agregar():bool
    {
        $exito=false;
        try{
            $pdo= new PDO("mysql:host=localhost;dbname=garage_bd","root","");
            $sql=$pdo->prepare("INSERT INTO `autos`(`patente`,`marca`, `color`, `precio`, `foto`) 
                                VALUES (:patente,:marca,:color,:precio,:foto)");
            
            $sql->bindParam(':patente',$this->patente,PDO::PARAM_STR,30);
            $sql->bindParam(':marca',$this->marca,PDO::PARAM_STR,30);
            $sql->bindParam(':color',$this->color,PDO::PARAM_STR,15);
            $sql->bindParam(':precio',$this->precio,PDO::PARAM_INT);
            $sql->bindParam(':foto',$this->pathFoto,PDO::PARAM_STR,50);

            $sql->execute();
            $exito=true;
        }catch(PDOException $e){

            echo $e->getMessage();
            $exito=false;

        }
        return $exito;
    }
    public static function Traer():array
    {
        try{
            $neumaticos=array();
            $pdo= new PDO("mysql:host=localhost;dbname=garage_bd","root","");
            $sql=$pdo->query("SELECT * FROM autos ");

            $sql->execute();
            
            while($fila=$sql->fetch())
            {
                $patente =  $fila["patente"];   
                $marca = $fila["marca"];
                $color = $fila["color"];
                $precio = $fila["precio"];
                $pathFoto = $fila["foto"];
                if($pathFoto != null){
                    $item= new AutoBD($patente,$marca,$color,$precio,$pathFoto); 
                }else{
                    $item= new AutoBD($patente,$marca,$color,$precio,"sin foto");
                }
                array_push($neumaticos, $item);
            }
            return $neumaticos;         		
    
        }catch(PDOException $e){

            echo $e->getMessage();

        }
    }

    public static function Eliminar($patente): bool
    {
        $exito=false;
        try
        {
            $pdo= new PDO("mysql:host=localhost;dbname=garage_bd","root","");

            $sql=$pdo->prepare("DELETE FROM `autos` WHERE patente=:patente");
            $sql->bindParam(':patente',$patente,PDO::PARAM_STR,30);
            $sql->execute();

            $exito=true;

        }catch(PDOException $e)
        {
            echo $e->getMessage();
            $exito=false;
        }

        return $exito;
    }

    public function Modificar(): bool
    {
        $exito=false;
        try{
            $pdo= new PDO("mysql:host=localhost;dbname=garage_bd","root","");
            $sql=$pdo->prepare("UPDATE autos SET patente=:patente,marca=:marca,color=:color,
                                precio=:precio,foto=:foto WHERE patente = :patente");
            $sql->bindParam(':patente',$this->patente,PDO::PARAM_STR,30);
            $sql->bindParam(':marca',$this->marca,PDO::PARAM_STR,30);
            $sql->bindParam(':color',$this->color,PDO::PARAM_STR,15);
            $sql->bindParam(':precio',$this->precio,PDO::PARAM_INT);
            $sql->bindParam(':foto',$this->pathFoto,PDO::PARAM_STR,50);
            $sql->execute();
            $exito=true;
        }catch(PDOException $e){

            echo $e->getMessage();
            $exito=false;

        }
        return $exito;
    }
    public function Existe($array_autos): bool
    {
        $retorno = false;
        if(count($array_autos) > 0){           
            foreach($array_autos as $item) {
                if($this->patente == $item->patente){
                    $retorno = true;
                    break;
                }
            }
        }
        return $retorno;  
    }

    public function traerUno(){
        $array = AutoBD::Traer();
        $retorno = null;
        if(count($array) > 0){
            foreach($array as $item) {
                if($this->patente == $item->patente){
                    $retorno = $item;
                    break;
                }
            }
        }
        return $retorno;
    }
    public function GuardarEnArchivo(string $path): string //"./archivos/autosbd_borrados.txt"
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
    public function MostrarDatos():string{

        return "patente:{$this->patente},marca:{$this->marca},color:{$this->color},precio:{$this->precio},foto:{$this->pathFoto}";
    }

}


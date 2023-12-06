<?php

namespace MendozaJavier;

require_once('Auto.php');
require_once('IParte1.php');
require_once('IParte2.php');

use PDO;

class AutoBD extends Auto implements IParte1, IParte2
{
    protected $pathFoto;

    public function __construct($patente, $marca, $color, $precio, $pathFoto = '')
    {
        parent::__construct($patente, $marca, $color, $precio);
        $this->pathFoto = $pathFoto;
    }

    public function toJSON()
    {
        return json_encode([
            'patente' => $this->getPatente(),
            'marca' => $this->getMarca(),
            'color' => $this->getColor(),
            'precio' => $this->getPrecio(),
            'pathFoto' => $this->pathFoto,
        ]);
    }

    public function agregar(PDO $conexion)
    {
        $consulta = $conexion->prepare("INSERT INTO autos (patente, marca, color, precio, foto) VALUES (?, ?, ?, ?, ?)");
    
        $consulta->bindParam(1, $this->getPatente());
        $consulta->bindParam(2, $this->getMarca());
        $consulta->bindParam(3, $this->getColor());
        $consulta->bindParam(4, $this->getPrecio());
        $consulta->bindParam(5, $this->pathFoto);
    
        if ($consulta->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public static function traer(PDO $conexion)
    {
        $consulta = $conexion->prepare("SELECT patente, marca, color, precio, foto FROM autos");

        if ($consulta->execute()) {
            
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

            $autos = [];

            foreach ($resultados as $fila) {
                
                $auto = new AutoBD(
                    $fila["patente"],
                    $fila["marca"],
                    $fila["color"],
                    $fila["precio"],
                    $fila["foto"]
                );

                $autos[] = $auto;
            }

            return $autos;
        } else {
            return [];
        }
    }

}
 
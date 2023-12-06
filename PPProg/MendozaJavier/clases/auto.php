<?php

//require_once('IBM.php');

namespace MendozaJavier;

class Auto{
    public int $patente;
    public string $marca;
    public string $color;
    public double $precio;

    public function __construct(int $patente, string $marca, string $color, double $precio) {
        $this->patente = $patente;
        $this->marca = $marca;
        $this->color = $color;
        $this->precio = $precio;
    }

    public function toJSON()
    {
        $autoData = array(
            'patente' => $this->patente,
            'marca' => $this->marca,
            'color' => $this->color,
            'precio' => $this->precio
        );
        return json_encode($autoData);
    }
    
    public function guardarJSON($path)
    {
        $autoData = $this->toJSON();
        $resultado = file_put_contents($path, $autoData . PHP_EOL, FILE_APPEND);
        if ($resultado !== false) {
            return json_encode(array('éxito' => true, 'mensaje' => 'Auto guardado correctamente.'));
        } else {
            return json_encode(array('éxito' => false, 'mensaje' => 'Error al guardar el auto.'));
        }
    }   

    public static function traerJSON($path){
        $autos = array();
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines !== false) {
            foreach ($lines as $line) {
                $autoData = json_decode($line, true);
                if ($autoData !== null) {
                    $auto = new Auto($autoData['patente'], $autoData['marca'], $autoData['color'], $autoData['precio']);
                    $autos[] = $auto;
                }
            } 
        }
    }
    public static function verificarAutoJSON($patente, $path){

        $autos = self::traerJSON($path);
        foreach ($autos as $auto) {
            if ($auto->patente === $patente) 
            {
                return json_encode(array('existe' => true, 'mensaje' => 'El auto está registrado.'));
            }
        }
        return json_encode(array('existe' => false, 'mensaje' => 'El auto no está registrado.')); 
    }
}


<?php

require_once "./clases/Ciudadano.php";

use Tignino_Christian\Ciudadano;

$a = new Ciudadano($_POST["ciudad"],$_POST["email"],$_POST["clave"]);

echo $a->guardarEnArchivo("clases/archivos/ciudadanos.json");
?>
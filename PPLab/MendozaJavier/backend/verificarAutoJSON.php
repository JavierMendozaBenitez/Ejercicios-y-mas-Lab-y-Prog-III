<?php
require_once("./clases/auto.php");
use MendozaJavier\Auto;

$patente = isset($_POST["patente"]) ? $_POST["patente"] : "sin patente"; 

$neumatico= new Auto($patente,"","",0);

$respuesta = Auto::verificarNeumaticoJSON($neumatico,"./archivos/autos.json");

echo $respuesta;
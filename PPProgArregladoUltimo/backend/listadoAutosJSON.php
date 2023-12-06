<?php
require_once("./clases/auto.php");
use MendozaJavier\Auto;

$retorno=Auto::TraerTodosJSON("./archivos/autos.json");


foreach($retorno as $auto) {
    $autos_data = array(
        "patente" => $auto->Patente(),
        "marca" => $auto->Marca(),
        "color" => $auto->Color(),
        "precio" => $auto->Precio(),
    );
    $autos[] = $autos_data;
}
echo(json_encode($autos));
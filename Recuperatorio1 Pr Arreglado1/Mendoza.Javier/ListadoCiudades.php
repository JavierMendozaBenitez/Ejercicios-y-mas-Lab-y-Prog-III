<?php
require_once './clases/Ciudad.php';
use Mendoza\Javier\Ciudad;

$tabla = isset($_GET["tabla"]) ? (string)$_GET["tabla"] : null ;

$array_ciudades = Ciudad::Traer();

if($tabla === "mostrar")
{  
    $tablaHTML = '<html>
    <head><title>Listado de Ciudades </title></head>
    <body>
    
    <h1>Listado de Ciudades</h1>
    
    <table>
    <tr>
      <th style="padding:0 15px 0 15px;"><strong>ID</strong></th>      
      <th style="padding:0 15px 0 15px;"><strong>NOMBRE </strong></th>
      <th style="padding:0 15px 0 15px;"><strong>POBLACION </strong></th>
      <th style="padding:0 15px 0 15px;"><strong>PAIS </strong></th>
      <th style="padding:0 15px 0 15px;"><strong>FOTO </strong></th>
    </tr>
    
    ';

    foreach ($array_ciudades as $ciudad) 
    {
        $stringProducto = '<tr>
        <td style="padding:0 15px 0 15px;"><strong>'. $ciudad -> id .'</strong></td>
        <td style="padding:0 15px 0 15px;"><strong>'. $ciudad -> nombre .'</strong></td>
        <td style="padding:0 15px 0 15px;"><strong>'. $ciudad -> poblacion .'</strong></td>
        <td style="padding:0 15px 0 15px;"><strong>'. $ciudad -> pais .'</strong></td> 
        <td style="padding:0 15px 0 15px;"><img src="'. $ciudad -> pathFoto .'" width="100" height="100"></td>
        </tr>
        
        ';

        $tablaHTML .= $stringProducto;       
    }

    $tablaHTML .= "</table>

    </body>
    </html>";
    
    echo $tablaHTML;

}
else
{
    echo json_encode($array_ciudades);
}
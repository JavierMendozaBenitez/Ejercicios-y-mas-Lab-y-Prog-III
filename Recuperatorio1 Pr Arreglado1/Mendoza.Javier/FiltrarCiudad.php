<?php
require_once './clases/Ciudad.php';
use Mendoza\Javier\Ciudad;

try 
{
    $array_ciudades = Ciudad::Traer();

    if(isset($_POST["nombre"]) === true && isset($_POST["pais"]) === true)
   {

     $nombre = empty($_POST["nombre"]) === false ? $_POST["nombre"] : throw new Exception("El parametro nombre fue enviado pero se encuentra vacio");
     $pais = empty($_POST["pais"]) === false ? $_POST["pais"] : throw new Exception("El parametro nombre fue enviado pero se encuentra vacio");

    $tablaHTML = '<html>
    <head><title>Listado de Ciudades con mismo nombre y pais </title></head>
    <body>
    
    <h1>Listado de Ciudades con mismo nombre y pais</h1>
    
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
        if($ciudad -> nombre === $nombre && $ciudad -> pais === $pais)
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
    }

    $tablaHTML .= "</table>

    </body>
    </html>";
    
    echo $tablaHTML;
    


}
else if(isset($_POST["nombre"]) === true && isset($_POST["pais"]) != true)
{
    $nombre = empty($_POST["nombre"]) === false ? $_POST["nombre"] : throw new Exception("El parametro nombre fue enviado pero se encuentra vacio");

    $tablaHTML = '<html>
    <head><title>Listado de Ciudades con mismo nombre  </title></head>
    <body>
    
    <h1>Listado de Ciudades con mismo nombre </h1>
    
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
        if($ciudad -> nombre === $nombre)
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
    }

    $tablaHTML .= "</table>

    </body>
    </html>";
    
    echo $tablaHTML;


}
else
{
    $pais = empty($_POST["pais"]) === false ? $_POST["pais"] : throw new Exception("El parametro nombre fue enviado pero se encuentra vacio");

    $tablaHTML = '<html>
    <head><title>Listado de Ciudades con mismo pais  </title></head>
    <body>
    
    <h1>Listado de Ciudades con mismo pais </h1>
    
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
        if($ciudad -> pais === $pais)
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
    }

    $tablaHTML .= "</table>

    </body>
    </html>";
    
    echo $tablaHTML;

    
}
    
} catch (Exception $ex) 
{
    echo "ERROR : " . $ex -> getMessage();    
}
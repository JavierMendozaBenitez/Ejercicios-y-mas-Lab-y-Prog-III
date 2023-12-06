<?php
require_once './clases/Ciudad.php';
use Mendoza\Javier\Ciudad;

// require_once("./clases/autoBD.php");
// use MendozaJavier\AutoBD;

$mostar = isset($_GET["tabla"]) ? $_GET["tabla"] : "sin tabla"; 
$retorno = Ciudad::traer();
if($mostar == "mostrar"){
    echo "<style>
    table {
      border-collapse: collapse; 
      width: 80%; 
      padding: 10px;
      margin: 50px auto;
      text-align: center;
    }
    td, th {
      border: 1px solid black;
      padding: 8px; 
      text-align: center;
    }
    </style>";
    echo "
    <table >
        <thead>
            <tr>public 
                <th>ID</th>
                <th>NOMBRE</th>
                <th>POBLACION</th>
                <th>PAIS</th>
                <th>PATHFOTO</th>
                <th>ACCION</th>
            </tr>
        </thead>"; 
    foreach($retorno as $ciudad)
    {
        echo "<tr>";
            echo "<td>" . $ciudad->id . "</td>";
            echo "<td>" . $ciudad->nombre . "</td>";
            echo "<td>" . $ciudad->poblacion . "</td>";
            echo "<td>" . $ciudad->pais . "</td>";
            echo "<td>" . $ciudad->pathfoto . "</td>";
            echo "<td>";
            if($ciudad->pathfoto != "sinFoto")
            {
                //if(file_exists("/PRIMERPARCIAL/neumaticos/imagenes/".$neumatico->Pathfoto())) {
                //echo "<img src='auto/imagenes/'.$auto->Pathfoto().' alt='.$auto->Pathfoto() . ' height="100px" width="100px">'"; 
                echo "<td><img src='./ciudades/fotos/{$ciudad->pathfoto}' alt='Foto de la ciudad' width='100' height='100'></td>";
                //}else{
                  //  echo 'No hay imagen guardada en '. $neumatico->Pathfoto(); 
                //}
            }else{
                echo "Sin datos //";
            }
            echo "</td>";
            echo '<td><button type="button" class="btn btn-info" id="btnModificar" data-obj=' . $auto->toJson() . '
            name="btnModificar">modificar<span class="bi bi-pencil"></span>
            </button>
            <button type="button" class="btn btn-danger" id="btnEliminar" data-obj=' . $ciudad->toJson() . ' name="btnEliminar"> 
            <span class="bi bi-x-circle">eliminar</span>
            </button>
             </td>';
           
        echo "</tr>";
    }
    echo "</table>";
}else{
var_dump($retorno);
}


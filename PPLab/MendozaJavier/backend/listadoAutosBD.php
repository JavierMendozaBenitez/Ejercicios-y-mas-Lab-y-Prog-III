<?php
require_once("./clases/autoBD.php");
use MendozaJavier\AutoBD;

$mostar = isset($_GET["tabla"]) ? $_GET["tabla"] : "sin tabla"; 
$retorno = AutoBd::traer();
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
            <tr>
                <th>ID</th>
                <th>MARCA</th>
                <th>MEDIDAS</th>
                <th>PRECIO</th>
                <th>PATH</th>
                <th>Foto</th>
                <th>ACCION</th>
            </tr>
        </thead>"; 
    foreach($retorno as $auto)
    {
        echo "<tr>";
            echo "<td>" . $auto->Patente() . "</td>";
            echo "<td>" . $auto->Marca() . "</td>";
            echo "<td>" . $auto->Color() . "</td>";
            echo "<td>" . $auto->Precio() . "</td>";
            echo "<td>" . $auto->Pathfoto() . "</td>";
            echo "<td>";
            if($auto->Pathfoto() != "sinFoto")
            {
                //if(file_exists("/PRIMERPARCIAL/neumaticos/imagenes/".$neumatico->Pathfoto())) {
                //echo "<img src='auto/imagenes/'.$auto->Pathfoto().' alt='.$auto->Pathfoto() . ' height="100px" width="100px">'"; 
                echo "<td><img src='./autos/imagenes/{$auto->Pathfoto()}' alt='Foto del auto' width='100' height='100'></td>";
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
            <button type="button" class="btn btn-danger" id="btnEliminar" data-obj=' . $auto->toJson() . ' name="btnEliminar"> 
            <span class="bi bi-x-circle">eliminar</span>
            </button>
             </td>';
           
        echo "</tr>";
    }
    echo "</table>";
}else{
var_dump($retorno);
}


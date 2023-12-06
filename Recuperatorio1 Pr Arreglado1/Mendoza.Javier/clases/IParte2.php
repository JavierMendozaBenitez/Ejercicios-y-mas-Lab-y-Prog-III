<?php

namespace Mendoza\Javier;

interface IParte2
{
	function Eliminar() : bool; 
    function Modificar() : bool;
    static function GuardarEnArchivo(Ciudad $ciudad) : bool;	

}
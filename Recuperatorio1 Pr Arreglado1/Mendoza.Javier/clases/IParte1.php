<?php

namespace Mendoza\Javier;

interface IParte1 {
    function Agregar() : bool;
    static function Traer() : array;
	function Existe(array $ciudades) : bool;
}

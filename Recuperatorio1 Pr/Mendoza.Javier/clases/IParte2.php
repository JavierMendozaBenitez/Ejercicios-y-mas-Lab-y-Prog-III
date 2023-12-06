<?php

namespace Mendoza\Javier;

interface IParte2 {
    public function modificar();
    public function eliminar();
    public static function guardarEnArchivo(Ciudad $ciudad);
}
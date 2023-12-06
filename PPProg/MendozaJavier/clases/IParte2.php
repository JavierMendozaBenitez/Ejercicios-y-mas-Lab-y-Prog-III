<?php

namespace MendozaJavier;

interface IParte2
{
    public static function eliminar(PDO $conexion, $patente);
    public function modificar(PDO $conexion);
}

<?php

namespace MendozaJavier;

interface IParte1
{
    public function agregar(PDO $conexion);
    public static function traer(PDO $conexion);
}

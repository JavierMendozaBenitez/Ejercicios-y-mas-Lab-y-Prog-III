<?php
namespace MendozaJavier;

interface IParte3{

    public function Existe($array_neumaticos):bool;
    public function GuardarEnArchivo(string $path): string;

}
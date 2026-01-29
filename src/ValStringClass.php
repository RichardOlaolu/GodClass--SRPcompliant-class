<?php
namespace Richardolaolu\GodClass;

class ValStringClass
{
    public function validateString($str)
    {
        return isset($str) && strlen(trim($str)) > 0;
    }
}
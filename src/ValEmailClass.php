<?php
namespace Richardolaolu\GodClass;

class ValEmailClass
{
    public function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
<?php
namespace Richardolaolu\GodClass;

class ErrorPageClass
{
    public function renderErrorPage($message)
    {
        echo "<html><head><title>Error</title></head><body>";
        echo "<h1 style='color: red;'>Error</h1>";
        echo "<p>$message</p>";
        echo "</body></html>";
    }
}
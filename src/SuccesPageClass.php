<?php
namespace Richardolaolu\GodClass;

class SuccessPageClass
{
    public function renderSuccessPage($message)
    {
        echo "<html><head><title>Success</title></head><body>";
        echo "<h1 style='color: green;'>Success</h1>";
        echo "<p>$message</p>";
        echo "</body></html>";
    }
}
<?php
namespace Richardolaolu\GodClass;

class EmailClass extends LoggerClass
{
    public function sendEmail($to, $subject, $body)
    {
        // Hardcoded mail logic (often would be a separate service)
        $headers = "From: system@example.com";
        if (mail($to, $subject, $body, $headers)) {
            $this->log("Email sent to $to");
        } else {
            $this->log("Failed to send email to $to", 'ERROR');
        }
    }

}
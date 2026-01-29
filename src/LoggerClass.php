<?php
namespace Richardolaolu\GodClass;

class LoggerClass
{
    public function log($message, $level = 'INFO')
    {
        $file = __DIR__ . '/app.log';
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] [$level] $message" . PHP_EOL;
        file_put_contents($file, $logEntry, FILE_APPEND);
    }
}
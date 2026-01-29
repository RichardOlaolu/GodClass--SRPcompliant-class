<?php
namespace Richardolaolu\GodClass;

use PDO;
use Exception;

class DbClass extends GodClass
{
    public function connectDB()
    {
        $logger = new LoggerClass(); // Instantiate the logger
        $errorpage = new ErrorPageClass();

        try {
            $dsn = "mysql:host={$this->dbHost};dbname={$this->dbName};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $this->dbUser, $this->dbPass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $logger->log("Database connected successfully.");
        } catch (Exception $e) {
            $logger->log("DB Connection failed: " . $e->getMessage(), 'ERROR');
            // Responsibility 7: HTML Rendering (Error page)
            $errorpage->renderErrorPage("Critical Database Error");
            exit;
        }
    }
}

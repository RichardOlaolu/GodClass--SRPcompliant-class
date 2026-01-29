<?php
namespace Richardolaolu\GodClass;



/*
 * GodClass
 * 
 * This class violates the Single Responsibility Principle (SRP) by handling:
 * - Database connections and queries
 * - User authentication and session management
 * - Email notifications
 * - Logging to files
 * - Data validation
 * - Business logic (Order processing)
 * - HTML rendering
 */
class GodClass
{
    public $dbHost = 'localhost';
    public $dbUser = 'root';
    public $dbPass = '';
    public $dbName = 'my_app';
    public $pdo;

    public function __construct()
    {
        // Responsibility 1: Database Connection Management
        $this->connectDB();
    }

    // --- Database Responsibilities ---





    // --- User Authentication Responsibilities ---




    // --- Email Responsibilities ---


    // --- Logging Responsibilities ---

    public function log($message, $level = 'INFO')
    {
        $file = __DIR__ . '/app.log';
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] [$level] $message" . PHP_EOL;
        file_put_contents($file, $logEntry, FILE_APPEND);
    }

    // --- Business Logic (Order Processing) ---

    public function processOrder($userId, $cartItems)
    {
        $total = 0;
        foreach ($cartItems as $item) {
            $price = $this->getProductPrice($item['id']);
            $total += $price * $item['quantity'];
        }

        // Apply discount logic directly here
        if ($total > 100) {
            $total *= 0.9; // 10% discount
        }

        // Save order
        $this->query("INSERT INTO orders (user_id, total) VALUES (?, ?)", [$userId, $total]);

        $this->log("Order processed for user $userId. Total: $total");

        // Generate Invoice HTML directly
        return $this->generateInvoiceHtml($userId, $cartItems, $total);
    }

    private function getProductPrice($productId)
    {
        // Direct DB access for business logic
        $result = $this->query("SELECT price FROM products WHERE id = ?", [$productId]);
        return $result[0]['price'] ?? 0;
    }

    // --- Validation Responsibilities ---

    public function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function validateString($str)
    {
        return isset($str) && strlen(trim($str)) > 0;
    }

    // --- Presentation / HTML Generation Responsibilities ---

    public function renderSuccessPage($message)
    {
        echo "<html><head><title>Success</title></head><body>";
        echo "<h1 style='color: green;'>Success</h1>";
        echo "<p>$message</p>";
        echo "</body></html>";
    }

    public function renderErrorPage($message)
    {
        echo "<html><head><title>Error</title></head><body>";
        echo "<h1 style='color: red;'>Error</h1>";
        echo "<p>$message</p>";
        echo "</body></html>";
    }

    public function generateInvoiceHtml($userId, $items, $total)
    {
        $html = "<div class='invoice'>";
        $html .= "<h1>Invoice for User $userId</h1>";
        $html .= "<ul>";
        foreach ($items as $item) {
            $html .= "<li>Product ID: {$item['id']} - Qty: {$item['quantity']}</li>";
        }
        $html .= "</ul>";
        $html .= "<strong>Total: $$total</strong>";
        $html .= "</div>";
        return $html;
    }
}
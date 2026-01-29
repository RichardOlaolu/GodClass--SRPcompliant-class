<?php
namespace Richardolaolu\GodClass;

use PDO;
use Exception;

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
    private $dbHost = 'localhost';
    private $dbUser = 'root';
    private $dbPass = '';
    private $dbName = 'my_app';
    private $pdo;

    public function __construct()
    {
        // Responsibility 1: Database Connection Management
        $this->connectDB();
    }

    // --- Database Responsibilities ---

    private function connectDB()
    {
        try {
            $dsn = "mysql:host={$this->dbHost};dbname={$this->dbName};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $this->dbUser, $this->dbPass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->log("Database connected successfully.");
        } catch (Exception $e) {
            $this->log("DB Connection failed: " . $e->getMessage(), 'ERROR');
            // Responsibility 7: HTML Rendering (Error page)
            $this->renderErrorPage("Critical Database Error");
            exit;
        }
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- User Authentication Responsibilities ---

    public function login($username, $password)
    {
        // Responsibility 6: Validation
        if (!$this->validateString($username) || !$this->validateString($password)) {
            return false;
        }

        $user = $this->query("SELECT * FROM users WHERE username = ?", [$username]);

        if ($user && password_verify($password, $user[0]['password'])) {
            $_SESSION['user_id'] = $user[0]['id'];
            $this->log("User $username logged in.");
            // Responsibility 3: Email (Login notification)
            $this->sendEmail($user[0]['email'], "Login Alert", "You just logged in.");
            return true;
        }

        $this->log("Failed login attempt for $username", 'WARNING');
        return false;
    }

    public function registerUser($username, $password, $email)
    {
        if (!$this->validateEmail($email)) {
            $this->renderErrorPage("Invalid Email");
            return;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $this->query($sql, [$username, $email, $hash]);

        $this->sendEmail($email, "Welcome", "Thanks for registering!");
        $this->renderSuccessPage("Registration successful!");
    }

    // --- Email Responsibilities ---

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
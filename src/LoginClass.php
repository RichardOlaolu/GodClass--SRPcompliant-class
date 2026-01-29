<?php
namespace Richardolaolu\GodClass;

class LoginClass
{
    public function login($username, $password)
    {
        $logger = new LoggerClass();
        $mailler = new EmailClass();
        $query = new QueryClass();
        $valstring = new ValStringClass();

        // Responsibility 6: Validation
        if (!$valstring->validateString($username) || !$valstring->validateString($password)) {
            return false;
        }

        $user = $query->query("SELECT * FROM users WHERE username = ?", [$username]);

        if ($user && password_verify($password, $user[0]['password'])) {
            $_SESSION['user_id'] = $user[0]['id'];
            $logger->log("User $username logged in.");
            // Responsibility 3: Email (Login notification)
            $mailler->sendEmail($user[0]['email'], "Login Alert", "You just logged in.");
            return true;
        }

        $logger->log("Failed login attempt for $username", 'WARNING');
        return false;
    }
}
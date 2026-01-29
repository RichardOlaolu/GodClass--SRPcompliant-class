<?php
namespace Richardolaolu\GodClass;

class RegisterClass
{
    public function registerUser($username, $password, $email)
    {
        $valEmail = new ValEmailClass();
        $query = new QueryClass();
        $sucesspage = new SuccessPageClass();
        $errorpage = new ErrorPageClass();

        if (!$valEmail->validateEmail($email)) {
            $errorpage->renderErrorPage("Invalid Email");
            return;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $query->query($sql, [$username, $email, $hash]);

        $mailer = new EmailClass();
        $mailer->sendEmail($email, "Welcome", "Thanks for registering!");
        $sucesspage->renderSuccessPage("Registration successful!");
    }

}
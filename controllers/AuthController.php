<?php

require_once 'models/Admin.php';

class AuthController
{
    private $adminModel;

    public function __construct($db)
    {
        $this->adminModel = new Admin($db);
    }

    public function login()
    {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $this->adminModel->login($username, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                header("Location: index.php");
                exit;
            } else {
                $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
            }
        }

        require 'views/auth/login.php';
    }

    public function logout()
    {
        session_destroy();
        header("Location: index.php?page=login");
        exit;
    }
}
?>
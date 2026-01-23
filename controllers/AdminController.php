<?php

require_once 'models/Admin.php';

class AdminController
{
    private $adminModel;

    public function __construct($db)
    {

        if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
            header("Location: index.php?page=dashboard");
            exit;
        }
        $this->adminModel = new Admin($db);
    }

    public function index()
    {
        $admins = $this->adminModel->getAll();
        $content = 'views/admins/index.php';
        include 'views/layout.php';
    }

    public function create()
    {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $fullName = $_POST['full_name'];

            if ($this->adminModel->create($username, $password, $fullName)) {
                header("Location: index.php?page=admins");
                exit;
            } else {
                $error = "Tên đăng nhập đã tồn tại hoặc có lỗi xảy ra.";
            }
        }
        $content = 'views/admins/create.php';
        include 'views/layout.php';
    }

    public function edit()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            header("Location: index.php?page=admins");
            exit;
        }

        $admin = $this->adminModel->getById($id);
        if (!$admin) {
            header("Location: index.php?page=admins");
            exit;
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $fullName = $_POST['full_name'];

            if ($this->adminModel->update($id, $username, $password, $fullName)) {
                header("Location: index.php?page=admins");
                exit;
            } else {
                $error = "Có lỗi xảy ra cập nhật.";
            }
        }

        $content = 'views/admins/edit.php';
        include 'views/layout.php';
    }

    public function delete()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if ($id) {
            $admin = $this->adminModel->getById($id);

            if ($admin && $admin['username'] !== 'admin') {
                $this->adminModel->delete($id);
            }
        }

        header("Location: index.php?page=admins");
        exit;
    }
}
?>
<?php

require_once 'models/Admin.php';

class AdminController
{
    private $adminModel;

    public function __construct($db)
    {

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
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
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                $error = "Lỗi bảo mật CSRF!";
            } else {
                $username = strtolower($_POST['username']);
                $password = $_POST['password'];
                $fullName = $_POST['full_name'];
                $role = $_POST['role'] ?? 'staff';

                if (!preg_match('/^[a-z0-9]+$/', $username)) {
                    $error = "Tên đăng nhập chỉ được chứa chữ cái thường và số, không có ký tự đặc biệt.";
                } elseif ($this->adminModel->create($username, $password, $fullName, $role)) {
                    header("Location: index.php?page=admins");
                    exit;
                } else {
                    $error = "Tên đăng nhập đã tồn tại hoặc có lỗi xảy ra.";
                }
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

     
        if ($id == $_SESSION['user_id']) {
            echo "<script>alert('Bạn không thể chỉnh sửa tài khoản đang đăng nhập!'); window.location.href='index.php?page=admins';</script>";
            exit;
        }

        $admin = $this->adminModel->getById($id);
        if (!$admin) {
            header("Location: index.php?page=admins");
            exit;
        }

        // Prevent editing super admin role if we are not strict enough, but here "except for username admin"
        // We will allow editing other fields but handle role specially? 
        // User request: "edit the role (except for username admin)" implies 'admin' user role cannot be changed (it's always admin).

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                $error = "Lỗi bảo mật CSRF!";
            } else {
                $username = strtolower($_POST['username']);
                $password = $_POST['password'];
                $fullName = $_POST['full_name'];
                $role = $_POST['role'] ?? 'staff';

                if (!preg_match('/^[a-z0-9]+$/', $username)) {
                    $error = "Tên đăng nhập chỉ được chứa chữ cái thường và số, không có ký tự đặc biệt.";
                } else {
                    if ($admin['username'] === 'admin') {
                        $role = 'admin'; // Force role to admin if editing super user
                    }

                    if ($this->adminModel->update($id, $username, $password, $fullName, $role)) {
                        header("Location: index.php?page=admins");
                        exit;
                    } else {
                        $error = "Có lỗi xảy ra cập nhật.";
                    }
                }
            }
        }

        $content = 'views/admins/edit.php';
        include 'views/layout.php';
    }

    public function delete()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if ($id) {
            // Prevent deleting self
            if ($id == $_SESSION['user_id']) {
                echo "<script>alert('Bạn không thể xóa tài khoản đang đăng nhập!'); window.location.href='index.php?page=admins';</script>";
                exit;
            }

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
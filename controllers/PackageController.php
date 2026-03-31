<?php

require_once 'models/Package.php';

class PackageController
{
    private $packageModel;

    public function __construct($db)
    {
        $this->packageModel = new Package($db);
    }

    public function index()
    {
        $packages = $this->packageModel->getAll();
        $content = 'views/packages/index.php';
        include 'views/layout.php';
    }

    public function create()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?page=packages");
            exit;
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = "Lỗi bảo mật CSRF!";
            } else {
                $name = trim($_POST['package_name']);
                $duration = (int)$_POST['duration_days'];
                $price = (float)$_POST['price'];

                if (!preg_match('/^[\p{L}\p{N}\s]+$/u', $name)) {
                    $_SESSION['error'] = "Tên gói tập không hợp lệ (không chứa ký tự đặc biệt).";
                } else {
                    $duplicate = $this->packageModel->checkDuplicate($name, $duration);
                    if ($duplicate === 'name') {
                        $_SESSION['error'] = "Tên gói tập đã tồn tại.";
                    } elseif ($duplicate === 'duration') {
                        $_SESSION['error'] = "Thời hạn gói tập đã tồn tại.";
                    } else {
                        if ($this->packageModel->create($name, $duration, $price)) {
                            $_SESSION['success'] = "Thêm gói tập thành công!";
                            header("Location: index.php?page=packages");
                            exit;
                        } else {
                            $_SESSION['error'] = "Có lỗi xảy ra khi tạo gói tập.";
                        }
                    }
                }
                
                $package = [
                    'package_name' => $name,
                    'duration_days' => $duration,
                    'price' => $price
                ];
            }
        }
        $content = 'views/packages/create.php';
        include 'views/layout.php';
    }

    public function edit()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?page=packages");
            exit;
        }

        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            header("Location: index.php?page=packages");
            exit;
        }

        $package = $this->packageModel->getById($id);
        if (!$package) {
            $_SESSION['error'] = 'Gói tập không tồn tại!';
            header("Location: index.php?page=packages");
            exit;
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = "Lỗi bảo mật CSRF!";
            } else {
                $name = trim($_POST['package_name']);
                $duration = (int)$_POST['duration_days'];
                $price = (float)$_POST['price'];

                if (!preg_match('/^[\p{L}\p{N}\s]+$/u', $name)) {
                    $_SESSION['error'] = "Tên gói tập không hợp lệ (không chứa ký tự đặc biệt).";
                } else {
                    $duplicate = $this->packageModel->checkDuplicate($name, $duration, $id);
                    if ($duplicate === 'name') {
                        $_SESSION['error'] = "Tên gói tập đã tồn tại.";
                    } elseif ($duplicate === 'duration') {
                        $_SESSION['error'] = "Thời hạn gói tập đã tồn tại.";
                    } else {
                        if ($this->packageModel->update($id, $name, $duration, $price)) {
                            $_SESSION['success'] = "Cập nhật gói tập thành công!";
                            header("Location: index.php?page=packages");
                            exit;
                        } else {
                            $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật gói tập.";
                        }
                    }
                }
                $package['package_name'] = $name;
                $package['duration_days'] = $duration;
                $package['price'] = $price;
            }
        }
        $content = 'views/packages/edit.php';
        include 'views/layout.php';
    }

    public function delete()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?page=packages");
            exit;
        }

        $id = isset($_POST['id']) ? $_POST['id'] : null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                header("Location: index.php?page=packages&error=csrf");
                exit;
            }



            // Check if package exists
            $package = $this->packageModel->getById($id);
            if (!$package) {
                $_SESSION['error'] = 'Gói tập không tồn tại!';
                header("Location: index.php?page=packages");
                exit;
            }

            if ($this->packageModel->delete($id)) {
                $_SESSION['success'] = 'Xóa gói tập thành công!';
            }
        }
        header("Location: index.php?page=packages");
        exit;
    }
}
?>
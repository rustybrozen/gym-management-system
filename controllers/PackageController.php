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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                echo "Lỗi bảo mật CSRF!";
                exit;
            }
            $name = $_POST['package_name'];
            $duration = $_POST['duration_days'];
            $price = $_POST['price'];

            if ($this->packageModel->create($name, $duration, $price)) {
                header("Location: index.php?page=packages");
                exit;
            }
        }
        $content = 'views/packages/create.php';
        include 'views/layout.php';
    }

    public function edit()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            header("Location: index.php?page=packages");
            exit;
        }

        $package = $this->packageModel->getById($id);
        if (!$package) {
            header("Location: index.php?page=packages");
            exit;
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                $error = "Lỗi bảo mật CSRF!";
            } else {
                $name = $_POST['package_name'];
                $duration = $_POST['duration_days'];
                $price = $_POST['price'];

                if ($this->packageModel->update($id, $name, $duration, $price)) {
                    header("Location: index.php?page=packages");
                    exit;
                } else {
                    $error = "Có lỗi xảy ra khi cập nhật gói tập.";
                }
            }
        }
        $content = 'views/packages/edit.php';
        include 'views/layout.php';
    }

    public function delete()
    {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                header("Location: index.php?page=packages&error=csrf");
                exit;
            }

            if ($this->packageModel->isUsedInSubscriptions($id)) {
                header("Location: index.php?page=packages&error=used_in_subscription");
                exit;
            }

            $this->packageModel->delete($id);
        }
        header("Location: index.php?page=packages");
        exit;
    }
}
?>
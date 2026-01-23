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
}
?>
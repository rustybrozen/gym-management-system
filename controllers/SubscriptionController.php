<?php

require_once 'models/Subscription.php';
require_once 'models/Member.php';
require_once 'models/Package.php';

class SubscriptionController
{
    private $subscriptionModel;
    private $memberModel;
    private $packageModel;

    public function __construct($db)
    {
        $this->subscriptionModel = new Subscription($db);
        $this->memberModel = new Member($db);
        $this->packageModel = new Package($db);
    }

    public function index()
    {
        header("Location: index.php?page=members");
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $memberId = $_POST['member_id'];
            $packageId = $_POST['package_id'];

            $package = $this->packageModel->getById($packageId);

            if ($package) {
                $startDate = date('Y-m-d');
                $endDate = date('Y-m-d', strtotime($startDate . ' + ' . $package['duration_days'] . ' days'));
                $amount = $package['price'];

                if ($this->subscriptionModel->create($memberId, $packageId, $startDate, $endDate, $amount)) {
                    header("Location: index.php?page=members");
                    exit;
                }
            }
        }

        $members = $this->memberModel->getAll();
        $packages = $this->packageModel->getAll();

        $content = 'views/subscriptions/create.php';
        include 'views/layout.php';
    }
}
?>
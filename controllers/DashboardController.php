<?php

require_once 'models/Member.php';
require_once 'models/Package.php';
require_once 'models/Subscription.php';

class DashboardController
{
    private $db;
    private $memberModel;
    private $packageModel;
    private $subscriptionModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->memberModel = new Member($db);
        $this->packageModel = new Package($db);
        $this->subscriptionModel = new Subscription($db);
    }

    public function index()
    {
        $totalMembers = count($this->memberModel->getAll());
        $activePackages = count($this->packageModel->getAll());

        $subscriptions = $this->subscriptionModel->getAll();
        $totalRevenue = 0;
        foreach ($subscriptions as $sub) {
            $totalRevenue += $sub['amount_paid'];
        }

        $content = 'views/dashboard.php';
        include 'views/layout.php';
    }
}
?>
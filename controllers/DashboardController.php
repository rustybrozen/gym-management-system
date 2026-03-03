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
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?page=members");
            exit;
        }

        // Date Filtering Logic
        $startDate = isset($_GET['start_date']) && !empty($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $endDate = isset($_GET['end_date']) && !empty($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');

        // Global Stats
        $totalMembers = $this->memberModel->getTotalMembers();
        $activePackages = $this->packageModel->getActivePackagesCount();
        $totalRevenue = $this->subscriptionModel->getTotalRevenue();

        // Filtered Stats
        $filteredStats = $this->subscriptionModel->getFilteredStats($startDate, $endDate);

        $queryNewMembers = "SELECT COUNT(*) as count FROM members WHERE date(created_at) >= ? AND date(created_at) <= ?";
        $stmtNewMembers = $this->db->prepare($queryNewMembers);
        $stmtNewMembers->bindParam(1, $startDate);
        $stmtNewMembers->bindParam(2, $endDate);
        $stmtNewMembers->execute();
        $newMembersCount = $stmtNewMembers->fetch()['count'];

        // Chart Data Extraction
        $revenueData = $this->subscriptionModel->getRevenueChartData($startDate, $endDate);
        $subsData = $this->subscriptionModel->getSubscriptionsChartData($startDate, $endDate);

        $chartDates = [];
        $chartRevenues = [];
        $chartSubs = [];

        $dateMap = [];
        foreach ($revenueData as $row) {
            $dateMap[$row['log_date']] = ['rev' => $row['daily_revenue'], 'subs' => 0];
        }
        foreach ($subsData as $row) {
            if (!isset($dateMap[$row['log_date']])) {
                $dateMap[$row['log_date']] = ['rev' => 0, 'subs' => 0];
            }
            $dateMap[$row['log_date']]['subs'] = $row['daily_subs'];
        }

        ksort($dateMap);
        foreach ($dateMap as $date => $vals) {
            $chartDates[] = $date;
            $chartRevenues[] = $vals['rev'];
            $chartSubs[] = $vals['subs'];
        }

        $chartDatesJSON = json_encode($chartDates);
        $chartRevenuesJSON = json_encode($chartRevenues);
        $chartSubsJSON = json_encode($chartSubs);

        // Recent Members
        $recentMembers = $this->memberModel->getRecentMembers(5);

        $content = 'views/dashboard.php';
        include 'views/layout.php';
    }
}
?>
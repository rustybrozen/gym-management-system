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
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                echo "Lỗi bảo mật CSRF!";
                exit;
            }
            $memberId = $_POST['member_id'];
            $packageId = $_POST['package_id'];

            $member = $this->memberModel->getById($memberId);
            $package = $this->packageModel->getById($packageId);
            
            if (!$member || !$package) {
                $_SESSION['error'] = 'Hội viên hoặc gói tập không tồn tại!';
                header("Location: index.php?page=members");
                exit;
            }

            // Check if member already has an active subscription
            $activeSub = $this->subscriptionModel->getLatestActiveByMember($memberId);
            if ($activeSub) {
                // If they have an active subscription, display an error
                // Redirect back to members page with error message (you may want to handle this better in the view)
                echo "<script>alert('Hội viên này đang có gói tập còn hạn. Không thể đăng ký thêm.'); window.location.href='index.php?page=members';</script>";
                exit;
            }

            $package = $this->packageModel->getById($packageId);

            if ($package) {
                $startDate = date('Y-m-d');
                $endDate = date('Y-m-d', strtotime($startDate . ' + ' . $package['duration_days'] . ' days'));
                $amount = $package['price'];
                $adminId = $_SESSION['user_id'] ?? null;

                if ($this->subscriptionModel->create($memberId, $packageId, $adminId, $startDate, $endDate, $amount)) {
                    header("Location: index.php?page=members");
                    exit;
                }
            }
        }

        $members = $this->memberModel->getAll();
        $packages = $this->packageModel->getAll();

        $selected_member = null;
        if (isset($_GET['member_id'])) {
            $selected_member = $this->memberModel->getById($_GET['member_id']);
        }

        $content = 'views/subscriptions/create.php';
        include 'views/layout.php';
    }

    public function cancel()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Lỗi bảo mật CSRF!';
                header("Location: index.php?page=members");
                exit;
            }

            $memberId = $_POST['member_id'] ?? null;
            if (!$memberId) {
                $_SESSION['error'] = 'Thiếu thông tin hội viên!';
                header("Location: index.php?page=members");
                exit;
            }

            $member = $this->memberModel->getById($memberId);
            if (!$member) {
                $_SESSION['error'] = 'Hội viên không tồn tại!';
                header("Location: index.php?page=members");
                exit;
            }

            if ($this->subscriptionModel->cancelActiveSubscription($memberId)) {
                $_SESSION['success'] = 'Hủy gói tập thành công!';
            } else {
                $_SESSION['error'] = 'Hủy gói tập thất bại hoặc hội viên không có gói tập còn hạn.';
            }
        }
        
        header("Location: index.php?page=members");
        exit;
    }
}
?>
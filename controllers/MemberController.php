<?php

require_once 'models/Member.php';
require_once 'models/Subscription.php';

class MemberController
{
    private $memberModel;
    private $subscriptionModel;

    public function __construct($db)
    {
        $this->memberModel = new Member($db);
        $this->subscriptionModel = new Subscription($db);
    }

    public function index()
    {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

        if ($keyword) {
            $members = $this->memberModel->search($keyword);
        } else {
            $members = $this->memberModel->getAll();
        }

        foreach ($members as &$member) {
            $activeSub = $this->subscriptionModel->getLatestActiveByMember($member['id']);
            $member['status'] = $activeSub ? 'Active' : 'Expired';
        }

        $content = 'views/members/index.php';
        include 'views/layout.php';
    }

    public function create()
    {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = $_POST['full_name'];
            $phone = $_POST['phone_number'];
            $gender = $_POST['gender'];
            $birthDate = $_POST['birth_date'];
            $address = $_POST['address'];
            $healthNotes = $_POST['health_notes'];

            if ($this->memberModel->create($fullName, $phone, $gender, $birthDate, $address, $healthNotes)) {
                header("Location: index.php?page=members");
                exit;
            } else {
                $error = "Có lỗi xảy ra! Có thể số điện thoại đã tồn tại.";
            }
        }
        $content = 'views/members/create.php';
        include 'views/layout.php';
    }
}
?>
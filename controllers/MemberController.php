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
            $member['time_left'] = '';
            if ($activeSub) {
                $member['time_left'] = $this->calculateTimeLeft($activeSub['end_date']);
            }
        }

        $content = 'views/members/index.php';
        include 'views/layout.php';
    }

    public function create()
    {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                $error = "Lỗi bảo mật CSRF!";
            } else {
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
        }
        $content = 'views/members/create.php';
        include 'views/layout.php';
    }
    public function edit()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            header("Location: index.php?page=members");
            exit;
        }

        $member = $this->memberModel->getById($id);
        if (!$member) {
            header("Location: index.php?page=members");
            exit;
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                $error = "Lỗi bảo mật CSRF!";
            } else {
                $fullName = $_POST['full_name'];
                $phone = $_POST['phone_number'];
                $gender = $_POST['gender'];
                $birthDate = $_POST['birth_date'];
                $address = $_POST['address'];
                $healthNotes = $_POST['health_notes'];

                if ($this->memberModel->update($id, $fullName, $phone, $gender, $birthDate, $address, $healthNotes)) {
                    header("Location: index.php?page=members");
                    exit;
                } else {
                    $error = "Có lỗi xảy ra! Có thể số điện thoại đã tồn tại.";
                }
            }
        }
        $content = 'views/members/edit.php';
        include 'views/layout.php';
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                // Handle CSRF error
                header("Location: index.php?page=members&error=csrf");
                exit;
            }

            $id = $_POST['id'] ?? null;
            if ($id) {
                $this->memberModel->delete($id);
            }
        }
        header("Location: index.php?page=members");
        exit;
    }

    public function searchApi()
    {
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        if ($keyword) {
            $members = $this->memberModel->search($keyword);
        } else {
            $members = []; // Don't return all if no keyword, or maybe logic differs. Empty is safer for search box.
        }

        header('Content-Type: application/json');
        echo json_encode($members);
        exit;
    }
    private function calculateTimeLeft($endDate)
    {
        $now = new DateTime();
        $end = new DateTime($endDate);

        // Reset time part to ensure we count full dates
        $now->setTime(0, 0, 0);
        $end->setTime(0, 0, 0);

        if ($now > $end) {
            return "Đã hết hạn";
        }

        $interval = $now->diff($end);
        $parts = [];

        if ($interval->y > 0) {
            $parts[] = $interval->y . " năm";
        }
        if ($interval->m > 0) {
            $parts[] = $interval->m . " tháng";
        }
        if ($interval->d > 0) {
            $parts[] = $interval->d . " ngày";
        }

        if (empty($parts)) {
            return "Hết hạn hôm nay";
        }

        return implode(' ', $parts);
    }
}
?>
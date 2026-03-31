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
        unset($member);

        $content = 'views/members/index.php';
        include 'views/layout.php';
    }

    public function history()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            header("Location: index.php?page=members");
            exit;
        }

        $member = $this->memberModel->getById($id);
        if (!$member) {
            $_SESSION['error'] = 'Hội viên không tồn tại!';
            header("Location: index.php?page=members");
            exit;
        }

        $history = $this->subscriptionModel->getHistoryByMember($id);

        $content = 'views/members/history.php';
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

                if (mb_strlen(trim($fullName), 'UTF-8') < 5) {
                    $error = "Họ và tên phải từ 5 ký tự trở lên.";
                    $member = [
                        'full_name' => $fullName,
                        'phone_number' => $phone,
                        'gender' => $gender,
                        'birth_date' => $birthDate,
                        'address' => $address,
                        'health_notes' => $healthNotes
                    ];
                } elseif (!preg_match('/^[0-9]{10,11}$/', $phone)) {
                    $error = "Số điện thoại không hợp lệ (phải từ 10-11 số).";
                    $member = [
                        'full_name' => $fullName,
                        'phone_number' => $phone,
                        'gender' => $gender,
                        'birth_date' => $birthDate,
                        'address' => $address,
                        'health_notes' => $healthNotes
                    ];
                } elseif ((new DateTime())->diff(new DateTime($birthDate))->y < 12) {
                    $error = "Thành viên dưới 12 tuổi không thể đăng ký.";
                    $member = [
                        'full_name' => $fullName,
                        'phone_number' => $phone,
                        'gender' => $gender,
                        'birth_date' => $birthDate,
                        'address' => $address,
                        'health_notes' => $healthNotes
                    ];
                } elseif ($this->memberModel->create($fullName, $phone, $gender, $birthDate, $address, $healthNotes)) {
                    header("Location: index.php?page=members");
                    exit;
                } else {
                    $error = "Có lỗi xảy ra! Có thể số điện thoại đã tồn tại.";
                    $member = [
                        'full_name' => $fullName,
                        'phone_number' => $phone,
                        'gender' => $gender,
                        'birth_date' => $birthDate,
                        'address' => $address,
                        'health_notes' => $healthNotes
                    ];
                }
            }
        }
        $content = 'views/members/create.php';
        include 'views/layout.php';
    }
    public function edit()
    {
        // if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        //     echo "<script>alert('Bạn không có quyền thực hiện hành động này!'); window.location.href='index.php?page=members';</script>";
        //     exit;
        // }

        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            header("Location: index.php?page=members");
            exit;
        }

        $member = $this->memberModel->getById($id);
        if (!$member) {
            $_SESSION['error'] = 'Hội viên không tồn tại!';
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

                if (mb_strlen(trim($fullName), 'UTF-8') < 5) {
                    $error = "Họ và tên phải từ 5 ký tự trở lên.";
                    $member['full_name'] = $fullName;
                    $member['phone_number'] = $phone;
                    $member['gender'] = $gender;
                    $member['birth_date'] = $birthDate;
                    $member['address'] = $address;
                    $member['health_notes'] = $healthNotes;
                } elseif (!preg_match('/^[0-9]{10,11}$/', $phone)) {
                    $error = "Số điện thoại không hợp lệ (phải từ 10-11 số).";
                    $member['full_name'] = $fullName;
                    $member['phone_number'] = $phone;
                    $member['gender'] = $gender;
                    $member['birth_date'] = $birthDate;
                    $member['address'] = $address;
                    $member['health_notes'] = $healthNotes;
                } elseif ((new DateTime())->diff(new DateTime($birthDate))->y < 12) {
                    $error = "Thành viên dưới 12 tuổi không thể đăng ký.";
                    $member['full_name'] = $fullName;
                    $member['phone_number'] = $phone;
                    $member['gender'] = $gender;
                    $member['birth_date'] = $birthDate;
                    $member['address'] = $address;
                    $member['health_notes'] = $healthNotes;
                } elseif ($this->memberModel->update($id, $fullName, $phone, $gender, $birthDate, $address, $healthNotes)) {
                    header("Location: index.php?page=members");
                    exit;
                } else {
                    $error = "Có lỗi xảy ra! Có thể số điện thoại đã tồn tại.";
                    $member['full_name'] = $fullName;
                    $member['phone_number'] = $phone;
                    $member['gender'] = $gender;
                    $member['birth_date'] = $birthDate;
                    $member['address'] = $address;
                    $member['health_notes'] = $healthNotes;
                }
            }
        }
        $content = 'views/members/edit.php';
        include 'views/layout.php';
    }

    public function delete()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            echo "<script>alert('Bạn không có quyền thực hiện hành động này!'); window.location.href='index.php?page=members';</script>";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                // Handle CSRF error
                header("Location: index.php?page=members&error=csrf");
                exit;
            }

            $id = $_POST['id'] ?? null;
            if ($id) {
                // Check if member exists
                $member = $this->memberModel->getById($id);
                if (!$member) {
                    $_SESSION['error'] = 'Hội viên không tồn tại!';
                    header("Location: index.php?page=members");
                    exit;
                }
                
                if ($this->memberModel->delete($id)) {
                    $_SESSION['success'] = 'Xóa hội viên thành công!';
                }
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
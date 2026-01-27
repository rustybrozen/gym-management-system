<?php

require_once 'models/Admin.php';

class SettingController
{
    private $adminModel;

    public function __construct($db)
    {
        $this->adminModel = new Admin($db);
    }

    public function index()
    {

        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }
        
        $id = $_SESSION['user_id'];
        $user = $this->adminModel->getById($id);

        if (!$user) {
            session_destroy();
            header("Location: index.php?page=login");
            exit;
        }

        $error = null;
        $success = null;


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                $error = "Lỗi bảo mật CSRF!";
            } else {
                $fullName = trim($_POST['full_name']);
                
    
                $username = $_POST['username']; 

                $role = $user['role']; 

                $currentPassword = $_POST['current_password'] ?? '';
                $newPassword = $_POST['new_password'] ?? '';
                
             
                $passwordParam = ""; 

                $isValid = true;

     
                if (!empty($newPassword)) {
           
                    if (empty($currentPassword)) {
                        $error = "Bro phải nhập mật khẩu cũ để xác nhận thay đổi nhé.";
                        $isValid = false;
                    } 
                
                    elseif (!password_verify($currentPassword, $user['password'])) {
                        $error = "Mật khẩu cũ không đúng.";
                        $isValid = false;
                    } 
                    else {
               
                        $passwordParam = $newPassword;
                    }
                }

                if ($isValid) {
 
                    if ($this->adminModel->update($id, $username, $passwordParam, $fullName, $role)) {
                        $success = "Cập nhật profile thành công!";
            
                        $user = $this->adminModel->getById($id);
                        
         
                        $_SESSION['user_name'] = $fullName; 
                        $_SESSION['username'] = $username;
                    } else {
                        $error = "Có lỗi xảy ra (Có thể username bị trùng hoặc DB lỗi).";
                    }
                }
            }
        }

      
        $content = 'views/settings/index.php';
        include 'views/layout.php';
    }
}
?>
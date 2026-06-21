<?php
class AuthController extends Controller {
    public function login() {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = $this->model('User');
            $user = $userModel->login($username, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];
                $this->logActivity('login', 'auth', 'User logged in: ' . $user['username']);
                $this->redirect('dashboard');
            } else {
                $this->view('auth/login', ['error' => 'Invalid username or password']);
            }
        } else {
            $this->view('auth/login');
        }
    }

    public function logout() {
        $username = $_SESSION['username'] ?? 'Unknown';
        $this->logActivity('logout', 'auth', 'User logged out: ' . $username);
        session_destroy();
        $this->redirect('auth/login');
    }

    public function profile() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = $this->model('User');
            $data = [
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email']
            ];
            
            if (!empty($_POST['new_password'])) {
                $data['password'] = $_POST['new_password'];
            }
            
            $userModel->update($_SESSION['user_id'], $data);
            $_SESSION['full_name'] = $_POST['full_name'];
            $this->redirect('dashboard');
        }

        $userModel = $this->model('User');
        $user = $userModel->getById($_SESSION['user_id']);
        $this->view('auth/profile', ['user' => $user]);
    }
}

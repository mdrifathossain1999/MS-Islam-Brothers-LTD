<?php
class Controller {
    protected $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function model($model) {
        require_once "app/models/$model.php";
        return new $model($this->db);
    }

    public function view($view, $data = []) {
        extract($data);
        if (file_exists("app/views/$view.php")) {
            require_once "app/views/$view.php";
        } else {
            die("View not found: $view");
        }
    }

    public function redirect($url) {
        header("Location: " . BASE_URL . "/" . $url);
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        exit();
    }

    public function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    public function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
        }
    }

    public function requireAdmin() {
        $this->requireLogin();
        if ($_SESSION['role'] !== 'admin') {
            $this->redirect('dashboard');
        }
    }

    public function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    public function setFlash($type, $message) {
        $_SESSION['flash_' . $type] = $message;
    }

    public function success($message) {
        $_SESSION['success'] = $message;
    }

    public function error($message) {
        $_SESSION['error'] = $message;
    }

    public function logActivity($action, $module, $description = null, $referenceType = null, $referenceId = null) {
        $activityLogModel = $this->model('ActivityLog');
        $userId = $_SESSION['user_id'] ?? null;
        $activityLogModel->log($userId, $action, $module, $description, $referenceType, $referenceId);
    }
}

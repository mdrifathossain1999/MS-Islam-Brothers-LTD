<?php
class User {
    private $db;
    private $table = 'users';

    public function __construct($db) {
        $this->db = $db;
    }

    public function login($username, $password) {
        $sql = "SELECT * FROM {$this->table} WHERE username = ? AND status = 'active'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (username, password, full_name, email, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt->execute([
            $data['username'],
            $data['password'],
            $data['full_name'],
            $data['email'],
            $data['role']
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $params = [];

        if (isset($data['full_name'])) {
            $fields[] = "full_name = ?";
            $params[] = $data['full_name'];
        }
        if (isset($data['email'])) {
            $fields[] = "email = ?";
            $params[] = $data['email'];
        }
        if (isset($data['role'])) {
            $fields[] = "role = ?";
            $params[] = $data['role'];
        }
        if (isset($data['status'])) {
            $fields[] = "status = ?";
            $params[] = $data['status'];
        }
        if (isset($data['password']) && !empty($data['password'])) {
            $fields[] = "password = ?";
            $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $params[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    public function getRecent($limit = 5) {
        $limit = intval($limit);
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT $limit";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}

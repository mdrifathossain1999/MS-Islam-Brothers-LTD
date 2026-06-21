<?php
class HrmController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->requireLogin();
    }

    public function department() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare("INSERT INTO hrm_departments (department_name, description) VALUES (?, ?)");
            $stmt->execute([$_POST['department_name'], $_POST['description'] ?? '']);
            $this->success('Department added!');
            $this->redirect('hrm/department');
        }
        
        $departments = $this->db->query("SELECT * FROM hrm_departments ORDER BY department_name")->fetchAll();
        $this->view('hrm/department', ['departments' => $departments]);
    }
    
    public function editDepartment($id = null) {
        if (!$id) {
            $this->redirect('hrm/department');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare("UPDATE hrm_departments SET department_name = ?, description = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$_POST['department_name'], $_POST['description'] ?? '', $_POST['is_active'] ?? 'yes', $id]);
            $this->success('Department updated!');
            $this->redirect('hrm/department');
        }
        
        $dept = $this->db->query("SELECT * FROM hrm_departments WHERE id = $id")->fetch();
        $this->view('hrm/department-edit', ['department' => $dept]);
    }
    
    public function deleteDepartment($id = null) {
        if ($id) {
            $stmt = $this->db->prepare("DELETE FROM hrm_departments WHERE id = ?");
            $stmt->execute([$id]);
            $this->success('Department deleted!');
        }
        $this->redirect('hrm/department');
    }

    public function employee() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $employee_id = 'EMP-' . rand(1000, 9999);
            $stmt = $this->db->prepare("
                INSERT INTO hrm_employees (employee_id, first_name, last_name, department_id, designation, phone, email, address, salary, join_date)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $employee_id,
                $_POST['first_name'],
                $_POST['last_name'] ?? '',
                $_POST['department_id'],
                $_POST['designation'] ?? '',
                $_POST['phone'] ?? '',
                $_POST['email'] ?? '',
                $_POST['address'] ?? '',
                $_POST['salary'] ?? 0,
                $_POST['join_date'] ?? date('Y-m-d')
            ]);
            $this->success('Employee added!');
            $this->redirect('hrm/employee');
        }
        
        $employees = $this->db->query("
            SELECT e.*, d.department_name 
            FROM hrm_employees e 
            LEFT JOIN hrm_departments d ON e.department_id = d.id 
            ORDER BY e.first_name
        ")->fetchAll();
        $departments = $this->db->query("SELECT * FROM hrm_departments WHERE is_active = 'yes'")->fetchAll();
        
        $this->view('hrm/employee', ['employees' => $employees, 'departments' => $departments]);
    }

    public function attendance() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare("
                INSERT INTO hrm_attendance (employee_id, attendance_date, status, check_in, check_out, notes)
                VALUES (?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE status = VALUES(status), check_in = VALUES(check_in), check_out = VALUES(check_out)
            ");
            $stmt->execute([
                $_POST['employee_id'],
                $_POST['attendance_date'] ?? date('Y-m-d'),
                $_POST['status'] ?? 'present',
                $_POST['check_in'] ?? null,
                $_POST['check_out'] ?? null,
                $_POST['notes'] ?? ''
            ]);
            $this->success('Attendance recorded!');
            $this->redirect('hrm/attendance');
        }
        
        $employees = $this->db->query("SELECT * FROM hrm_employees WHERE status = 'active'")->fetchAll();
        $attendances = $this->db->query("
            SELECT a.*, e.first_name, e.last_name 
            FROM hrm_attendance a 
            LEFT JOIN hrm_employees e ON a.employee_id = e.id 
            ORDER BY a.attendance_date DESC, e.first_name
        ")->fetchAll();
        
        $this->view('hrm/attendance', ['employees' => $employees, 'attendances' => $attendances]);
    }

    public function holiday() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare("INSERT INTO hrm_holidays (holiday_name, holiday_date, description, is_recurring) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_POST['holiday_name'], $_POST['holiday_date'], $_POST['description'] ?? '', $_POST['is_recurring'] ?? 'no']);
            $this->success('Holiday added!');
            $this->redirect('hrm/holiday');
        }
        
        $holidays = $this->db->query("SELECT * FROM hrm_holidays ORDER BY holiday_date DESC")->fetchAll();
        $this->view('hrm/holiday', ['holidays' => $holidays]);
    }
    
    public function editEmployee($id = null) {
        if (!$id) {
            $this->redirect('hrm/employee');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare("
                UPDATE hrm_employees 
                SET first_name = ?, last_name = ?, department_id = ?, designation = ?, phone = ?, email = ?, salary = ?, status = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $_POST['first_name'],
                $_POST['last_name'] ?? '',
                $_POST['department_id'],
                $_POST['designation'] ?? '',
                $_POST['phone'] ?? '',
                $_POST['email'] ?? '',
                $_POST['salary'] ?? 0,
                $_POST['status'] ?? 'active',
                $id
            ]);
            $this->success('Employee updated!');
            $this->redirect('hrm/employee');
        }
        
        $employee = $this->db->query("SELECT * FROM hrm_employees WHERE id = $id")->fetch();
        $departments = $this->db->query("SELECT * FROM hrm_departments WHERE is_active = 'yes'")->fetchAll();
        
        $this->view('hrm/employee-edit', ['employee' => $employee, 'departments' => $departments]);
    }
    
    public function deleteEmployee($id = null) {
        if ($id) {
            $stmt = $this->db->prepare("DELETE FROM hrm_employees WHERE id = ?");
            $stmt->execute([$id]);
            $this->success('Employee deleted!');
        }
        $this->redirect('hrm/employee');
    }
}
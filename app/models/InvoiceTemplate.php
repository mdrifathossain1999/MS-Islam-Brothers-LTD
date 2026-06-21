<?php
class InvoiceTemplate {
    private $db;

    public function __construct($db = null) {
        if ($db) {
            $this->db = $db;
        } else {
            $database = new Database();
            $this->db = $database->getConnection();
        }
        $this->migrateColumns();
    }

    private function migrateColumns() {
        try {
            $this->db->exec("ALTER TABLE invoice_templates ADD COLUMN show_qr_code VARCHAR(10) DEFAULT 'no'");
        } catch (Exception $e) {}
        try {
            $this->db->exec("ALTER TABLE invoice_templates ADD COLUMN show_customer_signature VARCHAR(10) DEFAULT 'no'");
        } catch (Exception $e) {}
        try {
            $this->db->exec("ALTER TABLE invoice_templates ADD COLUMN show_cashier_signature VARCHAR(10) DEFAULT 'no'");
        } catch (Exception $e) {}
        try {
            $this->db->exec("ALTER TABLE invoice_templates ADD COLUMN font_size VARCHAR(20) DEFAULT 'medium'");
        } catch (Exception $e) {}
        try {
            $this->db->exec("ALTER TABLE invoice_templates ADD COLUMN custom_html TEXT");
        } catch (Exception $e) {}
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM invoice_templates ORDER BY is_default DESC, id DESC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM invoice_templates WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getActive() {
        $stmt = $this->db->query("SELECT * FROM invoice_templates WHERE is_active = 'yes' ORDER BY is_default DESC, id ASC");
        return $stmt->fetchAll();
    }

    public function getDefault() {
        $stmt = $this->db->query("SELECT * FROM invoice_templates WHERE is_default = 'yes' AND is_active = 'yes' LIMIT 1");
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO invoice_templates (
                template_name, template_type, print_size, is_default, header_text, footer_text,
                show_logo, show_barcode, show_qr_code, show_terms, terms_content,
                show_customer_signature, show_cashier_signature, color_scheme, font_size, custom_html, is_active
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $data['template_name'],
            $data['template_type'] ?? 'a4',
            $data['print_size'] ?? 'auto',
            $data['is_default'] ?? 'no',
            $data['header_text'] ?? null,
            $data['footer_text'] ?? null,
            $data['show_logo'] ?? 'yes',
            $data['show_barcode'] ?? 'yes',
            $data['show_qr_code'] ?? 'no',
            $data['show_terms'] ?? 'yes',
            $data['terms_content'] ?? null,
            $data['show_customer_signature'] ?? 'no',
            $data['show_cashier_signature'] ?? 'no',
            $data['color_scheme'] ?? '#667eea',
            $data['font_size'] ?? 'medium',
            $data['custom_html'] ?? null,
            $data['is_active'] ?? 'yes'
        ]);
    }

    public function update($id, $data) {
        error_log("InvoiceTemplate update called with id: " . $id);
        error_log("Data to update: " . print_r($data, true));
        
        $stmt = $this->db->prepare("
            UPDATE invoice_templates SET
                template_name = ?,
                template_type = ?,
                print_size = ?,
                header_text = ?,
                footer_text = ?,
                show_logo = ?,
                show_barcode = ?,
                show_qr_code = ?,
                show_terms = ?,
                terms_content = ?,
                show_customer_signature = ?,
                show_cashier_signature = ?,
                color_scheme = ?,
                font_size = ?,
                custom_html = ?,
                is_active = ?
            WHERE id = ?
        ");
        
        $result = $stmt->execute([
            $data['template_name'],
            $data['template_type'],
            $data['print_size'] ?? 'auto',
            $data['header_text'] ?? null,
            $data['footer_text'] ?? null,
            $data['show_logo'] ?? 'yes',
            $data['show_barcode'] ?? 'yes',
            $data['show_qr_code'] ?? 'no',
            $data['show_terms'] ?? 'yes',
            $data['terms_content'] ?? null,
            $data['show_customer_signature'] ?? 'no',
            $data['show_cashier_signature'] ?? 'no',
            $data['color_scheme'] ?? '#667eea',
            $data['font_size'] ?? 'medium',
            $data['custom_html'] ?? null,
            $data['is_active'] ?? 'yes',
            $id
        ]);
        
        error_log("Update result: " . ($result ? 'success' : 'failed'));
        return $result;
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM invoice_templates WHERE id = ? AND is_default = 'no'");
        return $stmt->execute([$id]);
    }

    public function setDefault($id) {
        $this->db->query("UPDATE invoice_templates SET is_default = 'no'");
        $stmt = $this->db->prepare("UPDATE invoice_templates SET is_default = 'yes' WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function toggleStatus($id) {
        $template = $this->getById($id);
        if ($template) {
            $newStatus = $template['is_active'] === 'yes' ? 'no' : 'yes';
            $stmt = $this->db->prepare("UPDATE invoice_templates SET is_active = ? WHERE id = ?");
            return $stmt->execute([$newStatus, $id]);
        }
        return false;
    }

    public function exists($name, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->db->prepare("SELECT id FROM invoice_templates WHERE template_name = ? AND id != ?");
            $stmt->execute([$name, $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT id FROM invoice_templates WHERE template_name = ?");
            $stmt->execute([$name]);
        }
        return $stmt->fetch();
    }
}

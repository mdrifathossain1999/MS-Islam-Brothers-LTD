<?php 
$pageTitle = 'User Management';
$currentPage = 'admin';
$subPage = 'users';
ob_start();
?>

<style>
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
    --bg: #f8fafc;
    --card: #ffffff;
    --text: #1e293b;
    --text-muted: #64748b;
    --border: #e2e8f0;
}
body.dark-mode {
    --bg: #0f172a;
    --card: #1e293b;
    --text: #f1f5f9;
    --text-muted: #94a3b8;
    --border: #334155;
}

/* Table */
.users-table {
    background: var(--card);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    border: 1px solid var(--border);
}
.table-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.table-header h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: var(--text);
}
.search-box {
    position: relative;
}
.search-box input {
    padding: 10px 16px;
    padding-left: 40px;
    border: 2px solid var(--border);
    border-radius: 10px;
    width: 280px;
    font-size: 13px;
    background: var(--bg);
    transition: all 0.2s;
}
.search-box input:focus {
    border-color: var(--primary);
    outline: none;
}
.search-box i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
}
.table {
    width: 100%;
    border-collapse: collapse;
}
.table th {
    background: var(--bg);
    padding: 14px 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--text-muted);
    text-align: left;
    border-bottom: 2px solid var(--border);
}
.table td {
    padding: 16px 20px;
    font-size: 14px;
    color: var(--text);
    border-bottom: 1px solid var(--border);
}
.table tr:hover {
    background: var(--bg);
}
.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}
.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
}
.user-name {
    font-weight: 600;
}
.user-email {
    font-size: 12px;
    color: var(--text-muted);
}
.status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.status-active {
    background: #dcfce7;
    color: #166534;
}
body.dark-mode .status-active {
    background: #064e3b;
    color: #6ee7b7;
}
.status-inactive {
    background: #fee2e2;
    color: #991b1b;
}
body.dark-mode .status-inactive {
    background: #7f1d1d;
    color: #fca5a5;
}
.role-badge {
    background: var(--primary);
    color: white;
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}
.role-badge.admin {
    background: linear-gradient(135deg, #8b5cf6, #a78bfa);
}
body.dark-mode .role-badge.admin {
    background: linear-gradient(135deg, #7c3aed, #8b5cf6);
}
.role-badge.manager {
    background: linear-gradient(135deg, #10b981, #34d399);
}
body.dark-mode .role-badge.manager {
    background: linear-gradient(135deg, #059669, #10b981);
}
.btn-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.2s;
    background: var(--bg);
    border: 1px solid var(--border);
    color: var(--text-muted);
}
.btn-icon:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}
.btn-icon.delete:hover {
    background: var(--danger);
    border-color: var(--danger);
}
.empty-state {
    text-align: center;
    padding: 60px 20px;
}
.empty-state i {
    font-size: 48px;
    color: var(--text-muted);
    margin-bottom: 16px;
}
.empty-state h4 {
    margin: 0 0 8px;
    color: var(--text);
}
.empty-state p {
    margin: 0;
    color: var(--text-muted);
}
</style>

<div class="page-header">
    <div style="display: flex; align-items: center; gap: 12px;">
        <a href="<?php echo BASE_URL; ?>/admin" class="btn-back">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <h1><i class="bi bi-people-fill"></i> User Management</h1>
    </div>
    <a href="<?php echo BASE_URL; ?>/admin/createUser" class="btn-add">
        <i class="bi bi-plus-lg"></i> Add User
    </a>
</div>

<?php if (empty($users)): ?>
<div class="users-table">
    <div class="empty-state">
        <i class="bi bi-people"></i>
        <h4>No Users Found</h4>
        <p>Add your first user to get started!</p>
    </div>
</div>
<?php else: ?>
<div class="users-table">
    <div class="table-header">
        <h3><i class="bi bi-list-ul"></i> All Users (<?php echo count($users); ?>)</h3>
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Search users...">
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Role</th>
                <th>Status</th>
                <th>Last Login</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td>
                    <div class="user-info">
                        <div class="user-avatar"><?php echo strtoupper(substr($user['name'] ?? '?', 0, 1)); ?></div>
                        <div>
                            <div class="user-name"><?php echo htmlspecialchars($user['name'] ?? ''); ?></div>
                            <div class="user-email"><?php echo htmlspecialchars($user['email'] ?? ''); ?></div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="role-badge <?php echo $user['role'] ?? ''; ?>"><?php echo ucfirst($user['role'] ?? ''); ?></span>
                </td>
                <td>
                    <span class="status-badge <?php echo isset($user['status']) && $user['status'] === 'active' ? 'status-active' : 'status-inactive'; ?>">
                        <?php echo ucfirst($user['status'] ?? ''); ?>
                    </span>
                </td>
                <td><?php echo isset($user['last_login']) && $user['last_login'] ? date('d M, Y', strtotime($user['last_login'])) : 'Never'; ?></td>
                <td>
                    <div style="display: flex; gap: 8px;">
                        <a href="<?php echo BASE_URL; ?>/admin/editUser/<?php echo $user['id']; ?>" class="btn-icon" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                        <a href="<?php echo BASE_URL; ?>/admin/deleteUser/<?php echo $user['id']; ?>" class="btn-icon delete" title="Delete" onclick="return confirm('Delete this user?')">
                            <i class="bi bi-trash"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
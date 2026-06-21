<?php 
$pageTitle = 'Activity Logs';
$currentPage = 'admin';
$subPage = 'activityLogs';
ob_start();
?>

<style>
.logs-card { background: var(--bg-card); border-radius: var(--radius-lg); box-shadow: var(--shadow-md); overflow: hidden; }
.logs-header { padding: 16px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
.logs-header h4 { margin: 0; font-size: 15px; }
.filter-form { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
.filter-form select, .filter-form input { padding: 6px 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px; }
.filter-form button { padding: 6px 14px; background: var(--primary); color: white; border: none; border-radius: 6px; font-size: 12px; cursor: pointer; }
.filter-form a { padding: 6px 14px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px; color: var(--text-muted); text-decoration: none; }
.logs-table { width: 100%; border-collapse: collapse; }
.logs-table th { padding: 10px 14px; text-align: left; font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; background: var(--bg-surface-alt); border-bottom: 2px solid var(--border); position: sticky; top: 0; }
.logs-table td { padding: 10px 14px; font-size: 12px; border-bottom: 1px solid var(--bg-surface-alt); }
.logs-table tr:hover { background: var(--bg-surface-alt); }
.action-badge { padding: 4px 8px; border-radius: 20px; font-size: 10px; font-weight: 600; }
.action-create { background: #d1fae5; color: #065f46; }
.action-update { background: #dbeafe; color: #1e40af; }
.action-delete { background: #fee2e2; color: #991b1b; }
.action-login { background: #fef3c7; color: #92400e; }
.action-logout { background: #f3e8ff; color: #7c3aed; }
.action-sale { background: #ccfbf1; color: #115e59; }
.action-default { background: var(--bg-surface-alt); color: var(--text-secondary); }
.module-badge { padding: 3px 8px; border-radius: 4px; background: var(--bg-surface-alt); color: var(--text-muted); font-size: 11px; font-weight: 500; }
.module-badge.stock { background: #fef3c7; color: #92400e; }
.pagination { display: flex; justify-content: center; gap: 6px; margin-top: 16px; }
.pagination a, .pagination span { padding: 6px 12px; border: 1px solid var(--border); border-radius: 6px; text-decoration: none; color: var(--text-secondary); font-size: 12px; }
.pagination a:hover { background: var(--bg-surface-alt); }
.pagination .active { background: var(--primary); color: white; border-color: var(--primary); }
.pagination .disabled { opacity: 0.5; pointer-events: none; }
.logs-info { padding: 10px 16px; background: var(--bg-surface-alt); border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: var(--text-muted); }
.clear-form { display: flex; gap: 8px; align-items: center; }
.clear-form select { padding: 6px 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 12px; }
.clear-form button { padding: 6px 12px; background: var(--danger); color: white; border: none; border-radius: 6px; font-size: 12px; cursor: pointer; }
.user-cell { display: flex; align-items: center; gap: 6px; }
.user-avatar { width: 26px; height: 26px; border-radius: 50%; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 600; }
</style>

<div class="page-header">
    <h1><i class="bi bi-activity"></i> Activity Logs</h1>
    <a href="<?php echo BASE_URL; ?>/admin" class="back-btn"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success" style="padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 20px; background: var(--success); color: var(--bg-card); border: 1px solid var(--success);">
    <i class="bi bi-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
</div>
<?php endif; ?>

<div class="logs-card">
    <div class="logs-header">
        <h4><i class="bi bi-list-ul me-2"></i>All Activities</h4>
        <form class="filter-form" method="GET" action="<?php echo BASE_URL; ?>/admin/activityLogs">
            <select name="user_id">
                <option value="">All Users</option>
                <?php foreach ($users as $user): ?>
                <option value="<?php echo $user['id']; ?>" <?php echo ($filters['user_id'] ?? '') == $user['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($user['full_name'] ?: $user['username']); ?>
                </option>
                <?php endforeach; ?>
            </select>
            <select name="module">
                <option value="">All Modules</option>
                <?php foreach ($modules as $module): ?>
                <option value="<?php echo $module; ?>" <?php echo ($filters['module'] ?? '') == $module ? 'selected' : ''; ?>>
                    <?php echo ucfirst($module); ?>
                </option>
                <?php endforeach; ?>
            </select>
            <select name="action">
                <option value="">All Actions</option>
                <?php foreach ($actions as $action): ?>
                <option value="<?php echo $action; ?>" <?php echo ($filters['action'] ?? '') == $action ? 'selected' : ''; ?>>
                    <?php echo ucfirst($action); ?>
                </option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="date_from" value="<?php echo $filters['date_from'] ?? ''; ?>" placeholder="From">
            <input type="date" name="date_to" value="<?php echo $filters['date_to'] ?? ''; ?>" placeholder="To">
            <button type="submit"><i class="bi bi-filter"></i> Filter</button>
            <a href="<?php echo BASE_URL; ?>/admin/activityLogs">Reset</a>
        </form>
    </div>

    <div class="table-responsive">
        <table class="logs-table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Module</th>
                    <th>Description</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0">No activity logs found</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($logs as $log): ?>
                <?php 
                    $actionClass = 'action-default';
                    $actionText = ucfirst($log['action']);
                    
                    if (strpos($log['action'], 'create') !== false || strpos($log['action'], 'add') !== false) $actionClass = 'action-create';
                    if (strpos($log['action'], 'update') !== false || strpos($log['action'], 'edit') !== false) $actionClass = 'action-update';
                    if (strpos($log['action'], 'delete') !== false) $actionClass = 'action-delete';
                    if (strpos($log['action'], 'login') !== false) $actionClass = 'action-login';
                    if (strpos($log['action'], 'logout') !== false) $actionClass = 'action-logout';
                    if (strpos($log['action'], 'sale') !== false || strpos($log['action'], 'sell') !== false) $actionClass = 'action-sale';
                    if ($log['module'] === 'stock') {
                        $actionClass = 'action-update';
                        $actionText = 'Stock Update';
                    }
                ?>
                <tr>
                    <td>
                        <small class="text-muted">
                            <?php echo date('d M Y', strtotime($log['created_at'])); ?><br>
                            <span style="font-size: 11px;"><?php echo date('h:i A', strtotime($log['created_at'])); ?></span>
                        </small>
                    </td>
                    <td>
                        <?php if ($log['user_id']): ?>
                        <div class="user-cell">
                            <div class="user-avatar"><?php echo strtoupper(substr($log['full_name'] ?? $log['username'] ?? 'U', 0, 1)); ?></div>
                            <div>
                                <div style="font-weight: 500;"><?php echo htmlspecialchars($log['full_name'] ?? $log['username'] ?? 'Unknown'); ?></div>
                                <small class="text-muted"><?php echo $log['username'] ?? ''; ?></small>
                            </div>
                        </div>
                        <?php else: ?>
                        <span class="text-muted">System</span>
                        <?php endif; ?>
                    </td>
                    <td><span class="action-badge <?php echo $actionClass; ?>"><?php echo $actionText; ?></span></td>
                    <td>
                        <?php 
                        $moduleClass = '';
                        $moduleText = ucfirst($log['module']);
                        if ($log['module'] === 'stock') {
                            $moduleClass = 'stock';
                            $moduleText = '<i class="bi bi-box-seam me-1"></i>Stock';
                        }
                        ?>
                        <span class="module-badge <?php echo $moduleClass; ?>"><?php echo $moduleText; ?></span>
                    </td>
                    <td><?php echo htmlspecialchars($log['description'] ?? '-'); ?></td>
                    <td><small class="text-muted"><?php echo $log['ip_address'] ?? '-'; ?></small></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="logs-info">
        <span>Showing <?php echo count($logs); ?> of <?php echo number_format($totalCount); ?> logs</span>
        <form class="clear-form" method="POST" action="<?php echo BASE_URL; ?>/admin/clearActivityLogs" onsubmit="return confirm('Clear logs older than selected days?');">
            <span>Clear logs older than:</span>
            <select name="days">
                <option value="7">7 days</option>
                <option value="30" selected>30 days</option>
                <option value="60">60 days</option>
                <option value="90">90 days</option>
            </select>
            <button type="submit"><i class="bi bi-trash"></i> Clear</button>
        </form>
    </div>
</div>

<?php if ($totalPages > 1): ?>
<div class="pagination">
    <?php 
    $filterQuery = http_build_query(array_filter($filters));
    $filterPrefix = $filterQuery ? '&' . $filterQuery : '';
    ?>
    <?php if ($page > 1): ?>
    <a href="?page=<?php echo $page - 1; ?><?php echo $filterPrefix; ?>"><i class="bi bi-chevron-left"></i></a>
    <?php endif; ?>
    
    <?php 
    $start = max(1, $page - 2);
    $end = min($totalPages, $page + 2);
    if ($start > 1): ?>
    <a href="?page=1<?php echo $filterPrefix; ?>">1</a>
    <?php if ($start > 2): ?><span>...</span><?php endif; ?>
    <?php endif; ?>
    
    <?php for ($i = $start; $i <= $end; $i++): ?>
    <?php if ($i == $page): ?>
    <span class="active"><?php echo $i; ?></span>
    <?php else: ?>
    <a href="?page=<?php echo $i; ?><?php echo $filterPrefix; ?>"><?php echo $i; ?></a>
    <?php endif; ?>
    <?php endfor; ?>
    
    <?php if ($end < $totalPages): ?>
    <?php if ($end < $totalPages - 1): ?><span>...</span><?php endif; ?>
    <a href="?page=<?php echo $totalPages; ?><?php echo $filterPrefix; ?>"><?php echo $totalPages; ?></a>
    <?php endif; ?>
    
    <?php if ($page < $totalPages): ?>
    <a href="?page=<?php echo $page + 1; ?><?php echo $filterPrefix; ?>"><i class="bi bi-chevron-right"></i></a>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';

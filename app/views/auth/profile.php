<?php 
$pageTitle = 'Profile';
$currentPage = 'users';
ob_start();
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="bi bi-person-circle"></i> User Profile</h1>
            <p>Manage your account settings</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <div class="profile-avatar mx-auto mb-3">
                    <i class="bi bi-person-fill"></i>
                </div>
                <h4><?= htmlspecialchars($user['full_name']); ?></h4>
                <p class="text-muted mb-2"><?= ucfirst($user['role']); ?></p>
                <span class="badge bg-success rounded-pill">
                    <i class="bi bi-check-circle me-1"></i>Active
                </span>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Account Settings</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']); ?>" disabled>
                            <small class="text-muted">Username cannot be changed</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-control" value="<?= ucfirst($user['role']); ?>" disabled>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-control" name="full_name" value="<?= htmlspecialchars($user['full_name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h6 class="mb-3"><i class="bi bi-lock me-2"></i>Change Password</h6>
                    <div class="mb-3">
                        <label class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" class="form-control" name="new_password" placeholder="Enter new password">
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Update Profile
                        </button>
                        <a href="<?= BASE_URL; ?>/dashboard" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.profile-avatar {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    color: #fff;
}
</style>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';

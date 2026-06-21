<?php 
$pageTitle = 'Login';
ob_start();
?>

<style>
/* Login Page - Modern UI with Dark Mode Support */
.login-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
    padding: 20px;
    position: relative;
    overflow: hidden;
}

body.dark-mode .login-page {
    background: linear-gradient(135deg, #020617 0%, #0f172a 50%, #1e293b 100%);
}

.login-page::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle at 30% 30%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 70% 70%, rgba(16, 185, 129, 0.1) 0%, transparent 50%);
    animation: bgPulse 15s ease-in-out infinite;
}

@keyframes bgPulse {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    50% { transform: translate(-5%, -5%) rotate(5deg); }
}

.login-container {
    width: 100%;
    max-width: 420px;
    position: relative;
    z-index: 1;
}

.login-card {
    background: #ffffff;
    border-radius: 24px;
    padding: 40px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
    animation: slideUp 0.5s ease;
}

body.dark-mode .login-card {
    background: #1e293b;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

.login-header {
    text-align: center;
    margin-bottom: 32px;
}

.login-logo {
    width: 80px;
    height: 80px;
    object-fit: contain;
    border-radius: 20px;
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
    margin-bottom: 16px;
    animation: logoFloat 3s ease-in-out infinite;
}

@keyframes logoFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

.login-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
    animation: logoFloat 3s ease-in-out infinite;
}

.login-icon i {
    font-size: 40px;
    color: #fff;
}

.brand-title {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 4px;
    letter-spacing: -0.5px;
}

body.dark-mode .brand-title {
    color: #f1f5f9;
}

.brand-subtitle {
    font-size: 14px;
    color: #64748b;
    font-weight: 500;
}

body.dark-mode .brand-subtitle {
    color: #94a3b8;
}

/* Form Styles */
.login-card .mb-3 {
    margin-bottom: 20px;
}

.login-card .form-label {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
    display: block;
}

body.dark-mode .login-card .form-label {
    color: #cbd5e1;
}

.login-card .input-group {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid #e2e8f0;
    transition: all 0.3s ease;
}

body.dark-mode .login-card .input-group {
    border-color: #334155;
}

.login-card .input-group:focus-within {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.login-card .input-group-text {
    background: #f8fafc;
    border: none;
    padding: 14px 16px;
    color: #64748b;
    font-size: 16px;
}

body.dark-mode .login-card .input-group-text {
    background: #0f172a;
    color: #94a3b8;
}

.login-card .form-control {
    border: none;
    padding: 14px 16px;
    font-size: 14px;
    outline: none;
    background: transparent;
    color: #1e293b;
}

body.dark-mode .login-card .form-control {
    color: #f1f5f9;
}

.login-card .form-control:focus {
    box-shadow: none;
}

/* Remember & Forgot */
.login-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    font-size: 13px;
}

.login-options .form-check {
    display: flex;
    align-items: center;
    gap: 6px;
}

.login-options .form-check-input {
    width: 16px;
    height: 16px;
    border-radius: 4px;
    border: 2px solid #e2e8f0;
    cursor: pointer;
}

.login-options .form-check-label {
    color: #64748b;
    cursor: pointer;
}

body.dark-mode .login-options .form-check-label {
    color: #94a3b8;
}

.login-options a {
    color: #4f46e5;
    text-decoration: none;
    font-weight: 600;
}

.login-options a:hover {
    text-decoration: underline;
}

/* Login Button */
.login-card button[type="submit"] {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
    border: none;
    border-radius: 12px;
    color: white;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.login-card button[type="submit"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(79, 70, 229, 0.5);
}

.login-card button[type="submit"] i {
    font-size: 18px;
}

/* Error Alert */
.login-card .alert {
    border-radius: 12px;
    padding: 14px 16px;
    margin-bottom: 20px;
    border: none;
}

.login-card .alert-danger {
    background: #fef2f2;
    color: #dc2626;
    border-left: 4px solid #dc2626;
}

/* Footer */
.login-footer {
    margin-top: 32px;
    text-align: center;
}

.login-footer .developer-info {
    font-size: 11px;
    color: #94a3b8;
    line-height: 1.8;
}

.login-footer .developer-info a {
    text-decoration: none;
    margin: 0 4px;
    transition: color 0.2s;
}

.login-footer .developer-info a:hover {
    text-decoration: underline;
}

/* Social Login Placeholder */
.social-login {
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid #e2e8f0;
    text-align: center;
}

.social-login p {
    font-size: 12px;
    color: #94a3b8;
    margin-bottom: 12px;
}

.social-buttons {
    display: flex;
    gap: 12px;
    justify-content: center;
}

.social-btn {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    color: #64748b;
    font-size: 18px;
}

.social-btn:hover {
    border-color: #4f46e5;
    color: #4f46e5;
}
</style>

<div class="login-page">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <?php if (!empty(COMPANY_LOGO)): ?>
                    <?php if (strpos(COMPANY_LOGO, 'http') === 0): ?>
                        <img src="<?php echo COMPANY_LOGO; ?>" alt="Logo" class="login-logo">
                    <?php else: ?>
                        <img src="<?php echo BASE_URL . '/' . COMPANY_LOGO; ?>" alt="Logo" class="login-logo">
                    <?php endif; ?>
                <?php else: ?>
                    <div class="login-icon">
                        <i class="bi bi-shop"></i>
                    </div>
                <?php endif; ?>
                <h3 class="brand-title"><?php echo SITE_NAME; ?></h3>
                <p class="brand-subtitle">Point of Sale System</p>
            </div>

            <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle me-2"></i>
                <?php echo $error; ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="username" name="username" required autofocus placeholder="Enter your username">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password">
                    </div>
                </div>

                <div class="login-options">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <a href="#">Forgot password?</a>
                </div>

                <button type="submit">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Sign In
                </button>
            </form>

            <div class="login-footer">
                <div class="developer-info">
                    <p class="mb-1">© <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
                    <p class="mb-0">
                        <a href="https://wa.me/8801889933520" target="_blank" style="color: #25D366;"><i class="bi bi-whatsapp"></i> +8801889933520</a>
                        <span style="color: #cbd5e1;">|</span>
                        <a href="https://www.facebook.com/mdrifathossain1999" target="_blank" style="color: #1877F2;"><i class="bi bi-facebook"></i> Md Rifat Hossain</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
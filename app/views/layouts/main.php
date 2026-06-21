<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts/dist/apexcharts.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/page-header.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/header-menu.css">
    <style>
        :root {
            --sidebar-width: 250px;
            --header-height: 60px;
        }

        body { font-family: 'Inter', 'Noto Sans Bengali', sans-serif; }

        .top-bar {
            position: fixed; top: 0; left: var(--sidebar-width); right: 0;
            height: var(--header-height);
            backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            background: rgba(255,255,255,0.85);
            border-bottom: 1px solid var(--header-border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.2rem;
            z-index: 1100;
            transition: left 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        }
        body.dark-mode .top-bar { background: rgba(15,23,42,0.85); }
        body.sidebar-collapsed .top-bar { left: 0; }

        .header-left {
            display: flex; align-items: center;
            gap: 0.75rem; overflow: hidden;
        }
        .header-left .brand {
            font-weight: 700; font-size: 1.15rem;
            color: var(--primary); white-space: nowrap;
            display: flex; align-items: center; gap: 8px;
        }
        .header-left .brand i { font-size: 1.3rem; }

        .sidebar-toggle {
            background: transparent; border: none;
            font-size: 1.5rem; color: var(--nav-text); cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            width: 36px; height: 36px; border-radius: 10px; transition: 0.2s;
            flex-shrink: 0; touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }
        .sidebar-toggle:hover { background: var(--nav-hover); color: var(--primary); }

        .header-right {
            display: flex; align-items: center; justify-content: flex-end;
            gap: 0.75rem; padding-right: 1.5rem;
        }
        .header-right .lang-btn, .header-right .theme-btn {
            display: flex; align-items: center; gap: 6px;
            padding: 7px 12px; background: transparent;
            border: 1px solid var(--header-border); border-radius: 8px;
            color: var(--nav-text); cursor: pointer;
            font-size: 12px; font-weight: 600; transition: all 0.2s ease;
        }
        .header-right .lang-btn:hover, .header-right .theme-btn:hover {
            background: var(--nav-hover); border-color: var(--primary-light); color: var(--primary);
        }
        .header-right .user-btn {
            display: flex; align-items: center; gap: 8px;
            padding: 5px 12px 5px 5px; background: transparent;
            border: 1px solid var(--header-border); border-radius: 10px;
            color: var(--nav-text); cursor: pointer; transition: all 0.2s ease;
        }
        .header-right .user-btn:hover { background: var(--nav-hover); border-color: var(--primary-light); }
        .header-right .user-avatar {
            width: 30px; height: 30px; border-radius: 8px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white; display: flex; align-items: center; justify-content: center; font-size: 14px;
        }

        .sidebar-layout { display: flex; }

        .main-sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            border-right: 1px solid rgba(255,255,255,0.08);
            z-index: 1050;
            transition: width 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1),
                        opacity 0.3s ease, visibility 0.3s ease;
            overflow: hidden; display: flex; flex-direction: column;
        }
        body.sidebar-collapsed .main-sidebar {
            width: 0; opacity: 0; visibility: hidden;
        }

        .sidebar-brand { display: none; }

        .sidebar-menu {
            flex: 1; overflow-y: auto; overflow-x: hidden;
            padding: 0.75rem; margin: 0; list-style: none;
            min-width: var(--sidebar-width);
            /* Reset old sidebar.css styles */
            position: static; width: auto; min-height: 0; max-height: none;
            background: transparent; left: auto; top: auto;
            z-index: auto; box-shadow: none;
        }
        .sidebar-menu::-webkit-scrollbar { width: 4px; }
        .sidebar-menu::-webkit-scrollbar-track { background: transparent; }
        .sidebar-menu::-webkit-scrollbar-thumb { background: var(--header-border); border-radius: 4px; }

        .sidebar-menu li { margin-bottom: 0.15rem; }

        /* Sidebar on dark gradient needs light text */
        .sidebar-menu li a {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 16px; text-decoration: none;
            color: #e2e8f0;
            font-weight: 500; font-size: 15px;
            border-radius: 10px;
            transition: all 0.2s ease; white-space: nowrap;
            border-left: 3px solid transparent;
            position: relative;
        }
        .sidebar-menu li a:hover {
            color: white; background: rgba(255,255,255,0.08);
            border-left-color: var(--primary-light);
        }
        .sidebar-menu li.active > a {
            color: white; font-weight: 600;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-left-color: var(--primary);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }
        .sidebar-menu li a i { font-size: 1.25rem; width: 24px; text-align: center; flex-shrink: 0;
            color: #64748b; }
        .sidebar-menu li.active > a i { color: white; }
        .sidebar-menu li a:hover i { color: white; }
        .sidebar-menu li a .chevron {
            margin-left: auto; font-size: 0.8rem; transition: transform 0.3s ease;
            color: #475569;
        }
        .sidebar-menu li.open > a .chevron { transform: rotate(180deg); }

        .sidebar-menu li ul.dropdown-menu {
            padding: 0; margin: 0 0 4px 0; display: none;
            position: static; float: none; width: 100%;
            box-shadow: none; background: transparent; border: none;
        }
        .sidebar-menu li.open > ul.dropdown-menu { display: block; }
        .sidebar-menu li ul.dropdown-menu li a {
            padding-left: 48px; height: 36px; font-weight: 400;
            font-size: 14px; color: #94a3b8;
            border-left: none; margin: 0; border-radius: 0;
        }
        .sidebar-menu li ul.dropdown-menu li a::before {
            content: "\f105"; font-family: "Font Awesome 5 Free";
            font-weight: 900; font-size: 11px; position: absolute;
            transition: all 0.3s ease; left: 28px; color: #64748b;
        }
        .sidebar-menu li ul.dropdown-menu li a:hover { color: white; background: transparent; border-left: none; }
        .sidebar-menu li ul.dropdown-menu li a:hover::before { color: var(--primary-light); left: 32px; }
        .sidebar-menu li ul.dropdown-menu li.active > a { color: white; font-weight: 600; border-left: none; }
        .sidebar-menu li ul.dropdown-menu li.active > a::before { color: var(--primary-light); font-weight: 600; }
        .sidebar-menu li ul.dropdown-menu li a i { margin-top: 1px; font-size: 13px; width: 14px;
            color: #64748b; }
        .sidebar-menu li ul.dropdown-menu li a:hover i { color: white; }
        .sidebar-menu li ul.dropdown-menu li.active > a i { color: white; }

        .sidebar-menu::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }

        /* Neutralize old sidebar.css sidebar-brand that may appear */
        .sidebar-brand { display: none !important; }

        .main-area {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            transition: margin-left 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            min-height: calc(100vh - var(--header-height));
            display: flex; flex-direction: column;
            flex: 1;
        }
        body.sidebar-collapsed .main-area { margin-left: 0; }

        .content-area { flex: 1; padding: 1.5rem; margin-top: 0; min-height: auto; }

        .site-footer {
            padding: 1rem 1.5rem; text-align: center;
            border-top: 1px solid var(--header-border);
            font-size: 13px; color: var(--nav-text-muted);
        }

        .overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.4); z-index: 1051;
        }

        /* ========== MOBILE RESPONSIVE ========== */
        @media (max-width: 992px) {
            :root { --header-height: 56px; }

            .top-bar { left: 0 !important; padding: 0 0.75rem; }
            body.sidebar-collapsed .top-bar { left: 0 !important; }

            .header-left .brand span { display: none; }
            .header-left .brand i { font-size: 1.3rem; }

            .header-right { gap: 0.4rem; padding-right: 0; }
            .header-right .lang-btn span { display: none; }
            .header-right .lang-btn { padding: 7px 10px; }
            .header-right .theme-btn { padding: 7px 10px; }
            .header-right .user-btn span { display: none; }
            .header-right .user-btn { padding: 4px; border: none; }
            .header-right .user-avatar { width: 36px; height: 36px; border-radius: 10px; font-size: 16px; }
            .header-right .user-btn i.bi-chevron-down { display: none; }

            .sidebar-toggle { display: flex !important; }
            .sidebar-menu { transform: none !important; width: auto !important; }

            .main-sidebar {
                left: -100%; width: 85vw !important; max-width: 320px !important;
                opacity: 1 !important; visibility: visible !important;
                top: 0; height: 100dvh;
                box-shadow: 4px 0 30px rgba(0,0,0,0.2);
                transition: left 0.35s cubic-bezier(0.2, 0.9, 0.3, 1.1);
                border-right: 1px solid rgba(255,255,255,0.08);
                border-radius: 0 16px 16px 0;
            }
            .main-sidebar.open,
            body.sidebar-collapsed .main-sidebar.open { left: 0; }
            body.sidebar-collapsed .main-sidebar {
                left: -100%;
                border-right: 1px solid var(--header-border);
            }

            .main-sidebar .sidebar-toggle-mobile {
                display: flex; align-items: center; justify-content: space-between;
                padding: 16px 16px 8px; margin-bottom: 4px;
            }
            .main-sidebar .sidebar-toggle-mobile .brand {
                font-weight: 700; font-size: 1rem;
                color: #e2e8f0; display: flex; align-items: center; gap: 8px;
            }
            .main-sidebar .sidebar-toggle-mobile .brand i { color: var(--primary-light); font-size: 1.2rem; }
            .main-sidebar .sidebar-toggle-mobile .close-btn {
                background: rgba(255,255,255,0.1); border: none;
                color: #94a3b8; width: 36px; height: 36px; border-radius: 10px;
                display: flex; align-items: center; justify-content: center;
                font-size: 1.3rem; cursor: pointer; transition: 0.2s;
            }
            .main-sidebar .sidebar-toggle-mobile .close-btn:active {
                background: rgba(255,255,255,0.18); color: white;
            }

            .sidebar-menu { padding: 0 10px 16px; }
            .sidebar-menu li a { padding: 14px 14px; min-height: 48px; font-size: 15px; }
            .sidebar-menu li ul.dropdown-menu li a { padding-left: 44px; min-height: 42px; font-size: 14px; }
            .sidebar-menu li ul.dropdown-menu li a::before { left: 22px; }

            .overlay.open { display: block; }
            .overlay { z-index: 1049; }
            .main-sidebar { z-index: 1051; }
            .main-area { margin-left: 0 !important; }

            .content-area { padding: 1rem 0.875rem; }

            .site-footer { padding: 0.75rem 1rem; font-size: 12px; }

            .card { border-radius: 12px; }
            .card-body { padding: 1rem; }

            .table-responsive { border-radius: 10px; }
            .table td, .table th { padding: 0.5rem 0.5rem; font-size: 13px; white-space: nowrap; }

            .modal-dialog { margin: 0.5rem; }
            .modal-content { border-radius: 14px; }

            .btn { font-size: 13px; padding: 0.4rem 0.75rem; }

            .form-control, .form-select { font-size: 14px; min-height: 44px; }

            .row { margin-left: -0.5rem; margin-right: -0.5rem; }
            .row > [class*="col-"] { padding-left: 0.5rem; padding-right: 0.5rem; }
        }
        @media (max-width: 480px) {
            .header-left .brand { display: none; }
            .content-area { padding: 0.75rem 0.625rem; }
            .card-body { padding: 0.75rem; }
        }
        @media (min-width: 993px) {
            .main-sidebar .sidebar-toggle-mobile { display: none; }
        }
    </style>
</head>
<body class="<?php echo $currentPage ?? ''; ?>">
<?php if (isset($_SESSION['user_id'])): ?>
<div class="overlay" id="sidebarOverlay"></div>

<header class="top-bar">
    <div class="header-left">
        <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
        <span class="brand"><i class="bi bi-shop-window"></i> <?php echo SITE_NAME; ?></span>
    </div>
    <div class="header-right">
        <div style="position:relative;">
            <button class="lang-btn" onclick="document.getElementById('langDropdown').style.display=document.getElementById('langDropdown').style.display==='block'?'none':'block'">
                <i class="bi bi-translate"></i> <span id="langBtnText">English</span>
            </button>
            <div id="langDropdown" style="display:none;position:absolute;top:100%;right:0;margin-top:4px;background:var(--submenu-bg);border:1px solid var(--submenu-border);border-radius:6px;box-shadow:var(--submenu-shadow);min-width:120px;z-index:1003;">
                <a href="#" onclick="setLanguage('en')" style="display:flex;align-items:center;gap:8px;padding:8px 12px;color:var(--nav-text);text-decoration:none;font-size:12px;"><i class="bi bi-globe-americas"></i> English</a>
                <a href="#" onclick="setLanguage('bn')" style="display:flex;align-items:center;gap:8px;padding:8px 12px;color:var(--nav-text);text-decoration:none;font-size:12px;"><i class="bi bi-flag-fill"></i> বাংলা</a>
            </div>
        </div>
        <button class="theme-btn" onclick="toggleTheme()" title="Theme">
            <i class="bi bi-moon-stars" id="themeIcon"></i>
        </button>
        <div class="user-dropdown" style="position:relative;">
            <button class="user-btn" onclick="toggleUserMenu()">
                <span class="user-avatar"><i class="bi bi-person-fill"></i></span>
                <span style="font-weight:500;font-size:13px;"><?php echo $_SESSION['name'] ?? 'User'; ?></span>
                <i class="bi bi-chevron-down" style="font-size:10px;"></i>
            </button>
            <div class="user-menu" id="userMenu">
                <a href="<?php echo BASE_URL; ?>/auth/profile"><i class="bi bi-person"></i> <span>Profile</span></a>
                <a href="<?php echo BASE_URL; ?>/admin"><i class="bi bi-gear"></i> <span>Settings</span></a>
                <div class="divider"></div>
                <a href="<?php echo BASE_URL; ?>/auth/logout" class="logout"><i class="bi bi-box-arrow-right"></i> <span>Logout</span></a>
            </div>
        </div>
    </div>
</header>

<div class="sidebar-layout">
    <aside class="main-sidebar" id="sidebar">
        <div class="sidebar-toggle-mobile">
        <span class="brand"><i class="bi bi-shop-window"></i> <span><?php echo SITE_NAME; ?></span></span>
            <button class="close-btn" id="mobileSidebarClose"><i class="bi bi-x-lg"></i></button>
        </div>
        <ul class="sidebar-menu">
            <li class="<?php echo ($currentPage ?? '') === 'dashboard' ? 'active' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>/dashboard"><i class="bi bi-grid-1x2-fill"></i> <span>Dashboard</span></a>
            </li>
            <li class="has-submenu <?php echo in_array($currentPage ?? '', ['purchase','purchase_create','purchase_return']) ? 'open' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>/purchase/index"><i class="bi bi-cart-plus-fill"></i> <span>Purchase</span> <i class="bi bi-chevron-down chevron"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo BASE_URL; ?>/purchase/index"><i class="bi bi-list-ul"></i> <span>All Purchase</span></a></li>
                    <li><a href="<?php echo BASE_URL; ?>/purchase/create"><i class="bi bi-plus-circle"></i> <span>Add Purchase</span></a></li>
                    <li><a href="<?php echo BASE_URL; ?>/purchase/return"><i class="bi bi-arrow-return-left"></i> <span>Returns</span></a></li>
                </ul>
            </li>
            <li class="has-submenu <?php echo in_array($currentPage ?? '', ['pos','invoice','sale']) ? 'open' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>/pos"><i class="bi bi-cart-check-fill"></i> <span>Sale</span> <i class="bi bi-chevron-down chevron"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo BASE_URL; ?>/pos"><i class="bi bi-cart"></i> <span>POS Sale</span></a></li>
                    <li><a href="<?php echo BASE_URL; ?>/invoice/index"><i class="bi bi-receipt"></i> <span>All Invoice</span></a></li>
                    <li><a href="<?php echo BASE_URL; ?>/sale/return"><i class="bi bi-arrow-return-left"></i> <span>Returns</span></a></li>
                </ul>
            </li>
            <li class="has-submenu <?php echo in_array($currentPage ?? '', ['account','expense']) ? 'open' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>/account/index"><i class="bi bi-calculator"></i> <span>Accounting</span> <i class="bi bi-chevron-down chevron"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo BASE_URL; ?>/account/index"><i class="bi bi-wallet"></i> <span>Accounts</span></a></li>
                    <li><a href="<?php echo BASE_URL; ?>/expense/index"><i class="bi bi-wallet2"></i> <span>Expense</span></a></li>
                    <li><a href="<?php echo BASE_URL; ?>/account/deposit"><i class="bi bi-arrow-down-circle"></i> <span>Deposit</span></a></li>
                    <li><a href="<?php echo BASE_URL; ?>/account/withdraw"><i class="bi bi-arrow-up-circle"></i> <span>Withdraw</span></a></li>
                </ul>
            </li>
            <li class="has-submenu <?php echo in_array($currentPage ?? '', ['hrm']) ? 'open' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>/hrm/employee"><i class="bi bi-people-fill"></i> <span>HRM</span> <i class="bi bi-chevron-down chevron"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo BASE_URL; ?>/hrm/employee"><i class="bi bi-person-badge"></i> <span>Employees</span></a></li>
                    <li><a href="<?php echo BASE_URL; ?>/hrm/attendance"><i class="bi bi-calendar-check"></i> <span>Attendance</span></a></li>
                    <li><a href="<?php echo BASE_URL; ?>/hrm/department"><i class="bi bi-diagram-3"></i> <span>Department</span></a></li>
                </ul>
            </li>
            <li class="has-submenu <?php echo in_array($currentPage ?? '', ['customer','supplier']) ? 'open' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>/customer/index"><i class="bi bi-person-check-fill"></i> <span>People</span> <i class="bi bi-chevron-down chevron"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo BASE_URL; ?>/customer/index"><i class="bi bi-people"></i> <span>Customers</span></a></li>
                    <li><a href="<?php echo BASE_URL; ?>/supplier/index"><i class="bi bi-box-seam"></i> <span>Suppliers</span></a></li>
                </ul>
            </li>
            <li class="<?php echo ($currentPage ?? '') === 'product' ? 'active' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>/product/index"><i class="bi bi-box-seam-fill"></i> <span>Product</span></a>
            </li>
            <li class="<?php echo ($currentPage ?? '') === 'report' ? 'active' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>/report/index"><i class="bi bi-graph-up-arrow"></i> <span>Report</span></a>
            </li>
            <li class="has-submenu <?php echo in_array($currentPage ?? '', ['admin','settings']) ? 'open' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>/admin/index"><i class="bi bi-gear-fill"></i> <span>Admin</span> <i class="bi bi-chevron-down chevron"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo BASE_URL; ?>/admin/index"><i class="bi bi-grid-3x3"></i> <span>Dashboard</span></a></li>
                    <li><a href="<?php echo BASE_URL; ?>/admin/settings"><i class="bi bi-sliders"></i> <span>Settings</span></a></li>
                    <li><a href="<?php echo BASE_URL; ?>/admin/users"><i class="bi bi-user-gear"></i> <span>Users</span></a></li>
                </ul>
            </li>
        </ul>
    </aside>
    <div class="main-area">
        <main class="content-area"><?php echo $content; ?></main>
        <footer class="site-footer">
            <div class="developer-info">
                &copy; <?php echo date('Y'); ?> <?php echo COMPANY_NAME; ?>. All rights reserved. <a href="https://wa.me/8801889933520" target="_blank" style="color: #25D366;"><i class="bi bi-whatsapp"></i> +8801889933520</a> | <a href="https://www.facebook.com/mdrifathossain1999" target="_blank" style="color: #1877F2;"><i class="bi bi-facebook"></i> Md Rifat Hossain</a>
            </div>
        </footer>
    </div>
</div>
<?php else: ?>
<?php echo $content; ?>
<?php endif; ?>

<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" style="z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
            <div class="modal-body text-center p-4">
                <div class="notif-icon mb-3" id="notificationIcon"><i class="bi bi-check-circle-fill"></i></div>
                <h5 class="notif-title mb-2" id="notificationTitle" style="font-size:1.25rem;font-weight:600;">Success!</h5>
                <p class="notif-message text-muted mb-3" id="notificationMessage" style="font-size:0.95rem;">Operation completed successfully.</p>
                <button type="button" class="btn btn-primary btn-sm px-4" data-bs-dismiss="modal" id="notifCloseBtn" style="border-radius:20px;padding:8px 25px;"><span>OK</span></button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
var BASE_URL = "<?php echo BASE_URL; ?>";
var DEFAULT_CURRENCY = "<?php echo defined('DEFAULT_CURRENCY') ? DEFAULT_CURRENCY : '৳'; ?>";

function showNotification(title, message, type) {
    const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill';
    const bg = type === 'success' ? 'rgba(5, 150, 105, 0.95)' : 'rgba(220, 38, 38, 0.95)';
    const n = document.createElement('div');
    n.style.cssText = 'position:fixed;top:20px;right:20px;background:' + bg + ';color:white;padding:16px 24px;border-radius:12px;z-index:9999;';
    n.innerHTML = '<i class="bi ' + icon + '"></i> <strong>' + title + '</strong>';
    document.body.appendChild(n);
    setTimeout(() => n.remove(), 3000);
}

let currentLang = localStorage.getItem('language') || 'en';
let isDark = localStorage.getItem('darkMode') === 'true';

document.addEventListener('DOMContentLoaded', function() {
    initLang();
    initTheme();
    initSidebar();
});

function initLang() {
    const langBtn = document.getElementById('langBtnText');
    if (langBtn) langBtn.textContent = currentLang === 'bn' ? 'বাংলা' : 'English';
    if (currentLang === 'bn') {
        setTimeout(translatePage, 100);
        setTimeout(translatePage, 500);
        setTimeout(translatePage, 1500);
    }
}

function toBengaliNum(num) {
    const bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    return String(num).replace(/[0-9]/g, d => bengaliDigits[d]);
}

function translatePage() {
    const dict = {
        'Dashboard': 'ড্যাশবোর্ড', 'Purchase': 'ক্রয়', 'Sale': 'বিক্রয়', 'Accounting': 'হিসাব',
        'Product': 'পণ্য', 'Reports': 'রিপোর্ট', 'Admin': 'অ্যাডমিন', 'HRM': 'এইচআরএম',
        'People': 'ব্যক্তি', 'All Purchase': 'সকল ক্রয়', 'Add Purchase': 'ক্রয় যোগ',
        'Returns': 'রিটার্ন', 'POS Sale': 'পস ও বিক্রয়', 'All Invoice': 'সকল চালান',
        'Accounts': 'অ্যাকাউন্ট', 'Expense': 'খরচ', 'Deposit': 'জমা', 'Withdraw': 'উত্তোলন',
        'Employees': 'কর্মী', 'Attendance': 'উপস্থিতি', 'Department': 'বিভাগ',
        'Customers': 'গ্রাহক', 'Suppliers': 'সরবরাহকারী', 'Daily Sales': 'দৈনিক বিক্রয়',
        'Monthly': 'মাসিক', 'Stock': 'স্টক', 'Custom Range': 'কাস্টম রেঞ্জ',
        'Product Sales': 'পণ্য বিক্রয়', 'Profit Report': 'মুনাফা রিপোর্ট',
        'Users': 'ব্যবহারকারী', 'Categories': 'ক্যাটাগরি', 'Units': 'একক',
        'Sales': 'বিক্রয়', 'Income': 'আয়', 'Due': 'বাকি', 'Today': 'আজ',
        'This Month': 'এই মাস', 'Total': 'মোট', 'Search': 'অনুসন্ধান',
        'Filter': 'ফিল্টার', 'Add': 'যোগ', 'Edit': 'সম্পাদনা', 'Delete': 'মুছুন',
        'Save': 'সংরক্ষণ', 'Cancel': 'বাতিল', 'Back': 'পেছনে', 'View': 'দেখুন', 'Clear': 'মুছুন',
        'Profile': 'প্রোফাইল', 'Settings': 'সেটিংস', 'Logout': 'লগআউট',
        'Welcome': 'স্বাগতম', 'Loading': 'লোড হচ্ছে', 'No data found': 'কোনো তথ্য পাওয়া যায়নি',
        'In Stock': 'স্টকে আছে', 'Low Stock': 'কম স্টক', 'Out of Stock': 'স্টক নেই',
        'Products': 'পণ্য সমূহ', 'Add Product': 'পণ্য যোগ', 'All Invoices': 'সব চালান',
        'Create': 'তৈরি', 'Name': 'নাম', 'Price': 'দাম', 'Quantity': 'পরিমাণ',
        'Amount': 'টাকা', 'Date': 'তারিখ', 'Description': 'বিবরণ', 'Category': 'ক্যাটাগরি',
        'Action': 'কার্যক্রম', 'Status': 'অবস্থা', 'Active': 'সক্রিয়', 'Inactive': 'নিষ্ক্রিয়',
        'Print': 'প্রিন্ট', 'Export': 'রপ্তানি', 'Download': 'ডাউনলোড',
        'Submit': 'জমা দিন', 'Reset': 'রিসেট', 'Close': 'বন্ধ',
        'Pay Now': 'এখন পেমেন্ট', 'Receive': 'গ্রহণ', 'Hold': 'ধরে রাখা',
        'Apply': 'প্রয়োগ', 'Select': 'নির্বাচন', 'OK': 'ঠিক আছে',
        'Barcode': 'বারকোড', 'Cost Price': 'ক্রয় মূল্য', 'Sell Price': 'বিক্রয় মূল্য',
        'Stock Quantity': 'স্টক পরিমাণ', 'Unit': 'একক', 'Size': 'সাইজ',
        'Image': 'ছবি', 'Phone': 'ফোন', 'Email': 'ইমেইল', 'Address': 'ঠিকানা',
        'Created': 'তৈরি হয়েছে', 'Updated': 'আপডেট হয়েছে',
        'General': 'সাধারণ', 'All': 'সব', 'Summary': 'সারাংশ', 'Quick Links': 'দ্রুত লিংক',
        'Daily Sales Report': 'দৈনিক বিক্রয় রিপোর্ট', 'Monthly Report': 'মাসিক রিপোর্ট',
        'Stock Report': 'স্টক রিপোর্ট', 'Custom Range Report': 'কাস্টম রেঞ্জ রিপোর্ট',
        'Walk-in Customer': 'ওয়াক-ইন গ্রাহক', 'Retailer': 'রিটেইলার', 'Wholesaler': 'পাইকার',
        'Admin Dashboard': 'অ্যাডমিন ড্যাশবোর্ড', 'User Management': 'ব্যবহারকারী ব্যবস্থাপনা',
        'General Settings': 'সাধারণ সেটিংস', 'Database': 'ডাটাবেস',
        'Database Backup': 'ডাটাবেস ব্যাকআপ', 'Uploads': 'আপলোড',
        'Role Permission': 'রোল অনুমতি', 'New Account': 'নতুন অ্যাকাউন্ট',
        'Gross Profit': 'মোট মুনাফা', 'Net Profit': 'নিট মুনাফা', 'Total Cost': 'মোট খরচ',
        'Total Expenses': 'মোট খরচ', 'Total Sales': 'মোট বিক্রয়', 'Total Collected': 'মোট আদায়',
        'Profit': 'মুনাফা', 'Loss': 'ক্ষতি', 'Revenue': 'রাজস্ব', 'Cost': 'খরচ',
        'Balance': 'ব্যালেন্স', 'Account': 'অ্যাকাউন্ট', 'Transfer': 'ট্রান্সফার',
        'Invoice': 'চালান', 'Invoice Number': 'চালান নং', 'Order': 'অর্ডার',
        'Sale': 'বিক্রয়', 'Purchase': 'ক্রয়', 'Return': 'রিটার্ন',
        'Discount': 'ডিসকাউন্ট', 'Tax': 'ট্যাক্স', 'VAT': 'ভ্যাট',
        'Payment': 'পেমেন্ট', 'Payment Method': 'পেমেন্ট পদ্ধতি', 'Cash': 'নগদ',
        'Card': 'কার্ড', 'Mobile Banking': 'মোবাইল ব্যাংকিং', 'Cheque': 'চেক',
        'Paid': 'পেয়েছে', 'Received': 'গ্রহণ করা হয়েছে', 'Pending': 'বাকি',
        'Completed': 'সম্পন্ন', 'Cancelled': 'বাতিল', 'Partial': 'আংশিক',
        'Year': 'বছর', 'Month': 'মাস', 'Day': 'দিন', 'Week': 'সপ্তাহ',
        'January': 'জানুয়ারি', 'February': 'ফেব্রুয়ারি', 'March': 'মার্চ',
        'April': 'এপ্রিল', 'May': 'মে', 'June': 'জুন', 'July': 'জুলাই',
        'August': 'আগস্ট', 'September': 'সেপ্টেম্বর', 'October': 'অক্টোবর',
        'November': 'নভেম্বর', 'December': 'ডিসেম্বর',
        'Total Profit': 'মোট মুনাফা', 'Previous Balance': 'আগের ব্যালেন্স',
        'Current Balance': 'বর্তমান ব্যালেন্স', 'Available Balance': 'উপলব্ধ ব্যালেন্স',
        'Total Amount': 'মোট পরিমাণ', 'Paid Amount': 'পেয়েছে', 'Due Amount': 'বাকি পরিমাণ',
        'Opening Balance': 'খোলার ব্যালেন্স', 'Closing Balance': 'বন্ধের ব্যালেন্স',
        'Total Debit': 'মোট ডেবিট', 'Total Credit': 'মোট ক্রেডিট',
        'Items Sold': 'বিক্রিত পণ্য', 'Units Sold': 'বিক্রিত একক',
        'Accounting Dashboard': 'হিসাব ড্যাশবোর্ড', 'Transaction History': 'লেনদেন ইতিহাস',
        'Account Name': 'অ্যাকাউন্টের নাম', 'Type': 'ধরন',
        'Create New Account': 'নতুন অ্যাকাউন্ট তৈরি', 'Account Number': 'অ্যাকাউন্ট নম্বর',
        'Account Type': 'অ্যাকাউন্টের ধরন', 'Bank': 'ব্যাংক',
        'Edit Account': 'অ্যাকাউন্ট সম্পাদনা', 'Save Changes': 'পরিবর্তন সংরক্ষণ',
        'Deposit Money': 'টাকা জমা', 'New Deposit': 'নতুন জমা',
        'Reference No': 'রেফারেন্স নং', 'Select Account': 'অ্যাকাউন্ট নির্বাচন',
        'Withdraw Money': 'টাকা উত্তোলন', 'New Withdrawal': 'নতুন উত্তোলন',
        'No deposits yet': 'এখনো কোনো জমা নেই', 'No withdrawals yet': 'এখনো কোনো উত্তোলন নেই',
        'Customer List': 'গ্রাহক তালিকা', 'Customer Type': 'গ্রাহকের ধরন',
        'Supplier List': 'সরবরাহকারী তালিকা', 'All Suppliers': 'সকল সরবরাহকারী',
        'Purchase List': 'ক্রয় তালিকা', 'All Purchases': 'সকল ক্রয়',
        'Add New Purchase': 'নতুন ক্রয় যোগ', 'Purchase Info': 'ক্রয় তথ্য',
        'Product Details': 'পণ্যের বিবরণ', 'Supplier': 'সরবরাহকারী',
        'Select Product': 'পণ্য নির্বাচন', 'Unit Cost': 'একক মূল্য',
        'Purchase Return': 'ক্রয় রিটার্ন', 'New Return': 'নতুন রিটার্ন',
        'Reason': 'কারণ', 'Submit Return': 'রিটার্ন জমা দিন',
        'No returns yet': 'এখনো কোনো রিটার্ন নেই',
        'Expense List': 'খরচের তালিকা', 'All Categories': 'সব ক্যাটাগরি',
        'No expenses found': 'কোনো খরচ পাওয়া যায়নি',
        'Reference': 'রেফারেন্স', 'By': 'দ্বারা', 'Save Expense': 'খরচ সংরক্ষণ',
        'Sale Return': 'বিক্রয় রিটার্ন', 'Sale Invoice': 'বিক্রয় চালান',
        'Role Permission': 'রোল অনুমতি', 'Select Role': 'রোল নির্বাচন',
        'Manager': 'ম্যানেজার', 'Cashier': 'ক্যাশিয়ার', 'Accountant': 'অ্যাকাউন্ট্যান্ট',
        'View': 'দেখুন', 'Save Permissions': 'অনুমতি সংরক্ষণ',
        'Point of Sale': 'পয়েন্ট অফ সেল', 'Sale Complete': 'বিক্রয় সম্পন্ন',
        'Sale Completed Successfully!': 'বিক্রয় সফলভাবে সম্পন্ন হয়েছে!',
        'Add New Customer': 'নতুন গ্রাহক যোগ', 'Quick Add Customer': 'দ্রুত গ্রাহক যোগ',
        'Customer Added!': 'গ্রাহক যোগ হয়েছে!', 'Subtotal': 'সাবটোটাল',
        'Complete Sale': 'বিক্রয় সম্পন্ন', 'Your cart is empty': 'আপনার কার্ট খালি',
        'Click products to add': 'পণ্য যোগ করতে ক্লিক করুন', 'Invoice Info': 'চালান তথ্য',
        'Payment Summary': 'পেমেন্ট সারাংশ', 'Quick Payment': 'দ্রুত পেমেন্ট',
        'Invoice Details': 'চালানের বিবরণ', 'Add Payment': 'পেমেন্ট যোগ',
        'Unit Price': 'একক মূল্য', 'No items found': 'কোনো আইটেম পাওয়া যায়নি',
        'Today\'s Sales': 'আজকের বিক্রয়', 'Transactions': 'লেনদেন',
        'Due Invoice List': 'বাকি চালান তালিকা', 'Days Overdue': 'মেয়াদোত্তীর্ণ দিন',
        'Unpaid Invoice List': 'অপরিশোধিত চালান তালিকা', 'Total Unpaid': 'মোট বাকি',
        'Sales by Date Range': 'তারিখ অনুযায়ী বিক্রয়',
        'Invoice Templates': 'চালান টেমপ্লেট', 'Create New Template': 'নতুন টেমপ্লেট তৈরি',
        'Existing Templates': 'বিদ্যমান টেমপ্লেট', 'Edit Invoice Template': 'চালান টেমপ্লেট সম্পাদনা',
        'Invoice Numbering': 'চালান নম্বরকরণ', 'Fiscal Year': 'অর্থ বছর',
        'Starting Number': 'শুরু নম্বর', 'Padding (digits)': 'প্যাডিং (অংক)',
        'Prefix': 'প্রিফিক্স', 'No invoices found': 'কোনো চালান পাওয়া যায়নি',
        'Total Purchase': 'মোট ক্রয়', 'Purchase History': 'ক্রয় ইতিহাস',
        'Payment History': 'পেমেন্ট ইতিহাস', 'Total Transactions': 'মোট লেনদেন',
        'Loyalty Points': 'লয়ালটি পয়েন্ট',
        'Receive Payment': 'পেমেন্ট গ্রহণ', 'Amount to Receive': 'গ্রহণের পরিমাণ',
        'Sales Overview': 'বিক্রয় সারাংশ', 'Recent Sales': 'সাম্প্রতিক বিক্রয়',
        'Low Stock Products': 'কম স্টক পণ্য', 'Top Categories': 'শীর্ষ ক্যাটাগরি',
        'View All': 'সব দেখুন', 'No recent sales': 'কোনো সাম্প্রতিক বিক্রয় নেই',
        'No low stock products': 'কোনো কম স্টক পণ্য নেই',
        'Overview': 'ওভারভিউ', 'Revenue Statistics': 'রাজস্ব পরিসংখ্যান',
        'Low Stock Alert': 'কম স্টক সতর্কতা', 'Last 7 Days': 'গত ৭ দিন',
        'Last 28 Days': 'গত ২৮ দিন', 'No recent sales found': 'কোনো সাম্প্রতিক বিক্রয় পাওয়া যায়নি',
        'Add Employee': 'কর্মী যোগ', 'First Name': 'নামের প্রথম অংশ',
        'Last Name': 'নামের শেষাংশ', 'Designation': 'পদবী', 'Salary': 'বেতন',
        'Join Date': 'যোগদানের তারিখ', 'No employees found': 'কোনো কর্মী পাওয়া যায়নি',
        'Edit Employee': 'কর্মী সম্পাদনা', 'Add Department': 'বিভাগ যোগ',
        'Department Name': 'বিভাগের নাম', 'No departments found': 'কোনো বিভাগ পাওয়া যায়নি',
        'Edit Department': 'বিভাগ সম্পাদনা', 'Check In': 'চেক ইন',
        'Check Out': 'চেক আউট', 'Present': 'উপস্থিত', 'Absent': 'অনুপস্থিত',
        'Late': 'দেরী', 'Leave': 'ছুটি', 'Mark Attendance': 'উপস্থিতি চিহ্নিত',
        'No attendance records': 'কোনো উপস্থিতি রেকর্ড নেই',
        'Add Holiday': 'ছুটি যোগ', 'Holiday Name': 'ছুটির নাম',
        'Recurring Yearly': 'বার্ষিক পুনরাবৃত্তি', 'Yes': 'হ্যাঁ', 'No': 'না',
        'No holidays found': 'কোনো ছুটি পাওয়া যায়নি',
        'Edit Product': 'পণ্য সম্পাদনা', 'Add New Product': 'নতুন পণ্য যোগ',
        'Add Unit': 'একক যোগ', 'Unit Name': 'এককের নাম',
        'Initial Stock': 'প্রাথমিক স্টক', 'Low Stock Alert': 'কম স্টক সতর্কতা',
        'Product Image': 'পণ্যের ছবি', 'No Products Found': 'কোনো পণ্য পাওয়া যায়নি',
        'No Image': 'কোনো ছবি নেই', 'Product Details': 'পণ্যের বিবরণ',
        'Add Category': 'ক্যাটাগরি যোগ', 'Category Name': 'ক্যাটাগরির নাম',
        'Size Options': 'সাইজ অপশন', 'Size Type': 'সাইজের ধরন',
        'No Size': 'কোনো সাইজ নেই', 'Single Size': 'একক সাইজ',
        'Multiple Sizes': 'একাধিক সাইজ', 'No Categories Found': 'কোনো ক্যাটাগরি পাওয়া যায়নি',
        'Edit Category': 'ক্যাটাগরি সম্পাদনা', 'Category-Unit Mapping': 'ক্যাটাগরি-একক ম্যাপিং',
        'Default Unit': 'ডিফল্ট একক', 'No mappings found': 'কোনো ম্যাপিং পাওয়া যায়নি',
        'Customer Types': 'গ্রাহকের ধরন', 'Add Customer Type': 'গ্রাহকের ধরন যোগ',
        'Type Name': 'ধরনের নাম', 'No Customer Types': 'কোনো গ্রাহকের ধরন নেই',
        'Edit Customer Type': 'গ্রাহকের ধরন সম্পাদনা', 'Display Order': 'প্রদর্শন ক্রম',
        'Business Settings': 'ব্যবসায়িক সেটিংস', 'Company Information': 'কোম্পানির তথ্য',
        'System Settings': 'সিস্টেম সেটিংস', 'Company Name': 'কোম্পানির নাম',
        'Site Name': 'সাইটের নাম', 'Phone Number': 'ফোন নম্বর',
        'Currency Symbol': 'মুদ্রা প্রতীক', 'Default Tax Rate (%)': 'ডিফল্ট করের হার (%)',
        'Low Stock Alert Threshold': 'কম স্টক সতর্কতা সীমা',
        'Show Barcode Field in Product': 'পণ্যে বারকোড ফিল্ড দেখান',
        'Summary Cards': 'সারাংশ কার্ড', 'Add New Summary Card': 'নতুন সারাংশ কার্ড যোগ',
        'Card Title': 'কার্ডের শিরোনাম', 'Card Type': 'কার্ডের ধরন',
        'Color': 'রঙ', 'Icon Class': 'আইকন ক্লাস',
        'Custom SQL Query': 'কাস্টম এসকিউএল কোয়েরি', 'Custom Query': 'কাস্টম কোয়েরি',
        'Preview': 'প্রিভিউ', 'Edit Summary Card': 'সারাংশ কার্ড সম্পাদনা',
        'Uploads Manager': 'আপলোড ম্যানেজার', 'No files found': 'কোনো ফাইল পাওয়া যায়নি',
        'Database Management': 'ডাটাবেস ম্যানেজমেন্ট', 'Database Tables': 'ডাটাবেস টেবিল',
        'Reports Export': 'রিপোর্ট রপ্তানি', 'Monthly Sales Report': 'মাসিক বিক্রয় রিপোর্ট',
        'Product Sales Report': 'পণ্য বিক্রয় রিপোর্ট', 'Custom Date Range': 'কাস্টম তারিখ রেঞ্জ',
        'Customer Data': 'গ্রাহক ডেটা', 'Table Name': 'টেবিলের নাম',
        'Activity Logs': 'কার্যকলাপ লগ', 'All Activities': 'সকল কার্যকলাপ',
        'Module': 'মডিউল', 'Date & Time': 'তারিখ ও সময়', 'IP Address': 'আইপি ঠিকানা',
        'All Users': 'সকল ব্যবহারকারী', 'All Actions': 'সকল কার্যক্রম',
        'All Modules': 'সকল মডিউল', 'From': 'থেকে', 'To': 'পর্যন্ত',
        'No activity logs found': 'কোনো কার্যকলাপ লগ পাওয়া যায়নি',
        'Product Import/Export': 'পণ্য ইম্পোর্ট/এক্সপোর্ট', 'Import Products': 'পণ্য ইম্পোর্ট',
        'Export Products': 'পণ্য এক্সপোর্ট', 'Download Sample Template': 'নমুনা টেমপ্লেট ডাউনলোড',
        'CSV Format Guide': 'সিএসভি ফরম্যাট গাইড', 'Invoice #': 'চালান #',
        'Cashier': 'ক্যাশিয়ার', 'SL': 'নং', 'Invoice No': 'চালান নং',
        'From': 'থেকে', 'Credit': 'ক্রেডিট', 'Debit': 'ডেবিট',
        'Cost of Goods': 'পণ্যের খরচ', 'Net Loss': 'নিট ক্ষতি',
        'Profit and loss analysis': 'মুনাফা ও ক্ষতি বিশ্লেষণ',
        'No sales data found for this period': 'এই সময়ের জন্য কোনো বিক্রয় তথ্য পাওয়া যায়নি',
        'No product sales for this period': 'এই সময়ের জন্য কোনো পণ্য বিক্রয় নেই',
        'No product sales found for this period': 'এই সময়ের জন্য কোনো পণ্য বিক্রয় পাওয়া যায়নি',
        'Sales Details': 'বিক্রয়ের বিবরণ', 'Payment': 'পেমেন্ট',
        'Total Products': 'মোট পণ্য', 'Low Stock Items': 'কম স্টক আইটেম',
        'Total Stock Value': 'মোট স্টক মূল্য', 'Current Stock': 'বর্তমান স্টক',
        'Critical': 'সমালোচনামূলক', 'Last updated': 'সর্বশেষ আপডেট',
        'Back to Reports': 'রিপোর্টে ফিরুন', 'No sales found for this date': 'এই তারিখে কোনো বিক্রয় পাওয়া যায়নি',
        'No sales found for this month': 'এই মাসে কোনো বিক্রয় পাওয়া যায়নি',
        'All Products Stock': 'সকল পণ্যের স্টক', 'Qty': 'পরিমাণ',
        'Product Sales Summary': 'পণ্য বিক্রয় সারাংশ',
        'Company': 'কোম্পানি', 'No suppliers found': 'কোনো সরবরাহকারী পাওয়া যায়নি',
        'Add Supplier': 'সরবরাহকারী যোগ',
        'Success!': 'সফল!', 'Operation completed successfully.': 'অপারেশন সফলভাবে সম্পন্ন হয়েছে।',
        'User Profile': 'ব্যবহারকারী প্রোফাইল', 'Account Settings': 'অ্যাকাউন্ট সেটিংস',
        'Change Password': 'পাসওয়ার্ড পরিবর্তন', 'Full Name': 'পুরো নাম',
        'Username': 'ব্যবহারকারী নাম', 'Password': 'পাসওয়ার্ড',
        'Remember me': 'আমাকে মনে রাখুন', 'Forgot password?': 'পাসওয়ার্ড ভুলে গেছেন?',
        'Login': 'লগইন', 'Sign In': 'সাইন ইন',
        'Company Name / Address / Phone': 'কোম্পানির নাম/ঠিকানা/ফোন',
        'Item Description': 'আইটেমের বিবরণ', 'Show Company Logo': 'কোম্পানির লোগো দেখান',
        'Show Barcode': 'বারকোড দেখান', 'Show QR Code': 'কিউআর কোড দেখান',
        'Show Terms & Conditions': 'শর্তাবলী দেখান', 'Header Text': 'হেডার টেক্সট',
        'Footer Text': 'ফুটার টেক্সট', 'Terms & Conditions': 'শর্তাবলী',
        'Cashier Signature': 'ক্যাশিয়ারের স্বাক্ষর', 'Customer Signature': 'গ্রাহকের স্বাক্ষর',
        'Basic Settings': 'মৌলিক সেটিংস', 'Display Options': 'প্রদর্শন অপশন',
        'Content Settings': 'কন্টেন্ট সেটিংস', 'Live Preview': 'লাইভ প্রিভিউ',
        'Print Size': 'প্রিন্ট সাইজ', 'General Invoice': 'সাধারণ চালান',
        'POS (80mm)': 'পস (৮০মিমি)', 'Thermal (58mm)': 'থার্মাল (৫৮মিমি)',
        'Custom HTML': 'কাস্টম এইচটিএমএল',
        'Bkash': 'বিকাশ', 'Nagad': 'নগদ', 'Rocket': 'রকেট', 'Upay': 'উপায়',
        'Mobile Type': 'মোবাইলের ধরন', 'Transaction ID': 'লেনদেন আইডি',
        'No payment history': 'কোনো পেমেন্ট ইতিহাস নেই', 'No activities': 'কোনো কার্যকলাপ নেই',
        'Total Paid': 'মোট প্রদত্ত', 'Total Due': 'মোট বাকি',
        'Sales Details': 'বিক্রয়ের বিবরণ', 'Change': 'পরিবর্তন',
        'Invoice Info': 'চালান তথ্য', 'Item': 'আইটেম',
        'INVOICE': 'চালান', 'Bill To': 'প্রাপক',
        'Add Customer': 'গ্রাহক যোগ', 'Customer': 'গ্রাহক',
        'Products': 'পণ্য সমূহ', 'Total:': 'মোট:',
        'Paid:': 'পেয়েছে:', 'Due:': 'বাকি:',
        'Change:': 'পরিবর্তন:', 'Receive Amount': 'গ্রহণের পরিমাণ',
        'Subtotal': 'সাবটোটাল', 'Discount': 'ডিসকাউন্ট',
        'VAT': 'ভ্যাট', 'Invoice:': 'চালান:',
        'Customer Type': 'গ্রাহকের ধরন', 'Mobile': 'মোবাইল',
        'Search by name or barcode...': 'নাম বা বারকোড দিয়ে অনুসন্ধান...',
        'Search customer...': 'গ্রাহক অনুসন্ধান...',
        'All Categories': 'সব ক্যাটাগরি', 'Walk-in Customer': 'ওয়াক-ইন গ্রাহক',
        'Select Category': 'ক্যাটাগরি নির্বাচন', 'Select Unit': 'একক নির্বাচন',
        'Enter product name': 'পণ্যের নাম লিখুন', 'Enter category name': 'ক্যাটাগরির নাম লিখুন',
        'e.g., Piece, Kg, Box': 'যেমন: পিস, কেজি, বক্স',
        'Product description (optional)': 'পণ্যের বিবরণ (ঐচ্ছিক)',
        'Barcode or SKU': 'বারকোড বা এসকেইউ',
        'Enter your username': 'আপনার ব্যবহারকারী নাম লিখুন',
        'Enter your password': 'আপনার পাসওয়ার্ড লিখুন',
        'New Password (leave blank to keep current)': 'নতুন পাসওয়ার্ড (বর্তমান রাখতে ফাঁকা রাখুন)',
        'Enter new password': 'নতুন পাসওয়ার্ড লিখুন',
        'Search by name, phone or address...': 'নাম, ফোন বা ঠিকানা দিয়ে অনুসন্ধান...',
        'Search...': 'অনুসন্ধান...',
        'Search invoice number...': 'চালান নম্বর অনুসন্ধান...',
        'Search products...': 'পণ্য অনুসন্ধান...',
        'Enter expense description': 'খরচের বিবরণ লিখুন',
        'Enter reference number': 'রেফারেন্স নম্বর লিখুন',
        'Select Employee': 'কর্মী নির্বাচন করুন',
        'Select Supplier': 'সরবরাহকারী নির্বাচন',
        '-- Select Supplier --': '-- সরবরাহকারী নির্বাচন --',
        '-- Select Product --': '-- পণ্য নির্বাচন --',
        '-- Select Role --': '-- রোল নির্বাচন --',
        'Select Purchase': 'ক্রয় নির্বাচন',
        'Note (Optional)': 'নোট (ঐচ্ছিক)',
        'Add a note...': 'একটি নোট যোগ করুন...',
        'Optional': 'ঐচ্ছিক',
        'Description': 'বিবরণ',
        'e.g., Main Cash, Bank Account': 'যেমন: প্রধান নগদ, ব্যাংক অ্যাকাউন্ট',
        'e.g., ACC-001': 'যেমন: একেসি-০০১',
        'Enter type name': 'ধরনের নাম লিখুন',
        'Brief description of this customer type': 'এই গ্রাহকের ধরনের সংক্ষিপ্ত বিবরণ',
        'e.g., Total Sales': 'যেমন: মোট বিক্রয়',
        'SELECT COUNT(*) as count FROM table_name': 'সিলেক্ট কাউন্ট(*) আস কাউন্ট ফ্রম টেবিল_নাম',
        'https://example.com/logo.png': 'https://example.com/logo.png',
        'S, M, L, XL': 'এস, এম, এল, এক্সএল',
        '0.00': '০.০০',
        '10': '১০',
        'My Custom Template': 'আমার কাস্টম টেমপ্লেট',
        'INV': 'ইনভ', '2026': '২০২৬',
        'Template Type': 'টেমপ্লেটের ধরন',
        'Color Scheme': 'রঙের স্কিম',
        'No numbering prefixes configured yet.': 'এখনো কোনো নম্বরকরণ প্রিফিক্স কনফিগার করা হয়নি।',
        'No purchase history found': 'কোনো ক্রয় ইতিহাস পাওয়া যায়নি',
        'No payment history found': 'কোনো পেমেন্ট ইতিহাস পাওয়া যায়নি',
        'No sales found for this period': 'এই সময়ের জন্য কোনো বিক্রয় পাওয়া যায়নি'
    };

    document.querySelectorAll('.sidebar-menu a span, .user-menu span, .lang-btn span, .brand-text, .brand span').forEach(el => {
        const text = el.textContent?.trim();
        if (text && dict[text]) {
            el.textContent = dict[text];
        }
    });

    document.querySelectorAll('.sidebar-menu a').forEach(el => {
        const text = el.textContent?.trim();
        if (text === 'Sumon Enterprise') el.textContent = 'সুমন এন্টারপ্রাইজ';
    });

    document.querySelectorAll('table tbody tr').forEach(row => {
        row.querySelectorAll('td').forEach(td => {
            if (td.classList.contains('num') || td.classList.contains('amount') || td.classList.contains('price')) {
                const num = parseFloat(td.textContent?.replace(/[^0-9.-]/g, ''));
                if (!isNaN(num)) {
                    td.textContent = toBengaliNum(num.toLocaleString('en-IN'));
                }
            }
        });
    });

    document.querySelectorAll('.card-value, .stat-value, .total-amount, .grand-total, .summary-value').forEach(el => {
        const num = parseFloat(el.textContent?.replace(/[^0-9.-]/g, ''));
        if (!isNaN(num)) {
            el.textContent = toBengaliNum(num.toLocaleString('en-IN'));
        }
    });

    const langBtn = document.getElementById('langBtnText');
    if (langBtn) langBtn.textContent = currentLang === 'bn' ? 'বাংলা' : 'English';
}

function setLanguage(lang) {
    currentLang = lang;
    localStorage.setItem('language', lang);
    const langBtn = document.getElementById('langBtnText');
    if (langBtn) langBtn.textContent = lang === 'bn' ? 'বাংলা' : 'English';
    translatePage();
    const dd = document.getElementById('langDropdown');
    if (dd) dd.style.display = 'none';
}

function initTheme() {
    if (isDark) document.body.classList.add('dark-mode');
    const iconEl = document.getElementById('themeIcon');
    if (iconEl) iconEl.className = isDark ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
}

function toggleTheme() {
    isDark = !isDark;
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', isDark);
    const iconEl = document.getElementById('themeIcon');
    if (iconEl) iconEl.className = isDark ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
}

function toggleUserMenu() {
    const menuEl = document.getElementById('userMenu');
    if (menuEl) menuEl.style.display = menuEl.style.display === 'block' ? 'none' : 'block';
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.user-dropdown')) {
        const menuEl = document.getElementById('userMenu');
        if (menuEl) menuEl.style.display = 'none';
    }
});

let sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
        e.preventDefault();
        toggleSidebar();
    }
});

function setSidebar(collapsed) {
    if (collapsed) {
        document.body.classList.add('sidebar-collapsed');
    } else {
        document.body.classList.remove('sidebar-collapsed');
    }
    localStorage.setItem('sidebarCollapsed', collapsed);
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const icon = toggleBtn ? toggleBtn.querySelector('i') : null;
    if (window.innerWidth <= 992) {
        const isOpen = sidebar.classList.toggle('open');
        document.body.classList.remove('sidebar-collapsed');
        localStorage.setItem('mobileSidebarOpen', isOpen);
        const overlay = document.getElementById('sidebarOverlay');
        if (overlay) overlay.classList.toggle('open');
        if (icon) icon.className = isOpen ? 'bi bi-x-lg' : 'bi bi-list';
    } else {
        sidebarCollapsed = !sidebarCollapsed;
        setSidebar(sidebarCollapsed);
    }
}

function closeMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.remove('open');
    if (overlay) overlay.classList.remove('open');
    localStorage.setItem('mobileSidebarOpen', 'false');
    const toggleBtn = document.getElementById('sidebarToggle');
    const icon = toggleBtn ? toggleBtn.querySelector('i') : null;
    if (icon) icon.className = 'bi bi-list';
}

function initSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (window.innerWidth > 992 && sidebarCollapsed) {
        setSidebar(true);
    }
    if (window.innerWidth <= 992 && localStorage.getItem('mobileSidebarOpen') === 'true') {
        sidebar.classList.add('open');
        if (overlay) overlay.classList.add('open');
    }

    const toggleBtn = document.getElementById('sidebarToggle');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', toggleSidebar);
    }
    const mobileClose = document.getElementById('mobileSidebarClose');
    if (mobileClose) {
        mobileClose.addEventListener('click', closeMobileSidebar);
    }
    if (overlay) {
        overlay.addEventListener('click', closeMobileSidebar);
    }
    document.querySelectorAll('.sidebar-menu .has-submenu > a').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            this.parentElement.classList.toggle('open');
        });
    });
    document.querySelectorAll('.sidebar-menu .has-submenu.open').forEach(function(item) {
        const sub = item.querySelector('.dropdown-menu');
        if (sub) sub.style.display = 'block';
    });

    // On mobile: close sidebar when any nav link is clicked (except parent toggles)
    if (window.innerWidth <= 992) {
        document.querySelectorAll('.sidebar-menu a').forEach(function(link) {
            link.addEventListener('click', function(e) {
                const parent = this.parentElement;
                if (parent.classList.contains('has-submenu') && this.nextElementSibling) {
                    return;
                }
                closeMobileSidebar();
            });
        });
    }
}

<?php if (file_exists(__DIR__ . '/../../../dev-reload.php')): ?>
// Live reload for development
(function() {
    var poll = function() {
        fetch(BASE_URL + '/dev-reload.php').then(function(r) { return r.json(); }).then(function(d) {
            if (d.reload) location.reload();
        }).catch(function(){});
    };
    setInterval(poll, 30000);
})();
<?php endif; ?>
</script>
</body>
</html>

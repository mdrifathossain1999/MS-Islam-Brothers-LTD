<?php 
$pageTitle = 'Uploads Manager';
$currentPage = 'admin';
$subPage = 'uploads';
ob_start();
?>

<style>
.uploads-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 14px; }
.upload-card { background: var(--bg-card); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-md); transition: all 0.3s; }
.upload-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
.upload-preview { height: 100px; display: flex; align-items: center; justify-content: center; background: var(--bg-surface-alt); overflow: hidden; }
.upload-preview img { width: 100%; height: 100%; object-fit: cover; }
.upload-preview .file-icon { font-size: 2.5rem; color: var(--border); }
.upload-info { padding: 10px; }
.upload-name { font-size: 12px; font-weight: 500; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.upload-meta { font-size: 10px; color: var(--text-muted); margin-top: 4px; }
.upload-actions { padding: 8px 10px; border-top: 1px solid var(--bg-surface-alt); display: flex; gap: 6px; }
.folder-card { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; }
.folder-card .upload-preview { background: transparent; }
.folder-card .file-icon { color: white; }
.stat-card { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; padding: 14px; border-radius: 12px; }
.stat-card.green { background: linear-gradient(135deg, var(--success), #059669); }
.stat-card.orange { background: linear-gradient(135deg, var(--warning), #d97706); }
.stat-card.blue { background: linear-gradient(135deg, var(--info), #0284c7); }
.btn-sm { padding: 4px 8px; font-size: 10px; border-radius: 4px; text-decoration: none; }
.btn-danger { background: var(--danger); color: white; }
.image-modal {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.9);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}
.image-modal.active { display: flex; }
.image-modal img {
    max-width: 90%;
    max-height: 90%;
    border-radius: 8px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.5);
}
.image-modal .close-btn {
    position: absolute;
    top: 20px; right: 30px;
    font-size: 40px;
    color: white;
    cursor: pointer;
    background: none;
    border: none;
}
.image-modal .filename {
    position: absolute;
    bottom: 20px; left: 50%;
    transform: translateX(-50%);
    color: white;
    font-size: 14px;
    font-weight: 500;
}
</style>

<div class="page-header">
    <h1><i class="bi bi-cloud-upload"></i> Uploads Manager</h1>
    <a href="<?php echo BASE_URL; ?>/admin" class="back-btn"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div style="background:var(--success);color:#fff;border:1px solid var(--success);padding:12px 16px;border-radius:var(--radius-sm);margin-bottom:20px;">
    <i class="bi bi-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
</div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
<div style="background:var(--danger);color:#fff;border:1px solid var(--danger);padding:12px 16px;border-radius:var(--radius-sm);margin-bottom:20px;">
    <i class="bi bi-x-circle me-2"></i><?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
</div>
<?php endif; ?>

<?php if (empty($files)): ?>
<div class="alert alert-info">
    <i class="bi bi-folder2-open me-2"></i>No files found in uploads directory.
</div>
<?php else: ?>

<!-- Summary Cards -->
<div class="row mb-4">
    <?php
    $imageCount = 0;
    $otherCount = 0;
    $totalSize = 0;
    foreach ($files as $f) {
        if ($f['isImage']) $imageCount++; else $otherCount++;
        $totalSize += $f['size'];
    }
    ?>
    <div class="col-md-3">
        <div class="stat-card">
            <h3><?php echo count($files); ?></h3>
            <p><i class="bi bi-file-earmark me-1"></i>Total Files</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card green">
            <h3><?php echo $imageCount; ?></h3>
            <p><i class="bi bi-image me-1"></i>Images</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card orange">
            <h3><?php echo $otherCount; ?></h3>
            <p><i class="bi bi-file-earmark-text me-1"></i>Other Files</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card blue">
            <h3><?php echo number_format($totalSize / 1024, 1); ?> KB</h3>
            <p><i class="bi bi-hdd me-1"></i>Total Size</p>
        </div>
    </div>
</div>

<div class="uploads-grid">
    <?php 
    usort($files, function($a, $b) {
        return $b['modified'] - $a['modified'];
    });
    
    foreach ($files as $file): 
    ?>
    <div class="upload-card">
        <div class="upload-preview">
            <?php if ($file['isImage']): ?>
                <img src="<?php echo $file['url']; ?>" alt="<?php echo htmlspecialchars($file['name']); ?>" loading="lazy" onclick="openImageModal('<?php echo $file['url']; ?>', '<?php echo htmlspecialchars($file['name']); ?>')" style="cursor:pointer;">
            <?php else: ?>
                <i class="bi bi-file-earmark file-icon"></i>
            <?php endif; ?>
        </div>
        <div class="upload-info">
            <div class="upload-name" title="<?php echo htmlspecialchars($file['name']); ?>">
                <?php echo htmlspecialchars($file['name']); ?>
            </div>
            <div class="upload-meta">
                <?php echo number_format($file['size'] / 1024, 1); ?> KB &bull; <?php echo date('M d, Y', $file['modified']); ?>
            </div>
        </div>
        <div class="upload-actions">
            <?php if ($file['isImage']): ?>
            <a href="<?php echo $file['url']; ?>" target="_blank" class="btn btn-sm btn-outline-primary flex-grow-1">
                <i class="bi bi-eye"></i> View
            </a>
            <?php endif; ?>
            <form method="POST" action="<?php echo $baseUrl; ?>/admin/deleteUpload" style="display:inline;" onsubmit="return confirm('Delete this file?')">
                <input type="hidden" name="path" value="<?php echo $file['path']; ?>">
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php endif; ?>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
?>

<div class="image-modal" id="imageModal" onclick="this.classList.remove('active')">
    <button class="close-btn">&times;</button>
    <img src="" alt="" id="modalImage">
    <div class="filename" id="modalFilename"></div>
</div>

<script>
function openImageModal(url, filename) {
    document.getElementById('modalImage').src = url;
    document.getElementById('modalFilename').textContent = filename;
    document.getElementById('imageModal').classList.add('active');
}
</script>

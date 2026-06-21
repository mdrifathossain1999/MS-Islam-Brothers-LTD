<?php 
$pageTitle = 'Product Import/Export';
$currentPage = 'admin';
$subPage = 'productImportExport';
ob_start();
?>

<style>
.ie-card { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 24px; }
.ie-section { margin-bottom: 32px; }
.ie-section h4 { font-size: 16px; font-weight: 600; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
.ie-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
.ie-icon.green { background: linear-gradient(135deg, #22c55e, #16a34a); color: #fff; }
.ie-icon.blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: #fff; }
.ie-icon.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: #fff; }
.file-upload { 
    border: 2px dashed #e2e8f0; 
    border-radius: 12px; 
    padding: 40px 20px; 
    text-align: center; 
    transition: all 0.3s;
    cursor: pointer;
}
.file-upload:hover { border-color: #667eea; background: #f8fafc; }
.file-upload.dragover { border-color: #667eea; background: #eef2ff; }
.file-upload i { font-size: 48px; color: #94a3b8; }
.file-upload p { margin: 12px 0 0; color: #64748b; font-size: 14px; }
.file-upload input[type="file"] { display: none; }
.info-box { 
    background: #f0f9ff; 
    border: 1px solid #bae6fd; 
    border-radius: 8px; 
    padding: 16px; 
    margin-bottom: 20px; 
}
.info-box p { margin: 0; font-size: 14px; color: #0369a1; }
.info-box ul { margin: 8px 0 0 20px; font-size: 13px; }
.table-preview { max-height: 300px; overflow: auto; border: 1px solid #e2e8f0; border-radius: 8px; margin-top: 16px; }
.table-preview table { margin: 0; font-size: 12px; }
.table-preview th { background: #f8fafc; position: sticky; top: 0; }
.progress-bar-custom { height: 8px; border-radius: 4px; background: #e2e8f0; overflow: hidden; }
.progress-bar-custom .progress { height: 100%; background: linear-gradient(90deg, #667eea, #764ba2); transition: width 0.3s; }
</style>

<div class="page-header">
    <h1><i class="bi bi-file-earmark-spreadsheet"></i> Product Import/Export</h1>
    <a href="<?php echo BASE_URL; ?>/admin" class="back-btn"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="ie-card">

    <div class="row">
        <!-- Export Section -->
        <div class="col-md-6">
            <div class="ie-section">
                <h4><i class="bi bi-download text-success"></i> Export Products</h4>
                
                <div class="info-box">
                    <p><i class="bi bi-info-circle me-2"></i><strong>Export</strong> all your products to a CSV file. You can open this file in Excel or any spreadsheet application.</p>
                    <ul>
                        <li>Includes: Name, Barcode, Category, Price, Stock, etc.</li>
                        <li>All active products are exported</li>
                    </ul>
                </div>
                
                <div class="d-flex gap-3">
                    <a href="<?php echo BASE_URL; ?>/admin/exportProducts" class="btn btn-success">
                        <i class="bi bi-download me-2"></i>Download CSV
                    </a>
                </div>
            </div>

            <!-- Sample Download -->
            <div class="ie-section">
                <h4><i class="bi bi-file-earmark-arrow-down text-primary"></i> Download Sample Template</h4>
                
                <div class="info-box">
                    <p><i class="bi bi-info-circle me-2"></i>Download a sample CSV file with example products to see the correct format.</p>
                </div>
                
                <form action="<?php echo BASE_URL; ?>/admin/downloadSample" method="POST">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-file-earmark-spreadsheet me-2"></i>Download Sample CSV
                    </button>
                </form>
            </div>
        </div>

        <!-- Import Section -->
        <div class="col-md-6">
            <div class="ie-section">
                <h4><i class="bi bi-upload text-warning"></i> Import Products</h4>
                
                <div class="info-box">
                    <p><i class="bi bi-info-circle me-2"></i><strong>Import</strong> products from a CSV file. Products are matched by Barcode or Product Name.</p>
                    <ul>
                        <li>New products will be created</li>
                        <li>Existing products (by barcode/name) will be updated</li>
                        <li>Supports CSV and Excel files (.csv, .xlsx, .xls)</li>
                    </ul>
                </div>
                
                <form action="<?php echo BASE_URL; ?>/admin/importProducts" method="POST" enctype="multipart/form-data" id="importForm">
                    <div class="file-upload" id="fileDropZone">
                        <i class="bi bi-cloud-arrow-up"></i>
                        <p id="fileLabel">Drag & drop your CSV file here<br><small>or click to browse</small></p>
                        <input type="file" name="import_file" id="importFile" accept=".csv,.xlsx,.xls">
                    </div>
                    
                    <div id="fileInfo" class="mt-3" style="display: none;">
                        <div class="d-flex align-items-center gap-3 p-3 bg-light rounded">
                            <i class="bi bi-file-earmark-text text-primary" style="font-size: 2rem;"></i>
                            <div class="flex-grow-1">
                                <strong id="selectedFileName">filename.csv</strong>
                                <small class="d-block text-muted" id="selectedFileSize">0 KB</small>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="removeFile">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-warning w-100" id="importBtn" disabled>
                            <i class="bi bi-upload me-2"></i>Import Products
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Format Guide -->
    <div class="ie-section">
        <h4><i class="bi bi-question-circle text-info"></i> CSV Format Guide</h4>
        
        <div class="table-responsive table-preview">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Product Name *</th>
                        <th>Barcode</th>
                        <th>Category</th>
                        <th>Unit</th>
                        <th>Cost Price *</th>
                        <th>Sell Price *</th>
                        <th>Stock Qty</th>
                        <th>Low Stock</th>
                        <th>Description</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Premium Rice 5kg</td>
                        <td>8901234567890</td>
                        <td>Rice</td>
                        <td>bag</td>
                        <td>350</td>
                        <td>450</td>
                        <td>50</td>
                        <td>10</td>
                        <td>Premium quality rice</td>
                        <td><span class="badge bg-success">active</span></td>
                    </tr>
                    <tr>
                        <td>Sugar 1kg</td>
                        <td>8901234567891</td>
                        <td>Sugar</td>
                        <td>kg</td>
                        <td>85</td>
                        <td>100</td>
                        <td>100</td>
                        <td>20</td>
                        <td>White crystal sugar</td>
                        <td><span class="badge bg-success">active</span></td>
                    </tr>
                    <tr>
                        <td>Cooking Oil 5L</td>
                        <td>8901234567892</td>
                        <td>Oil</td>
                        <td>bottle</td>
                        <td>550</td>
                        <td>650</td>
                        <td>30</td>
                        <td>5</td>
                        <td>Pure mustard oil</td>
                        <td><span class="badge bg-success">active</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="alert alert-info mt-3">
            <i class="bi bi-lightbulb me-2"></i>
            <strong>Tips:</strong>
            <ul class="mb-0 mt-2">
                <li>Fields marked with * are required</li>
                <li>If barcode matches, product will be updated</li>
                <li>If name matches, product will be updated</li>
                <li>For new products, leave barcode empty or use unique value</li>
                <li>Status can be "active" or "inactive"</li>
            </ul>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('fileDropZone');
    const fileInput = document.getElementById('importFile');
    const fileInfo = document.getElementById('fileInfo');
    const fileLabel = document.getElementById('fileLabel');
    const selectedFileName = document.getElementById('selectedFileName');
    const selectedFileSize = document.getElementById('selectedFileSize');
    const importBtn = document.getElementById('importBtn');
    const removeFile = document.getElementById('removeFile');

    dropZone.addEventListener('click', function() {
        fileInput.click();
    });

    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', function() {
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            handleFileSelect(e.dataTransfer.files[0]);
        }
    });

    fileInput.addEventListener('change', function() {
        if (this.files.length) {
            handleFileSelect(this.files[0]);
        }
    });

    removeFile.addEventListener('click', function() {
        fileInput.value = '';
        fileInfo.style.display = 'none';
        dropZone.style.display = 'block';
        importBtn.disabled = true;
        fileLabel.innerHTML = 'Drag & drop your CSV file here<br><small>or click to browse</small>';
    });

    function handleFileSelect(file) {
        const validTypes = ['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        const ext = file.name.split('.').pop().toLowerCase();
        
        if (!['csv', 'xlsx', 'xls'].includes(ext)) {
            alert('Please upload a CSV or Excel file!');
            return;
        }

        selectedFileName.textContent = file.name;
        selectedFileSize.textContent = formatFileSize(file.size);
        
        dropZone.style.display = 'none';
        fileInfo.style.display = 'flex';
        importBtn.disabled = false;
    }

    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }
});
</script>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';

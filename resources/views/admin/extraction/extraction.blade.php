@extends('layouts.app')

@section('content')
<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1><i class="fas fa-download"></i> Data Extraction</h1>
                    <p class="text-muted">Export comprehensive data from your database with all 70+ fields</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card extraction-card">
                    <div class="card-header">
                        <h5><i class="fas fa-database"></i> Database Tables</h5>
                        <p class="mb-0">Select data sources for export</p>
                    </div>
                    <div class="card-body">
                        <div class="table-grid">
                            <div class="table-item">
                                <div class="table-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="table-info">
                                    <h6>CIF</h6>
                                    <p>Customer Information</p>
                                    <span class="record-count">{{ $cifCount ?? 'N/A' }} records</span>
                                </div>
                            </div>

                            <div class="table-item">
                                <div class="table-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="table-info">
                                    <h6>LNACC</h6>
                                    <p>Loan Accounts</p>
                                    <span class="record-count">{{ $lnaccCount ?? 'N/A' }} records</span>
                                </div>
                            </div>

                            <div class="table-item">
                                <div class="table-icon">
                                    <i class="fas fa-link"></i>
                                </div>
                                <div class="table-info">
                                    <h6>RELACC</h6>
                                    <p>Related Accounts</p>
                                    <span class="record-count">{{ $relaccCount ?? 'N/A' }} records</span>
                                </div>
                            </div>

                            <div class="table-item">
                                <div class="table-icon">
                                    <i class="fas fa-history"></i>
                                </div>
                                <div class="table-info">
                                    <h6>TRNHIST</h6>
                                    <p>Transaction History</p>
                                    <span class="record-count">{{ $trnhistCount ?? 'N/A' }} records</span>
                                </div>
                            </div>

                            <div class="table-item">
                                <div class="table-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="table-info">
                                    <h6>BRPARMS</h6>
                                    <p>Branch Parameters</p>
                                    <span class="record-count">{{ $brparmsCount ?? 'N/A' }} records</span>
                                </div>
                            </div>

                            <div class="table-item">
                                <div class="table-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <div class="table-info">
                                    <h6>USERLOOKUP</h6>
                                    <p>User Lookup</p>
                                    <span class="record-count">{{ $userlookupCount ?? 'N/A' }} records</span>
                                </div>
                            </div>

                            <div class="table-item">
                                <div class="table-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="table-info">
                                    <h6>LNHIST</h6>
                                    <p>Loan History</p>
                                    <span class="record-count">{{ $lnhistCount ?? 'N/A' }} records</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card export-card">
                    <div class="card-header">
                        <h5><i class="fas fa-file-excel"></i> Export Options</h5>
                    </div>
                    <div class="card-body">
                        <div class="export-info">
                            <div class="info-item">
                                <i class="fas fa-list"></i>
                                <span>70+ Fields Included</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-file"></i>
                                <span>Single Excel File</span>
                            </div>
                           
                            <div class="info-item">
                                <i class="fas fa-expand-arrows-alt"></i>
                                <span>Auto-sized Columns</span>
                            </div>
                        </div>

                        <form action="{{ route('extraction.export') }}" method="POST" class="export-form" id="exportForm">
                            @csrf
                            <button type="button" class="btn btn-primary btn-export" onclick="confirmExport()">
                                <i class="fas fa-download"></i>
                                <span>Export to Excel</span>
                                <small>Process all records</small>
                            </button>
                        </form>

                        <div class="export-note">
                            <i class="fas fa-info-circle"></i>
                            <p>Export includes comprehensive data from all tables with unlimited processing time.</p>
                        </div>
                    </div>
                </div>

                <div class="card status-card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-bar"></i> Export Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="status-item">
                            <div class="status-icon success">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="status-content">
                                <h6>Database Connected</h6>
                                <p>Ready for export</p>
                            </div>
                        </div>

                        <div class="status-item">
                            <div class="status-icon info">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="status-content">
                                <h6>Processing Time</h6>
                                <p>Unlimited execution</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-header {
    margin-bottom: 30px;
}

.page-header h1 {
    color: #2c3e50;
    margin-bottom: 5px;
    font-weight: 600;
}

.page-header p {
    color: #6c757d;
    margin: 0;
}

.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 12px;
    margin-bottom: 20px;
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 20px;
    border-radius: 12px 12px 0 0;
}

.card-header h5 {
    margin: 0 0 5px 0;
    color: #495057;
    font-weight: 600;
}

.card-header p {
    margin: 0;
    color: #6c757d;
    font-size: 14px;
}

.card-header i {
    margin-right: 8px;
    color: #3498db;
}

.card-body {
    padding: 20px;
}

.table-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.table-item {
    display: flex;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.table-item:hover {
    background: #e3f2fd;
    border-color: #3498db;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.2);
}

.table-icon {
    width: 40px;
    height: 40px;
    background: #3498db;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

.table-icon i {
    color: white;
    font-size: 18px;
}

.table-info h6 {
    margin: 0 0 2px 0;
    color: #2c3e50;
    font-weight: 600;
    font-size: 14px;
}

.table-info p {
    margin: 0 0 5px 0;
    color: #6c757d;
    font-size: 12px;
}

.record-count {
    color: #3498db;
    font-weight: 600;
    font-size: 12px;
}

.export-card {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    color: white;
}

.export-card .card-header {
    background: rgba(255, 255, 255, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.export-card .card-header h5 {
    color: white;
}

.export-card .card-header p {
    color: rgba(255, 255, 255, 0.8);
}

.export-info {
    margin-bottom: 20px;
}

.info-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    color: rgba(255, 255, 255, 0.9);
}

.info-item i {
    margin-right: 10px;
    width: 16px;
    text-align: center;
}

.export-form {
    margin-bottom: 20px;
}

.btn-export {
    width: 100%;
    padding: 15px;
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
}

.btn-export:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.btn-export i {
    font-size: 20px;
}

.btn-export span {
    font-size: 16px;
}

.btn-export small {
    font-size: 12px;
    opacity: 0.8;
}

.export-note {
    background: rgba(255, 255, 255, 0.1);
    padding: 15px;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.export-note i {
    margin-right: 8px;
    color: rgba(255, 255, 255, 0.8);
}

.export-note p {
    margin: 0;
    color: rgba(255, 255, 255, 0.9);
    font-size: 14px;
}

.status-card {
    margin-top: 20px;
}

.status-item {
    display: flex;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #f1f3f4;
}

.status-item:last-child {
    border-bottom: none;
}

.status-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

.status-icon.success {
    background: #d4edda;
    color: #155724;
}

.status-icon.info {
    background: #d1ecf1;
    color: #0c5460;
}

.status-content h6 {
    margin: 0 0 2px 0;
    color: #2c3e50;
    font-size: 14px;
    font-weight: 600;
}

.status-content p {
    margin: 0;
    color: #6c757d;
    font-size: 12px;
}
</style>

<script>
function confirmExport() {
    Swal.fire({
        title: 'Export Data',
        text: 'This will export all data with all 70+ fields. This may take some time. Continue?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3498db',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Export!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading alert
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we export your data',
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit the form
            document.getElementById('exportForm').submit();

            // Show success message after a delay (assuming download started)
            setTimeout(() => {
                Swal.fire({
                    title: 'Export Started!',
                    text: 'Your Excel file is being prepared. The download should start automatically.',
                    icon: 'success',
                    confirmButtonColor: '#3498db'
                });
            }, 2000);
        }
    });
}

// Handle success/error messages from server
@if(session('success'))
    Swal.fire({
        title: 'Success!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonColor: '#3498db'
    });
@endif

@if(session('error'))
    Swal.fire({
        title: 'Error!',
        text: '{{ session('error') }}',
        icon: 'error',
        confirmButtonColor: '#e74c3c'
    });
@endif
</script>
@endsection

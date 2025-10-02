@extends('layouts.app')
<link rel="stylesheet" href="{{asset('extraction.css')}}">
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

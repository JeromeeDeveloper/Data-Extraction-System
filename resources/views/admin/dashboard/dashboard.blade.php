@extends('layouts.app')
<link rel="stylesheet" href="{{asset('dashboard.css')}}">
@section('content')
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
                    <p class="text-muted">Welcome back! Here's an overview of your data extraction system</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card stats-card">
                            <div class="card-body">
                                <div class="stats-content">
                                    <div class="stats-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="stats-info">
                                        <h3>{{ $cifCount ?? 'N/A' }}</h3>
                                        <p>Customer Records</p>
                                        <span class="stats-badge">CIF</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card stats-card">
                            <div class="card-body">
                                <div class="stats-content">
                                    <div class="stats-icon">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <div class="stats-info">
                                        <h3>{{ $lnaccCount ?? 'N/A' }}</h3>
                                        <p>Loan Accounts</p>
                                        <span class="stats-badge">LNACC</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card stats-card">
                            <div class="card-body">
                                <div class="stats-content">
                                    <div class="stats-icon">
                                        <i class="fas fa-link"></i>
                                    </div>
                                    <div class="stats-info">
                                        <h3>{{ $relaccCount ?? 'N/A' }}</h3>
                                        <p>Related Accounts</p>
                                        <span class="stats-badge">RELACC</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card stats-card">
                            <div class="card-body">
                                <div class="stats-content">
                                    <div class="stats-icon">
                                        <i class="fas fa-history"></i>
                                    </div>
                                    <div class="stats-info">
                                        <h3>{{ $trnhistCount ?? 'N/A' }}</h3>
                                        <p>Transactions</p>
                                        <span class="stats-badge">TRNHIST</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card action-card">
                    <div class="card-header">
                        <h5><i class="fas fa-rocket"></i> Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('extraction.index') }}" class="action-btn">
                            <div class="action-icon">
                                <i class="fas fa-download"></i>
                            </div>
                            <div class="action-content">
                                <h6>Data Extraction</h6>
                                <p>Export comprehensive data with 70+ fields</p>
                            </div>
                            <div class="action-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card status-card">
                    <div class="card-header">
                        <h5><i class="fas fa-server"></i> System Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="status-item">
                            <div class="status-icon success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="status-content">
                                <h6>Database Connected</h6>
                                <p>Microbanker Database</p>
                            </div>
                        </div>

                        <div class="status-item">
                            <div class="status-icon info">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="status-content">
                                <h6>Last Updated</h6>
                                <p>{{ date('M d, Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="status-item">
                            <div class="status-icon warning">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="status-content">
                                <h6>Processing Time</h6>
                                <p>Unlimited execution</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card info-card">
                    <div class="card-header">
                        <h5><i class="fas fa-info-circle"></i> System Info</h5>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">

                            <div class="info-item">
                                <span class="info-label">Export Fields</span>
                                <span class="info-value">70+</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">File Format</span>
                                <span class="info-value">Excel</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@extends('layouts.app')

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
                                <span class="info-label">Total Tables</span>
                                <span class="info-value">7</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Export Fields</span>
                                <span class="info-value">70+</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">File Format</span>
                                <span class="info-value">Excel</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Status</span>
                                <span class="info-value success">Active</span>
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
    margin-bottom: 40px;
}

.page-header h1 {
    color: #2c3e50;
    margin-bottom: 8px;
    font-weight: 600;
    font-size: 2.5rem;
}

.page-header p {
    color: #6c757d;
    margin: 0;
    font-size: 1.1rem;
}

.page-header i {
    margin-right: 12px;
    color: #3498db;
}

.card {
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border-radius: 16px;
    margin-bottom: 25px;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    padding: 20px 25px;
    border-radius: 16px 16px 0 0;
}

.card-header h5 {
    margin: 0;
    color: #495057;
    font-weight: 600;
    font-size: 1.1rem;
}

.card-header i {
    margin-right: 10px;
    color: #3498db;
    font-size: 1.2rem;
}

.card-body {
    padding: 25px;
}

.stats-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 1px solid #e9ecef;
}

.stats-content {
    display: flex;
    align-items: center;
    gap: 20px;
}

.stats-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
}

.stats-icon i {
    color: white;
    font-size: 24px;
}

.stats-info h3 {
    margin: 0 0 5px 0;
    color: #2c3e50;
    font-weight: 700;
    font-size: 2.2rem;
}

.stats-info p {
    margin: 0 0 8px 0;
    color: #6c757d;
    font-size: 0.95rem;
    font-weight: 500;
}

.stats-badge {
    background: #e3f2fd;
    color: #3498db;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.action-card {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    color: white;
    border: none;
}

.action-card .card-header {
    background: rgba(255, 255, 255, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.action-card .card-header h5 {
    color: white;
}

.action-card .card-header i {
    color: rgba(255, 255, 255, 0.9);
}

.action-btn {
    display: flex;
    align-items: center;
    padding: 20px;
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    text-decoration: none;
    color: white;
    transition: all 0.3s ease;
    gap: 20px;
}

.action-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.4);
    transform: translateX(5px);
    color: white;
}

.action-icon {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.action-content {
    flex: 1;
}

.action-content h6 {
    margin: 0 0 5px 0;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
}

.action-content p {
    margin: 0;
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
}

.action-arrow {
    font-size: 18px;
    opacity: 0.7;
    transition: all 0.3s ease;
}

.action-btn:hover .action-arrow {
    opacity: 1;
    transform: translateX(3px);
}

.status-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
}

.status-item {
    display: flex;
    align-items: center;
    padding: 18px 0;
    border-bottom: 1px solid #f1f3f4;
}

.status-item:last-child {
    border-bottom: none;
}

.status-icon {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 18px;
}

.status-icon.success {
    background: #d4edda;
    color: #155724;
}

.status-icon.info {
    background: #d1ecf1;
    color: #0c5460;
}

.status-icon.warning {
    background: #fff3cd;
    color: #856404;
}

.status-content h6 {
    margin: 0 0 3px 0;
    color: #2c3e50;
    font-size: 0.95rem;
    font-weight: 600;
}

.status-content p {
    margin: 0;
    color: #6c757d;
    font-size: 0.85rem;
}

.info-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.info-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    text-align: center;
}

.info-label {
    color: #6c757d;
    font-size: 0.8rem;
    font-weight: 500;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    color: #2c3e50;
    font-weight: 700;
    font-size: 1.1rem;
}

.info-value.success {
    color: #28a745;
}

@media (max-width: 768px) {
    .stats-content {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }

    .action-btn {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

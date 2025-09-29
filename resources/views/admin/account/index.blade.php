@extends('layouts.app')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1><i class="fas fa-cog"></i> Account Settings</h1>
                    <p class="text-muted">Manage your account security and preferences</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> Please correct the errors below.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-envelope"></i> Email Settings</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('account.email') }}">
                            @csrf

                            <div class="form-group">
                                <label for="email" class="form-label">New Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                       id="current_password" name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Enter your current password to confirm this change.</small>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-envelope"></i> Update Email
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h5><i class="fas fa-key"></i> Password Security</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('account.password') }}">
                            @csrf

                            <div class="form-group">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                       id="current_password" name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="form-label">New Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                               id="password" name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control"
                                               id="password_confirmation" name="password_confirmation" required>
                                    </div>
                                </div>
                            </div>

                            <div class="password-requirements">
                                <h6>Password Requirements:</h6>
                                <ul>
                                    <li>At least 8 characters long</li>
                                    <li>Mix of letters, numbers, and symbols</li>
                                    <li>Not easily guessable</li>
                                </ul>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-key"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4 danger-zone">
                    <div class="card-header">
                        <h5><i class="fas fa-exclamation-triangle"></i> Danger Zone</h5>
                    </div>
                    <div class="card-body">
                        <div class="danger-item">
                            <div class="danger-content">
                                <h6>Delete Account</h6>
                                <p>Permanently delete your account and all associated data. This action cannot be undone.</p>
                            </div>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                <i class="fas fa-trash"></i> Delete Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-shield-alt"></i> Security Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="security-item">
                            <div class="security-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="security-content">
                                <h6>Email Verification</h6>
                                <span class="badge {{ $user->email_verified_at ? 'bg-success' : 'bg-warning' }}">
                                    {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                                </span>
                            </div>
                        </div>

                        <div class="security-item">
                            <div class="security-icon">
                                <i class="fas fa-key"></i>
                            </div>
                            <div class="security-content">
                                <h6>Password Strength</h6>
                                <span class="badge bg-info">Strong</span>
                            </div>
                        </div>

                        <div class="security-item">
                            <div class="security-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="security-content">
                                <h6>Last Login</h6>
                                <span>Recently</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h5><i class="fas fa-info-circle"></i> Account Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="info-item">
                            <label>Account ID:</label>
                            <span>{{ $user->id }}</span>
                        </div>

                        <div class="info-item">
                            <label>Current Email:</label>
                            <span>{{ $user->email }}</span>
                        </div>

                        <div class="info-item">
                            <label>Account Created:</label>
                            <span>{{ $user->created_at->format('M d, Y') }}</span>
                        </div>

                        <div class="info-item">
                            <label>Last Updated:</label>
                            <span>{{ $user->updated_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                <p class="text-danger"><strong>Warning:</strong> All your data will be permanently removed.</p>

                <form method="POST" action="{{ route('account.delete') }}" id="deleteAccountForm">
                    @csrf
                    <div class="form-group">
                        <label for="password" class="form-label">Enter your password to confirm:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="deleteAccountForm" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete Account
                </button>
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

.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 8px;
    margin-bottom: 20px;
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 15px 20px;
}

.card-header h5 {
    margin: 0;
    color: #495057;
    font-weight: 600;
}

.card-header i {
    margin-right: 8px;
    color: #3498db;
}

.card-body {
    padding: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 5px;
}

.form-control {
    border: 1px solid #ced4da;
    border-radius: 6px;
    padding: 10px 12px;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}

.form-actions {
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.password-requirements {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.password-requirements h6 {
    color: #495057;
    margin-bottom: 10px;
}

.password-requirements ul {
    margin: 0;
    padding-left: 20px;
    color: #6c757d;
}

.danger-zone {
    border-left: 4px solid #dc3545;
}

.danger-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.danger-content h6 {
    color: #dc3545;
    margin-bottom: 5px;
}

.danger-content p {
    color: #6c757d;
    margin: 0;
}

.security-item {
    display: flex;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #f1f3f4;
}

.security-item:last-child {
    border-bottom: none;
}

.security-icon {
    width: 40px;
    height: 40px;
    background: #e3f2fd;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

.security-icon i {
    color: #3498db;
    font-size: 16px;
}

.security-content h6 {
    margin: 0 0 5px 0;
    color: #2c3e50;
    font-size: 14px;
}

.security-content span {
    color: #6c757d;
    font-size: 12px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f1f3f4;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item label {
    font-weight: 500;
    color: #6c757d;
    margin: 0;
}

.info-item span {
    color: #2c3e50;
    font-weight: 500;
}

.btn {
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn i {
    margin-right: 5px;
}

.alert {
    border-radius: 6px;
    border: none;
}

.alert i {
    margin-right: 8px;
}

.modal-content {
    border-radius: 8px;
    border: none;
}

.modal-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.modal-title {
    color: #2c3e50;
    font-weight: 600;
}
</style>
@endsection

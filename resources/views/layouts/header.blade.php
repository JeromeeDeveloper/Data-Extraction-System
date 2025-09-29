<div class="header">
    <div class="header-left">
        <div class="header-title">
            <h4>Data Extraction System</h4>
        </div>
    </div>

    <div class="header-right">
        <div class="user-dropdown">
            <button class="user-btn" onclick="toggleDropdown()">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <span class="user-name">{{ Auth::user()->name }}</span>
                <i class="fas fa-chevron-down dropdown-arrow"></i>
            </button>

            <div class="dropdown-menu" id="userDropdown">
                <div class="dropdown-header">
                    <div class="user-info">
                        <div class="user-avatar-large">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-details">
                            <div class="user-name-large">{{ Auth::user()->name }}</div>
                            <div class="user-email">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                </div>

                <div class="dropdown-divider"></div>

                <a href="{{ route('profile.index') }}" class="dropdown-item">
                    <i class="fas fa-user-cog"></i>
                    Profile Settings
                </a>

                <a href="{{ route('account.index') }}" class="dropdown-item">
                    <i class="fas fa-cog"></i>
                    Account Settings
                </a>

                <div class="dropdown-divider"></div>

                <form method="POST" action="{{ route('logout') }}" class="dropdown-form">
                    @csrf
                    <button type="submit" class="dropdown-item logout-item">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.header {
    position: fixed;
    top: 0;
    left: 250px;
    right: 0;
    height: 70px;
    background: white;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 30px;
    z-index: 1000;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.header-left {
    display: flex;
    align-items: center;
}

.header-title h4 {
    margin: 0;
    color: #2c3e50;
    font-weight: 600;
    font-size: 20px;
}

.header-right {
    display: flex;
    align-items: center;
}

.user-dropdown {
    position: relative;
}

.user-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 16px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
    color: #495057;
}

.user-btn:hover {
    background: #e9ecef;
    border-color: #3498db;
}

.user-avatar {
    width: 32px;
    height: 32px;
    background: #3498db;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
}

.user-name {
    font-weight: 500;
    color: #2c3e50;
}

.dropdown-arrow {
    font-size: 12px;
    color: #6c757d;
    transition: transform 0.3s ease;
}

.user-btn.active .dropdown-arrow {
    transform: rotate(180deg);
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    width: 280px;
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 1001;
    margin-top: 8px;
}

.dropdown-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-header {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px 8px 0 0;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar-large {
    width: 48px;
    height: 48px;
    background: #3498db;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.user-details {
    flex: 1;
}

.user-name-large {
    font-weight: 600;
    color: #2c3e50;
    font-size: 16px;
    margin-bottom: 2px;
}

.user-email {
    color: #6c757d;
    font-size: 14px;
}

.dropdown-divider {
    height: 1px;
    background: #e9ecef;
    margin: 8px 0;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: #495057;
    text-decoration: none;
    transition: background 0.3s ease;
    font-size: 14px;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
}

.dropdown-item:hover {
    background: #f8f9fa;
    color: #2c3e50;
}

.dropdown-item i {
    width: 16px;
    text-align: center;
    color: #6c757d;
}

.dropdown-form {
    margin: 0;
}

.logout-item {
    color: #dc3545;
}

.logout-item:hover {
    background: #f8d7da;
    color: #721c24;
}

.logout-item i {
    color: #dc3545;
}

/* Main content adjustment for header */
.main-content {
    margin-left: 250px;
    margin-top: 70px;
    padding: 20px;
    min-height: calc(100vh - 70px);
    background: #f8f9fa;
}
</style>

<script>
function toggleDropdown() {
    const dropdown = document.getElementById('userDropdown');
    const button = document.querySelector('.user-btn');

    dropdown.classList.toggle('show');
    button.classList.toggle('active');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('userDropdown');
    const button = document.querySelector('.user-btn');

    if (!button.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.remove('show');
        button.classList.remove('active');
    }
});
</script>

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

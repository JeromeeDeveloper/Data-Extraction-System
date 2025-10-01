<div class="sidebar">
    <div class="sidebar-header">
        <h3>Data Extraction System</h3>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('extraction.index') }}" class="{{ request()->routeIs('extraction.*') ? 'active' : '' }}">
                    <i class="fas fa-download"></i>
                    Data Extraction
                </a>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle {{ request()->routeIs('configurations.*') ? 'active' : '' }}" data-bs-toggle="dropdown">
                    <i class="fas fa-cogs"></i>
                    Configurations
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('configurations.index') }}" class="{{ request()->routeIs('configurations.index') ? 'active' : '' }}">
                            <i class="fas fa-box"></i>
                            Product Configuration
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('configurations.title') }}" class="{{ request()->routeIs('configurations.title') ? 'active' : '' }}">
                            <i class="fas fa-user-tag"></i>
                            Title Configuration
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('configurations.gender') }}" class="{{ request()->routeIs('configurations.gender') ? 'active' : '' }}">
                            <i class="fas fa-venus-mars"></i>
                            Gender Configuration
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('configurations.civil') }}" class="{{ request()->routeIs('configurations.civil') ? 'active' : '' }}">
                            <i class="fas fa-heart"></i>
                            Civil Status Configuration
                        </a>
                    </li>
                </ul>
                @if(request()->routeIs('configurations.*'))
                    <div class="selected-item">
                        <i class="fas fa-check-circle"></i>
                        <span>
                            @if(request()->routeIs('configurations.index'))
                                <i class="fas fa-box"></i> Product Configuration
                            @elseif(request()->routeIs('configurations.title'))
                                <i class="fas fa-user-tag"></i> Title Configuration
                            @elseif(request()->routeIs('configurations.gender'))
                                <i class="fas fa-venus-mars"></i> Gender Configuration
                            @elseif(request()->routeIs('configurations.civil'))
                                <i class="fas fa-heart"></i> Civil Status Configuration
                            @endif
                        </span>
                    </div>
                @endif
            </li>
        </ul>
    </nav>
</div>

<style>
.sidebar {
    width: 250px;
    height: 100vh;
    background: #2c3e50;
    color: white;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1000;
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    padding: 20px;
    background: #34495e;
    border-bottom: 1px solid #4a5f7a;
}

.sidebar-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.sidebar-nav {
    flex: 1;
    padding: 20px 0;
}

.sidebar-nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

.sidebar-nav li {
    margin: 0;
}

.sidebar-nav a {
    display: block;
    padding: 15px 20px;
    color: #ecf0f1;
    text-decoration: none;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.sidebar-nav a:hover {
    background: #34495e;
    border-left-color: #3498db;
}

.sidebar-nav a.active {
    background: #3498db;
    border-left-color: #2980b9;
}

.sidebar-nav i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

/* Dropdown styles */
.dropdown {
    position: relative;
}

.dropdown-toggle {
    cursor: pointer;
}

.dropdown-toggle .ms-auto {
    margin-left: auto;
    font-size: 12px;
    transition: transform 0.3s ease;
}

.dropdown-toggle[aria-expanded="true"] .ms-auto {
    transform: rotate(180deg);
}

.dropdown-menu {
    position: static;
    background: #34495e;
    border: none;
    box-shadow: none;
    padding: 0;
    margin: 0;
    display: none;
}

.dropdown-menu.show {
    display: block;
}

.dropdown-menu li {
    margin: 0;
}

.dropdown-menu a {
    padding: 12px 20px 12px 50px;
    font-size: 14px;
    background: #34495e;
}

.dropdown-menu a:hover {
    background: #2c3e50;
    border-left-color: #3498db;
}

.dropdown-menu a.active {
    background: #3498db;
    border-left-color: #2980b9;
}

/* Selected item display */
.selected-item {
    background: #27ae60;
    color: white;
    padding: 8px 20px;
    margin: 5px 0;
    border-radius: 4px;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 8px;
    animation: slideIn 0.3s ease;
}

.selected-item i.fa-check-circle {
    color: #2ecc71;
    font-size: 12px;
}

.selected-item span {
    display: flex;
    align-items: center;
    gap: 6px;
}

.selected-item span i {
    font-size: 11px;
    opacity: 0.9;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}


/* Main content adjustment */
.main-content {
    margin-left: 250px;
    margin-top: 70px;
    padding: 20px;
    min-height: calc(100vh - 70px);
    background: #f8f9fa;
}
</style>

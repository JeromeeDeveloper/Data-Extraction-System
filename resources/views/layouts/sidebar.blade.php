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
            <li>
                <a href="{{ route('configurations.index') }}" class="{{ request()->routeIs('configurations.*') ? 'active' : '' }}">
                    <i class="fas fa-cogs"></i>
                    Configurations
                </a>
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


/* Main content adjustment */
.main-content {
    margin-left: 250px;
    margin-top: 70px;
    padding: 20px;
    min-height: calc(100vh - 70px);
    background: #f8f9fa;
}
</style>

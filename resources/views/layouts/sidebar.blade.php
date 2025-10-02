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
                <a href="{{ route('branches.index') }}" class="{{ request()->routeIs('branches.*') ? 'active' : '' }}">
                    <i class="fas fa-code-branch"></i>
                    Branch Profile
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


<ul class="nav">
    <li class="nav-item sidebar-actions">
        <span class="nav-link fw-bold">GENERAL</span>
    </li>
    <li class="nav-item @ifroute('admin') active @endifroute">
        <a class="nav-link" href="{{ route('admin') }}">
            <span class="menu-title">Dashboard</span>
            <i class="mdi mdi-home menu-icon"></i>
        </a>
    </li>
    <li class="nav-item @ifroute('users.index') active @endifroute">
        <a class="nav-link" href="{{ route('users.index') }}">
            <span class="menu-title">Users</span>
            <i class="mdi mdi-account menu-icon"></i>
        </a>
    </li>
</ul>

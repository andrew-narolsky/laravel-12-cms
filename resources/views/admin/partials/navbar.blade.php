<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

<ul class="navbar-nav navbar-nav-right">
    <li class="nav-item nav-profile dropdown">
        <a class="nav-link dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="nav-profile-img">
                <img src="{{ $currentUser->avatar?->getFileUrl() ?: asset('/build/images/user-default-image.jpg')  }}" alt="image">
                <span class="availability-status online"></span>
            </div>
            <div class="nav-profile-text">
                <p class="mb-1 text-black">{{ $currentUser->name }}</p>
            </div>
        </a>
        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
            <a class="dropdown-item" href="{{ route('users.edit', $currentUser) }}">
                <i class="mdi mdi-account me-2 text-success"></i> Profile
            </a>
            <div class="dropdown-divider"></div>
            <button class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="mdi mdi-logout me-2 text-primary"></i> Logout
            </button>
        </div>
    </li>
    <li class="nav-item nav-logout d-none d-lg-block">
        <button class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="mdi mdi-power"></i>
        </button>
    </li>
</ul>

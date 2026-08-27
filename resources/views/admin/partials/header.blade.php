<header class="admin-topbar">
    <div class="admin-topbar-inner">
        <button type="button" class="admin-shell-menu rounded-lg p-2 text-gray-500 hover:bg-gray-100" data-sidebar-menu aria-label="Toggle navigation">
            <i class="fa-solid fa-bars"></i>
        </button>

        <a href="{{ route('admin.dashboard', absolute: false) }}" class="admin-brand">
            TNT SOL
        </a>

        <form class="admin-search" role="search">
            <input placeholder="Search by name, ID or module..." type="search" aria-label="Search">
            <button type="submit" aria-label="Search"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>

        <div class="admin-topbar-icons ml-auto flex items-center gap-4">
            <button type="button" class="rounded-lg p-2 hover:bg-gray-100" title="Notifications" aria-label="Notifications">
                <i class="fa-regular fa-bell"></i>
            </button>
            <div class="admin-profile-menu">
                <button type="button" class="admin-profile-trigger" data-profile-toggle aria-expanded="false" aria-haspopup="true">
                    <span class="admin-profile-avatar">{{ strtoupper(substr((string) (auth()->user()->name ?? 'U'), 0, 1)) }}</span>
                    <span class="admin-profile-name">{{ auth()->user()->name ?? 'User' }}</span>
                    <i class="fa-solid fa-chevron-down admin-profile-chevron"></i>
                </button>

                <div class="admin-profile-dropdown" data-profile-dropdown hidden>
                    <div class="admin-profile-summary">
                        <strong>{{ auth()->user()->name ?? 'User' }}</strong>
                        <span>{{ auth()->user()->email ?? '' }}</span>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="admin-profile-item">
                        <i class="fa-regular fa-user"></i><span>Edit profile</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="admin-profile-item">
                        <i class="fa-solid fa-gear"></i><span>Account settings</span>
                    </a>
                    <a href="{{ route('admin.systemsettings.dashboard', absolute: false) }}" class="admin-profile-item">
                        <i class="fa-solid fa-circle-info"></i><span>Support</span>
                    </a>
                    <div class="admin-profile-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="admin-profile-item admin-profile-logout">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i><span>Sign out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

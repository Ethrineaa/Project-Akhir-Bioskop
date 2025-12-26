<aside class="left-sidebar">
    <div>

        {{-- BRAND / LOGO --}}
        <div class="brand-logo px-4 py-4 d-flex align-items-center gap-3">
            <img src="{{ asset('template-admin/src/assets/logo/cinema-removebg.png') }}"
                class="logo-full"
                alt="Logo Cinema"
                style="width:40px;height:40px;object-fit:contain;">

            <div class="logo-text">
                <h5 class="mb-0 fw-bold">PAYON</h5>
                <small class="text-muted">Admin Panel</small>
            </div>
        </div>

        {{-- NAVIGATION --}}
        <nav class="sidebar-nav scroll-sidebar mt-2" data-simplebar="">
            <ul id="sidebarnav">

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>

                <li class="sidebar-item {{ Request::is('dashboard') ? 'selected' : '' }}">
                    <a class="sidebar-link {{ Request::is('dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <i class="ti ti-layout-dashboard"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Datamaster</span>
                </li>

                <li class="sidebar-item {{ Request::is('genre*') ? 'selected' : '' }}">
                    <a class="sidebar-link {{ Request::is('genre*') ? 'active' : '' }}"
                        href="{{ route('admin.genre.index') }}">
                        <i class="ti ti-category"></i>
                        <span class="hide-menu">Data Genre</span>
                    </a>
                </li>

                <li class="sidebar-item {{ Request::is('film*') ? 'selected' : '' }}">
                    <a class="sidebar-link {{ Request::is('film*') ? 'active' : '' }}"
                        href="{{ route('admin.film.index') }}">
                        <i class="ti ti-movie"></i>
                        <span class="hide-menu">Data Film</span>
                    </a>
                </li>

                <li class="sidebar-item {{ Request::is('studio*') ? 'selected' : '' }}">
                    <a class="sidebar-link {{ Request::is('studio*') ? 'active' : '' }}"
                        href="{{ route('admin.studio.index') }}">
                        <i class="ti ti-building"></i>
                        <span class="hide-menu">Data Studio</span>
                    </a>
                </li>

                <li class="sidebar-item {{ Request::is('kursi*') ? 'selected' : '' }}">
                    <a class="sidebar-link {{ Request::is('kursi*') ? 'active' : '' }}"
                        href="{{ route('admin.kursi.index') }}">
                        <i class="ti ti-armchair"></i>
                        <span class="hide-menu">Data Kursi</span>
                    </a>
                </li>

                <li class="sidebar-item {{ Request::is('jadwal*') ? 'selected' : '' }}">
                    <a class="sidebar-link {{ Request::is('jadwal*') ? 'active' : '' }}"
                        href="{{ route('admin.jadwal.index') }}">
                        <i class="ti ti-calendar-time"></i>
                        <span class="hide-menu">Data Jadwal</span>
                    </a>
                </li>

            </ul>
        </nav>

    </div>
</aside>

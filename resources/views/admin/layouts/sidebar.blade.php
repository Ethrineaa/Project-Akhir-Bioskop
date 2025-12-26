<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="./index.html" class="text-nowrap logo-img">
                <img src="{{ asset('template-admin/src/assets/logo/cinema-removebg.png') }}" width="180"
                    alt="Logo Cinema" />
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                {{-- TAMPILAN MENU UNTUK ADMIN --}}
                {{--    @if (Auth::user()->role == 'Admin') --}}
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item {{ Request::is('dashboard') ? 'selected' : '' }}">
                    <a class="sidebar-link {{ Request::is('dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}" aria-expanded="false">
                        <span><i class="ti ti-layout-dashboard"></i></span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Datamaster</span>
                </li>
                <li class="sidebar-item {{ Request::is('genre*') ? 'selected' : '' }}">
                    <a class="sidebar-link {{ Request::is('genre*') ? 'active' : '' }}"
                        href="{{ route('admin.genre.index') }}" aria-expanded="false">
                        <span><i class="ti ti-category"></i></span>
                        <span class="hide-menu">Data Genre</span>
                    </a>
                </li>
                <li class="sidebar-item {{ Request::is('film*') ? 'selected' : '' }}">
                    <a class="sidebar-link {{ Request::is('film*') ? 'active' : '' }}"
                        href="{{ route('admin.film.index') }}" aria-expanded="false">
                        <span><i class="ti ti-movie"></i></span>
                        <span class="hide-menu">Data Films</span>
                    </a>
                </li>
                <li class="sidebar-item {{ Request::is('studio*') ? 'selected' : '' }}">
                    <a class="sidebar-link {{ Request::is('studio*') ? 'active' : '' }}"
                        href="{{ route('admin.studio.index') }}" aria-expanded="false">
                        <span><i class="ti ti-building"></i></span>
                        <span class="hide-menu">Data Studio</span>
                    </a>
                </li>
                <li class="sidebar-item {{ Request::is('kursi*') ? 'selected' : '' }}">
                    <a class="sidebar-link {{ Request::is('kursi*') ? 'active' : '' }}"
                        href="{{ route('admin.kursi.index') }}" aria-expanded="false">
                        <span><i class="ti ti-armchair"></i></span>
                        <span class="hide-menu">Data Kursi</span>
                    </a>
                </li>
                <li class="sidebar-item {{ Request::is('jadwal*') ? 'selected' : '' }}">
                    <a class="sidebar-link {{ Request::is('jadwal*') ? 'active' : '' }}"
                        href="{{ route('admin.jadwal.index') }}" aria-expanded="false">
                        <span><i class="ti ti-calendar-time"></i></span>
                        <span class="hide-menu">Data Jadwal</span>
                    </a>
                </li>

                {{--    @endif --}}
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>

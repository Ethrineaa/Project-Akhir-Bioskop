<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light px-3">

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link nav-icon-hover sidebartoggler" href="javascript:void(0)">
                    <i class="ti ti-menu-2 fs-5"></i>
                </a>
            </li>
        </ul>

        <div class="navbar-collapse justify-content-end">
            <ul class="navbar-nav flex-row align-items-center">

                <li class="nav-item dropdown">
                    <a class="nav-link nav-icon-hover" href="javascript:void(0)" data-bs-toggle="dropdown">
                        <img src="{{ asset('template-admin/src/assets/images/profile/user-1.jpg') }}" width="35"
                            height="35" class="rounded-circle">
                    </a>

                    <div class="dropdown-menu dropdown-menu-end">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary w-100 mt-2">
                                Logout
                            </button>
                        </form>
                    </div>
                </li>

            </ul>
        </div>

    </nav>
</header>

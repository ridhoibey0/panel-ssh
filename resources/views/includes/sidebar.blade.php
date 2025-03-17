<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="{{ url('/') }}"><img src="{{ asset('assets/logo.svg') }}" alt="Logo"
                            srcset=""></a>
                </div>
                <div class="sidebar-toggler  x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>
                @hasrole('admin')
                <li class="sidebar-item {{ request()->is('admin/dashboard') || request()->is('admin/dashboard/*') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item  has-sub {{ request()->is('admin/servers') || request()->is('admin/servers/*') ? 'active' : '' }}"">
                    <a href="#" class='sidebar-link'>
                       <i class="bi bi-list"></i>
                        <span>Server</span>
                    </a>

                    <ul class="submenu ">

                        @foreach ($categories as $category)
                            <li class="submenu-item"><a
                                    href="{{ route('admin.servers.index', [$category->slug]) }}">{{ $category->name }}</a>
                            </li>
                        @endforeach

                    </ul>


                </li>
                <li class="sidebar-item ">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <a href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class='sidebar-link'>
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </a>
                    </form>
                </li>
                @else
                <li class="sidebar-item {{ Request::is('member') ? 'active' : '' }} ">
                    <a href="{{ url('/dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>


                </li>

                <li class="sidebar-item  has-sub {{ Request::is('server*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shopping-cart"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                            <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                            <path d="M17 17h-11v-14h-2"></path>
                            <path d="M6 5l14 1l-1 7h-13"></path>
                        </svg>
                        <span>Buy Accounts</span>
                    </a>

                    <ul class="submenu ">

                        @foreach ($categories as $category)
                            <li class="submenu-item"><a
                                    href="{{ route('servers.index', [$category->slug]) }}">{{ $category->name }}</a>
                            </li>
                        @endforeach

                    </ul>


                </li>

                <li class="sidebar-item {{ Request::is('topup*') ? 'active' : '' }}  has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-currency-dollar"></i>
                        <span>Topup Saldo</span>
                    </a>

                    <ul class="submenu ">

                        <li class="submenu-item  ">
                            <a href="{{ url('/topup') }}" class="submenu-link">Topup Saldo</a>

                        </li>

                        <li class="submenu-item  ">
                            <a href="{{ url('/topup/riwayat') }}" class="submenu-link">Riwayat Topup</a>

                        </li>


                    </ul>


                </li>
                <li class="sidebar-item has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-list"></i>
                        <span>List Akun</span>
                    </a>

                    <ul class="submenu ">

                        @foreach ($categories as $category)
                            <li class="submenu-item"><a
                                    href="{{ route('accounts.index', [$category->slug]) }}">{{ $category->name }}</a>
                            </li>
                        @endforeach


                    </ul>


                </li>

                <li class="sidebar-title">Setting</li>

                <li class="sidebar-item ">
                    <a href="{{ url('settings/profile') }}" class='sidebar-link'>
                        <i class="bi bi-person-fill"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="sidebar-item ">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <a href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class='sidebar-link'>
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </a>
                    </form>
                </li>
                @endrole
            </ul>
        </div>
    </div>
</div>

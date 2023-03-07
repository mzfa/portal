<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/home') }}" class="brand-link">
        <img src="{{ env('APP_LOGO') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ env('APP_NAME') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ url('home') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @php
                    $sidebar = Session('menu');
                    // dump($sidebar);
                @endphp
                @isset($sidebar)
                    @foreach ($sidebar as $item)
                        @if (empty($item['submenu'][0]))
                            <li class="nav-item">
                                <a href="@if (Route::has($item['url_menu'])) {{ route($item['url_menu']) }} @endif" class="nav-link">
                                    <i class="nav-icon {{ $item['icon_menu'] }}"></i>
                                    <p>
                                        {{ $item['nama_menu'] }}
                                    </p>
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon {{ $item['icon_menu'] }}"></i>
                                    <p>
                                        {{ $item['nama_menu'] }}
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @foreach ($item['submenu'] as $submenu)
                                        @php $url = $submenu['url_menu']; @endphp
                                        <li class="nav-item">
                                            <a href="@if (Route::has($submenu['url_menu'])) {{ route($submenu['url_menu']) }} @endif" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>{{ $submenu['nama_menu'] }}</p>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach
                @endisset
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="post" id="logout">
                        @csrf
                    </form>
                    <a onclick="return document.getElementById('logout').submit()" class="nav-link">
                        <i class="nav-icon fa fa-arrow-right"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="pages/widgets.html" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Widgets
                            <span class="right badge badge-danger">New</span>
                        </p>
                    </a>
                </li> --}}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->

</aside>

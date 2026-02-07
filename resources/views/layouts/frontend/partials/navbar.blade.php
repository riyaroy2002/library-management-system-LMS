<header class="wrapper bg-light">
    <nav
        class="navbar navbar-expand-lg classic transparent navbar-light {{ request()->routeIs('home') ? 'position-absolute' : 'position-relative bg-white shadow-lg' }}">
        <div class="container flex-lg-row flex-nowrap align-items-center">

            <div class="navbar-brand w-100">
                <a href="/">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Library Logo" style="max-height: 60px;">
                </a>
            </div>

            <div class="navbar-collapse offcanvas offcanvas-nav offcanvas-start">
                <div class="offcanvas-header d-lg-none">
                    <h3 class="text-white fs-30 mb-0">LMS</h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>

                <div class="offcanvas-body ms-lg-auto d-flex flex-column h-100">
                    <ul class="navbar-nav align-items-center">

                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('about-us') }}">About Us</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('contact-us') }}">Contact Us</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('gallery') }}">Gallery</a></li>

                        @guest
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Register</a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('register-member') }}">Member</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="btn btn-outline-dark btn-lg login-btn w-100 w-lg-auto" href="#"
                                    data-bs-toggle="dropdown">Login <i class="fa fa-arrow-right ms-2"></i></a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('login') }}">Member</a></li>
                                    <li><a class="dropdown-item" href="{{ route('librarian.login') }}">Librarian</a></li>
                                </ul>
                            </li>


                        @endguest

                        @auth
                            <li class="nav-item dropdown ms-3">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                                    data-bs-toggle="dropdown">

                                    <img src="{{ Auth::user()->image_url ? asset(Auth::user()->image_url) : asset('assets/img/default-user.webp') }}"
                                        alt="User" class="rounded-circle"
                                        style="width: 35px; height: 35px; object-fit: cover; margin-right: 8px;">
                                    @auth
                                        @if (Auth::user()->role === 'admin')
                                            {{ Auth::user()->full_name }}
                                        @elseif(Auth::user()->role === 'librarian')
                                            {{ Auth::user()->full_name }}
                                        @elseif(Auth::user()->role === 'member')
                                            {{ Auth::user()->full_name }}
                                        @else
                                            {{ Auth::user()->full_name }}
                                        @endif
                                    @endauth
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end custom-dropdown">
                                    <li>
                                        @if (Auth::user()->role === 'admin')
                                            <a class="dropdown-item" href="{{ route('admin.index') }}">
                                                Dashboard
                                            </a>
                                        @elseif(Auth::user()->role === 'librarian')
                                            <a class="dropdown-item" href="{{ route('librarian.index') }}">
                                                Dashboard
                                            </a>
                                        @elseif(Auth::user()->role === 'member')
                                            <a class="dropdown-item" href="{{ route('index') }}">
                                                Dashboard
                                            </a>
                                        @endif
                                    </li>

                                    <li>
                                        @if (Auth::user()->role === 'librarian')
                                            <a class="dropdown-item" href="{{ route('librarian.edit-profile') }}">
                                                Profile
                                            </a>
                                        @elseif(Auth::user()->role === 'member')
                                            <a class="dropdown-item" href="{{ route('edit-profile') }}">
                                                Profile
                                            </a>
                                        @endif
                                    </li>
                                    <li>
                                        <form method="POST"
                                            action="{{ match (Auth::user()->role) {
                                                'admin' => route('admin.logout'),
                                                'librarian' => route('librarian.logout'),
                                                default => route('logout'),
                                            } }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endauth

                    </ul>
                </div>
            </div>

            <div class="navbar-other ms-lg-4">
                <ul class="navbar-nav flex-row align-items-center ms-auto">
                    <li class="nav-item d-lg-none">
                        <button class="hamburger offcanvas-nav-btn"><span></span></button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

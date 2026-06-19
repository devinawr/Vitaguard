<header class="vg-header">
    <nav class="navbar navbar-expand-lg navbar-light vg-navbar">
        <div class="container">
            <a class="navbar-brand vg-brand d-flex align-items-center gap-2"
               href="{{ route('member.welcome') }}">
                <span class="vg-brand-badge">
                    <i class="bi bi-heart-pulse-fill fs-5"></i>
                </span>
                <span>VitaGuard</span>
            </a>

            <button class="navbar-toggler vg-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"
                aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-4 text-vg-primary"></i>
            </button>

            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav mx-auto gap-lg-2 align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link vg-nav-link {{ request()->routeIs('member.welcome') ? 'active' : '' }}"
                           href="{{ route('member.welcome') }}">
                            Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link vg-nav-link {{ request()->routeIs('member.articles.*') ? 'active' : '' }}"
                           href="{{ route('member.articles.index') }}">
                            Artikel
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link vg-nav-link" href="#">
                            Cari Dokter
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link vg-nav-link" href="#">
                            Konsultasi
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center vg-auth-group">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-vg-primary">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-vg-primary">
                            Daftar
                        </a>
                    @else
                        <div class="dropdown">
                            <button class="vg-user-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="vg-user-avatar">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                                <span>{{ auth()->user()->name }}</span>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end vg-dropdown border-0">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-person me-2"></i> Profil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-heart me-2"></i> Favorit
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>
</header>
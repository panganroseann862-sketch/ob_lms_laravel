<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OB-LMS | Admin Rose</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            background-color: #f6f5fc;
            font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }

        #sidebar-wrapper {
            min-height: 100vh;
            width: 260px;
            position: fixed;
            z-index: 100;
            background: #ffffff;
            border-right: 1px solid #ece9f8;
            transform: translateX(-260px);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 24px rgba(59,40,168,0.07);
            display: flex;
            flex-direction: column;
        }

        #wrapper.toggled #sidebar-wrapper {
            transform: translateX(0);
        }

        #page-content-wrapper {
            width: 100%;
            padding-left: 0;
            min-height: 100vh;
            transition: padding-left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @media (min-width: 768px) {
            #wrapper.toggled #page-content-wrapper {
                padding-left: 260px;
            }
        }

        .sidebar-header {
            padding: 24px 20px 20px;
            text-align: center;
            border-bottom: 1px solid #ece9f8;
            position: relative;
            overflow: hidden;
            background: linear-gradient(160deg, #f8f6ff 0%, #ffffff 100%);
        }
        .sidebar-header::before {
            content: '';
            position: absolute;
            top: -30px; left: -30px;
            width: 100px; height: 100px;
            background: radial-gradient(circle, rgba(108,71,214,0.07) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .logo-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }
        .logo-wrap svg {
            filter: drop-shadow(0 4px 12px rgba(30,19,84,0.18));
            transition: transform 0.4s cubic-bezier(0.34,1.56,0.64,1);
        }
        .logo-wrap svg:hover { transform: scale(1.06) rotate(3deg); }

        .admin-name {
            font-weight: 800;
            color: #1e1354;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .admin-role {
            font-size: 9.5px;
            font-weight: 600;
            color: #b8add8;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .sidebar-nav {
            padding: 16px 0;
            flex: 1;
        }

        .nav-link-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 13px 24px;
            font-weight: 600;
            font-size: 12.5px;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            text-decoration: none;
            color: #7c72a8;
            border-radius: 0 50px 50px 0;
            margin-right: 20px;
            margin-bottom: 4px;
            position: relative;
            transition:
                color 0.3s ease,
                background 0.3s ease,
                transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1),
                box-shadow 0.3s ease,
                padding-left 0.3s ease;
            overflow: hidden;
        }

        .nav-link-item::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(108,71,214,0.08), transparent);
            opacity: 0;
            transition: opacity 0.3s;
            border-radius: inherit;
        }

        .nav-link-item:hover {
            color: #6c47d6;
            background: #f4f0ff;
            transform: translateX(6px);
            padding-left: 30px;
        }
        .nav-link-item:hover::before { opacity: 1; }

        .nav-link-item:active {
            transform: translateX(4px) scale(0.97);
        }

        .nav-link-item.active {
            background: linear-gradient(135deg, #6c47d6 0%, #3b28a8 100%);
            color: #ffffff !important;
            box-shadow: 0 6px 20px rgba(108,71,214,0.35);
            transform: translateX(0);
            padding-left: 24px;
        }
        .nav-link-item.active::before { opacity: 0; }

        .nav-link-item.active::after {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 3px;
            background: rgba(255,255,255,0.5);
            border-radius: 0 2px 2px 0;
        }

        .nav-icon {
            width: 20px;
            text-align: center;
            font-size: 14px;
            flex-shrink: 0;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .nav-link-item:hover .nav-icon {
            transform: scale(1.25) rotate(-5deg);
        }
        .nav-link-item.active .nav-icon {
            transform: scale(1.1);
        }

        .top-navbar {
            background: #ffffff;
            border-bottom: 1px solid #ece9f8;
            padding: 12px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
            box-shadow: 0 2px 12px rgba(59,40,168,0.05);
            transition: box-shadow 0.3s;
        }

        #menu-toggle {
            cursor: pointer;
            font-size: 1.4rem;
            color: #6c47d6;
            background: none;
            border: none;
            padding: 6px 10px;
            border-radius: 10px;
            transition: background 0.2s, transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            line-height: 1;
        }
        #menu-toggle:hover {
            background: #f4f0ff;
            transform: scale(1.15) rotate(10deg);
        }
        #menu-toggle:active {
            transform: scale(0.95);
        }

        .navbar-title {
            font-weight: 700;
            color: #6c47d6;
            font-size: 1rem;
            margin-left: 12px;
            letter-spacing: -0.2px;
        }

        .user-profile-name {
            font-weight: 600;
            color: #5a5c69;
            font-size: 0.9rem;
        }

        .navbar-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 2px solid #7c4dbd;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f5ff;
            flex-shrink: 0;
            transition: border-color 0.2s, transform 0.2s;
        }
        .navbar-avatar:hover {
            border-color: #3b1f7a;
            transform: scale(1.05);
        }

        .dropdown-toggle {
            transition: transform 0.2s;
        }
        .dropdown-toggle:hover {
            transform: translateY(-1px);
        }

        .dropdown-menu .logout-btn {
            color: #6c47d6 !important;
            font-weight: 700;
        }
        .dropdown-menu .logout-btn:hover {
            background: #f4f0ff;
            color: #3b1f7a !important;
        }

        #page-body {
            animation: pageIn 0.35s cubic-bezier(0.4, 0, 0.2, 1) both;
        }
        @keyframes pageIn {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(30,19,84,0.35);
            z-index: 50;
            backdrop-filter: blur(2px);
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
        }
        #wrapper.toggled #sidebar-overlay {
            display: block;
            opacity: 1;
            pointer-events: auto;
        }
        @media (min-width: 768px) {
            #sidebar-overlay { display: none !important; }
        }

        .modal {
            z-index: 1055 !important;
        }
        .modal-backdrop {
            z-index: 1054 !important;
        }
        body.modal-open #sidebar-overlay {
            pointer-events: none !important;
            z-index: 0 !important;
        }
    </style>
</head>
<body>

<div class="d-flex" id="wrapper">

    <div id="sidebar-overlay"></div>

    <div id="sidebar-wrapper">
        <div class="sidebar-header">
            <div class="logo-wrap">
                <svg width="115" height="115" viewBox="0 0 680 680" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="340" cy="340" r="245" fill="#3b1f7a"/>
                    <circle cx="340" cy="340" r="232" fill="#f5f0ff"/>
                    <circle cx="340" cy="340" r="226" fill="none" stroke="#7c4dbd" stroke-width="2"/>
                    <circle cx="340" cy="340" r="220" fill="none" stroke="#3b1f7a" stroke-width="1"/>
                    <circle cx="340" cy="340" r="195" fill="#2d1463"/>
                    <circle cx="340" cy="340" r="184" fill="#f8f5ff"/>
                    <text x="340" y="198" text-anchor="middle" font-family="Georgia, serif" font-size="15" font-weight="700" fill="#3b1f7a" letter-spacing="2">UNIVERSIDAD DE DAGUPAN</text>
                    <line x1="210" y1="208" x2="470" y2="208" stroke="#7c4dbd" stroke-width="1.2"/>
                    <text x="340" y="295" text-anchor="middle" font-family="Georgia, serif" font-size="72" font-weight="900" fill="#3b1f7a" letter-spacing="5">OB</text>
                    <line x1="195" y1="315" x2="485" y2="315" stroke="#7c4dbd" stroke-width="3"/>
                    <text x="340" y="390" text-anchor="middle" font-family="Georgia, serif" font-size="72" font-weight="900" fill="#3b1f7a" letter-spacing="5">LMS</text>
                    <line x1="210" y1="408" x2="470" y2="408" stroke="#7c4dbd" stroke-width="1.2"/>
                    <text x="340" y="426" text-anchor="middle" font-family="Georgia, serif" font-size="11" font-weight="700" fill="#3b1f7a" letter-spacing="1">OUTCOMES-BASED LEARNING</text>
                    <text x="340" y="444" text-anchor="middle" font-family="Georgia, serif" font-size="11" font-weight="700" fill="#3b1f7a" letter-spacing="1">MANAGEMENT SYSTEM</text>
                    <circle cx="340" cy="100" r="4.5" fill="#c9a84c"/>
                    <circle cx="340" cy="580" r="4.5" fill="#c9a84c"/>
                </svg>
            </div>
            <div class="admin-name">Admin Rose</div>
            <div class="admin-role">System Manager</div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}"
               class="nav-link-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-gauge-high"></i></span>
                Dashboard
            </a>
            <a href="{{ route('students.index') }}"
               class="nav-link-item {{ request()->is('students*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-users"></i></span>
                Students
            </a>
            <a href="{{ route('subjects.index') }}"
               class="nav-link-item {{ request()->is('subjects*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-book-open"></i></span>
                Subjects
            </a>
            <a href="{{ route('assessment') }}"
               class="nav-link-item {{ request()->is('assessments*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-clipboard-check"></i></span>
                Assessment
            </a>
            <a href="{{ route('reports.index') }}"
               class="nav-link-item {{ request()->is('reports*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-chart-bar"></i></span>
                Reports
            </a>
        </nav>
    </div>

    <div id="page-content-wrapper">

        <nav class="top-navbar">
            <div class="d-flex align-items-center">
                <button id="menu-toggle">☰</button>
                <span class="navbar-title d-none d-md-inline">Outcome-Based Learning Management System</span>
            </div>

            <div class="dropdown">
                <button class="btn dropdown-toggle border-0 d-flex align-items-center gap-2" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="user-profile-name">{{ Auth::user()->username ?? 'admin' }}</span>

                    <div class="navbar-avatar">
                        <svg width="34" height="34" viewBox="0 0 680 680" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="340" cy="340" r="340" fill="#3b1f7a"/>
                            <circle cx="340" cy="340" r="322" fill="#f5f0ff"/>
                            <circle cx="340" cy="340" r="314" fill="none" stroke="#7c4dbd" stroke-width="3"/>
                            <circle cx="340" cy="340" r="272" fill="#2d1463"/>
                            <circle cx="340" cy="340" r="258" fill="#f8f5ff"/>
                            <text x="340" y="192" text-anchor="middle" font-family="Georgia, serif" font-size="13" font-weight="700" fill="#3b1f7a" letter-spacing="1.5">UNIVERSIDAD DE DAGUPAN</text>
                            <line x1="200" y1="204" x2="480" y2="204" stroke="#7c4dbd" stroke-width="1.5"/>
                            <text x="340" y="298" text-anchor="middle" font-family="Georgia, serif" font-size="76" font-weight="900" fill="#3b1f7a" letter-spacing="5">OB</text>
                            <line x1="185" y1="318" x2="495" y2="318" stroke="#7c4dbd" stroke-width="4"/>
                            <text x="340" y="398" text-anchor="middle" font-family="Georgia, serif" font-size="76" font-weight="900" fill="#3b1f7a" letter-spacing="5">LMS</text>
                            <line x1="200" y1="416" x2="480" y2="416" stroke="#7c4dbd" stroke-width="1.5"/>
                            <text x="340" y="434" text-anchor="middle" font-family="Georgia, serif" font-size="11" font-weight="700" fill="#3b1f7a" letter-spacing="1">OUTCOMES-BASED LEARNING</text>
                            <text x="340" y="452" text-anchor="middle" font-family="Georgia, serif" font-size="11" font-weight="700" fill="#3b1f7a" letter-spacing="1">MANAGEMENT SYSTEM</text>
                            <circle cx="340" cy="96" r="5" fill="#c9a84c"/>
                            <circle cx="340" cy="584" r="5" fill="#c9a84c"/>
                        </svg>
                    </div>
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2"
                    style="border-radius: 12px; min-width: 160px;">
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 logout-btn">
                                <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <div id="page-body" class="container-fluid p-4">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $("#menu-toggle").click(function () {
        $("#wrapper").toggleClass("toggled");
    });

    $("#sidebar-overlay").click(function () {
        $("#wrapper").removeClass("toggled");
    });

    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            $("#wrapper").removeClass("toggled");
        });
    });

    document.querySelectorAll(".nav-link-item").forEach(function (link) {
        link.addEventListener("click", function (e) {
            const href = this.getAttribute("href");
            if (!href || href === "#") return;
            if (document.querySelector('.modal.show')) return;
            e.preventDefault();

            const body = document.getElementById("page-body");
            body.style.transition = "opacity 0.22s ease, transform 0.22s ease";
            body.style.opacity = "0";
            body.style.transform = "translateY(10px)";

            setTimeout(function () {
                window.location.href = href;
            }, 220);
        });
    });

    const navbar = document.querySelector(".top-navbar");
    window.addEventListener("scroll", function () {
        if (window.scrollY > 10) {
            navbar.style.boxShadow = "0 4px 20px rgba(59,40,168,0.10)";
        } else {
            navbar.style.boxShadow = "0 2px 12px rgba(59,40,168,0.05)";
        }
    });
</script>
</body>
</html>

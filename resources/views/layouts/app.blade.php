<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G-Scores</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { background:#f4f7fb; color:#1e2a3a; }
        .nav-btn { width:100%; display:flex; align-items:center; gap:10px; padding:9px 12px; border-radius:8px; font-size:13px; text-align:left; cursor:pointer; transition:background 0.15s, color 0.15s; border:none; background:transparent; color:#5b7a99; }
        .nav-btn:hover { background:#e8eef5; color:#1e3a5f; }
        .nav-btn.active { background:#dce8f5; color:#1a56a0; font-weight:500; }

        .sidebar { transition: transform 0.2s ease; }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                z-index: 60;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                padding-left: 0 !important;
            }
            .overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.3);
                z-index: 55;
            }
            .overlay.open {
                display: block;
            }
        }
    </style>
</head>
<body class="antialiased font-sans" x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">

        {{-- Overlay cho mobile --}}
        <div class="overlay" :class="{ 'open': sidebarOpen }" @click="sidebarOpen = false"></div>

        <aside class="sidebar w-56 fixed h-full flex flex-col justify-between"
               :class="{ 'open': sidebarOpen }"
               style="background:#eaf1f9; border-right:1px solid #cddaeb;">
            <div>
                <div class="h-14 flex items-center justify-between px-5"
                     style="border-bottom:1px solid #cddaeb;">
                    <div class="flex items-center gap-2.5">
                        <div class="w-6 h-6 rounded-md flex items-center justify-center text-xs font-medium"
                             style="background:#1a56a0; color:#fff;">G</div>
                        <span class="text-sm font-medium" style="color:#1e3a5f;">G-Scores</span>
                    </div>
                    {{-- Close button - chỉ hiện trên mobile --}}
                    <button @click="sidebarOpen = false" class="md:hidden" style="color:#5b7a99;">
                        <svg style="width:18px;height:18px" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>

                <nav class="p-3" style="display:flex; flex-direction:column; gap:2px;">
                    <a href="{{ route('search') }}" class="nav-btn {{ request()->routeIs('search') ? 'active' : '' }}">
                        <svg style="width:16px;height:16px;flex-shrink:0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        Tra cứu điểm
                    </a>
                    <a href="{{ route('chart') }}" class="nav-btn {{ request()->routeIs('chart') ? 'active' : '' }}">
                        <svg style="width:16px;height:16px;flex-shrink:0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 3v18h18"/><path d="M7 16l4-4 4 4 4-7"/></svg>
                        Phổ điểm
                    </a>
                    <a href="{{ route('ranking') }}" class="nav-btn {{ request()->routeIs('ranking') ? 'active' : '' }}">
                        <svg style="width:16px;height:16px;flex-shrink:0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                        Bảng xếp hạng
                    </a>
                </nav>
            </div>

            <div class="p-4 text-xs" style="border-top:1px solid #cddaeb; color:#7a9ab8;">
                Golden Owl © 2026
            </div>
        </aside>

        <div class="main-content flex-1 pl-56 flex flex-col min-h-screen">
            <header class="h-14 sticky top-0 z-40 flex items-center gap-3 px-6"
                    style="background:#ffffff; border-bottom:1px solid #cddaeb;">
                {{-- Hamburger - chỉ hiện trên mobile --}}
                <button @click="sidebarOpen = true" class="md:hidden" style="color:#5b7a99;">
                    <svg style="width:20px;height:20px" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
                </button>
                <span class="text-sm" style="color:#5b7a99;">Kỳ thi THPT Quốc Gia 2024</span>
            </header>

            <main class="p-4 md:p-6 grow">
                @yield('content')
            </main>
        </div>

    </div>

    @stack('scripts')
</body>
</html>
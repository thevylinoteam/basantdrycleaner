<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Horizon Laundry - Sistem Informasi Pengelolaan Laundry') ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('logo-laundry.png') ?>">
    
    <!-- Google Fonts: DM Sans & Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    
    <!-- Dark Mode Initializer to avoid flash -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"DM Sans"', 'sans-serif'],
                        dm: ['"DM Sans"', 'sans-serif'],
                        poppins: ['"Poppins"', 'sans-serif'],
                    },
                    colors: {
                        lightPrimary: '#F4F7FE',
                        blueSecondary: '#4318FF',
                        brandLinear: '#868CFF',
                        brand: {
                            50: '#E9E3FF',
                            100: '#C0B8FE',
                            200: '#A195FD',
                            300: '#8171FC',
                            400: '#7551FF',
                            500: '#422AFB',
                            600: '#3311DB',
                            700: '#2111A5',
                            800: '#190793',
                            900: '#11047A',
                        },
                        navy: {
                            50: '#d0dcfb',
                            100: '#aac0fe',
                            200: '#a3b9f8',
                            300: '#728fea',
                            400: '#3652ba',
                            500: '#1b3bbb',
                            600: '#24388a',
                            700: '#1B254B',
                            800: '#111c44',
                            900: '#0b1437',
                        },
                        gray: {
                            50: '#F5F6FA',
                            100: '#EEF0F6',
                            200: '#DADEEC',
                            300: '#C9D0E3',
                            400: '#B0BBD5',
                            500: '#B5BED9',
                            600: '#A3AED0',
                            700: '#707eae',
                            800: '#2D396B',
                            900: '#1B2559',
                        },
                        background: {
                            100: '#F4F7FE',
                            900: '#070f2e',
                        },
                        horizonGreen: {
                            50: '#E1FFF4',
                            100: '#BDFFE7',
                            200: '#7BFECE',
                            300: '#39FEB6',
                            400: '#01F99E',
                            500: '#01B574',
                            600: '#01935D',
                            700: '#016B44',
                            800: '#00472D',
                            900: '#002417',
                        },
                    },
                    boxShadow: {
                        '3xl': '14px 17px 40px 4px rgba(112, 144, 176, 0.08)',
                        'shadow-500': '0px 18px 40px rgba(112, 144, 176, 0.12)',
                        'shadow-100': '0px 18px 40px rgba(112, 144, 176, 0.06)',
                    }
                }
            }
        }
    </script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        function confirmDelete(url, title = 'Konfirmasi Hapus Data', text = 'Apakah Anda yakin ingin menghapus data ini?') {
            const isDark = document.documentElement.classList.contains('dark');
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#707eae',
                confirmButtonText: 'Ya, Hapus Data',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                background: isDark ? '#111c44' : '#ffffff',
                color: isDark ? '#ffffff' : '#1B254B',
                customClass: {
                    popup: 'rounded-[24px] shadow-3xl border border-gray-100 dark:border-white/10 p-6',
                    confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-xs shadow-lg shadow-rose-500/25',
                    cancelButton: 'px-5 py-2.5 rounded-xl font-bold text-xs',
                    title: 'font-poppins font-bold text-lg',
                    htmlContainer: 'text-xs text-gray-500 dark:text-gray-400',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'DM Sans', sans-serif;
            letter-spacing: -0.2px;
        }
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            body { background: white !important; }
        }
    </style>
</head>
<body class="h-full bg-background-100 dark:bg-background-900 text-navy-700 dark:text-white transition-colors duration-200" x-data="{ 
    sidebarOpen: false, 
    isDark: document.documentElement.classList.contains('dark'),
    toggleDark() {
        this.isDark = !this.isDark;
        if (this.isDark) {
            document.documentElement.classList.add('dark');
            localStorage.theme = 'dark';
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.theme = 'light';
        }
    }
}">

    <div class="flex h-full min-h-screen w-full bg-background-100 dark:bg-background-900">
        <!-- Sidebar Navigation (Desktop) -->
        <aside class="hidden xl:flex xl:flex-col xl:w-[290px] xl:fixed xl:inset-y-0 bg-white dark:bg-navy-800 shadow-3xl shadow-shadow-500 dark:shadow-none z-50 transition-all duration-200">
            <!-- Brand Logo -->
            <div class="mx-7 mt-8 mb-6 flex items-center gap-3">
                <img src="<?= base_url('logo-laundry.png') ?>" alt="Logo Horizon Laundry" class="w-10 h-10 object-cover rounded-full drop-shadow-md">
                <div>
                    <h1 class="font-poppins text-xl font-bold tracking-tight text-navy-700 dark:text-white uppercase leading-none">
                        Horizon <span class="font-normal text-brand-500">Laundry</span>
                    </h1>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium tracking-wide mt-1">Management System</p>
                </div>
            </div>

            <div class="mb-4 mx-6 h-px bg-gray-200 dark:bg-white/10"></div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto px-4 py-2 space-y-6">
                <?php 
                    $uri = service('uri'); 
                    $seg1 = $uri->getSegment(1); 
                    $seg2 = $uri->getSegment(2);

                    $getNavClasses = function(bool $active): array {
                        if ($active) {
                            return [
                                'link' => 'relative flex items-center py-2.5 px-4 rounded-xl group transition-all text-sm font-bold text-brand-500 dark:text-white bg-lightPrimary/70 dark:bg-navy-700/50',
                                'icon' => 'flex items-center text-brand-500 dark:text-brand-400',
                            ];
                        }
                        return [
                            'link' => 'relative flex items-center py-2.5 px-4 rounded-xl group transition-all text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-navy-700 dark:hover:text-white hover:bg-lightPrimary/40 dark:hover:bg-navy-700/30',
                            'icon' => 'flex items-center text-gray-400 group-hover:text-brand-500 dark:group-hover:text-white',
                        ];
                    };
                ?>

                <!-- Section: Utama -->
                <div>
                    <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Utama</p>
                    <nav class="space-y-1">
                        <?php 
                            $isDashboard = ($seg1 == 'dashboard' || $seg1 == ''); 
                            $dCls = $getNavClasses($isDashboard);
                        ?>
                        <a href="<?= base_url('dashboard') ?>" class="<?= $dCls['link'] ?>">
                            <span class="<?= $dCls['icon'] ?>">
                                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                            </span>
                            <span class="ml-3 leading-none">Dashboard</span>
                            <?php if ($isDashboard): ?>
                                <div class="absolute right-0 top-1.5 h-8 w-1 rounded-l-lg bg-brand-500 dark:bg-brand-400"></div>
                            <?php endif; ?>
                        </a>
                    </nav>
                </div>

                <!-- Section: Operasional -->
                <div>
                    <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Operasional</p>
                    <nav class="space-y-1">
                        <?php 
                            $isCreateTx = ($seg1 == 'transactions' && $seg2 == 'create'); 
                            $cTxCls = $getNavClasses($isCreateTx);
                        ?>
                        <a href="<?= base_url('transactions/create') ?>" class="<?= $cTxCls['link'] ?>">
                            <span class="<?= $cTxCls['icon'] ?>">
                                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                            </span>
                            <span class="ml-3 leading-none">Buat Transaksi</span>
                            <?php if ($isCreateTx): ?>
                                <div class="absolute right-0 top-1.5 h-8 w-1 rounded-l-lg bg-brand-500 dark:bg-brand-400"></div>
                            <?php endif; ?>
                        </a>

                        <?php 
                            $isListTx = ($seg1 == 'transactions' && $seg2 != 'create'); 
                            $lTxCls = $getNavClasses($isListTx);
                        ?>
                        <a href="<?= base_url('transactions') ?>" class="<?= $lTxCls['link'] ?>">
                            <span class="<?= $lTxCls['icon'] ?>">
                                <i data-lucide="receipt" class="w-5 h-5"></i>
                            </span>
                            <span class="ml-3 leading-none">Daftar Transaksi</span>
                            <?php if ($isListTx): ?>
                                <div class="absolute right-0 top-1.5 h-8 w-1 rounded-l-lg bg-brand-500 dark:bg-brand-400"></div>
                            <?php endif; ?>
                        </a>

                        <?php 
                            $isCustomers = ($seg1 == 'customers'); 
                            $custCls = $getNavClasses($isCustomers);
                        ?>
                        <a href="<?= base_url('customers') ?>" class="<?= $custCls['link'] ?>">
                            <span class="<?= $custCls['icon'] ?>">
                                <i data-lucide="users" class="w-5 h-5"></i>
                            </span>
                            <span class="ml-3 leading-none">Data Pelanggan</span>
                            <?php if ($isCustomers): ?>
                                <div class="absolute right-0 top-1.5 h-8 w-1 rounded-l-lg bg-brand-500 dark:bg-brand-400"></div>
                            <?php endif; ?>
                        </a>

                        <?php 
                            $isServices = ($seg1 == 'services'); 
                            $svcCls = $getNavClasses($isServices);
                        ?>
                        <a href="<?= base_url('services') ?>" class="<?= $svcCls['link'] ?>">
                            <span class="<?= $svcCls['icon'] ?>">
                                <i data-lucide="sparkle" class="w-5 h-5"></i>
                            </span>
                            <span class="ml-3 leading-none">Layanan & Tarif</span>
                            <?php if ($isServices): ?>
                                <div class="absolute right-0 top-1.5 h-8 w-1 rounded-l-lg bg-brand-500 dark:bg-brand-400"></div>
                            <?php endif; ?>
                        </a>
                    </nav>
                </div>

                <!-- Section: Laporan -->
                <div>
                    <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Laporan</p>
                    <nav class="space-y-1">
                        <?php 
                            $isRev = ($seg1 == 'reports' && $seg2 == 'revenue'); 
                            $revCls = $getNavClasses($isRev);
                        ?>
                        <a href="<?= base_url('reports/revenue') ?>" class="<?= $revCls['link'] ?>">
                            <span class="<?= $revCls['icon'] ?>">
                                <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                            </span>
                            <span class="ml-3 leading-none">Laporan Pendapatan</span>
                            <?php if ($isRev): ?>
                                <div class="absolute right-0 top-1.5 h-8 w-1 rounded-l-lg bg-brand-500 dark:bg-brand-400"></div>
                            <?php endif; ?>
                        </a>

                        <?php 
                            $isSvc = ($seg1 == 'reports' && $seg2 == 'services'); 
                            $srepCls = $getNavClasses($isSvc);
                        ?>
                        <a href="<?= base_url('reports/services') ?>" class="<?= $srepCls['link'] ?>">
                            <span class="<?= $srepCls['icon'] ?>">
                                <i data-lucide="pie-chart" class="w-5 h-5"></i>
                            </span>
                            <span class="ml-3 leading-none">Laporan Layanan</span>
                            <?php if ($isSvc): ?>
                                <div class="absolute right-0 top-1.5 h-8 w-1 rounded-l-lg bg-brand-500 dark:bg-brand-400"></div>
                            <?php endif; ?>
                        </a>
                    </nav>
                </div>

                <!-- Section: Publik -->
                <div>
                    <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Publik</p>
                    <nav class="space-y-1">
                        <a href="<?= base_url('track') ?>" target="_blank" class="relative flex items-center justify-between py-2.5 px-4 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-navy-700 dark:hover:text-white hover:bg-lightPrimary/40 dark:hover:bg-navy-700/30 transition-all">
                            <span class="flex items-center">
                                <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                                <span class="ml-3 leading-none">Cek Status Publik</span>
                            </span>
                            <i data-lucide="external-link" class="w-4 h-4 text-gray-400"></i>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Horizon Sidebar Promotion/Info Card -->
            <div class="px-5 py-4">
                <div class="relative flex flex-col items-center rounded-[20px] bg-gradient-to-br from-brand-500 to-brand-600 p-4 text-center text-white shadow-lg shadow-brand-500/25">
                    <div class="absolute -top-6 flex h-12 w-12 items-center justify-center rounded-full border-4 border-white bg-white dark:bg-navy-800 dark:border-navy-800 shadow-md overflow-hidden">
                        <img src="<?= base_url('logo-laundry.png') ?>" alt="Logo" class="w-full h-full object-cover rounded-full">
                    </div>
                    <h4 class="mt-4 font-bold text-sm">Laundry Modern Hub</h4>
                    <p class="text-[11px] text-brand-100 mt-1 leading-snug">Kelola cucian pelanggan lebih cepat, rapi, dan otomatis.</p>
                    <a href="<?= base_url('transactions/create') ?>" class="mt-3 w-full rounded-xl bg-white/20 hover:bg-white/30 py-2 text-xs font-bold text-white backdrop-blur transition-all">
                        + Order Baru
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="xl:pl-[290px] flex flex-col flex-1 min-w-0 font-dm">
            <!-- Floating Horizon UI Navbar -->
            <header class="sticky top-3 z-40 mx-3 md:mx-6 mt-3">
                <div class="flex flex-row flex-wrap items-center justify-between rounded-2xl bg-white/80 dark:bg-navy-800/80 p-2.5 backdrop-blur-xl shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5">
                    <!-- Left: Breadcrumb & Page Title -->
                    <div class="ml-2">
                        <div class="h-6">
                            <span class="text-xs font-normal text-gray-500 dark:text-gray-400">
                                Pages <span class="mx-1">/</span>
                            </span>
                            <span class="text-xs font-semibold text-navy-700 dark:text-white capitalize">
                                <?= esc($pageTitle ?? 'Dashboard') ?>
                            </span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold capitalize text-navy-700 dark:text-white tracking-tight">
                            <?= esc($pageTitle ?? 'Dashboard') ?>
                        </h2>
                    </div>

                    <!-- Right: Floating Pill Bar -->
                    <div class="relative mt-1 sm:mt-0 flex h-[54px] items-center justify-around gap-2 rounded-full bg-lightPrimary dark:bg-navy-900 px-3 py-1.5 shadow-inner">
                        <!-- Search form shortcut -->
                        <form action="<?= base_url('transactions') ?>" method="GET" class="hidden md:flex h-full items-center rounded-full bg-white dark:bg-navy-800 text-navy-700 dark:text-white px-3 shadow-sm">
                            <i data-lucide="search" class="w-4 h-4 text-gray-400 dark:text-gray-300"></i>
                            <input type="text" name="search" placeholder="Cari transaksi..." class="block h-full w-28 lg:w-40 bg-transparent text-xs font-medium text-navy-700 dark:text-white outline-none pl-2 placeholder:text-gray-400 dark:placeholder:text-gray-500">
                        </form>

                        <!-- Mobile Hamburger button -->
                        <button type="button" @click="sidebarOpen = true" class="xl:hidden p-2 text-gray-600 dark:text-gray-300 hover:text-navy-700 dark:hover:text-white focus:outline-none">
                            <i data-lucide="menu" class="w-5 h-5"></i>
                        </button>

                        <!-- Dark Mode Toggle Button -->
                        <button type="button" @click="toggleDark()" class="p-2 text-gray-600 dark:text-gray-300 hover:text-navy-700 dark:hover:text-white rounded-full transition-colors" title="Ubah Tema (Dark/Light)">
                            <template x-if="isDark">
                                <i data-lucide="sun" class="w-5 h-5 text-amber-400"></i>
                            </template>
                            <template x-if="!isDark">
                                <i data-lucide="moon" class="w-5 h-5 text-gray-600"></i>
                            </template>
                        </button>

                        <!-- Notifications Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.outside="open = false" class="p-2 text-gray-600 dark:text-gray-300 hover:text-navy-700 dark:hover:text-white rounded-full transition-colors relative" title="Notifikasi">
                                <i data-lucide="bell" class="w-5 h-5"></i>
                                <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-brand-500"></span>
                            </button>
                            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="absolute right-0 mt-3 w-80 sm:w-96 rounded-[20px] bg-white dark:bg-navy-800 p-4 shadow-3xl shadow-shadow-500 dark:shadow-none border border-gray-100 dark:border-white/10 z-50">
                                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-white/10">
                                    <p class="text-sm font-bold text-navy-700 dark:text-white font-poppins">Pemberitahuan Laundry</p>
                                    <span class="text-xs font-semibold text-brand-500">3 Baru</span>
                                </div>
                                <div class="mt-3 space-y-2.5 text-xs max-h-72 overflow-y-auto">
                                    <!-- Notification 1 -->
                                    <a href="<?= base_url('transactions?status=siap_diambil') ?>" class="p-2.5 rounded-xl bg-lightPrimary dark:bg-navy-700/50 hover:bg-brand-50 dark:hover:bg-navy-700 flex items-start gap-2.5 transition-colors">
                                        <div class="p-2 rounded-lg bg-emerald-500/10 text-emerald-500 flex-shrink-0 mt-0.5">
                                            <i data-lucide="package-check" class="w-4 h-4"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="font-bold text-navy-700 dark:text-white">Cucian Siap Diambil</p>
                                                <span class="text-[10px] text-gray-400">Baru saja</span>
                                            </div>
                                            <p class="text-gray-500 dark:text-gray-400 text-[11px] mt-0.5 leading-snug">Pesanan yang telah selesai dipacking siap diserahkan ke pelanggan.</p>
                                        </div>
                                    </a>

                                    <!-- Notification 2 -->
                                    <a href="<?= base_url('transactions?payment_status=lunas') ?>" class="p-2.5 rounded-xl bg-lightPrimary dark:bg-navy-700/50 hover:bg-brand-50 dark:hover:bg-navy-700 flex items-start gap-2.5 transition-colors">
                                        <div class="p-2 rounded-lg bg-brand-500/10 text-brand-500 flex-shrink-0 mt-0.5">
                                            <i data-lucide="wallet" class="w-4 h-4"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="font-bold text-navy-700 dark:text-white">Pembayaran Diterima</p>
                                                <span class="text-[10px] text-gray-400">10 mnt lalu</span>
                                            </div>
                                            <p class="text-gray-500 dark:text-gray-400 text-[11px] mt-0.5 leading-snug">Kas masuk pembayaran tagihan laundry telah diverifikasi.</p>
                                        </div>
                                    </a>

                                    <!-- Notification 3 -->
                                    <a href="<?= base_url('transactions') ?>" class="p-2.5 rounded-xl bg-lightPrimary dark:bg-navy-700/50 hover:bg-brand-50 dark:hover:bg-navy-700 flex items-start gap-2.5 transition-colors">
                                        <div class="p-2 rounded-lg bg-amber-500/10 text-amber-500 flex-shrink-0 mt-0.5">
                                            <i data-lucide="clock" class="w-4 h-4"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="font-bold text-navy-700 dark:text-white">Pengingat Jadwal</p>
                                                <span class="text-[10px] text-gray-400">Hari ini</span>
                                            </div>
                                            <p class="text-gray-500 dark:text-gray-400 text-[11px] mt-0.5 leading-snug">Periksa antrian cucian yang mendekati batas waktu estimasi selesai.</p>
                                        </div>
                                    </a>
                                </div>

                                <div class="pt-2.5 mt-2 border-t border-gray-100 dark:border-white/10 text-center">
                                    <a href="<?= base_url('transactions') ?>" class="text-[11px] font-bold text-brand-500 hover:text-brand-600 dark:text-brand-400 inline-flex items-center gap-1">
                                        <span>Lihat Semua Aktivitas Transaksi</span>
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- User Profile Avatar & Dropdown -->
                        <div class="relative" x-data="{ userOpen: false }">
                            <button @click="userOpen = !userOpen" @click.outside="userOpen = false" class="flex items-center focus:outline-none">
                                <img src="<?= base_url('img/avatars/avatar4.png') ?>" alt="Avatar" class="h-9 w-9 rounded-full object-cover ring-2 ring-brand-500/30">
                            </button>
                            <div x-show="userOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="absolute right-0 mt-3 w-60 rounded-[20px] bg-white dark:bg-navy-800 p-4 shadow-3xl shadow-shadow-500 dark:shadow-none border border-gray-100 dark:border-white/10 z-50">
                                <div class="pb-3 border-b border-gray-100 dark:border-white/10">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">👋 Halo,</p>
                                    <p class="text-sm font-bold text-navy-700 dark:text-white truncate"><?= esc(session()->get('userName') ?? 'Admin Laundry') ?></p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-brand-50 text-brand-600 dark:bg-brand-900/60 dark:text-brand-300 mt-1">
                                        <?= esc(session()->get('userRole') ?? 'admin') ?>
                                    </span>
                                </div>
                                <div class="mt-3 flex flex-col space-y-2 text-xs">
                                    <a href="<?= base_url('profile') ?>" class="flex items-center gap-2 py-1.5 px-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-lightPrimary dark:hover:bg-navy-700 font-medium transition-colors">
                                        <i data-lucide="user" class="w-4 h-4 text-brand-500"></i>
                                        Pengaturan Akun
                                    </a>
                                    <a href="<?= base_url('logout') ?>" class="flex items-center gap-2 py-1.5 px-2 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 font-semibold transition-colors">
                                        <i data-lucide="log-out" class="w-4 h-4"></i>
                                        Keluar (Logout)
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Container -->
            <main class="flex-1 p-3 md:p-6 max-w-7xl w-full mx-auto">
                <!-- Flash Alerts -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 flex items-center justify-between p-4 rounded-[20px] bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                            </div>
                            <p class="text-sm font-semibold"><?= session()->getFlashdata('success') ?></p>
                        </div>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 p-1">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 flex items-center justify-between p-4 rounded-[20px] bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="alert-circle" class="w-5 h-5"></i>
                            </div>
                            <p class="text-sm font-semibold"><?= session()->getFlashdata('error') ?></p>
                        </div>
                        <button @click="show = false" class="text-rose-500 hover:text-rose-700 p-1">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                <?php endif; ?>

                <!-- Render Specific Page Content -->
                <?= $this->renderSection('content') ?>
            </main>

            <!-- Horizon Footer -->
            <footer class="mt-auto py-5 text-center text-xs text-gray-500 dark:text-gray-400 font-medium">
                <p>&copy; <?= date('Y') ?> Horizon Laundry UI. All Rights Reserved. Built with CodeIgniter 4.</p>
            </footer>
        </div>
    </div>

    <!-- Mobile Sidebar Drawer -->
    <div x-show="sidebarOpen" x-cloak class="relative z-50 xl:hidden" role="dialog" aria-modal="true">
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-navy-900/80 backdrop-blur-sm"></div>
        <div class="fixed inset-0 flex">
            <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative mr-16 flex-1 w-full max-w-xs bg-white dark:bg-navy-800 text-navy-700 dark:text-white flex flex-col shadow-2xl">
                <div class="flex items-center justify-between px-6 h-20 border-b border-gray-100 dark:border-white/10">
                    <div class="flex items-center gap-3">
                        <img src="<?= base_url('logo-laundry.png') ?>" alt="Logo" class="w-9 h-9 object-cover rounded-full">
                        <span class="font-poppins font-bold text-lg text-navy-700 dark:text-white">HORIZON <span class="font-normal text-brand-500">LAUNDRY</span></span>
                    </div>
                    <button @click="sidebarOpen = false" class="p-2 text-gray-500 dark:text-gray-400 hover:text-navy-700 dark:hover:text-white">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto px-4 py-4 space-y-2">
                    <a href="<?= base_url('dashboard') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium hover:bg-lightPrimary dark:hover:bg-navy-700">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 text-brand-500"></i> Dashboard
                    </a>
                    <a href="<?= base_url('transactions/create') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium hover:bg-lightPrimary dark:hover:bg-navy-700">
                        <i data-lucide="plus-circle" class="w-5 h-5 text-brand-500"></i> Buat Transaksi
                    </a>
                    <a href="<?= base_url('transactions') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium hover:bg-lightPrimary dark:hover:bg-navy-700">
                        <i data-lucide="receipt" class="w-5 h-5 text-brand-500"></i> Daftar Transaksi
                    </a>
                    <a href="<?= base_url('customers') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium hover:bg-lightPrimary dark:hover:bg-navy-700">
                        <i data-lucide="users" class="w-5 h-5 text-brand-500"></i> Data Pelanggan
                    </a>
                    <a href="<?= base_url('services') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium hover:bg-lightPrimary dark:hover:bg-navy-700">
                        <i data-lucide="sparkle" class="w-5 h-5 text-brand-500"></i> Layanan & Tarif
                    </a>
                    <a href="<?= base_url('reports/revenue') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium hover:bg-lightPrimary dark:hover:bg-navy-700">
                        <i data-lucide="bar-chart-3" class="w-5 h-5 text-brand-500"></i> Laporan Pendapatan
                    </a>
                    <a href="<?= base_url('reports/services') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium hover:bg-lightPrimary dark:hover:bg-navy-700">
                        <i data-lucide="pie-chart" class="w-5 h-5 text-brand-500"></i> Laporan Layanan
                    </a>
                    <a href="<?= base_url('profile') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium hover:bg-lightPrimary dark:hover:bg-navy-700">
                        <i data-lucide="user" class="w-5 h-5 text-brand-500"></i> Profil & Password
                    </a>
                    <a href="<?= base_url('logout') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30">
                        <i data-lucide="log-out" class="w-5 h-5"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>

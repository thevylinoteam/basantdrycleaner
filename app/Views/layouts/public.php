<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Track Your Order - Basant Dry Cleaners') ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= 'https://basantdrycleaners.com/wp-content/uploads/2022/02/basantdrycleaners.png' ?>">
    
    <!-- Google Fonts: DM Sans & Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    
    <!-- Dark Mode Initializer -->
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
                        blueSecondary: '#1CB6FC',
                        brandLinear: '#89B92E',
                        brand: {
                            50: '#E5F7FF',
                            100: '#BAEAFE',
                            200: '#8DDCFC',
                            300: '#55CBFC',
                            400: '#1CB6FC',
                            500: '#0876AE',
                            600: '#086496',
                            700: '#14517C',
                            800: '#183862',
                            900: '#112B4C',
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
                        }
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
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'DM Sans', sans-serif; }
    </style>
</head>
<body class="min-h-full flex flex-col font-dm antialiased text-navy-700 dark:text-white bg-background-100 dark:bg-background-900 transition-colors duration-200" x-data="{
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
    <!-- Floating Header Bar -->
    <header class="sticky top-3 z-30 mx-4 sm:mx-6 lg:mx-auto max-w-5xl w-full mt-3">
        <div class="px-5 py-3 rounded-2xl bg-white/80 dark:bg-navy-800/80 backdrop-blur-xl shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="<?= 'https://basantdrycleaners.com/wp-content/uploads/2022/02/basantdrycleaners.png' ?>" alt="Logo Basant Dry Cleaners" class="w-10 h-10 object-contain bg-white rounded-lg drop-shadow-md">
                <div>
                    <h1 class="font-poppins font-bold text-base text-navy-700 dark:text-white leading-none">Basant <span class="text-brand-500 font-normal">Dry Cleaners</span></h1>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium">Real-time Order Tracking</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" @click="toggleDark()" class="p-2 text-gray-500 dark:text-gray-400 hover:text-navy-700 dark:hover:text-white rounded-full transition-colors" title="Toggle Dark/Light Mode">
                    <template x-if="isDark">
                        <i data-lucide="sun" class="w-4 h-4 text-amber-400"></i>
                    </template>
                    <template x-if="!isDark">
                        <i data-lucide="moon" class="w-4 h-4"></i>
                    </template>
                </button>
                <a href="<?= base_url('login') ?>" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-navy-700 dark:text-white bg-lightPrimary dark:bg-navy-700 hover:bg-brand-50 dark:hover:bg-navy-600 border border-gray-200 dark:border-navy-600 transition-colors">
                    <i data-lucide="lock" class="w-3.5 h-3.5 text-brand-500"></i>
                    Login
                </a>
            </div>
        </div>
    </header>

    <!-- Main Public Content -->
    <main class="flex-1 max-w-5xl w-full mx-auto p-4 sm:p-6 lg:p-8">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="mt-auto py-6 text-center text-xs text-gray-500 dark:text-gray-400 font-medium">
        <p>&copy; <?= date('Y') ?> Basant Dry Cleaners. All Rights Reserved.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>

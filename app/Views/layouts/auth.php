<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sign In - Basant Dry Cleaners Management') ?></title>
    
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
<body class="h-full bg-white dark:bg-navy-900 text-navy-700 dark:text-white transition-colors duration-200">
    <div class="relative min-h-screen flex">
        <!-- Left Side: Main Form Content -->
        <div class="mx-auto flex min-h-full w-full flex-col justify-start pt-8 pb-8 md:max-w-[75%] lg:max-w-[1013px] lg:px-8 lg:pt-0 xl:min-h-screen xl:max-w-[1383px] xl:px-0 xl:pl-[70px]">
            <div class="mb-auto flex flex-col pl-5 pr-5 md:pl-12 md:pr-0 lg:max-w-[48%] lg:pl-0 xl:max-w-full">
                <!-- Back Link -->
                <div class="mt-4 lg:pt-8 w-max">
                    <a href="<?= base_url('track') ?>" class="flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-navy-700 dark:hover:text-white transition-colors">
                        <i data-lucide="chevron-left" class="w-4 h-4 text-gray-400"></i>
                        <span>Back to Order Tracking</span>
                    </a>
                </div>

                <!-- Page / Form Section -->
                <?= $this->renderSection('content') ?>
            </div>

            <!-- Auth Footer -->
            <div class="mt-6 flex flex-col items-center justify-between gap-2 px-5 text-xs text-gray-400 md:flex-row md:px-0 lg:max-w-[48%]">
                <p>&copy; <?= date('Y') ?> Basant Dry Cleaners. All Rights Reserved.</p>
                <div class="flex items-center gap-4">
                    <a href="<?= base_url('track') ?>" class="hover:text-brand-500">Track Order</a>
                    <a href="mailto:bookings@basantdrycleaners.com" class="hover:text-brand-500">Help</a>
                </div>
            </div>
        </div>

        <!-- Right Side: Clean Modern Gradient Art Banner (Desktop) -->
        <div class="absolute right-0 top-0 bottom-0 hidden h-full min-h-screen md:block lg:w-[49vw] 2xl:w-[44vw]">
            <div class="relative flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-500 via-brand-600 to-navy-800 lg:rounded-bl-[120px] xl:rounded-bl-[200px] overflow-hidden">
                <!-- Ambient Glow Circles -->
                <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-brand-400/30 blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-blueSecondary/40 blur-3xl pointer-events-none"></div>

                <div class="relative z-10 text-center px-12 text-white max-w-lg">
                    <div class="inline-flex h-24 w-24 items-center justify-center rounded-3xl bg-white/15 backdrop-blur-xl border border-white/25 mb-6 shadow-2xl p-3.5">
                        <img src="<?= 'https://basantdrycleaners.com/wp-content/uploads/2022/02/basantdrycleaners.png' ?>" alt="Logo Basant Dry Cleaners" class="w-full h-full object-contain bg-white rounded-lg drop-shadow-md">
                    </div>
                    <h2 class="font-poppins text-3xl xl:text-4xl font-bold tracking-tight text-white drop-shadow-sm">Basant Dry Cleaners</h2>
                    <p class="mt-4 text-sm xl:text-base text-brand-100/90 font-normal leading-relaxed">
                        Professional garment care since 1969. Manage orders, payments and service progress in one place.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>

<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="mt-8 lg:mt-16 w-full max-w-full flex-col items-center md:pl-4 lg:pl-0 xl:max-w-[420px]">
    <!-- Brand Header -->
    <div class="flex items-center gap-3 mb-6">
        <img src="<?= base_url('logo-laundry.png') ?>" alt="Basant Dry Cleaners logo" class="w-11 h-11 object-contain bg-white rounded-lg drop-shadow">
        <div>
            <h2 class="font-poppins text-lg font-bold text-navy-700 dark:text-white leading-none">Basant <span class="text-brand-500 font-normal">Dry Cleaners</span></h2>
            <span class="text-[11px] text-gray-400 font-medium">Staff & Admin Portal</span>
        </div>
    </div>

    <!-- Header Title -->
    <h3 class="mb-2 text-2xl sm:text-3xl font-bold text-navy-700 dark:text-white font-poppins">
        Sign In
    </h3>
    <p class="mb-6 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
        Enter your email and password to sign in.
    </p>

    <!-- Error Alert -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-5 p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs flex items-center gap-2.5">
            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-500 flex-shrink-0"></i>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-xs flex items-center gap-2.5">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500 flex-shrink-0"></i>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <!-- Login Form -->
    <form action="<?= base_url('login') ?>" method="POST" class="space-y-4">
        <?= csrf_field() ?>
        
        <!-- Email Field -->
        <div>
            <label for="email" class="block text-xs font-semibold text-navy-700 dark:text-white mb-1.5">Email<span class="text-brand-500">*</span></label>
            <div class="relative">
                <input type="email" name="email" id="email" required placeholder="you@example.com" value="<?= old('email') ?>" class="w-full h-12 px-4 bg-lightPrimary dark:bg-navy-900 border border-gray-200 dark:border-navy-700 rounded-xl text-sm font-medium text-navy-700 dark:text-white placeholder:text-gray-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
            </div>
        </div>

        <!-- Password Field -->
        <div>
            <label for="password" class="block text-xs font-semibold text-navy-700 dark:text-white mb-1.5">Password<span class="text-brand-500">*</span></label>
            <div class="relative">
                <input type="password" name="password" id="password" required placeholder="Enter your password" class="w-full h-12 px-4 bg-lightPrimary dark:bg-navy-900 border border-gray-200 dark:border-navy-700 rounded-xl text-sm font-medium text-navy-700 dark:text-white placeholder:text-gray-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
            </div>
        </div>

        <div class="flex items-center justify-between py-1">
            <label class="flex items-center text-xs text-navy-700 dark:text-gray-300 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded text-brand-500 border-gray-300 focus:ring-brand-500 mr-2">
                Remember me
            </label>
            <span class="text-xs text-gray-400">Forgot password? Contact your administrator</span>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full h-12 rounded-xl bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white font-bold text-sm tracking-wide shadow-lg shadow-brand-500/25 transition-all flex items-center justify-center gap-2">
            <span>Sign In</span>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </button>
    </form>


</div>
<?= $this->endSection() ?>

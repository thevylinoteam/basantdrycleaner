<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="mt-8 lg:mt-16 w-full max-w-full flex-col items-center md:pl-4 lg:pl-0 xl:max-w-[420px]" x-data="{
    fillCreds(email, pass) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = pass;
    }
}">
    <!-- Brand Header -->
    <div class="flex items-center gap-3 mb-6">
        <img src="<?= base_url('logo-laundry.png') ?>" alt="Logo Laundry" class="w-11 h-11 object-cover rounded-full drop-shadow">
        <div>
            <h2 class="font-poppins text-lg font-bold text-navy-700 dark:text-white leading-none">Horizon <span class="text-brand-500 font-normal">Laundry</span></h2>
            <span class="text-[11px] text-gray-400 font-medium">Portal Karyawan & Admin</span>
        </div>
    </div>

    <!-- Header Title -->
    <h3 class="mb-2 text-2xl sm:text-3xl font-bold text-navy-700 dark:text-white font-poppins">
        Sign In
    </h3>
    <p class="mb-6 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
        Masukkan email dan password untuk masuk ke sistem laundry!
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
                <input type="email" name="email" id="email" required placeholder="admin@laundry.com" value="<?= old('email') ?>" class="w-full h-12 px-4 bg-lightPrimary dark:bg-navy-900 border border-gray-200 dark:border-navy-700 rounded-xl text-sm font-medium text-navy-700 dark:text-white placeholder:text-gray-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
            </div>
        </div>

        <!-- Password Field -->
        <div>
            <label for="password" class="block text-xs font-semibold text-navy-700 dark:text-white mb-1.5">Password<span class="text-brand-500">*</span></label>
            <div class="relative">
                <input type="password" name="password" id="password" required placeholder="Min. 6 karakter" class="w-full h-12 px-4 bg-lightPrimary dark:bg-navy-900 border border-gray-200 dark:border-navy-700 rounded-xl text-sm font-medium text-navy-700 dark:text-white placeholder:text-gray-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-colors">
            </div>
        </div>

        <div class="flex items-center justify-between py-1">
            <label class="flex items-center text-xs text-navy-700 dark:text-gray-300 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded text-brand-500 border-gray-300 focus:ring-brand-500 mr-2">
                Ingat saya
            </label>
            <span class="text-xs text-gray-400">Hubungi Administrator jika lupa</span>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full h-12 rounded-xl bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white font-bold text-sm tracking-wide shadow-lg shadow-brand-500/25 transition-all flex items-center justify-center gap-2">
            <span>Masuk Sekarang</span>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </button>
    </form>

    <!-- Quick Demo Accounts Helper -->
    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-white/10">
        <p class="text-xs text-gray-500 dark:text-gray-400 text-center font-medium mb-3">Akun Demo (Klik untuk auto-fill):</p>
        <div class="flex items-center justify-center gap-2.5">
            <button type="button" @click="fillCreds('admin@laundry.com', 'admin123')" class="px-3.5 py-2 rounded-xl bg-lightPrimary dark:bg-navy-800 hover:bg-brand-50 dark:hover:bg-navy-700 text-navy-700 dark:text-white text-xs font-semibold border border-gray-200 dark:border-navy-700 transition-colors flex items-center gap-1.5 shadow-sm">
                <i data-lucide="shield-check" class="w-4 h-4 text-brand-500"></i>
                Admin (admin123)
            </button>
            <button type="button" @click="fillCreds('kasir@laundry.com', 'kasir123')" class="px-3.5 py-2 rounded-xl bg-lightPrimary dark:bg-navy-800 hover:bg-brand-50 dark:hover:bg-navy-700 text-navy-700 dark:text-white text-xs font-semibold border border-gray-200 dark:border-navy-700 transition-colors flex items-center gap-1.5 shadow-sm">
                <i data-lucide="user-check" class="w-4 h-4 text-emerald-500"></i>
                Kasir (kasir123)
            </button>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

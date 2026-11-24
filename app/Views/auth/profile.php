<?php
/** @var array $user */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-navy-700 dark:text-white font-poppins tracking-tight">Account Settings</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Manage your profile and password security</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="rounded-[20px] bg-white dark:bg-navy-800 p-6 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5 space-y-4 text-center md:text-left">
            <div class="flex flex-col items-center md:items-start">
                <div class="relative mb-4">
                    <img src="<?= base_url('img/avatars/avatar4.png') ?>" alt="Avatar" class="w-24 h-24 rounded-full object-cover ring-4 ring-brand-500/20 shadow-lg">
                    <div class="absolute bottom-1 right-1 w-5 h-5 rounded-full bg-emerald-500 border-2 border-white dark:border-navy-800"></div>
                </div>
                <h3 class="text-lg font-bold text-navy-700 dark:text-white font-poppins"><?= esc($user['name'] ?? '-') ?></h3>
                <p class="text-xs text-gray-500 dark:text-gray-400"><?= esc($user['email'] ?? '-') ?></p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-50 text-brand-600 dark:bg-brand-900/50 dark:text-brand-300 mt-3">
                    Role: <?= esc($user['role'] ?? 'admin') ?>
                </span>
            </div>

            <div class="pt-4 border-t border-gray-100 dark:border-white/10 text-xs text-gray-500 dark:text-gray-400 space-y-1">
                <p>Member since:</p>
                <p class="font-bold text-navy-700 dark:text-white"><?= date('d M Y, H:i', strtotime($user['created_at'] ?? 'now')) ?></p>
            </div>
        </div>

        <!-- Change Password Form -->
        <div class="md:col-span-2 rounded-[20px] bg-white dark:bg-navy-800 p-6 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5">
            <div class="flex items-center gap-2 pb-4 mb-5 border-b border-gray-100 dark:border-white/10">
                <i data-lucide="key" class="w-5 h-5 text-brand-500"></i>
                <h3 class="text-base font-bold text-navy-700 dark:text-white font-poppins">Change Password</h3>
            </div>

            <form action="<?= base_url('profile/change-password') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>

                <div>
                    <label for="current_password" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Current Password</label>
                    <div class="relative">
                        <input type="password" name="current_password" id="current_password" required placeholder="Enter current password" class="w-full px-4 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="new_password" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">New Password</label>
                        <input type="password" name="new_password" id="new_password" required minlength="6" placeholder="At least 6 characters" class="w-full px-4 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                    </div>
                    <div>
                        <label for="new_password_confirm" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Confirm New Password</label>
                        <input type="password" name="new_password_confirm" id="new_password_confirm" required minlength="6" placeholder="Repeat new password" class="w-full px-4 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                    </div>
                </div>

                <div class="pt-4 flex justify-end border-t border-gray-100 dark:border-white/10">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-brand-500/25 transition-all">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

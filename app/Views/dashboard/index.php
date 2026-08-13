<?php
/** @var array $stats */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Top Row: Welcome & Quick Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-700 dark:text-white font-poppins tracking-tight">Ringkasan Operasional</h1>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-1">Pantau transaksi dan progres cucian laundry hari ini (<?= date('d F Y') ?>)</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="<?= base_url('transactions/create') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-brand-500/25 transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Transaksi Baru
            </a>
            <a href="<?= base_url('customers') ?>" class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-white dark:bg-navy-800 hover:bg-lightPrimary dark:hover:bg-navy-700 text-navy-700 dark:text-white text-xs font-bold rounded-xl border border-gray-200 dark:border-navy-700 shadow-sm transition-colors">
                <i data-lucide="users" class="w-4 h-4 text-brand-500"></i>
                Pelanggan
            </a>
        </div>
    </div>

    <!-- 4 Horizon UI Metric Widgets -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
        <!-- Widget 1: Transaksi Hari Ini -->
        <div class="relative flex items-center rounded-[20px] bg-white dark:bg-navy-800 p-4 sm:p-5 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5">
            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-lightPrimary dark:bg-navy-700 text-brand-500 dark:text-brand-400 flex-shrink-0">
                <i data-lucide="shopping-bag" class="w-7 h-7"></i>
            </div>
            <div class="ml-4 flex flex-col justify-center min-w-0">
                <p class="font-dm text-xs font-medium text-gray-500 dark:text-gray-400">Transaksi Hari Ini</p>
                <h4 class="text-xl sm:text-2xl font-bold text-navy-700 dark:text-white font-poppins mt-0.5">
                    <?= number_format($stats['today_transactions_count'] ?? 0) ?>
                </h4>
                <a href="<?= base_url('transactions?start_date=' . date('Y-m-d')) ?>" class="text-[11px] font-semibold text-brand-500 hover:text-brand-600 mt-1 inline-flex items-center gap-1">
                    Detail pesanan &rarr;
                </a>
            </div>
        </div>

        <!-- Widget 2: Pendapatan Hari Ini -->
        <div class="relative flex items-center rounded-[20px] bg-white dark:bg-navy-800 p-4 sm:p-5 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5">
            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-500 dark:text-emerald-400 flex-shrink-0">
                <i data-lucide="wallet" class="w-7 h-7"></i>
            </div>
            <div class="ml-4 flex flex-col justify-center min-w-0">
                <p class="font-dm text-xs font-medium text-gray-500 dark:text-gray-400">Pendapatan Hari Ini</p>
                <h4 class="text-lg sm:text-xl font-bold text-navy-700 dark:text-white font-poppins mt-0.5 truncate">
                    Rp <?= number_format($stats['today_revenue'] ?? 0, 0, ',', '.') ?>
                </h4>
                <a href="<?= base_url('reports/revenue') ?>" class="text-[11px] font-semibold text-emerald-500 hover:text-emerald-600 mt-1 inline-flex items-center gap-1">
                    Laporan keuangan &rarr;
                </a>
            </div>
        </div>

        <!-- Widget 3: Siap Diambil -->
        <div class="relative flex items-center rounded-[20px] bg-white dark:bg-navy-800 p-4 sm:p-5 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5">
            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-500 dark:text-amber-400 flex-shrink-0">
                <i data-lucide="package-check" class="w-7 h-7"></i>
            </div>
            <div class="ml-4 flex flex-col justify-center min-w-0">
                <p class="font-dm text-xs font-medium text-gray-500 dark:text-gray-400">Siap Diambil</p>
                <h4 class="text-xl sm:text-2xl font-bold text-amber-500 font-poppins mt-0.5">
                    <?= number_format($stats['ready_for_pickup_count'] ?? 0) ?>
                </h4>
                <a href="<?= base_url('transactions?status=siap_diambil') ?>" class="text-[11px] font-semibold text-amber-500 hover:text-amber-600 mt-1 inline-flex items-center gap-1">
                    Menunggu ambil &rarr;
                </a>
            </div>
        </div>

        <!-- Widget 4: Portal Pelacak Publik -->
        <div class="relative flex items-center rounded-[20px] bg-gradient-to-br from-brand-600 to-brand-700 p-4 sm:p-5 text-white shadow-3xl shadow-brand-500/20">
            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-white/20 backdrop-blur-md text-white flex-shrink-0">
                <i data-lucide="search" class="w-7 h-7"></i>
            </div>
            <div class="ml-4 flex flex-col justify-center min-w-0">
                <p class="font-dm text-xs font-medium text-brand-100">Portal Publik</p>
                <h4 class="text-base sm:text-lg font-bold text-white font-poppins mt-0.5">Cek Resi Tanpa Login</h4>
                <a href="<?= base_url('track') ?>" target="_blank" class="text-[11px] font-semibold text-brand-200 hover:text-white mt-1 inline-flex items-center gap-1">
                    Buka Pelacak &nearr;
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Transactions Table Card -->
    <div class="rounded-[20px] bg-white dark:bg-navy-800 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-white/10 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-navy-700 dark:text-white font-poppins">Transaksi Terbaru</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">5 transaksi terakhir yang tercatat di sistem</p>
            </div>
            <a href="<?= base_url('transactions') ?>" class="text-xs font-bold text-brand-500 hover:text-brand-600 inline-flex items-center gap-1">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Kode Transaksi</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Pelanggan</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Total Harga</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Status Pengerjaan</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Pembayaran</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Waktu Order</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5 text-navy-700 dark:text-white font-medium">
                    <?php if (empty($stats['recent_transactions'])): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-400 dark:text-gray-500">
                                Belum ada transaksi yang tercatat.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($stats['recent_transactions'] as $tx): ?>
                            <tr class="hover:bg-lightPrimary/50 dark:hover:bg-navy-700/40 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-brand-500 dark:text-brand-400">
                                    <?= esc($tx['transaction_code']) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-navy-700 dark:text-white"><?= esc($tx['customer_name']) ?></p>
                                    <p class="text-[11px] text-gray-400"><?= esc($tx['customer_phone']) ?></p>
                                </td>
                                <td class="px-6 py-4 font-bold text-navy-700 dark:text-white font-poppins">
                                    Rp <?= number_format($tx['total_price'], 0, ',', '.') ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                        $statusBadges = [
                                            'diterima'     => 'bg-gray-100 text-gray-600 dark:bg-navy-700 dark:text-gray-300',
                                            'dicuci'       => 'bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-300',
                                            'dikeringkan'  => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-300',
                                            'disetrika'    => 'bg-purple-50 text-purple-600 dark:bg-purple-950/50 dark:text-purple-300',
                                            'siap_diambil' => 'bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-300 font-bold',
                                            'selesai'      => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-300',
                                        ];
                                        $badgeClass = $statusBadges[$tx['status']] ?? 'bg-gray-100 text-gray-600';
                                    ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold capitalize <?= $badgeClass ?>">
                                        <?= str_replace('_', ' ', $tx['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                        $payBadges = [
                                            'belum_bayar' => 'bg-rose-50 text-rose-600 dark:bg-rose-950/50 dark:text-rose-300',
                                            'dp'          => 'bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-300',
                                            'lunas'       => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-300 font-bold',
                                        ];
                                        $pBadge = $payBadges[$tx['payment_status']] ?? 'bg-gray-100 text-gray-600';
                                    ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold uppercase tracking-wider <?= $pBadge ?>">
                                        <?= str_replace('_', ' ', $tx['payment_status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-400 dark:text-gray-400 text-[11px]">
                                    <?= date('d M Y H:i', strtotime($tx['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="<?= base_url('transactions/detail/' . $tx['id']) ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-lightPrimary dark:bg-navy-700 hover:bg-brand-50 dark:hover:bg-navy-600 text-navy-700 dark:text-white text-xs font-bold transition-colors">
                                        <i data-lucide="eye" class="w-3.5 h-3.5 text-brand-500"></i>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

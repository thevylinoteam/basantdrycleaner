<?php
/** @var string|null $start_date */
/** @var string|null $end_date */
/** @var float $total_revenue */
/** @var array $report */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-700 dark:text-white font-poppins tracking-tight">Laporan Performa Layanan</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Statistik volume penggunaan dan kontribusi omzet per jenis paket cucian</p>
        </div>
        <div>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-navy-800 hover:bg-lightPrimary dark:hover:bg-navy-700 text-navy-700 dark:text-white text-xs font-bold rounded-xl border border-gray-200 dark:border-navy-700 shadow-sm transition-colors">
                <i data-lucide="printer" class="w-4 h-4 text-brand-500"></i>
                Cetak Laporan
            </button>
        </div>
    </div>

    <!-- Filter Date Range -->
    <div class="rounded-[20px] bg-white dark:bg-navy-800 p-5 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5 no-print">
        <form action="<?= base_url('reports/services') ?>" method="GET" class="flex flex-col sm:flex-row items-end gap-3">
            <div class="flex-1 w-full grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="<?= esc($start_date ?? '') ?>" class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-xs sm:text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="<?= esc($end_date ?? '') ?>" class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-xs sm:text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                </div>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-brand-500/25 transition-all flex items-center justify-center gap-1.5">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    Terapkan Filter
                </button>
                <?php if (!empty($start_date) || !empty($end_date)): ?>
                    <a href="<?= base_url('reports/services') ?>" class="px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-700 hover:bg-gray-200 text-gray-500 dark:text-gray-300 text-xs font-bold rounded-xl transition-colors">
                        Reset
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Table of Services Breakdown -->
    <div class="rounded-[20px] bg-white dark:bg-navy-800 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-white/10 flex items-center justify-between">
            <h3 class="text-sm font-bold text-navy-700 dark:text-white font-poppins">Performa Paket Layanan Laundry</h3>
            <span class="text-xs font-bold text-navy-700 dark:text-white">Total Akumulasi: <strong class="text-brand-500 font-poppins font-bold">Rp <?= number_format($total_revenue ?? 0, 0, ',', '.') ?></strong></span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Nama Layanan</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Tipe Paket</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Jumlah Order</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Total Volume</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400 text-right">Kontribusi Omzet</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5 text-navy-700 dark:text-white font-medium">
                    <?php if (empty($report)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400 dark:text-gray-500">
                                Belum ada data transaksi layanan pada periode ini.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($report as $row): ?>
                            <tr class="hover:bg-lightPrimary/50 dark:hover:bg-navy-700/40 transition-colors">
                                <td class="px-6 py-4 font-bold text-navy-700 dark:text-white text-sm">
                                    <?= esc($row['service_name']) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold capitalize bg-lightPrimary dark:bg-navy-700 text-navy-700 dark:text-white">
                                        <?= esc($row['service_type']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <?= (int)$row['transaction_count'] ?> order
                                </td>
                                <td class="px-6 py-4 font-mono font-bold text-navy-700 dark:text-white">
                                    <?= (float)$row['total_qty'] ?> <?= esc($row['unit']) ?>
                                </td>
                                <td class="px-6 py-4 font-poppins font-bold text-emerald-500 text-right text-sm">
                                    Rp <?= number_format($row['total_revenue'], 0, ',', '.') ?>
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

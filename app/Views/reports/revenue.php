<?php 
/** @var string|null $start_date */
/** @var string|null $end_date */
/** @var float $total_revenue */
/** @var int $total_payments */
/** @var array $report */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-700 dark:text-white font-poppins tracking-tight">Revenue Report</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Review order payments by date range</p>
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
        <form action="<?= base_url('reports/revenue') ?>" method="GET" class="flex flex-col sm:flex-row items-end gap-3">
            <div class="flex-1 w-full grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Start Date</label>
                    <input type="date" name="start_date" value="<?= esc($start_date ?? '') ?>" class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-xs sm:text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">End Date</label>
                    <input type="date" name="end_date" value="<?= esc($end_date ?? '') ?>" class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-xs sm:text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                </div>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-brand-500/25 transition-all flex items-center justify-center gap-1.5">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    Terapkan Filter
                </button>
                <?php if (!empty($start_date) || !empty($end_date)): ?>
                    <a href="<?= base_url('reports/revenue') ?>" class="px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-700 hover:bg-gray-200 text-gray-500 dark:text-gray-300 text-xs font-bold rounded-xl transition-colors">
                        Reset
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-5">
        <div class="relative flex items-center justify-between rounded-[20px] bg-gradient-to-br from-brand-500 to-brand-600 p-6 text-white shadow-3xl shadow-brand-500/25">
            <div>
                <p class="text-xs font-medium text-brand-100">Total Revenue:</p>
                <h4 class="text-2xl sm:text-3xl font-bold font-poppins mt-1">₹ <?= number_format($total_revenue ?? 0, 2, '.', ',') ?></h4>
                <p class="text-[11px] text-brand-100 mt-2 font-medium">
                    <?= (!empty($start_date) || !empty($end_date)) ? "Period: " . ($start_date ?: 'Awal') . " s/d " . ($end_date ?: 'Sekarang') : "All Dates" ?>
                </p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white flex-shrink-0">
                <i data-lucide="wallet" class="w-8 h-8"></i>
            </div>
        </div>

        <div class="relative flex items-center justify-between rounded-[20px] bg-white dark:bg-navy-800 p-6 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5">
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Payment Count:</p>
                <h4 class="text-2xl sm:text-3xl font-bold text-navy-700 dark:text-white font-poppins mt-1"><?= number_format($total_payments ?? 0) ?></h4>
                <p class="text-[11px] text-gray-400 mt-2 font-medium">Total successful payments</p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-lightPrimary dark:bg-navy-700 flex items-center justify-center text-brand-500 dark:text-brand-400 flex-shrink-0">
                <i data-lucide="receipt" class="w-8 h-8"></i>
            </div>
        </div>
    </div>

    <!-- Breakdown Table -->
    <div class="rounded-[20px] bg-white dark:bg-navy-800 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-white/10">
            <h3 class="text-sm font-bold text-navy-700 dark:text-white font-poppins">Daily Revenue</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Date</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Payment Count</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400 text-right">Total Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5 text-navy-700 dark:text-white font-medium">
                    <?php if (empty($report)): ?>
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-400 dark:text-gray-500">
                                Tidak ada data pendapatan pada rentang tanggal ini.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($report as $row): ?>
                            <tr class="hover:bg-lightPrimary/50 dark:hover:bg-navy-700/40 transition-colors">
                                <td class="px-6 py-4 font-bold text-navy-700 dark:text-white text-sm">
                                    <?= date('d F Y', strtotime($row['date'])) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-lightPrimary dark:bg-navy-700 text-navy-700 dark:text-white font-semibold">
                                        <?= (int)$row['payment_count'] ?> kali bayar
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-poppins font-bold text-emerald-500 text-right text-sm">
                                    ₹ <?= number_format($row['total_revenue'], 2, '.', ',') ?>
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

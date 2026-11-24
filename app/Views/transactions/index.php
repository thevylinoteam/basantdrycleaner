<?php
/** @var array $transactions */
/** @var array $filters */
/** @var \CodeIgniter\Pager\Pager|null $pager */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-700 dark:text-white font-poppins tracking-tight">Laundry Orders</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Manage orders, progress and payments</p>
        </div>
        <div>
            <a href="<?= base_url('transactions/create') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-brand-500/25 transition-all">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                Create New Order
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="rounded-[20px] bg-white dark:bg-navy-800 p-5 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5">
        <form action="<?= base_url('transactions') ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Search (Order / Customer / Phone)</label>
                <div class="relative">
                    <input type="text" name="search" value="<?= esc($filters['search'] ?? '') ?>" placeholder="Search..." class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-xs sm:text-sm font-medium text-navy-700 dark:text-white placeholder:text-gray-400 focus:outline-none focus:border-brand-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Order Status</label>
                <select name="status" class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-xs sm:text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                    <option value="">All Statuses</option>
                    <option value="diterima" <?= ($filters['status'] ?? '') == 'diterima' ? 'selected' : '' ?>>Received</option>
                    <option value="dicuci" <?= ($filters['status'] ?? '') == 'dicuci' ? 'selected' : '' ?>>Washing</option>
                    <option value="dikeringkan" <?= ($filters['status'] ?? '') == 'dikeringkan' ? 'selected' : '' ?>>Drying</option>
                    <option value="disetrika" <?= ($filters['status'] ?? '') == 'disetrika' ? 'selected' : '' ?>>Ironing</option>
                    <option value="siap_diambil" <?= ($filters['status'] ?? '') == 'siap_diambil' ? 'selected' : '' ?>>Ready for Collection</option>
                    <option value="selesai" <?= ($filters['status'] ?? '') == 'selesai' ? 'selected' : '' ?>>Completed</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Payment Status</label>
                <select name="payment_status" class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-xs sm:text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                    <option value="">All Payments</option>
                    <option value="belum_bayar" <?= ($filters['payment_status'] ?? '') == 'belum_bayar' ? 'selected' : '' ?>>Unpaid</option>
                    <option value="dp" <?= ($filters['payment_status'] ?? '') == 'dp' ? 'selected' : '' ?>>Partially Paid</option>
                    <option value="lunas" <?= ($filters['payment_status'] ?? '') == 'lunas' ? 'selected' : '' ?>>Paid</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-brand-500/25 transition-all flex items-center justify-center gap-1.5">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    Filter
                </button>
                <a href="<?= base_url('transactions') ?>" class="px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-700 hover:bg-gray-200 text-gray-500 dark:text-gray-300 text-xs font-bold rounded-xl transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Transactions Table Card -->
    <div class="rounded-[20px] bg-white dark:bg-navy-800 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Order Code</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Customer</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Total Due</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Order Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Payment</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Estimated Completion</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5 text-navy-700 dark:text-white font-medium">
                    <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-400 dark:text-gray-500">
                                No orders match the selected filters.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $tx): ?>
                            <tr class="hover:bg-lightPrimary/50 dark:hover:bg-navy-700/40 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-mono font-bold text-brand-500 dark:text-brand-400 text-sm block"><?= esc($tx['transaction_code']) ?></span>
                                    <span class="text-[11px] text-gray-400"><?= date('d M Y H:i', strtotime($tx['created_at'])) ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-navy-700 dark:text-white"><?= esc($tx['customer_name']) ?></p>
                                    <p class="text-[11px] text-gray-400"><?= esc($tx['customer_phone']) ?></p>
                                </td>
                                <td class="px-6 py-4 font-bold text-navy-700 dark:text-white font-poppins text-sm">
                                    ₹ <?= number_format($tx['total_price'], 2, '.', ',') ?>
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
                                        <?= esc(basant_label($tx['status'])) ?>
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
                                        <?= esc(basant_label($tx['payment_status'])) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-400 dark:text-gray-400 text-[11px]">
                                    <?= !empty($tx['estimated_done_at']) ? date('d M Y, H:i', strtotime($tx['estimated_done_at'])) : '-' ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="<?= base_url('transactions/detail/' . $tx['id']) ?>" class="p-2 rounded-xl bg-lightPrimary dark:bg-navy-700 hover:bg-brand-50 hover:text-brand-600 text-gray-600 dark:text-gray-300 transition-colors" title="Order Details">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="<?= base_url('transactions/print/' . $tx['id']) ?>" target="_blank" class="p-2 rounded-xl bg-lightPrimary dark:bg-navy-700 hover:bg-teal-50 hover:text-teal-600 text-gray-600 dark:text-gray-300 transition-colors" title="Print Receipt">
                                            <i data-lucide="printer" class="w-4 h-4"></i>
                                        </a>
                                        <button type="button" @click="confirmDelete('<?= base_url('transactions/delete/' . $tx['id']) ?>', 'Delete Order', 'Archive this order?')" class="p-2 rounded-xl bg-lightPrimary dark:bg-navy-700 hover:bg-rose-50 hover:text-rose-600 text-gray-600 dark:text-gray-300 transition-colors" title="Delete Order">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Horizon Pagination -->
        <?php if (isset($pager)): ?>
            <?= $pager->links('default', 'horizon_full') ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

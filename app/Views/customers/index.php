<?php
/** @var array $customers */
/** @var string|null $search */
/** @var \CodeIgniter\Pager\Pager|null $pager */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6" x-data="{ addModalOpen: false }">
    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-700 dark:text-white font-poppins tracking-tight">Customers</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Search registered customers by name or mobile number</p>
        </div>
        <div>
            <button type="button" @click="addModalOpen = true" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-brand-500/25 transition-all">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                Add Customer
            </button>
        </div>
    </div>

    <!-- Search Box -->
    <div class="rounded-[20px] bg-white dark:bg-navy-800 p-5 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5">
        <form action="<?= base_url('customers') ?>" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="Search by customer name or mobile..." class="w-full pl-10 pr-4 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-xs sm:text-sm font-medium text-navy-700 dark:text-white placeholder:text-gray-400 focus:outline-none focus:border-brand-500">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold rounded-xl transition-all shadow-sm flex items-center gap-1.5">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    Cari Data
                </button>
                <?php if (!empty($search)): ?>
                    <a href="<?= base_url('customers') ?>" class="px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-700 hover:bg-gray-200 text-gray-500 dark:text-gray-300 text-xs font-semibold rounded-xl transition-colors">
                        Reset
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Customer Table Card -->
    <div class="rounded-[20px] bg-white dark:bg-navy-800 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">No</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Customer Name</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Mobile / WhatsApp</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Address</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Registered</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5 text-navy-700 dark:text-white font-medium">
                    <?php if (empty($customers)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400 dark:text-gray-500">
                                Tidak ada data pelanggan ditemukan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php 
                            $currentPage = (int) (service('request')->getGet('page') ?? 1);
                            $no = 1 + (10 * max(0, $currentPage - 1));
                            foreach ($customers as $c): 
                        ?>
                            <tr class="hover:bg-lightPrimary/50 dark:hover:bg-navy-700/40 transition-colors">
                                <td class="px-6 py-4 text-gray-400 font-mono"><?= $no++ ?></td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-navy-700 dark:text-white text-sm"><?= esc($c['name']) ?></p>
                                    <p class="text-[11px] text-gray-400">Customer ID: #<?= $c['id'] ?></p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono text-navy-700 dark:text-white font-semibold"><?= esc($c['phone']) ?></span>
                                        <?php
                                            $cleanPhone = preg_replace('/[^0-9]/', '', $c['phone']);
                                            $waPhone = basant_whatsapp($cleanPhone);
                                        ?>
                                        <a href="https://wa.me/<?= $waPhone ?>" target="_blank" class="p-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-500 hover:bg-emerald-100 transition-colors" title="Send WhatsApp Message">
                                            <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                    <?= esc($c['address'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-4 text-gray-400 dark:text-gray-400 text-[11px]">
                                    <?= date('d M Y', strtotime($c['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="<?= base_url('customers/edit/' . $c['id']) ?>" class="p-2 rounded-xl bg-lightPrimary dark:bg-navy-700 hover:bg-brand-50 hover:text-brand-600 text-gray-600 dark:text-gray-300 transition-colors" title="Edit Customer">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                        <button type="button" @click="confirmDelete('<?= base_url('customers/delete/' . $c['id']) ?>', 'Delete Customer', 'Archive this customer?')" class="p-2 rounded-xl bg-lightPrimary dark:bg-navy-700 hover:bg-rose-50 hover:text-rose-600 text-gray-600 dark:text-gray-300 transition-colors" title="Delete Customer">
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

    <!-- Quick Add Customer Modal -->
    <div x-show="addModalOpen" x-cloak class="relative z-50" role="dialog" aria-modal="true">
        <div x-show="addModalOpen" x-transition.opacity class="fixed inset-0 bg-navy-900/80 backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 flex items-center justify-center">
            <div x-show="addModalOpen" x-transition class="relative transform overflow-hidden rounded-[20px] bg-white dark:bg-navy-800 p-6 text-left shadow-3xl shadow-shadow-500 dark:shadow-none transition-all w-full max-w-md border border-white/40 dark:border-white/10">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-white/10">
                    <h3 class="text-base font-bold text-navy-700 dark:text-white font-poppins">Add Customer</h3>
                    <button type="button" @click="addModalOpen = false" class="text-gray-400 hover:text-navy-700 dark:hover:text-white">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="<?= base_url('customers/store') ?>" method="POST" class="space-y-4 mt-4">
                    <?= csrf_field() ?>
                    <div>
                        <label for="name" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Full Name *</label>
                        <input type="text" name="name" id="name" required placeholder="Example: Ravi Sharma" class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-xs sm:text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Mobile / WhatsApp *</label>
                        <input type="text" name="phone" id="phone" required placeholder="9876543210" class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-xs sm:text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                    </div>

                    <div>
                        <label for="address" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Address (Optional)</label>
                        <textarea name="address" id="address" rows="2" placeholder="Street address..." class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-xs sm:text-sm text-navy-700 dark:text-white focus:outline-none focus:border-brand-500"></textarea>
                    </div>

                    <div class="pt-3 flex justify-end gap-2">
                        <button type="button" @click="addModalOpen = false" class="px-4 py-2.5 rounded-xl bg-lightPrimary dark:bg-navy-700 hover:bg-gray-200 text-gray-600 dark:text-gray-300 text-xs font-bold transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold shadow-lg shadow-brand-500/25 transition-all flex items-center gap-1.5">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

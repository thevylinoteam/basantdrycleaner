<?php
/** @var array $services */
/** @var \CodeIgniter\Pager\Pager|null $pager */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6" x-data="{ addModalOpen: false }">
    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-700 dark:text-white font-poppins tracking-tight">Services & Pricing</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Manage services, pricing, units and turnaround times</p>
        </div>
        <div>
            <button type="button" @click="addModalOpen = true" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-brand-500/25 transition-all">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                Add New Service
            </button>
        </div>
    </div>

    <!-- Services Table Card -->
    <div class="rounded-[20px] bg-white dark:bg-navy-800 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">No</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Service Name</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Package Type</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Unit of Measure</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Rate / Price</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Estimated Completion</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5 text-navy-700 dark:text-white font-medium">
                    <?php if (empty($services)): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-400 dark:text-gray-500">
                                No services available yet.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php 
                            $currentPage = (int) (service('request')->getGet('page') ?? 1);
                            $no = 1 + (10 * max(0, $currentPage - 1));
                            foreach ($services as $s): 
                        ?>
                            <tr class="hover:bg-lightPrimary/50 dark:hover:bg-navy-700/40 transition-colors">
                                <td class="px-6 py-4 text-gray-400 font-mono"><?= $no++ ?></td>
                                <td class="px-6 py-4 font-bold text-navy-700 dark:text-white text-sm">
                                    <?= esc($s['name']) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                        $typeBadges = [
                                            'kiloan'  => 'bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-300',
                                            'satuan'  => 'bg-purple-50 text-purple-600 dark:bg-purple-950/50 dark:text-purple-300',
                                            'ekspres' => 'bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-300 font-bold',
                                        ];
                                        $tBadge = $typeBadges[$s['type']] ?? 'bg-gray-100 text-gray-600';
                                    ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold capitalize <?= $tBadge ?>">
                                        <?= esc($s['type']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-mono uppercase text-gray-500 dark:text-gray-400">
                                    / <?= esc($s['unit']) ?>
                                </td>
                                <td class="px-6 py-4 font-bold text-navy-700 dark:text-white font-poppins text-sm">
                                    Rp <?= number_format($s['price'], 0, ',', '.') ?>
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-brand-500"></i>
                                        <span><?= esc($s['duration_estimate_hours']) ?> Hours</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($s['is_active']): ?>
                                        <span class="inline-flex items-center gap-1.5 text-emerald-500 font-bold text-xs">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            Active
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 text-gray-400 font-medium text-xs">
                                            <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                            Inactive
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="<?= base_url('services/edit/' . $s['id']) ?>" class="p-2 rounded-xl bg-lightPrimary dark:bg-navy-700 hover:bg-brand-50 hover:text-brand-600 text-gray-600 dark:text-gray-300 transition-colors" title="Edit Service">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                        <button type="button" @click="confirmDelete('<?= base_url('services/delete/' . $s['id']) ?>', 'Delete Service', 'Are you sure you want to archive this service?')" class="p-2 rounded-xl bg-lightPrimary dark:bg-navy-700 hover:bg-rose-50 hover:text-rose-600 text-gray-600 dark:text-gray-300 transition-colors" title="Delete Service">
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

    <!-- Quick Add Service Modal -->
    <div x-show="addModalOpen" x-cloak class="relative z-50" role="dialog" aria-modal="true">
        <div x-show="addModalOpen" x-transition.opacity class="fixed inset-0 bg-navy-900/80 backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 flex items-center justify-center">
            <div x-show="addModalOpen" x-transition class="relative transform overflow-hidden rounded-[20px] bg-white dark:bg-navy-800 p-6 text-left shadow-3xl shadow-shadow-500 dark:shadow-none transition-all w-full max-w-md border border-white/40 dark:border-white/10">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-white/10">
                    <h3 class="text-base font-bold text-navy-700 dark:text-white font-poppins">Add New Service</h3>
                    <button type="button" @click="addModalOpen = false" class="text-gray-400 hover:text-navy-700 dark:hover:text-white">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="<?= base_url('services/store') ?>" method="POST" class="space-y-4 mt-4">
                    <?= csrf_field() ?>
                    <div>
                        <label for="svc_name" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Service Name *</label>
                        <input type="text" name="name" id="svc_name" required placeholder="e.g. Regular Laundry" class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-xs sm:text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="svc_type" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Type *</label>
                            <select name="type" id="svc_type" required class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-xs sm:text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                                <option value="kiloan">By Weight</option>
                                <option value="satuan">Per Item</option>
                                <option value="ekspres">Express</option>
                            </select>
                        </div>
                        <div>
                            <label for="svc_unit" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Per Item *</label>
                            <select name="unit" id="svc_unit" required class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-xs sm:text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                                <option value="kg">Kilogram (kg)</option>
                                <option value="pcs">Pieces (pcs)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="svc_price" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Tarif (Rp) *</label>
                            <input type="number" name="price" id="svc_price" required min="0" step="500" placeholder="7000" class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-xs sm:text-sm font-bold text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                        </div>
                        <div>
                            <label for="svc_duration" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Duration (Hours) *</label>
                            <input type="number" name="duration_estimate_hours" id="svc_duration" required min="1" placeholder="24" class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-xs sm:text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                        </div>
                    </div>

                    <div class="pt-3 flex justify-end gap-2 border-t border-gray-100 dark:border-white/10">
                        <button type="button" @click="addModalOpen = false" class="px-4 py-2.5 rounded-xl bg-lightPrimary dark:bg-navy-700 hover:bg-gray-200 text-gray-600 dark:text-gray-300 text-xs font-bold transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-brand-500/25 transition-all flex items-center gap-1.5">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            Save Service
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

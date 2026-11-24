<?php /** @var array $service */ ?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-navy-700 dark:text-white font-poppins tracking-tight">Edit Service</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Update service information</p>
        </div>
        <a href="<?= base_url('services') ?>" class="text-xs font-bold text-brand-500 hover:text-brand-600 flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back
        </a>
    </div>

    <div class="rounded-[20px] bg-white dark:bg-navy-800 p-6 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5">
        <form action="<?= base_url('services/update/' . $service['id']) ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label for="name" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Service Name *</label>
                <input type="text" name="name" id="name" required value="<?= esc(old('name', $service['name'])) ?>" class="w-full px-4 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="type" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Service Type *</label>
                    <select name="type" id="type" required class="w-full px-4 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                        <option value="kiloan" <?= $service['type'] == 'kiloan' ? 'selected' : '' ?>>Per kg</option>
                        <option value="satuan" <?= $service['type'] == 'satuan' ? 'selected' : '' ?>>Per item</option>
                        <option value="ekspres" <?= $service['type'] == 'ekspres' ? 'selected' : '' ?>>Express</option>
                    </select>
                </div>
                <div>
                    <label for="unit" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Unit of Measure *</label>
                    <select name="unit" id="unit" required class="w-full px-4 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                        <option value="kg" <?= $service['unit'] == 'kg' ? 'selected' : '' ?>>Kilogram (kg)</option>
                        <option value="pcs" <?= $service['unit'] == 'pcs' ? 'selected' : '' ?>>Pieces (pcs)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Price (₹) *</label>
                    <input type="number" name="price" id="price" required min="0" step="0.01" value="<?= esc(old('price', $service['price'])) ?>" class="w-full px-4 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-sm font-bold text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                </div>
                <div>
                    <label for="duration_estimate_hours" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Estimated Turnaround (Hours) *</label>
                    <input type="number" name="duration_estimate_hours" id="duration_estimate_hours" required min="1" value="<?= esc(old('duration_estimate_hours', $service['duration_estimate_hours'])) ?>" class="w-full px-4 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                </div>
            </div>

            <div class="pt-3 flex justify-end gap-2 border-t border-gray-100 dark:border-white/10">
                <a href="<?= base_url('services') ?>" class="px-4 py-2.5 rounded-xl bg-lightPrimary dark:bg-navy-700 hover:bg-gray-200 text-gray-600 dark:text-gray-300 text-xs font-bold transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-brand-500/25 transition-all flex items-center gap-1.5">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

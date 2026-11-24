<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-navy-700 dark:text-white font-poppins tracking-tight">Add New Customer</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Enter customer details for laundry orders</p>
        </div>
        <a href="<?= base_url('customers') ?>" class="text-xs font-bold text-brand-500 hover:text-brand-600 flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back
        </a>
    </div>

    <div class="rounded-[20px] bg-white dark:bg-navy-800 p-6 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5">
        <form action="<?= base_url('customers/store') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label for="name" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Customer Full Name *</label>
                <input type="text" name="name" id="name" required placeholder="e.g. Customer Name" value="<?= old('name') ?>" class="w-full px-4 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
            </div>

            <div>
                <label for="phone" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">WhatsApp / Mobile Number *</label>
                <input type="text" name="phone" id="phone" required placeholder="e.g. Mobile number" value="<?= old('phone') ?>" class="w-full px-4 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                <p class="text-[11px] text-gray-400 mt-1">This number is used by customers to track their orders.</p>
            </div>

            <div>
                <label for="address" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Address (Optional)</label>
                <textarea name="address" id="address" rows="3" placeholder="Street address..." class="w-full px-4 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-sm text-navy-700 dark:text-white focus:outline-none focus:border-brand-500"><?= old('address') ?></textarea>
            </div>

            <div class="pt-3 flex justify-end gap-2 border-t border-gray-100 dark:border-white/10">
                <a href="<?= base_url('customers') ?>" class="px-4 py-2.5 rounded-xl bg-lightPrimary dark:bg-navy-700 hover:bg-gray-200 text-gray-600 dark:text-gray-300 text-xs font-bold transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-brand-500/25 transition-all flex items-center gap-1.5">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Save Customer
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

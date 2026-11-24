<?php
/** @var array $services */
/** @var array $customers */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6" x-data="{
    servicesList: <?= htmlspecialchars(json_encode($services ?? []), ENT_QUOTES, 'UTF-8') ?>,
    customerId: '',
    items: [
        { service_id: '', qty: 1, unit: 'kg', price: 0, subtotal: 0 }
    ],
    paymentAmount: 0,
    paymentMethod: 'tunai',
    notes: '',

    addItem() {
        this.items.push({ service_id: '', qty: 1, unit: 'kg', price: 0, subtotal: 0 });
    },
    removeItem(index) {
        if (this.items.length > 1) {
            this.items.splice(index, 1);
        }
    },
    onServiceChange(index) {
        const selectedId = this.items[index].service_id;
        const svc = this.servicesList.find(s => s.id == selectedId);
        if (svc) {
            this.items[index].price = parseFloat(svc.price);
            this.items[index].unit = svc.unit;
            this.calculateSubtotal(index);
        } else {
            this.items[index].price = 0;
            this.items[index].subtotal = 0;
        }
    },
    calculateSubtotal(index) {
        const item = this.items[index];
        const qty = parseFloat(item.qty) || 0;
        item.subtotal = qty * item.price;
    },
    getTotalPrice() {
        return this.items.reduce((sum, item) => sum + (parseFloat(item.subtotal) || 0), 0);
    },
    setFullPayment() {
        this.paymentAmount = this.getTotalPrice();
    },
    formatRupiah(num) {
        return new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', minimumFractionDigits: 2 }).format(num || 0);
    }
}">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-navy-700 dark:text-white font-poppins tracking-tight">Create New Order</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Select a customer and requested services</p>
        </div>
        <a href="<?= base_url('transactions') ?>" class="text-xs font-bold text-brand-500 hover:text-brand-600 flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Orders
        </a>
    </div>

    <!-- Main Order Form -->
    <form action="<?= base_url('transactions/store') ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>

        <!-- Step 1: Customer Selection Card -->
        <div class="rounded-[20px] bg-white dark:bg-navy-800 p-6 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5 space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-white/10 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-brand-50 dark:bg-brand-900/40 text-brand-500 font-bold text-xs flex items-center justify-center">
                        1
                    </div>
                    <h3 class="text-sm font-bold text-navy-700 dark:text-white font-poppins">Select Customer</h3>
                </div>
                <a href="<?= base_url('customers/create') ?>" target="_blank" class="text-xs text-brand-500 hover:text-brand-600 font-bold flex items-center gap-1">
                    <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                    + New Customer
                </a>
            </div>

            <div>
                <label for="customer_id" class="block text-xs font-bold text-navy-700 dark:text-white mb-2">Customer <span class="text-brand-500">*</span></label>
                <select name="customer_id" id="customer_id" required x-model="customerId" class="w-full px-4 py-3 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                    <option value="">-- Select a Customer --</option>
                    <?php if (!empty($customers)): ?>
                        <?php foreach ($customers as $c): ?>
                            <option value="<?= $c['id'] ?>">
                                <?= esc($c['name']) ?> (<?= esc($c['phone']) ?>) - <?= esc($c['address'] ?? 'Address -') ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <!-- Step 2: Dynamic Services Selection Card -->
        <div class="rounded-[20px] bg-white dark:bg-navy-800 p-6 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5 space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-white/10 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-brand-50 dark:bg-brand-900/40 text-brand-500 font-bold text-xs flex items-center justify-center">
                        2
                    </div>
                    <h3 class="text-sm font-bold text-navy-700 dark:text-white font-poppins">Order Items</h3>
                </div>
                <button type="button" @click="addItem()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-lightPrimary dark:bg-navy-700 hover:bg-brand-50 dark:hover:bg-navy-600 text-brand-500 dark:text-brand-300 text-xs font-bold transition-colors">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Add Item
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(item, index) in items" :key="index">
                    <div class="p-4 rounded-xl bg-lightPrimary dark:bg-navy-900 border border-gray-100 dark:border-navy-700 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <!-- Service Select -->
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-navy-700 dark:text-gray-300 mb-1.5" x-text="'Service #' + (index + 1)"></label>
                            <select :name="'items[' + index + '][service_id]'" x-model="item.service_id" @change="onServiceChange(index)" required class="w-full px-3.5 py-2.5 bg-white dark:bg-navy-800 border border-gray-200 dark:border-navy-700 rounded-xl text-xs font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                                <option value="">-- Select a Service --</option>
                                <template x-for="svc in servicesList" :key="svc.id">
                                    <option :value="svc.id" x-text="svc.name + ' (' + formatRupiah(svc.price) + '/' + svc.unit + ')'"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Qty -->
                        <div class="w-full sm:w-28">
                            <label class="block text-xs font-bold text-navy-700 dark:text-gray-300 mb-1.5" x-text="'Quantity (' + item.unit + ')'"></label>
                            <input type="number" :name="'items[' + index + '][qty]'" x-model="item.qty" @input="calculateSubtotal(index)" required min="0.1" step="0.1" class="w-full px-3.5 py-2.5 bg-white dark:bg-navy-800 border border-gray-200 dark:border-navy-700 rounded-xl text-xs font-mono font-bold text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                        </div>

                        <!-- Subtotal -->
                        <div class="w-full sm:w-36 text-left sm:text-right">
                            <span class="block text-xs font-bold text-navy-700 dark:text-gray-300 mb-1.5">Subtotal</span>
                            <span class="text-sm font-bold text-navy-700 dark:text-white block pt-1 font-poppins text-brand-500" x-text="formatRupiah(item.subtotal)"></span>
                        </div>

                        <!-- Delete Button -->
                        <div class="sm:pt-5 flex justify-end">
                            <button type="button" @click="removeItem(index)" :disabled="items.length === 1" class="p-2 rounded-xl text-gray-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/40 disabled:opacity-20 disabled:hover:bg-transparent transition-colors">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Grand Total Bar -->
            <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-brand-600 to-brand-700 text-white flex items-center justify-between shadow-lg shadow-brand-500/20">
                <div>
                    <span class="text-xs text-brand-100 block font-medium">Grand Total:</span>
                    <span class="text-xl sm:text-2xl font-bold font-poppins" x-text="formatRupiah(getTotalPrice())"></span>
                </div>
                <div class="text-right text-xs text-brand-100 font-medium">
                    <span x-text="items.length + ' Services Selected'"></span>
                </div>
            </div>
        </div>

        <!-- Step 3: Payment & Notes Card -->
        <div class="rounded-[20px] bg-white dark:bg-navy-800 p-6 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5 space-y-4">
            <div class="flex items-center gap-3 border-b border-gray-100 dark:border-white/10 pb-4">
                <div class="w-8 h-8 rounded-full bg-brand-50 dark:bg-brand-900/40 text-brand-500 font-bold text-xs flex items-center justify-center">
                    3
                </div>
                <h3 class="text-sm font-bold text-navy-700 dark:text-white font-poppins">Payment & Special Instructions</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="payment_amount" class="block text-xs font-bold text-navy-700 dark:text-white">Amount Paid Now (₹)</label>
                        <button type="button" @click="setFullPayment()" class="text-xs font-bold text-brand-500 hover:underline">
                            Pay in Full
                        </button>
                    </div>
                    <input type="number" name="payment_amount" id="payment_amount" x-model="paymentAmount" min="0" step="0.01" placeholder="0" class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-sm font-mono text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                    <p class="text-[11px] text-gray-400 mt-1">Leave blank to pay on collection.</p>
                </div>

                <div>
                    <label for="payment_method" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Payment Method</label>
                    <select name="payment_method" id="payment_method" x-model="paymentMethod" class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                        <option value="tunai">Cash</option>
                        <option value="transfer">Bank Transfer / UPI</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="notes" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Special Instructions (Optional)</label>
                <textarea name="notes" id="notes" rows="2" placeholder="Example: Separate white garments; avoid strong fragrance..." class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-sm text-navy-700 dark:text-white focus:outline-none focus:border-brand-500"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 flex justify-end gap-3 border-t border-gray-100 dark:border-white/10">
                <a href="<?= base_url('transactions') ?>" class="px-5 py-2.5 rounded-xl bg-lightPrimary dark:bg-navy-700 hover:bg-gray-200 text-gray-600 dark:text-gray-300 text-xs font-bold transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-brand-500/25 transition-all flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    Save Order & Issue Receipt
                </button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

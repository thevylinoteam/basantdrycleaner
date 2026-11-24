<?php
/** @var array $transaction */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6" x-data="{
    statusModalOpen: false,
    payModalOpen: false,
    selectedStatus: '<?= $transaction['status'] ?>',
    balanceDue: <?= (float) $transaction['balance_due'] ?>,
    totalPrice: <?= (float) $transaction['total_price'] ?>
}">
    <!-- Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-navy-700 dark:text-white font-poppins font-mono"><?= esc($transaction['transaction_code']) ?></h1>
                <?php
                    $payBadges = [
                        'belum_bayar' => 'bg-rose-50 text-rose-600 dark:bg-rose-950/50 dark:text-rose-300',
                        'dp'          => 'bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-300',
                        'lunas'       => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-300 font-bold',
                    ];
                    $pBadge = $payBadges[$transaction['payment_status']] ?? 'bg-gray-100 text-gray-600';
                ?>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider <?= $pBadge ?>">
                    <?= esc(['diterima'=>'Received','dicuci'=>'Washing','dikeringkan'=>'Drying','disetrika'=>'Ironing','siap_diambil'=>'Ready for Collection','selesai'=>'Completed','belum_bayar'=>'Unpaid','dp'=>'Partial Payment','lunas'=>'Paid','kiloan'=>'By Weight','satuan'=>'Per Item','ekspres'=>'Express','tunai'=>'Cash','transfer'=>'Bank Transfer','kasir'=>'Cashier'][$transaction['payment_status']] ?? str_replace('_', ' ', $transaction['payment_status'])) ?>
                </span>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Created on <?= date('d F Y, H:i', strtotime($transaction['created_at'])) ?> by cashier <span class="font-bold text-navy-700 dark:text-white"><?= esc($transaction['user_name'] ?? 'Admin') ?></span></p>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" @click="statusModalOpen = true" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-navy-700 hover:bg-navy-800 dark:bg-brand-500 dark:hover:bg-brand-600 text-white text-xs font-bold transition-all shadow-sm">
                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                Update Status
            </button>
            <?php if ($transaction['balance_due'] > 0): ?>
                <button type="button" @click="payModalOpen = true" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold transition-all shadow-lg shadow-brand-500/25">
                    <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                    Pay Bill
                </button>
            <?php endif; ?>
            <a href="<?= base_url('transactions/print/' . $transaction['id']) ?>" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-white dark:bg-navy-800 hover:bg-lightPrimary dark:hover:bg-navy-700 text-navy-700 dark:text-white text-xs font-bold border border-gray-200 dark:border-navy-700 transition-colors shadow-sm">
                <i data-lucide="printer" class="w-3.5 h-3.5 text-gray-400"></i>
                Print Receipt
            </a>
            <button type="button" onclick="confirmDelete('<?= base_url('transactions/delete/' . $transaction['id']) ?>', 'Delete Order', 'Are you sure you want to archive this order?')" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/50 text-rose-600 dark:text-rose-300 text-xs font-bold transition-colors shadow-sm" title="Delete Order">
                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                Delete
            </button>
        </div>
    </div>

    <!-- Visual Washing Status Progress Stepper -->
    <div class="rounded-[20px] bg-white dark:bg-navy-800 p-6 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5">
        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400 mb-6">Order Progress</h3>
        
        <?php
            $stages = [
                'diterima'     => ['label' => 'Received', 'icon' => 'inbox'],
                'dicuci'       => ['label' => 'Washing', 'icon' => 'waves'],
                'dikeringkan'  => ['label' => 'Drying', 'icon' => 'wind'],
                'disetrika'    => ['label' => 'Ironing', 'icon' => 'zap'],
                'siap_diambil' => ['label' => 'Ready for Collection', 'icon' => 'package-check'],
                'selesai'      => ['label' => 'Completed', 'icon' => 'check-circle-2'],
            ];
            $stageKeys = array_keys($stages);
            $currentIdx = array_search($transaction['status'], $stageKeys);
        ?>

        <div class="relative flex items-center justify-between">
            <div class="absolute left-6 right-6 top-1/2 -translate-y-1/2 h-1.5 bg-lightPrimary dark:bg-navy-900 z-0">
                <div class="h-full bg-brand-500 transition-all duration-500" style="width: <?= ($currentIdx / (count($stages) - 1)) * 100 ?>%;"></div>
            </div>

            <?php foreach ($stages as $key => $st): ?>
                <?php
                    $thisIdx = array_search($key, $stageKeys);
                    $isDone = $thisIdx < $currentIdx;
                    $isCurrent = $thisIdx === $currentIdx;

                    $stepCircleClass = 'border-2 border-gray-200 dark:border-navy-700 bg-white dark:bg-navy-800 text-gray-400';
                    if ($isDone) {
                        $stepCircleClass = 'bg-brand-500 text-white shadow-md shadow-brand-500/30';
                    } elseif ($isCurrent) {
                        $stepCircleClass = 'bg-navy-700 text-white ring-4 ring-brand-100 dark:ring-brand-900/50';
                    }
                ?>
                <div class="relative z-10 flex flex-col items-center group">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold transition-all <?= $stepCircleClass ?>">
                        <?php if ($isDone): ?>
                            <i data-lucide="check" class="w-4 h-4"></i>
                        <?php else: ?>
                            <i data-lucide="<?= $st['icon'] ?>" class="w-4 h-4"></i>
                        <?php endif; ?>
                    </div>
                    <span class="text-[11px] font-bold mt-2.5 <?= $isCurrent ? 'text-brand-500 dark:text-brand-400 font-extrabold' : ($isDone ? 'text-navy-700 dark:text-gray-200' : 'text-gray-400') ?>">
                        <?= $st['label'] ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($transaction['picked_up_at'])): ?>
            <div class="mt-6 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs text-center font-semibold flex items-center justify-center gap-2">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500"></i>
                Order was collected by the customer on <?= date('d M Y, H:i', strtotime($transaction['picked_up_at'])) ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- 2 Column Layout: Details & Payment Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Left 2 Cols: Customer Info & Items Breakdown -->
        <div class="md:col-span-2 space-y-6">
            <!-- Customer Card -->
            <div class="rounded-[20px] bg-white dark:bg-navy-800 p-5 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-white/10">
                    <div class="flex items-center gap-2.5">
                        <i data-lucide="user" class="w-4 h-4 text-brand-500"></i>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Customer Information</h3>
                    </div>
                    <?php
                        $cleanPhone = preg_replace('/[^0-9]/', '', $transaction['customer_phone']);
                        $waPhone = (strlen($cleanPhone) === 10 ? '91' . $cleanPhone : (strlen($cleanPhone) === 11 && str_starts_with($cleanPhone, '0') ? '91' . substr($cleanPhone, 1) : $cleanPhone));
                    ?>
                    <a href="https://wa.me/<?= $waPhone ?>?text=Hello%20<?= urlencode($transaction['customer_name']) ?>,%20your%20Basant%20Dry%20Cleaners%20order%20(<?= $transaction['transaction_code'] ?>)%20status:%20<?= esc(['diterima'=>'Received','dicuci'=>'Washing','dikeringkan'=>'Drying','disetrika'=>'Ironing','siap_diambil'=>'Ready for Collection','selesai'=>'Completed','belum_bayar'=>'Unpaid','dp'=>'Partial Payment','lunas'=>'Paid','kiloan'=>'By Weight','satuan'=>'Per Item','ekspres'=>'Express','tunai'=>'Cash','transfer'=>'Bank Transfer','kasir'=>'Cashier'][$transaction['status']] ?? str_replace('_', ' ', $transaction['status'])) ?>" target="_blank" class="text-xs font-bold text-emerald-500 hover:text-emerald-600 flex items-center gap-1">
                        <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                        Send WhatsApp
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-3 text-xs">
                    <div>
                        <p class="text-gray-400">Customer Name:</p>
                        <p class="font-bold text-navy-700 dark:text-white text-sm mt-0.5"><?= esc($transaction['customer_name']) ?></p>
                    </div>
                    <div>
                        <p class="text-gray-400">No. WhatsApp:</p>
                        <p class="font-mono font-bold text-navy-700 dark:text-white text-sm mt-0.5"><?= esc($transaction['customer_phone']) ?></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-400">Address:</p>
                        <p class="text-navy-700 dark:text-gray-300 mt-0.5"><?= esc($transaction['customer_address'] ?? 'Address not provided') ?></p>
                    </div>
                </div>
            </div>

            <!-- Items Breakdown Card -->
            <div class="rounded-[20px] bg-white dark:bg-navy-800 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-white/10">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Service & Garment Details</h3>
                </div>
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-white/10">
                            <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Service</th>
                            <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Quantity</th>
                            <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">Unit Price</th>
                            <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5 font-medium text-navy-700 dark:text-white">
                        <?php foreach ($transaction['items'] as $item): ?>
                            <tr class="hover:bg-lightPrimary/50 dark:hover:bg-navy-700/40">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-navy-700 dark:text-white"><?= esc($item['service_name']) ?></p>
                                    <span class="inline-block text-[10px] text-gray-400 uppercase"><?= esc(['diterima'=>'Received','dicuci'=>'Washing','dikeringkan'=>'Drying','disetrika'=>'Ironing','siap_diambil'=>'Ready for Collection','selesai'=>'Completed','belum_bayar'=>'Unpaid','dp'=>'Partial Payment','lunas'=>'Paid','kiloan'=>'By Weight','satuan'=>'Per Item','ekspres'=>'Express','tunai'=>'Cash','transfer'=>'Bank Transfer','kasir'=>'Cashier'][$item['service_type']] ?? str_replace('_', ' ', $item['service_type'])) ?></span>
                                </td>
                                <td class="px-6 py-4 font-mono font-bold">
                                    <?= (float)$item['qty'] ?> <?= esc($item['service_unit']) ?>
                                </td>
                                <td class="px-6 py-4 font-mono text-gray-500 dark:text-gray-400">
                                    Rp <?= number_format($item['price_per_unit'], 0, ',', '.') ?>
                                </td>
                                <td class="px-6 py-4 font-mono font-bold text-brand-500 dark:text-brand-400 text-right">
                                    Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Status History Timeline -->
            <div class="rounded-[20px] bg-white dark:bg-navy-800 p-6 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400 mb-4">Status History</h3>
                <div class="space-y-4">
                    <?php foreach ($transaction['histories'] as $h): ?>
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-full bg-lightPrimary dark:bg-navy-700 flex items-center justify-center text-brand-500 dark:text-brand-400 flex-shrink-0 mt-0.5">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                            </div>
                            <div class="flex-1 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-navy-700 dark:text-white capitalize"><?= esc(['diterima'=>'Received','dicuci'=>'Washing','dikeringkan'=>'Drying','disetrika'=>'Ironing','siap_diambil'=>'Ready for Collection','selesai'=>'Completed','belum_bayar'=>'Unpaid','dp'=>'Partial Payment','lunas'=>'Paid','kiloan'=>'By Weight','satuan'=>'Per Item','ekspres'=>'Express','tunai'=>'Cash','transfer'=>'Bank Transfer','kasir'=>'Cashier'][$h['status']] ?? str_replace('_', ' ', $h['status'])) ?></span>
                                    <span class="text-[11px] text-gray-400"><?= date('d M Y, H:i', strtotime($h['created_at'])) ?></span>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400 mt-0.5"><?= esc($h['notes'] ?? 'Status updated') ?> <span class="text-gray-400">oleh <?= esc($h['changed_by_name'] ?? 'Admin') ?></span></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Right Col: Payment Summary & Notes -->
        <div class="space-y-6">
            <!-- Payment Card -->
            <div class="rounded-[20px] bg-white dark:bg-navy-800 p-5 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400 pb-2 border-b border-gray-100 dark:border-white/10">Payment Summary</h3>

                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between text-gray-500 dark:text-gray-400">
                        <span>Total Bill:</span>
                        <span class="font-bold text-navy-700 dark:text-white font-mono text-sm">Rp <?= number_format($transaction['total_price'], 0, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between text-gray-500 dark:text-gray-400">
                        <span>Total Paid:</span>
                        <span class="font-bold text-emerald-500 font-mono text-sm">Rp <?= number_format($transaction['total_paid'], 0, ',', '.') ?></span>
                    </div>
                    <div class="pt-2.5 border-t border-gray-100 dark:border-white/10 flex justify-between items-center">
                        <span class="font-bold text-navy-700 dark:text-white">Outstanding Balance:</span>
                        <span class="font-extrabold text-base font-mono <?= $transaction['balance_due'] > 0 ? 'text-rose-500' : 'text-gray-400' ?>">
                            Rp <?= number_format($transaction['balance_due'], 0, ',', '.') ?>
                        </span>
                    </div>
                </div>

                <?php if ($transaction['balance_due'] > 0): ?>
                    <button type="button" @click="payModalOpen = true" class="w-full py-3 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-brand-500/25 transition-all flex items-center justify-center gap-1.5">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Record Payment
                    </button>
                <?php endif; ?>

                <!-- Payment Records -->
                <?php if (!empty($transaction['payments'])): ?>
                    <div class="pt-3 border-t border-gray-100 dark:border-white/10">
                        <p class="text-[11px] font-bold text-gray-400 uppercase mb-2">Payment History:</p>
                        <div class="space-y-2">
                            <?php foreach ($transaction['payments'] as $p): ?>
                                <div class="p-2.5 rounded-xl bg-lightPrimary dark:bg-navy-900 text-[11px] flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-navy-700 dark:text-white">Rp <?= number_format($p['amount'], 0, ',', '.') ?> (<?= esc(['diterima'=>'Received','dicuci'=>'Washing','dikeringkan'=>'Drying','disetrika'=>'Ironing','siap_diambil'=>'Ready for Collection','selesai'=>'Completed','belum_bayar'=>'Unpaid','dp'=>'Partial Payment','lunas'=>'Paid','kiloan'=>'By Weight','satuan'=>'Per Item','ekspres'=>'Express','tunai'=>'Cash','transfer'=>'Bank Transfer','kasir'=>'Cashier'][$p['method']] ?? str_replace('_', ' ', $p['method'])) ?>)</p>
                                        <p class="text-[10px] text-gray-400"><?= date('d M Y H:i', strtotime($p['paid_at'])) ?></p>
                                    </div>
                                    <span class="text-[10px] text-gray-400">Cashier: <?= esc($p['received_by_name'] ?? 'Admin') ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Notes Card -->
            <?php if (!empty($transaction['notes'])): ?>
                <div class="rounded-[20px] bg-amber-50/60 dark:bg-amber-950/30 p-5 border border-amber-200/60 dark:border-amber-800/40 text-xs">
                    <h3 class="font-bold text-amber-900 dark:text-amber-300 mb-1 flex items-center gap-1.5">
                        <i data-lucide="sticky-note" class="w-4 h-4 text-amber-500"></i>
                        Order Notes
                    </h3>
                    <p class="text-amber-800 dark:text-amber-200 leading-relaxed"><?= nl2br(esc($transaction['notes'])) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal 1: Update Status Modal -->
    <div x-show="statusModalOpen" x-cloak class="relative z-50" role="dialog" aria-modal="true">
        <div x-show="statusModalOpen" x-transition.opacity class="fixed inset-0 bg-navy-900/80 backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 flex items-center justify-center">
            <div x-show="statusModalOpen" x-transition class="relative transform overflow-hidden rounded-[20px] bg-white dark:bg-navy-800 p-6 text-left shadow-3xl shadow-shadow-500 dark:shadow-none transition-all w-full max-w-md border border-white/40 dark:border-white/10">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-white/10">
                    <h3 class="text-base font-bold text-navy-700 dark:text-white font-poppins">Update Order Status</h3>
                    <button type="button" @click="statusModalOpen = false" class="text-gray-400 hover:text-navy-700 dark:hover:text-white">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="<?= base_url('transactions/updateStatus/' . $transaction['id']) ?>" method="POST" class="space-y-4 mt-4">
                    <?= csrf_field() ?>
                    <div>
                        <label for="status" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">New Status *</label>
                        <select name="status" id="status" x-model="selectedStatus" required class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-xs sm:text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                            <option value="diterima">1. Received</option>
                            <option value="dicuci">2. Washing</option>
                            <option value="dikeringkan">3. Drying</option>
                            <option value="disetrika">4. Ironing</option>
                            <option value="siap_diambil">5. Ready for Collection</option>
                            <option value="selesai">6. Completed / Collected</option>
                        </select>
                    </div>

                    <!-- Warning Alert if selectedStatus == 'selesai' and balanceDue > 0 -->
                    <div x-show="selectedStatus === 'selesai' && balanceDue > 0" class="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs">
                        <p class="font-bold flex items-center gap-1.5">
                            <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                            Payment Required
                        </p>
                        <p class="mt-1">Order <strong>cannot be marked completed or collected</strong> because there is an outstanding balance of <strong class="font-mono">Rp <?= number_format($transaction['balance_due'], 0, ',', '.') ?></strong>. Please record the payment first.</p>
                    </div>

                    <div>
                        <label for="notes" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Additional Notes (Optional)</label>
                        <input type="text" name="notes" id="notes" placeholder="e.g. Ironed and packed neatly" class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-xs sm:text-sm text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                    </div>

                    <div class="pt-3 flex justify-end gap-2">
                        <button type="button" @click="statusModalOpen = false" class="px-4 py-2.5 rounded-xl bg-lightPrimary dark:bg-navy-700 hover:bg-gray-200 text-gray-600 dark:text-gray-300 text-xs font-bold transition-colors">
                            Cancel
                        </button>
                        <button type="submit" :disabled="selectedStatus === 'selesai' && balanceDue > 0" class="px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 disabled:opacity-40 disabled:hover:bg-brand-500 text-white text-xs font-bold shadow-lg shadow-brand-500/25 transition-all flex items-center gap-1.5">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal 2: Payment Modal -->
    <div x-show="payModalOpen" x-cloak class="relative z-50" role="dialog" aria-modal="true">
        <div x-show="payModalOpen" x-transition.opacity class="fixed inset-0 bg-navy-900/80 backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 flex items-center justify-center">
            <div x-show="payModalOpen" x-transition class="relative transform overflow-hidden rounded-[20px] bg-white dark:bg-navy-800 p-6 text-left shadow-3xl shadow-shadow-500 dark:shadow-none transition-all w-full max-w-md border border-white/40 dark:border-white/10">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-white/10">
                    <h3 class="text-base font-bold text-navy-700 dark:text-white font-poppins">Record Payment</h3>
                    <button type="button" @click="payModalOpen = false" class="text-gray-400 hover:text-navy-700 dark:hover:text-white">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="<?= base_url('transactions/pay/' . $transaction['id']) ?>" method="POST" class="space-y-4 mt-4">
                    <?= csrf_field() ?>
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="amount" class="block text-xs font-bold text-navy-700 dark:text-white">Payment Amount (Rp) *</label>
                            <button type="button" @click="$refs.payInput.value = balanceDue" class="text-xs font-bold text-brand-500 hover:underline">
                                Pay in Full (Remaining Rp <?= number_format($transaction['balance_due'], 0, ',', '.') ?>)
                            </button>
                        </div>
                        <input type="number" name="amount" id="amount" x-ref="payInput" required min="1000" :max="balanceDue" value="<?= (float)$transaction['balance_due'] ?>" class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-xs sm:text-sm font-mono font-bold text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                    </div>

                    <div>
                        <label for="method" class="block text-xs font-bold text-navy-700 dark:text-white mb-1.5">Payment Method *</label>
                        <select name="method" id="method" required class="w-full px-3.5 py-2.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-xs sm:text-sm font-medium text-navy-700 dark:text-white focus:outline-none focus:border-brand-500">
                            <option value="tunai">Cash</option>
                            <option value="transfer">Bank Transfer / QRIS</option>
                        </select>
                    </div>

                    <div class="pt-3 flex justify-end gap-2">
                        <button type="button" @click="payModalOpen = false" class="px-4 py-2.5 rounded-xl bg-lightPrimary dark:bg-navy-700 hover:bg-gray-200 text-gray-600 dark:text-gray-300 text-xs font-bold transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold shadow-lg shadow-brand-500/25 transition-all flex items-center gap-1.5">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            Save Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

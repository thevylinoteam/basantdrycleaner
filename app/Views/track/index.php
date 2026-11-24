<?php
/** @var string|null $keyword */
/** @var string|null $error */
/** @var array|null $results */
?>
<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<div class="max-w-3xl mx-auto space-y-8 py-4 sm:py-6">
    <!-- Hero Header -->
    <div class="text-center space-y-3">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-navy-700 dark:text-white font-poppins tracking-tight">Track Your Order</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-lg mx-auto">
            Track your laundry order using its order code or registered mobile number.
        </p>
    </div>

    <!-- Search Card -->
    <div class="rounded-[20px] bg-white dark:bg-navy-800 p-6 sm:p-8 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5">
        <form action="<?= base_url('track/check') ?>" method="GET" class="space-y-4">
            <div>
                <label for="keyword" class="block text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400 mb-2">
                    Order Code / Mobile Number:
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="search" class="w-5 h-5"></i>
                    </div>
                    <input type="text" name="keyword" id="keyword" required value="<?= esc($keyword ?? '') ?>" placeholder="Example: LDY-20260812-0001 or 9876543210" class="w-full pl-12 pr-4 py-3.5 bg-lightPrimary dark:bg-navy-900 border border-transparent dark:border-navy-700 rounded-xl text-sm sm:text-base font-medium text-navy-700 dark:text-white placeholder:text-gray-400 focus:outline-none focus:border-brand-500">
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-brand-500/25 transition-all flex items-center justify-center gap-2">
                <i data-lucide="search" class="w-4 h-4"></i>
                <span>Track Order</span>
            </button>
        </form>
    </div>

    <!-- Error / Not Found Message -->
    <?php if (isset($error)): ?>
        <div class="p-5 rounded-[20px] bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm flex items-start gap-3 shadow-sm">
            <i data-lucide="alert-circle" class="w-5 h-5 text-rose-500 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="font-bold">Order Not Found</p>
                <p class="text-xs text-rose-600 dark:text-rose-400 mt-0.5"><?= esc($error) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Tracking Results List -->
    <?php if (!empty($results)): ?>
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-navy-700 dark:text-white font-poppins">Tracking Results (<?= count($results) ?> Orders Found):</h2>
            </div>

            <?php foreach ($results as $tx): ?>
                <div class="rounded-[20px] bg-white dark:bg-navy-800 p-6 sm:p-8 shadow-3xl shadow-shadow-500 dark:shadow-none border border-white/40 dark:border-white/5 space-y-6">
                    <!-- Order Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-gray-100 dark:border-white/10">
                        <div>
                            <span class="text-xs text-gray-400 font-medium">Order Number:</span>
                            <h3 class="text-xl font-bold font-mono text-brand-500 dark:text-brand-400"><?= esc($tx['transaction_code']) ?></h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Customer: <strong class="text-navy-700 dark:text-white"><?= esc($tx['customer_name']) ?></strong></p>
                        </div>
                        <div>
                            <?php
                                $payBadges = [
                                    'belum_bayar' => 'bg-rose-50 text-rose-600 dark:bg-rose-950/50 dark:text-rose-300',
                                    'dp'          => 'bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-300',
                                    'lunas'       => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-300 font-bold',
                                ];
                                $pBadge = $payBadges[$tx['payment_status']] ?? 'bg-gray-100 text-gray-600';
                            ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider <?= $pBadge ?>">
                                Balance: <?= esc(basant_label($tx['payment_status'])) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Progress Stepper -->
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400 mb-6">Current Order Status:</p>
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
                            $currentIdx = array_search($tx['status'], $stageKeys);
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
                                <div class="relative z-10 flex flex-col items-center">
                                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full flex items-center justify-center text-xs font-bold transition-all <?= $stepCircleClass ?>">
                                        <?php if ($isDone): ?>
                                            <i data-lucide="check" class="w-4 h-4"></i>
                                        <?php else: ?>
                                            <i data-lucide="<?= $st['icon'] ?>" class="w-4 h-4"></i>
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-[10px] sm:text-[11px] font-bold mt-2.5 text-center <?= $isCurrent ? 'text-brand-500 dark:text-brand-400 font-extrabold' : ($isDone ? 'text-navy-700 dark:text-gray-200' : 'text-gray-400') ?>">
                                        <?= $st['label'] ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Highlight Info Bar -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 rounded-xl bg-lightPrimary dark:bg-navy-900 border border-gray-100 dark:border-navy-700 text-xs">
                        <div>
                            <span class="text-gray-400">Estimated Completion:</span>
                            <p class="font-bold text-navy-700 dark:text-white text-sm mt-0.5">
                                <?= !empty($tx['estimated_done_at']) ? date('d F Y, H:i', strtotime($tx['estimated_done_at'])) : '-' ?>
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-400">Outstanding Balance:</span>
                            <p class="font-mono font-bold text-sm mt-0.5 <?= $tx['balance_due'] > 0 ? 'text-rose-500' : 'text-emerald-500' ?>">
                                ₹ <?= number_format($tx['balance_due'], 2, '.', ',') ?> <?= $tx['balance_due'] == 0 ? '(Paid)' : '' ?>
                            </p>
                        </div>
                    </div>

                    <!-- Items Detail -->
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400 mb-2">Order Items:</p>
                        <div class="divide-y divide-gray-100 dark:divide-white/5 border border-gray-100 dark:border-white/10 rounded-xl overflow-hidden text-xs">
                            <?php foreach ($tx['items'] as $item): ?>
                                <div class="px-4 py-3 flex justify-between items-center bg-white dark:bg-navy-800">
                                    <div>
                                        <span class="font-bold text-navy-700 dark:text-white"><?= esc($item['service_name']) ?></span>
                                        <span class="text-gray-400 text-[11px] block"><?= (float)$item['qty'] ?> <?= esc($item['service_unit']) ?></span>
                                    </div>
                                    <span class="font-mono font-bold text-brand-500 dark:text-brand-400">₹ <?= number_format($item['subtotal'], 2, '.', ',') ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

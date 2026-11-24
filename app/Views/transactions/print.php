<?php
/** @var array $receipt */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - <?= esc($receipt['receipt_number']) ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="<?= base_url('basant-brand.webp') ?>">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Courier Prime', monospace; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .receipt-container { box-shadow: none !important; border: none !important; width: 100% !important; max-width: 80mm !important; margin: 0 auto !important; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-8 flex flex-col items-center justify-start text-slate-900">

    <!-- Action Bar (Hidden on Print) -->
    <div class="no-print mb-6 flex items-center gap-3">
        <button onclick="window.print()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-sans text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Print Receipt
        </button>
        <button onclick="window.close()" class="px-4 py-2.5 bg-white hover:bg-slate-200 text-slate-700 font-sans text-xs font-semibold rounded-xl border border-slate-300 transition-colors">
            Close
        </button>
    </div>

    <!-- Thermal Receipt Card -->
    <div class="receipt-container bg-white w-full max-w-[85mm] p-6 rounded-2xl shadow-xl border border-slate-200 text-xs">
        <!-- Store Header -->
        <div class="text-center pb-4 border-b border-dashed border-slate-300 space-y-1">
            <img src="<?= base_url('basant-brand.webp') ?>" alt="Basant Dry Cleaners" class="w-28 h-16 object-contain mx-auto mb-1">
            <h1 class="font-bold text-base tracking-wider uppercase"><?= esc($receipt['store']['name']) ?></h1>
            <p class="text-[11px] text-slate-600"><?= esc($receipt['store']['address']) ?></p>
            <p class="text-[11px] text-slate-600">Phone / WhatsApp: <?= esc($receipt['store']['phone']) ?></p>
        </div>

        <!-- Meta Info -->
        <div class="py-3 border-b border-dashed border-slate-300 space-y-1 text-[11px]">
            <div class="flex justify-between">
                <span>Receipt No.:</span>
                <span class="font-bold"><?= esc($receipt['receipt_number']) ?></span>
            </div>
            <div class="flex justify-between">
                <span>Printed At:</span>
                <span><?= date('d/m/Y H:i', strtotime($receipt['printed_at'])) ?></span>
            </div>
            <div class="flex justify-between">
                <span>Cashier:</span>
                <span><?= esc($receipt['cashier'] ?? 'Admin') ?></span>
            </div>
            <div class="flex justify-between">
                <span>Customer:</span>
                <span class="font-bold"><?= esc($receipt['customer']['name']) ?> (<?= esc($receipt['customer']['phone']) ?>)</span>
            </div>
            <div class="flex justify-between text-emerald-700 font-bold">
                <span>Payment Status:</span>
                <span><?= (['lunas' => 'Paid', 'belum_bayar' => 'Unpaid', 'dp' => 'Partial', 'sebagian' => 'Partial'][$receipt['payment_status']] ?? esc($receipt['payment_status'])) ?></span>
            </div>
        </div>

        <!-- Items Table -->
        <div class="py-3 border-b border-dashed border-slate-300 space-y-2">
            <p class="font-bold text-[11px] uppercase">Order Items:</p>
            <?php foreach ($receipt['items'] as $item): ?>
                <div class="text-[11px] space-y-0.5">
                    <p class="font-bold"><?= esc($item['service_name']) ?></p>
                    <div class="flex justify-between text-slate-600">
                        <span><?= (float)$item['qty'] ?> <?= esc($item['service_unit']) ?> x Rp <?= number_format($item['price_per_unit'], 0, ',', '.') ?></span>
                        <span class="font-bold text-slate-900">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Price Totals -->
        <div class="py-3 border-b border-dashed border-slate-300 space-y-1.5 text-[11px]">
            <div class="flex justify-between font-bold text-sm pt-1">
                <span>TOTAL:</span>
                <span>Rp <?= number_format($receipt['total_price'], 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-between text-slate-600">
                <span>Total Paid:</span>
                <span>Rp <?= number_format($receipt['total_paid'], 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-between font-bold <?= $receipt['balance_due'] > 0 ? 'text-rose-600' : 'text-slate-700' ?>">
                <span>Outstanding Balance:</span>
                <span>Rp <?= number_format($receipt['balance_due'], 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Estimated Completion & Notes -->
        <div class="py-3 border-b border-dashed border-slate-300 text-[11px] space-y-1 text-center">
            <p class="text-slate-500">Estimated Completion:</p>
            <p class="font-bold"><?= !empty($receipt['estimated_done_at']) ? date('d M Y, H:i', strtotime($receipt['estimated_done_at'])) : '-' ?></p>
            <?php if (!empty($receipt['notes'])): ?>
                <p class="text-[10px] text-slate-500 italic mt-1">"<?= esc($receipt['notes']) ?>"</p>
            <?php endif; ?>
        </div>

        <!-- Footer -->
        <div class="pt-4 text-center text-[10px] text-slate-500 space-y-1">
            <p class="font-bold">*** THANK YOU ***</p>
            <p>Please bring this receipt when collecting your order.</p>
            <p>Track online: <?= base_url('track') ?></p>
        </div>
    </div>

</body>
</html>

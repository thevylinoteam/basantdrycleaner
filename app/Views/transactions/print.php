<?php
/** @var array $receipt */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota - <?= esc($receipt['receipt_number']) ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('logo-laundry.png') ?>">
    
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
            Cetak Nota Sekarang
        </button>
        <button onclick="window.close()" class="px-4 py-2.5 bg-white hover:bg-slate-200 text-slate-700 font-sans text-xs font-semibold rounded-xl border border-slate-300 transition-colors">
            Tutup
        </button>
    </div>

    <!-- Thermal Receipt Card -->
    <div class="receipt-container bg-white w-full max-w-[85mm] p-6 rounded-2xl shadow-xl border border-slate-200 text-xs">
        <!-- Store Header -->
        <div class="text-center pb-4 border-b border-dashed border-slate-300 space-y-1">
            <img src="<?= base_url('logo-laundry.png') ?>" alt="Logo Laundry" class="w-12 h-12 object-cover rounded-full mx-auto mb-1">
            <h1 class="font-bold text-base tracking-wider uppercase"><?= esc($receipt['store']['name']) ?></h1>
            <p class="text-[11px] text-slate-600"><?= esc($receipt['store']['address']) ?></p>
            <p class="text-[11px] text-slate-600">Telp/WA: <?= esc($receipt['store']['phone']) ?></p>
        </div>

        <!-- Meta Info -->
        <div class="py-3 border-b border-dashed border-slate-300 space-y-1 text-[11px]">
            <div class="flex justify-between">
                <span>No. Nota:</span>
                <span class="font-bold"><?= esc($receipt['receipt_number']) ?></span>
            </div>
            <div class="flex justify-between">
                <span>Waktu Cetak:</span>
                <span><?= date('d/m/Y H:i', strtotime($receipt['printed_at'])) ?></span>
            </div>
            <div class="flex justify-between">
                <span>Kasir:</span>
                <span><?= esc($receipt['cashier'] ?? 'Admin') ?></span>
            </div>
            <div class="flex justify-between">
                <span>Pelanggan:</span>
                <span class="font-bold"><?= esc($receipt['customer']['name']) ?> (<?= esc($receipt['customer']['phone']) ?>)</span>
            </div>
            <div class="flex justify-between text-emerald-700 font-bold">
                <span>Status Bayar:</span>
                <span><?= strtoupper($receipt['payment_status']) ?></span>
            </div>
        </div>

        <!-- Items Table -->
        <div class="py-3 border-b border-dashed border-slate-300 space-y-2">
            <p class="font-bold text-[11px] uppercase">Rincian Cucian:</p>
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
                <span>Total Dibayar:</span>
                <span>Rp <?= number_format($receipt['total_paid'], 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-between font-bold <?= $receipt['balance_due'] > 0 ? 'text-rose-600' : 'text-slate-700' ?>">
                <span>Sisa Tagihan:</span>
                <span>Rp <?= number_format($receipt['balance_due'], 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Estimated Completion & Notes -->
        <div class="py-3 border-b border-dashed border-slate-300 text-[11px] space-y-1 text-center">
            <p class="text-slate-500">Estimasi Selesai:</p>
            <p class="font-bold"><?= !empty($receipt['estimated_done_at']) ? date('d M Y, H:i', strtotime($receipt['estimated_done_at'])) : '-' ?></p>
            <?php if (!empty($receipt['notes'])): ?>
                <p class="text-[10px] text-slate-500 italic mt-1">"<?= esc($receipt['notes']) ?>"</p>
            <?php endif; ?>
        </div>

        <!-- Footer -->
        <div class="pt-4 text-center text-[10px] text-slate-500 space-y-1">
            <p class="font-bold">*** TERIMA KASIH ***</p>
            <p>Harap bawa nota ini saat pengambilan cucian.</p>
            <p>Cek status online: <?= base_url('track') ?></p>
        </div>
    </div>

</body>
</html>

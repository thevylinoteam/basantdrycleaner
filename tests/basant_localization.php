<?php
require __DIR__ . '/../app/Common.php';
$checks = [
    basant_label('siap_diambil') === 'Ready for Collection',
    basant_label('lunas') === 'Paid',
    basant_label('kiloan') === 'Per kg',
    basant_whatsapp('9876543210') === '919876543210',
    basant_whatsapp('09876543210') === '919876543210',
    basant_whatsapp('+91 98765 43210') === '919876543210',
    basant_whatsapp('00442012345678') === '442012345678',
];
foreach ($checks as $i => $check) {
    if (!$check) { fwrite(STDERR, 'Failed check ' . ($i + 1) . PHP_EOL); exit(1); }
}
echo count($checks) . ' localization checks passed' . PHP_EOL;

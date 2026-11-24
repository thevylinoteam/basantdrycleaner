<?php
require __DIR__ . '/../app/Common.php';
require __DIR__ . '/../app/Libraries/BasantCatalogue.php';
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

$items = \App\Libraries\BasantCatalogue::ITEMS;
$names = array_map([\App\Libraries\BasantCatalogue::class, 'serviceName'], $items);
if (count($items) !== 20 || count(array_unique($names)) !== 20 || array_sum(array_column($items, 2)) !== 4130 || array_sum(array_column($items, 3)) !== 9080) { exit(1); }
echo '20 Basant catalogue entries validated' . PHP_EOL;

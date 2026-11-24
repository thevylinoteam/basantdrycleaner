<?php

namespace App\Libraries;

use CodeIgniter\Database\BaseConnection;

final class BasantCatalogue
{
    public const ITEMS = [
        ['Gentlemen Wear', 'Suit (2 Piece)', 200, 300],
        ['Gentlemen Wear', 'Suit (Pure)', 400, 400],
        ['Gentlemen Wear', 'Full Jacket', 200, 400],
        ['Gentlemen Wear', 'Leather Jacket', 300, 1000],
        ['Gentlemen Wear', 'Shirt', 50, 100],
        ['Gentlemen Wear', 'Pant / Jeans', 100, 150],
        ['Gentlemen Wear', 'Sherwani', 400, 1000],
        ['Ladies Wear', 'Saree (Plain)', 150, 150],
        ['Ladies Wear', 'Saree (Silk)', 200, 250],
        ['Ladies Wear', 'Saree (Heavy)', 300, 400],
        ['Ladies Wear', 'Lehenga (3 Piece)', 300, 1500],
        ['Ladies Wear', 'Gown', 300, 1500],
        ['Ladies Wear', 'Kurti', 50, 150],
        ['Ladies Wear', 'Saree Polish', 200, 500],
        ['Household Items', 'Single Blanket', 200, 300],
        ['Household Items', 'Double Blanket', 400, 600],
        ['Household Items', 'Bedsheet (Single)', 100, 100],
        ['Household Items', 'Bedsheet (Double)', 150, 150],
        ['Household Items', 'Pillow Cover', 30, 30],
        ['Household Items', 'Curtain', 100, 100],
    ];

    public static function serviceName(array $item): string
    {
        [$category, $name, $min, $max] = $item;
        return $category . ' - ' . $name . ($min !== $max ? ' (₹' . $min . '–' . $max . ')' : '');
    }

    /** Add missing items only. Never overwrite staff prices or historical data. */
    public static function import(BaseConnection $db): int
    {
        $db->transBegin();
        try {
            $count = 0;
            foreach (self::ITEMS as $item) {
                $name = self::serviceName($item);
                if ($db->table('services')->where('name', $name)->countAllResults() > 0) {
                    continue;
                }
                $now = date('Y-m-d H:i:s');
                if (!$db->table('services')->insert([
                    'name' => $name,
                    'type' => 'satuan',
                    'unit' => 'pcs',
                    'price' => $item[2],
                    'duration_estimate_hours' => 24,
                    'is_active' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])) {
                    throw new \RuntimeException('Service import failed.');
                }
                $count++;
            }
            if (!$db->transStatus() || !$db->transCommit()) {
                throw new \RuntimeException('Service import could not be committed.');
            }
            return $count;
        } catch (\Throwable $e) {
            $db->transRollback();
            throw $e;
        }
    }
}

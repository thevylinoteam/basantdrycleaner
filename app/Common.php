<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

function basant_label(string $value): string
{
    return [
        'diterima' => 'Received', 'dicuci' => 'Washing', 'dikeringkan' => 'Drying',
        'disetrika' => 'Ironing', 'siap_diambil' => 'Ready for Collection', 'selesai' => 'Completed',
        'belum_bayar' => 'Unpaid', 'dp' => 'Partially Paid', 'lunas' => 'Paid',
        'kiloan' => 'Per kg', 'satuan' => 'Per item', 'ekspres' => 'Express',
        'kasir' => 'Cashier', 'admin' => 'Administrator',
        'cash' => 'Cash', 'tunai' => 'Cash', 'transfer' => 'Bank Transfer / UPI',
    ][$value] ?? ucwords(str_replace('_', ' ', $value));
}

function basant_whatsapp(string $phone): string
{
    $digits = preg_replace('/[^0-9]/', '', $phone);
    if (str_starts_with($digits, '00')) {
        $digits = substr($digits, 2);
    } elseif (strlen($digits) === 11 && str_starts_with($digits, '0')) {
        $digits = '91' . substr($digits, 1);
    } elseif (strlen($digits) === 10) {
        $digits = '91' . $digits;
    }
    return $digits;
}

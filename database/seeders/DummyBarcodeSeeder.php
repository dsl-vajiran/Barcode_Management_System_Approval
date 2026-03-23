<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DummyBarcodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ybissue')->insert([
            'ibarcode' => 'BAR001',
            'invno' => 'INV-2026-001',
            'itmnme' => 'DSL Battery 150Ah',
            'itmmod' => 'DSL-150-V2',
            'itmamp' => '150Ah',
            'f_war' => 24,
            'pa_war' => 12,
            'remark' => 'Test battery item for barcode system',
            'prphase' => '3 Phase',
            'brand' => 'DSL',
            'isudtme' => Carbon::now()->format('Y-m-d H:i:s'),
            'iremark' => 'Issued from warehouse stock',
            'ichsale' => 1,
            'saledtme' => Carbon::now()->addDays(5)->format('Y-m-d H:i:s'),
            'ichapr' => 1,
            'iaprdte' => Carbon::now()->addDays(1)->format('Y-m-d H:i:s'),
            'fncusnm' => 'ABC Traders Pvt Ltd',
            'fncustp' => 'TRADER-001',
            'location' => 'Warehouse A - Shelf 5',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('ybissue')->insert([
            'ibarcode' => 'BAR002',
            'invno' => 'INV-2026-002',
            'itmnme' => 'DSL Battery 200Ah',
            'itmmod' => 'DSL-200-V1',
            'itmamp' => '200Ah',
            'f_war' => 36,
            'pa_war' => 18,
            'remark' => 'Premium battery model',
            'prphase' => '3 Phase',
            'brand' => 'DSL',
            'isudtme' => Carbon::now()->subDays(2)->format('Y-m-d H:i:s'),
            'iremark' => 'Regular stock issuance',
            'ichsale' => 0,
            'saledtme' => null,
            'ichapr' => 0,
            'iaprdte' => null,
            'fncusnm' => 'XYZ Enterprises',
            'fncustp' => 'TRADER-002',
            'location' => 'Warehouse B - Shelf 12',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('ybissue')->insert([
            'ibarcode' => 'BAR003',
            'invno' => 'INV-2026-003',
            'itmnme' => 'DSL Battery 100Ah',
            'itmmod' => 'DSL-100-V3',
            'itmamp' => '100Ah',
            'f_war' => 12,
            'pa_war' => 6,
            'remark' => 'Compact battery unit',
            'prphase' => '1 Phase',
            'brand' => 'DSL',
            'isudtme' => Carbon::now()->subDays(10)->format('Y-m-d H:i:s'),
            'iremark' => 'Pending warehouse allocation',
            'ichsale' => 0,
            'saledtme' => null,
            'ichapr' => 0,
            'iaprdte' => null,
            'fncusnm' => null,
            'fncustp' => null,
            'location' => 'Warehouse A - Receiving',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

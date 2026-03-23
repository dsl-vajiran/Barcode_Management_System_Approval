<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'itmcode' => 'BAT-001',
                'itmnme' => 'Alkaline AA Battery',
                'itmmod' => 'AA-1.5V',
                'itmamp' => '2500mAh',
                'f_war' => 12,
                'pa_war' => 24,
                'brand' => 'PowerCell',
                'prphase' => 'AA Alkaline Battery Pack',
                'remark' => 'Standard alkaline battery for general use',
            ],
            [
                'itmcode' => 'BAT-002',
                'itmnme' => 'Alkaline AAA Battery',
                'itmmod' => 'AAA-1.5V',
                'itmamp' => '1200mAh',
                'f_war' => 12,
                'pa_war' => 24,
                'brand' => 'PowerCell',
                'prphase' => 'AAA Alkaline Battery Pack',
                'remark' => 'Small alkaline battery for remote controls',
            ],
            [
                'itmcode' => 'BAT-003',
                'itmnme' => 'Lithium 9V Battery',
                'itmmod' => '9V-LITHIUM',
                'itmamp' => '1200mAh',
                'f_war' => 24,
                'pa_war' => 36,
                'brand' => 'ElectroMax',
                'prphase' => 'Lithium 9V Battery',
                'remark' => 'High performance lithium battery',
            ],
            [
                'itmcode' => 'BAT-004',
                'itmnme' => 'NiMH Rechargeable AA',
                'itmmod' => 'AA-NIMH',
                'itmamp' => '2400mAh',
                'f_war' => 24,
                'pa_war' => 48,
                'brand' => 'GreenEnergy',
                'prphase' => 'Rechargeable AA NiMH Battery',
                'remark' => 'Reusable battery for high-drain devices',
            ],
            [
                'itmcode' => 'BAT-005',
                'itmnme' => 'NiMH Rechargeable AAA',
                'itmmod' => 'AAA-NIMH',
                'itmamp' => '1200mAh',
                'f_war' => 24,
                'pa_war' => 48,
                'brand' => 'GreenEnergy',
                'prphase' => 'Rechargeable AAA NiMH Battery',
                'remark' => 'Reusable small battery',
            ],
            [
                'itmcode' => 'BAT-006',
                'itmnme' => 'Lithium CR2032 Coin',
                'itmmod' => 'CR2032',
                'itmamp' => '225mAh',
                'f_war' => 12,
                'pa_war' => 24,
                'brand' => 'TechBattery',
                'prphase' => 'Lithium Coin Cell CR2032',
                'remark' => 'Button cell battery for watches and calculators',
            ],
            [
                'itmcode' => 'BAT-007',
                'itmnme' => 'Lead-Acid 12V 7Ah',
                'itmmod' => '12V-7AH',
                'itmamp' => '7000mAh',
                'f_war' => 24,
                'pa_war' => 36,
                'brand' => 'PowerVault',
                'prphase' => 'Sealed Lead Acid Battery 12V',
                'remark' => 'Used in UPS and backup power systems',
            ],
            [
                'itmcode' => 'BAT-008',
                'itmnme' => 'Alkaline D Battery',
                'itmmod' => 'D-1.5V',
                'itmamp' => '10000mAh',
                'f_war' => 12,
                'pa_war' => 24,
                'brand' => 'PowerCell',
                'prphase' => 'D Size Alkaline Battery',
                'remark' => 'Large battery for flashlights',
            ],
            [
                'itmcode' => 'BAT-009',
                'itmnme' => 'Lithium AA Battery',
                'itmmod' => 'AA-LITHIUM',
                'itmamp' => '3000mAh',
                'f_war' => 12,
                'pa_war' => 24,
                'brand' => 'ElectroMax',
                'prphase' => 'Lithium AA Battery',
                'remark' => 'High energy density battery for cameras',
            ],
            [
                'itmcode' => 'BAT-010',
                'itmnme' => 'Silver Oxide SR44',
                'itmmod' => 'SR44',
                'itmamp' => '150mAh',
                'f_war' => 12,
                'pa_war' => 24,
                'brand' => 'TechBattery',
                'prphase' => 'Silver Oxide Watch Battery SR44',
                'remark' => 'Long life battery for watches',
            ],
            [
                'itmcode' => 'BAT-011',
                'itmnme' => 'Rechargeable C Battery',
                'itmmod' => 'C-NIMH',
                'itmamp' => '5000mAh',
                'f_war' => 24,
                'pa_war' => 48,
                'brand' => 'GreenEnergy',
                'prphase' => 'Rechargeable C NiMH Battery',
                'remark' => 'Medium size reusable battery',
            ],
            [
                'itmcode' => 'BAT-012',
                'itmnme' => 'Zinc Carbon D Battery',
                'itmmod' => 'D-ZN-CAR',
                'itmamp' => '6000mAh',
                'f_war' => 6,
                'pa_war' => 12,
                'brand' => 'BasicPower',
                'prphase' => 'Zinc Carbon D Battery',
                'remark' => 'Budget friendly battery for light devices',
            ],
        ];

        foreach ($items as $item) {
            DB::connection('hana')->table('YBIMMST')->updateOrInsert(
                ['itmcode' => $item['itmcode']],
                $item
            );
        }

        $this->command->info('Item Master seeder completed: ' . count($items) . ' items added/updated');
    }
}

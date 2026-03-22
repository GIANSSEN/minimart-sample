<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'brand_code' => 'BRD001',
                'brand_name' => 'Coca-Cola',
                'description' => 'Global beverage company',
                'website' => 'https://www.coca-cola.com',
                'status' => 'active'
            ],
            [
                'brand_code' => 'BRD002',
                'brand_name' => 'PepsiCo',
                'description' => 'Food and beverage corporation',
                'website' => 'https://www.pepsico.com',
                'status' => 'active'
            ],
            [
                'brand_code' => 'BRD003',
                'brand_name' => 'Nestlé',
                'description' => 'Multinational food and drink company',
                'website' => 'https://www.nestle.com',
                'status' => 'active'
            ],
            [
                'brand_code' => 'BRD004',
                'brand_name' => 'Universal Robina',
                'description' => 'Leading Filipino food and beverage company',
                'website' => 'https://www.urc.com.ph',
                'status' => 'active'
            ],
            [
                'brand_code' => 'FZN-BRD-001',
                'brand_name' => 'Purefoods',
                'description' => 'Premier meat brand',
                'website' => 'https://www.purefoods.com.ph',
                'status' => 'active'
            ],
            [
                'brand_code' => 'FZN-BRD-002',
                'brand_name' => 'CDO',
                'description' => 'Food manufacturing company',
                'website' => 'https://www.cdo.com.ph',
                'status' => 'active'
            ],
            [
                'brand_code' => 'HHD-BRD-001',
                'brand_name' => 'Breeze',
                'description' => 'Detergent brand by Unilever',
                'website' => 'https://www.unilever.com.ph',
                'status' => 'active'
            ],
            [
                'brand_code' => 'HHD-BRD-002',
                'brand_name' => 'Ariel',
                'description' => 'Detergent brand by P&G',
                'website' => 'https://www.pg.com.ph',
                'status' => 'active'
            ],
        ];

        foreach ($brands as $brand) {
            $brand['slug'] = Str::slug($brand['brand_name']);
            Brand::updateOrCreate(
                ['brand_name' => $brand['brand_name']],
                $brand
            );
        }
    }
}
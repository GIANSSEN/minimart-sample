<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentTermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $terms = [
            ['term_name' => 'COD', 'days_due' => 0, 'description' => 'Cash on Delivery'],
            ['term_name' => 'NET 7', 'days_due' => 7, 'description' => 'Payment due within 7 days'],
            ['term_name' => 'NET 15', 'days_due' => 15, 'description' => 'Payment due within 15 days'],
            ['term_name' => 'NET 30', 'days_due' => 30, 'description' => 'Payment due within 30 days'],
        ];

        foreach ($terms as $term) {
            \App\Models\PaymentTerm::updateOrCreate(['term_name' => $term['term_name']], $term);
        }
    }
}

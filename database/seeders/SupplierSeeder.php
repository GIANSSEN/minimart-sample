<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $suppliers = [
            [
                'supplier_code' => 'SUP001',
                'supplier_name' => 'Coca-Cola Beverages',
                'contact_person' => 'Juan Dela Cruz',
                'email' => 'juan@cocacola.com',
                'phone' => '0288888888',
                'mobile' => '09171234567',
                'address' => 'Makati City',
                'tax_id' => '123-456-789-000',
                'payment_terms' => 'Net 30',
                'status' => 'active',
                'created_by' => 1
            ],
            [
                'supplier_code' => 'SUP002',
                'supplier_name' => 'Universal Robina Corp',
                'contact_person' => 'Maria Santos',
                'email' => 'maria@urc.com.ph',
                'phone' => '0277777777',
                'mobile' => '09181234567',
                'address' => 'Pasig City',
                'tax_id' => '123-456-789-001',
                'payment_terms' => 'Net 30',
                'status' => 'active',
                'created_by' => 1
            ],
            [
                'supplier_code' => 'SUP003',
                'supplier_name' => 'San Miguel Corporation',
                'contact_person' => 'Pedro Reyes',
                'email' => 'pedro@sanmiguel.com.ph',
                'phone' => '0266666666',
                'mobile' => '09191234567',
                'address' => 'Mandaluyong City',
                'tax_id' => '123-456-789-002',
                'payment_terms' => 'Net 45',
                'status' => 'active',
                'created_by' => 1
            ],
            [
                'supplier_code' => 'SUP004',
                'supplier_name' => 'Nestle Philippines',
                'contact_person' => 'Ana Lopez',
                'email' => 'ana@nestle.com.ph',
                'phone' => '0255555555',
                'mobile' => '09201234567',
                'address' => 'Cabuyao, Laguna',
                'tax_id' => '123-456-789-003',
                'payment_terms' => 'Net 30',
                'status' => 'active',
                'created_by' => 1
            ],
            [
                'supplier_code' => 'SUP005',
                'supplier_name' => 'Pepsi-Cola Products',
                'contact_person' => 'Jose Garcia',
                'email' => 'jose@pepsi.com.ph',
                'phone' => '0244444444',
                'mobile' => '09211234567',
                'address' => 'Muntinlupa City',
                'tax_id' => '123-456-789-004',
                'payment_terms' => 'Net 30',
                'status' => 'active',
                'created_by' => 1
            ],
            [
                'supplier_code' => 'FZN-SUP-001',
                'supplier_name' => 'Purefoods-Hormel',
                'contact_person' => 'Ricardo Lim',
                'email' => 'ricardo@purefoods.com.ph',
                'phone' => '0233333333',
                'mobile' => '09221234567',
                'address' => 'Pasig City',
                'tax_id' => '123-456-789-005',
                'payment_terms' => 'Net 30',
                'status' => 'active',
                'created_by' => 1
            ],
            [
                'supplier_code' => 'FZN-SUP-002',
                'supplier_name' => 'CDO Foodservice',
                'contact_person' => 'Elena Cruz',
                'email' => 'elena@cdo.com.ph',
                'phone' => '0222222222',
                'mobile' => '09231234567',
                'address' => 'Valenzuela City',
                'tax_id' => '123-456-789-006',
                'payment_terms' => 'Net 30',
                'status' => 'active',
                'created_by' => 1
            ],
            [
                'supplier_code' => 'HHD-SUP-001',
                'supplier_name' => 'Unilever Philippines',
                'contact_person' => 'Robert Tan',
                'email' => 'robert@unilever.com',
                'phone' => '0211111111',
                'mobile' => '09241234567',
                'address' => 'Taguig City',
                'tax_id' => '123-456-789-007',
                'payment_terms' => 'Net 30',
                'status' => 'active',
                'created_by' => 1
            ],
            [
                'supplier_code' => 'HHD-SUP-002',
                'supplier_name' => 'P&G Philippines',
                'contact_person' => 'Grace Sy',
                'email' => 'grace@pg.com',
                'phone' => '0200000000',
                'mobile' => '09251234567',
                'address' => 'BGC, Taguig',
                'tax_id' => '123-456-789-008',
                'payment_terms' => 'Net 45',
                'status' => 'active',
                'created_by' => 1
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(
                ['supplier_name' => $supplier['supplier_name']],
                $supplier
            );
        }
    }
}
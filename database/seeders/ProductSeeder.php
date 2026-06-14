<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء شركة الجهيمي
        $company = \App\Models\Company::firstOrCreate(
            ['name' => 'شركة الجهيمي'],
            [
                'email' => 'info@aljuhaimi.com',
                'phone' => '01000000000',
            ]
        );

        $products = [
            [
                'name'            => 'بانادول إكسترا',
                'scientific_name' => 'Paracetamol / Caffeine',
                'description'     => 'مسكن فعال للآلام وخافض للحرارة',
                'strength'        => '500mg/65mg',
                'dosage_form'     => 'أقراص',
                'production_date' => '2023-01-01',
                'expiry_date'     => '2026-01-01',
                'batch_number'    => 'BT-12345',
                'price'           => 15.50,
                'stock_quantity'  => 500,
            ],
            [
                'name'            => 'أوجمنتين',
                'scientific_name' => 'Amoxicillin / Clavulanic Acid',
                'description'     => 'مضاد حيوي واسع المدى لعلاج الالتهابات البكتيرية',
                'strength'        => '1g',
                'dosage_form'     => 'أقراص مغلفة',
                'production_date' => '2023-05-10',
                'expiry_date'     => '2025-05-10',
                'batch_number'    => 'BT-67890',
                'price'           => 85.00,
                'stock_quantity'  => 200,
            ],
            [
                'name'            => 'بروفين',
                'scientific_name' => 'Ibuprofen',
                'description'     => 'مسكن للآلام ومضاد للالتهاب وقوة مفعول طويلة',
                'strength'        => '400mg',
                'dosage_form'     => 'أقراص',
                'production_date' => '2023-02-15',
                'expiry_date'     => '2025-02-15',
                'batch_number'    => 'BT-11223',
                'price'           => 12.00,
                'stock_quantity'  => 400,
            ],
            [
                'name'            => 'فنتولين',
                'scientific_name' => 'Salbutamol',
                'description'     => 'موسع للقصبات الهوائية وسريع المفعول',
                'strength'        => '100mcg',
                'dosage_form'     => 'بخاخ',
                'production_date' => '2024-01-10',
                'expiry_date'     => '2026-01-10',
                'batch_number'    => 'BT-44556',
                'price'           => 25.00,
                'stock_quantity'  => 150,
            ],
            [
                'name'            => 'نيكسيوم',
                'scientific_name' => 'Esomeprazole',
                'description'     => 'لعلاج حموضة المعدة وقرحة الجهاز الهضمي',
                'strength'        => '400mg',
                'dosage_form'     => 'أقراص',
                'production_date' => '2023-11-20',
                'expiry_date'     => '2025-11-20',
                'batch_number'    => 'BT-77889',
                'price'           => 110.00,
                'stock_quantity'  => 300,
            ],
            [
                'name'            => 'سينيكود',
                'scientific_name' => 'Butamirate citrate',
                'description'     => 'شراب مهدئ للسعال الجاف',
                'strength'        => '1.5mg/ml',
                'dosage_form'     => 'شراب',
                'production_date' => '2023-08-01',
                'expiry_date'     => '2025-08-01',
                'batch_number'    => 'BT-99001',
                'price'           => 18.00,
                'stock_quantity'  => 250,
            ],
            [
                'name'            => 'زيرتك',
                'scientific_name' => 'Cetirizine',
                'description'     => 'مضاد للحساسية والتهابات الجيوب الأنفية',
                'strength'        => '10mg',
                'dosage_form'     => 'أقراص',
                'production_date' => '2024-02-15',
                'expiry_date'     => '2026-02-15',
                'batch_number'    => 'BT-22334',
                'price'           => 28.50,
                'stock_quantity'  => 350,
            ],
            [
                'name'            => 'دالاسين سي',
                'scientific_name' => 'Clindamycin',
                'description'     => 'مضاد حيوي لعلاج الالتهابات البكتيرية الشديدة',
                'strength'        => '300mg',
                'dosage_form'     => 'كبسولات',
                'production_date' => '2023-10-05',
                'expiry_date'     => '2025-10-05',
                'batch_number'    => 'BT-55667',
                'price'           => 65.00,
                'stock_quantity'  => 180,
            ],
            [
                'name'            => 'ليبيتور',
                'scientific_name' => 'Atorvastatin',
                'description'     => 'لخفض نسبة الكوليسترول والدهون في الدم',
                'strength'        => '20mg',
                'dosage_form'     => 'أقراص',
                'production_date' => '2023-12-01',
                'expiry_date'     => '2025-12-01',
                'batch_number'    => 'BT-88990',
                'price'           => 145.00,
                'stock_quantity'  => 200,
            ],
            [
                'name'            => 'كلاريتين',
                'scientific_name' => 'Loratadine',
                'description'     => 'مضاد للهستامين لا يسبب النعاس',
                'strength'        => '10mg',
                'dosage_form'     => 'أقراص',
                'production_date' => '2024-03-01',
                'expiry_date'     => '2026-03-01',
                'batch_number'    => 'BT-33445',
                'price'           => 30.00,
                'stock_quantity'  => 400,
            ],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create(array_merge($product, [
                'company_id' => $company->id,
            ]));
        }
    }
}

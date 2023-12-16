<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('products')->insert([
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => null,
                'product_name'=> 'コーラ',
		        'price'=> '130',
	            'stock'=> '10',
                'company_name'=> '1',
                'img_path'=> null,
                'comment'=> null,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => null,
                'product_name'=> 'お茶',
		        'price'=> '130',
	            'stock'=> '6',
                'company_name'=> '2',
                'img_path'=> null,
                'comment'=> null,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => null,
                'product_name'=> '水',
		        'price'=> '110',
	            'stock'=> '6',
                'company_name'=> '3',
                'img_path'=> null,
                'comment'=> null,
            ],
        ]);
    }
}

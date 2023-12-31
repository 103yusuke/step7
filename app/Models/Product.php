<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name', 'company_name', 'price', 'stock', 'comment', 'img_path'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_name', 'id');
    }

    public static function getProducts($keyword = '', $selectedCompany = '')
    {
        $productsQuery = self::select([
            'products.id',
            'products.img_path',
            'products.product_name',
            'products.price',
            'products.stock',
            'products.company_name',
            'products.comment',
            'companies.company_name as company_name',
        ])
        ->from('products')
        ->join('companies', 'products.company_name', '=', 'companies.id')
        ->orderBy('products.id', 'DESC');

        if (!empty($keyword)) {
            $productsQuery->where(function ($query) use ($keyword) {
                $query->where('products.product_name', 'LIKE', "%{$keyword}%")
                    ->orWhere('products.comment', 'LIKE', "%{$keyword}%")
                    ->orWhere('companies.company_name', 'LIKE', "%{$keyword}%");
            });
        }

        if (!empty($selectedCompany)) {
            $productsQuery->where('products.company_name', '=', $selectedCompany);
        }

        return $productsQuery->paginate(5);
    }
}

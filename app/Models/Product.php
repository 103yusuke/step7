<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'product_name', 'company_name', 'price', 'stock', 'comment', 'img_path'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_name', 'id');
    }

    public static function getProducts($keyword = '', $selectedCompany = '', $minPrice = null, $maxPrice = null, $minStock = null, $maxStock = null)
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

        if ($minPrice !== null) {
            $productsQuery->where('products.price', '>=', $minPrice);
        }

        if ($maxPrice !== null) {
            $productsQuery->where('products.price', '<=', $maxPrice);
        }

        if ($minStock !== null) {
            $productsQuery->where('products.stock', '>=', $minStock);
        }

        if ($maxStock !== null) {
            $productsQuery->where('products.stock', '<=', $maxStock);
        }

        return $productsQuery->paginate(5);
    }

    public static function createProduct($data)
    {
        $product = new self;
        $product->fill($data);
        $product->save();

        return $product;
    }

    public function updateProduct($data)
    {
        $this->fill($data);
        $this->save();

        return $this;
    }

    public function deleteProduct()
    {
        if ($this->img_path) {
            Storage::delete('public/' . $this->img_path);
        }

        $this->delete();
    }
}
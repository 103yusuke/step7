<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $selectedCompany = $request->input('company_name');
        $companies = Company::all();

        $products = Product::getProducts($keyword, $selectedCompany);

        return view('index', compact('products', 'keyword', 'companies', 'selectedCompany'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $companies = Company::all();
        return view('create')->with('companies', $companies);
    }

    public function store(Request $request)
{
    try {
        $request->validate([
            'product_name' => 'required|max:20',
            'company_name' => 'required|integer',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'comment' => 'max:200',
            'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $path = null;
        if ($request->hasFile('img_path')) {
            $image = $request->file('img_path');
            $path = $image->store('public');
            $path = explode('/', $path);
        }

        $data = [
            'product_name' => $request->input('product_name'),
            'company_name' => $request->input('company_name'),
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
            'comment' => $request->input('comment'),
            'img_path' => $path ? end($path) : null,
        ];

        $product = Product::createProduct($data);

        return redirect()->route('products.index')->with('success', '商品を登録しました');
    } catch (ValidationException $e) {
        return redirect()->back()->withErrors($e->errors())->withInput();
    }
}

    public function show(Product $product)
    {
        $companies = Company::all();
        return view('show', compact('product', 'companies'));
    }

    public function edit(Product $product)
    {
        $companies = Company::all();
        return view('edit', compact('product', 'companies'));
    }

    

    public function update(Request $request, Product $product)
    {
        try {
            $request->validate([
                'product_name' => 'required|max:20',
                'company_name' => 'required|integer',
                'price' => 'required|integer',
                'stock' => 'required|integer',
                'comment' => 'max:200',
                'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
    
            if ($request->has('remove_img')) {
                $product->deleteProduct();
            } else {
                $path = null;
                if ($request->hasFile('img_path')) {
                    $image = $request->file('img_path');
                    $path = $image->store('public');
                    $path = explode('/', $path);
                }
    
                $data = [
                    'product_name' => $request->input('product_name'),
                    'company_name' => $request->input('company_name'),
                    'price' => $request->input('price'),
                    'stock' => $request->input('stock'),
                    'comment' => $request->input('comment'),
                    'img_path' => $path ? end($path) : null,
                ];
    
                $product->updateProduct($data);
            }
    
            return redirect()->route('products.index')->with('success', '商品を更新しました');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }


    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return redirect()->route('products.index')->with('success', '商品を削除しました');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '商品の削除に失敗しました: ' . $e->getMessage());
        }
    }
}
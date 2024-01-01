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

            $product = new Product;
            $product->product_name = $request->input('product_name');
            $product->company_name = $request->input('company_name');
            $product->price = $request->input('price');
            $product->stock = $request->input('stock');
            $product->comment = $request->input('comment');
            $product->img_path = $path ? end($path) : null;
            $product->save();

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

        // 画像削除のチェックが入っている場合
        if ($request->has('remove_img')) {
            // 既存の画像を削除
            Storage::delete('public/' . $product->img_path);
            $product->img_path = null;
        }

        // 新しい画像がアップロードされた場合の処理
        if ($request->hasFile('img_path')) {
            $image = $request->file('img_path');
            $path = $image->store('public');
            $path = explode('/', $path);

            // 既存の画像があれば削除
            if ($product->img_path) {
                Storage::delete('public/' . $product->img_path);
            }

            $product->img_path = end($path);
        }

        $product->product_name = $request->input('product_name');
        $product->company_name = $request->input('company_name');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->comment = $request->input('comment');
        $product->save();

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
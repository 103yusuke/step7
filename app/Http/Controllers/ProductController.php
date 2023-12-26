<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;

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
                $path = $image->store('/public');
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
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '商品の登録に失敗しました: ' . $e->getMessage());
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

            $path = null;
            if ($request->hasFile('img_path')) {
                $image = $request->file('img_path');
                $path = $image->store('/public');
                $path = explode('/', $path);
            }

            $product->product_name = $request->input('product_name');
            $product->company_name = $request->input('company_name');
            $product->price = $request->input('price');
            $product->stock = $request->input('stock');
            $product->comment = $request->input('comment');
            $product->img_path = $path ? end($path) : null;
            $product->save();

            return redirect()->route('products.index')->with('success', '商品を更新しました');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '商品の更新に失敗しました: ' . $e->getMessage());
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
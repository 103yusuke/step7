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
        $data = $request->all();
        $image = $request->file('img_path');
        if ($request->hasFile('img_path')) {
            $path = \Storage::put('/public', $image);
            $path = explode('/', $path);
        } else {
            $path = null;
        }

        $request->validate([
            'product_name' => 'required|max:20',
            'company_name' => 'required|integer',
            'price' => 'required|integer',
            'stock' => 'required|integer',
        ]);

        $product = new Product($data);
        $product->img_path = $path;
        $product->save();

        return redirect()->route('products.index');
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
        $request->validate([
            'product_name' => 'required|max:20',
            'company_name' => 'required|integer',
            'price' => 'required|integer',
            'stock' => 'required|integer',
        ]);

        $product->fill($request->all());
        $product->img_path = $request->hasFile('img_path') ? \Storage::put('/public', $request->file('img_path')) : $product->img_path;
        $product->save();

        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', '商品記録' . $product->name . 'を削除しました');
    }
}

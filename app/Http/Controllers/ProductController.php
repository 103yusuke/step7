<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Bunrui;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{
    // 商品一覧のクエリの作成
    $productsQuery = Product::select([
        'b.id',
        'b.img_path',
        'b.product_name',
        'b.price',
        'b.stock',
        'b.company_name',
        'b.comment',
        'r.str as company_name',
    ])
    ->from('products as b')
    ->join('bunruis as r', function ($join) {
        $join->on('b.company_name', '=', 'r.id');
    })
    ->orderBy('b.id', 'DESC');

    // 検索キーワードがある場合の処理
    $keyword = $request->input('keyword');
    $selectedBunrui = $request->input('company_name');
    $bunruis = Bunrui::all();

    if (!empty($keyword)) {
        $productsQuery->where(function ($query) use ($keyword) {
            $query->where('b.product_name', 'LIKE', "%{$keyword}%")
                ->orWhere('b.comment', 'LIKE', "%{$keyword}%")
                ->orWhere('r.str', 'LIKE', "%{$keyword}%");
        });
    }

    if (!empty($selectedBunrui)) {
        $productsQuery->where('b.company_name', '=', $selectedBunrui);
    }

    // ページネーションを適用して商品一覧を取得
    $products = $productsQuery->paginate(5);

    // ビューにデータを渡す
    return view('index', compact('products', 'keyword', 'bunruis', 'selectedBunrui'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bunruis = Bunrui::all();
        return view('create')
            ->with('bunruis',$bunruis);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $image = $request->file('img_path');
        if($request->hasFile('img_path')){
            $path = \Storage::put('/public', $image);
            $path = explode('/', $path);
        }else{
            $path = null;
        }

        $request->validate([
            'product_name' => 'required|max:20',
            'bunrui' => 'required|integer',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            //'comment' => 'required|max:200' 
            //'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $product = new Product;
            $product->product_name = $request->input(["product_name"]);
            $product->company_name = $request->input(["bunrui"]);
            $product->price = $request->input(["price"]);
            $product->stock = $request->input(["stock"]);
            $product->comment = $request->input(["comment"]);
            $product->img_path = $request->file(["img_path"]);
            $product->save();

            return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $bunruis = Bunrui::all();
        return view('show',compact('product'))
        //->with('page_id',request()->page_id)
        ->with('bunruis',$bunruis);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $bunruis = Bunrui::all();
        return view('edit',compact('product', 'bunruis'))
        ->with('bunruis',$bunruis);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_name' => 'required|max:20',
            'bunrui' => 'required|integer',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            //'comment' => 'required|max:200'  
            //'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $product->product_name = $request->input(["product_name"]);
            $product->company_name = $request->input(["bunrui"]);
            $product->price = $request->input(["price"]);
            $product->stock = $request->input(["stock"]);
            $product->comment = $request->input(["comment"]);
            $product->img_path = $request->input(["img_path"]);
            $product->save();

            return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')
                        ->with('success','商品記録'.$product->name.'を削除しました');
    }

    

}

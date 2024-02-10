<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // DBファサードを追加
use App\Models\Product;
use App\Models\Sale;

class SalesController extends Controller
{
    public function purchase(Request $request)
    {
        // トランザクション開始
        DB::beginTransaction();

        try {
            // リクエストから必要なデータを取得する
            $productId = $request->input('product_id');
            $quantity = $request->input('quantity', 1);

            // データベースから対象の商品を検索・取得
            $product = Product::find($productId);

            // 商品が存在しない場合のバリデーション
            if (!$product) {
                return response()->json(['message' => '商品が存在しません'], 404);
            }

            // 在庫が不足している場合のバリデーション
            if ($product->stock < $quantity) {
                return response()->json(['message' => '商品が在庫不足です'], 400);
            }

            // 在庫を減少させる
            $product->stock -= $quantity;
            $product->save();

            // Salesテーブルにレコードを追加
            $sale = new Sale([
                'product_id' => $productId,
            ]);

            $sale->save();

            // トランザクションのコミット
            DB::commit();

            // レスポンスを返す
            return response()->json(['message' => '購入成功']);
        } catch (\Exception $e) {
            // トランザクションのロールバック
            DB::rollback();

            // エラーレスポンスを返す
            return response()->json(['message' => '購入に失敗しました: ' . $e->getMessage()], 500);
        }
    }
}

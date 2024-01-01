@extends('app')
   
@section('content')
 
<div style="text-align:right;">
<form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
     
     <div class="row">

     <div class="col-12 mb-2 mt-2">
        <div class="form-group row">
            <label for="product_name" class="col-md-4 col-form-label text-md-right">商品名</label>
            <div class="col-md-6">
                <input type="text" name="product_name" class="form-control">
                @error('product_name')
                <span style="color:red;">※入力してください</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-12 mb-2 mt-2">
            <div class="form-group row">
            <label for="company" class="col-md-4 col-form-label text-md-right">メーカー名</label>
            <div class="col-md-6">
                <select name="company_name" class="form-select">
                    <option>選択してください</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->company_name }}</otion>
                    @endforeach
                </select>
                @error('company_name')
                <span style="color:red;">※選択してください</span>
                @enderror
            </div>
        </div>
        
    <div class="col-12 mb-2 mt-2">
        <div class="form-group row">
            <label for="price" class="col-md-4 col-form-label text-md-right">価格</label>
            <div class="col-md-6">
                <input type="text" name="price" class="form-control">
                @error('price')
                <span style="color:red;">※入力してください</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-12 mb-2 mt-2">
        <div class="form-group row">
            <label for="stock" class="col-md-4 col-form-label text-md-right">在庫数</label>
            <div class="col-md-6">
                <input type="text" name="stock" class="form-control">
                @error('stock')
                <span style="color:red;">※入力してください</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-12 mb-2 mt-2">
        <div class="form-group row">
            <label for="comment" class="col-md-4 col-form-label text-md-right">コメント</label>
            <div class="col-md-6">
                <textarea class="form-control" style="height:100px" name="comment"></textarea>

            </div>
        </div>
    </div>

    <div class="col-12 mb-2 mt-2">
        <div class="form-group row">
            <label for="img_path" class="col-md-4 col-form-label text-md-right">商品画像</label>
            <div class="col-md-2">
                <input type="file" class="form-control-file" name='img_path' id="img_path">
                @error('img_path')
                <span style="color:red;">※{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-12 mb-2 mt-2">
        <button type="submit" class="btn btn-primary">新規登録</button>
        <a class="btn btn-success" href="{{ url('/products') }}">戻る</a>
    </div>

    </div>      
</form>
</div>
@endsection
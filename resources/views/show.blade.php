@extends('app')
   
@section('content')

<div style="text-align:left;">
    <div class="row">

        <div class="col-12 mb-2 mt-2">
            <div class="form-group row">
                <label for="name" class="col-md-2 text-md-right">ID</label>
                {{ $product->id }}
            </div>
        </div>

        <div class="col-12 mb-2 mt-2">
            <div class="form-group row">
                <label for="name" class="col-md-2 text-md-right">商品画像</label>
                <div class="col-md-10">
                    <img src="{{ asset($product->img_path) }}" alt="商品画像" class="img-fluid">
                </div>
            </div>
        </div>


        <div class="col-12 mb-2 mt-2">
            <div class="form-group row">
              <label for="product_name" class="col-md-2 text-md-right">商品名</label>
              {{ $product->product_name }}
            </div>
        </div>

        <div class="col-12 mb-2 mt-2">
            <div class="form-group row">
                <label for="company_name" class="col-md-2 text-md-right">メーカー名</label>
                @foreach ($companies as $company)
                    @if($company->id==$product->company_name) {{ $company->company_name }} @endif
                @endforeach
            </div>
        </div>
        
        <div class="col-12 mb-2 mt-2">
            <div class="form-group row">
                <label for="price" class="col-md-2 text-md-right">価格</label>
                {{ $product->price }}
            </div>
        </div>

        <div class="col-12 mb-2 mt-2">
             <div class="form-group row">
                <label for="stock" class="col-md-2 text-md-right">在庫数</label>
                {{ $product->stock }}
            </div>
        </div>

        <div class="col-12 mb-2 mt-2">
            <div class="form-group row">
                <label for="name" class="col-md-2 text-md-right">コメント</label>
                {{ $product->comment }}
            </div>
        </div>

        <div class="col-12 mb-2 mt-2">
            <a class="btn btn-primary" href="{{ route('product.edit',$product->id) }}">編集</a>
            <a class="btn btn-success" href="{{ url('/products') }}">戻る</a>
        </div>

    </div>      
</div>

@endsection
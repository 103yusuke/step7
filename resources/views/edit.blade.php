@extends('app')
   
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb"></div>
</div>
 
<div style="text-align:left;">
    <form action="{{ route('product.update',$product->id) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="col-12 mb-2 mt-2">
            <div class="form-group row">
                <label for="name" class="col-md-2 text-md-right">ID</label>
                <div class="col-md-10">
                    {{ $product->id }}
                </div>
            </div>
        </div>
         
        <div class="row">
            <div class="col-12 mb-2 mt-2">
                <div class="form-group row">
                    <label for="product_name" class="col-md-2 col-form-label text-md-right">商品名</label>
                    <div class="col-md-10">
                        <input type="text" name="product_name" class="form-control" value="{{ old('product_name', $product->product_name) }}">
                        @error('product_name')
                            <span style="color:red;">※入力してください</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-12 mb-2 mt-2">
                <div class="form-group row">
                    <label for="company" class="col-md-2 col-form-label text-md-right">メーカー名</label>
                    <div class="col-md-10">
                        <select name="company_name" class="form-select">
                            <option value="">選択してください</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" {{ $company->id == $product->company_name ? 'selected' : '' }}>
                                    {{ $company->company_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('company_name')
                            <span style="color:red;">※選択してください</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-12 mb-2 mt-2">
                <div class="form-group row">
                    <label for="price" class="col-md-2 col-form-label text-md-right">価格</label>
                    <div class="col-md-10">
                        <input type="text" name="price" class="form-control" value="{{ old('price', $product->price) }}">
                        @error('price')
                            <span style="color:red;">※入力してください</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-12 mb-2 mt-2">
                <div class="form-group row">
                    <label for="stock" class="col-md-2 col-form-label text-md-right">在庫数</label>
                    <div class="col-md-10">
                        <input type="text" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}">
                        @error('stock')
                            <span style="color:red;">※入力してください</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-12 mb-2 mt-2">
                <div class="form-group row">
                    <label for="name" class="col-md-2 col-form-label text-md-right">コメント</label>
                    <div class="col-md-10">
                        <textarea name="comment" class="form-control" style="height:100px">{{ old('comment', $product->comment) }}</textarea>
                        @error('comment')
                            <span style="color:red;">※入力してください</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="img_path" class="col-md-2 col-form-label text-md-right">商品画像</label>
                <div class="col-md-10">
                    <input type="file" class="form-control-file" name='img_path' id="img_path">
                    @error('img_path')
                        <span style="color:red;">※{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-12 mb-2 mt-2">
            <button type="submit" class="btn btn-primary">変更</button>
            <a class="btn btn-success" href="{{ url('/products') }}">戻る</a>
        </div>
    </form>
</div>
@endsection

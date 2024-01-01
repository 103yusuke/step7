@extends('app')

@section('content')

<div class="d-flex align-items-center">
    <form action="{{ route('product.index') }}" method="GET" class="d-flex flex-row">
        <input type="text" name="keyword" value="{{ $keyword }}" placeholder="検索キーワード" class="form-control me-2">
        
        <select class="form-control me-2" name="company_name">
            <option selected="selected" value="">メーカー名</option>
            @foreach ($companies as $company)
                <option value="{{ $company->id }}" {{ $company->id == $selectedCompany ? 'selected' : '' }}>
                    {{ $company->company_name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">検索</button>
    </form>
</div>

<div class="row mt-3">
    <div class="col-lg-12">
        @if ($message = Session::get('success'))
            <div class="alert alert-success mt-1"><p>{{ $message }}</p></div>
        @endif
        @if ($error = Session::get('error'))
            <div class="alert alert-danger mt-1"><p>{{ $error }}</p></div>
        @endif
    </div>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>商品画像</th>
            <th>商品名</th>
            <th>価格</th>
            <th>在庫数</th>
            <th>メーカー名</th>
            <th colspan="2" style="text-align:center"><a class="btn btn-success" href="{{ route('product.create') }}">新規登録</a></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
        <tr>
            <td style="text-align:right">{{ $product->id }}</td>
            <td><img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像" style="max-width: 100px; max-height: 50px;"></td>
            <td>{{ $product->product_name }}</td>
            <td style="text-align:right">{{ $product->price }}円</td>
            <td style="text-align:right">{{ $product->stock }}</td>
            <td>{{ $product->company_name }}</td>
            <td style="text-align:center">
                <a class="btn btn-primary" href="{{ route('product.show',$product->id) }}">詳細</a>
            </td>
            <td style="text-align:center">
                <form action="{{ route('product.destroy' ,$product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick='return confirm("削除しますか？");'>削除</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{!! $products->links('pagination::bootstrap-5') !!}
@endsection

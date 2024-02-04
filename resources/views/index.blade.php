@extends('app')

@section('content')

<!-- 検索フォーム -->
<div class="d-flex align-items-center">
    <form id="search-form" action="{{ route('product.search') }}" method="POST" class="d-flex flex-row">
        @csrf
        <input type="text" name="keyword" value="{{ $keyword }}" placeholder="検索キーワード" class="form-control me-2">
        <select class="form-control me-2" name="company_name">
            <option selected="selected" value="">メーカー名</option>
            @foreach ($companies as $company)
                <option value="{{ $company->id }}" {{ $company->id == $selectedCompany ? 'selected' : '' }}>
                    {{ $company->company_name }}
                </option>
            @endforeach
        </select>
        <!-- 価格と在庫数 -->
        <input type="text" name="min_price" placeholder="下限価格" class="form-control me-2">
        <input type="text" name="max_price" placeholder="上限価格" class="form-control me-2">
        <input type="text" name="min_stock" placeholder="下限在庫数" class="form-control me-2">
        <input type="text" name="max_stock" placeholder="上限在庫数" class="form-control me-2">

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

<div id="search-results">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><a href="#" class="sort" data-column="id">ID</a></th>
                <th>商品画像</th>
                <th>商品名</th>
                <th><a href="#" class="sort" data-column="price">価格</a></th>
                <th><a href="#" class="sort" data-column="stock">在庫数</a></th>
                <th>メーカー名</th>
                <th colspan="2" style="text-align:center"><a class="btn btn-success" href="{{ route('product.create') }}">新規登録</a></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr id="product-row-{{ $product->id }}">
                <td style="text-align:right">{{ $product->id }}</td>
                <td><img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像" style="max-width: 100px; max-height: 50px;"></td>
                <td>{{ $product->product_name }}</td>
                <td style="text-align:right">{{ $product->price }}円</td>
                <td style="text-align:right">{{ $product->stock }}</td>
                <td>{{ $product->company_name }}</td>
                <td style="text-align:center">
                    <a class="btn btn-primary" href="{{ route('product.show',$product->id) }}">詳細</a>
                </td>
                <!-- 削除ボタン -->
                <td style="text-align:center">
                    <button class="btn btn-danger delete-btn" data-product-id="{{ $product->id }}">削除</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {!! $products->links('pagination::bootstrap-5') !!}
</div>

<script>
    $(document).ready(function () {
        // CSRFトークンを含める
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var sortOrders = {};

        // 初期ソート状態をセッションから取得
        var initialSortColumn = "{{ session('sort_column', 'id') }}";
        var initialSortOrder = "{{ session('sort_order', 'asc') }}";

        // ソート順を初期化
        $('.sort').each(function () {
            var column = $(this).data('column');
            sortOrders[column] = (column === initialSortColumn) ? initialSortOrder : 'asc';
            $(this).removeClass('asc desc').addClass(sortOrders[column]);
        });

        // クリック毎に昇順・降順を切り替える
    $(document).on('click', '.sort', function () {
        var column = $(this).data('column');
        sortOrders[column] = (sortOrders[column] === 'asc') ? 'desc' : 'asc';

        console.log('Column: ' + column + ', Order: ' + sortOrders[column]);

        $('.sort').removeClass('asc desc');
        $(this).addClass(sortOrders[column]);

        // 検索フォームのデータを取得
        var formData = $('#search-form').serialize();

        // ソート情報を追加
        formData += '&column=' + column + '&order=' + sortOrders[column];

        // ソート情報をサーバーに送信
        $.ajax({
            type: 'GET',
            url: '{{ route('product.sort') }}',
            data: formData,
            success: function (data) {
                $('#search-results').html(data);
                // クリック可能にする
                $('.sort').prop('disabled', false);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
        // クリック不可にする
        $('.sort').prop('disabled', true);
    });


    // 検索フォームのサブミット時に非同期で検索
    $('#search-form').on('submit', function (e) {
        e.preventDefault();
        var formData = $(this).serialize();
        // ソート情報も取得
        var column = $('.sort.asc, .sort.desc').data('column');
        var order = $('.sort.asc, .sort.desc').hasClass('asc') ? 'asc' : 'desc';
        formData += '&column=' + column + '&order=' + order;
        $.ajax({
            type: 'POST',
            url: '{{ route('product.search') }}',
            data: formData,
            success: function (data) {
                $('#search-results').html(data);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

        // 削除ボタンのクリック時に非同期で商品を削除
        $(document).on('click', '.delete-btn', function (e) {
            e.preventDefault();
            var productId = $(this).data('product-id');

            if (confirm('削除しますか？')) {
                $.ajax({
                    type: 'DELETE', // ここをDELETEに変更
                    url: '{{ url("/products/async-delete/") }}/' + productId,
                    success: function (data) {
                        // 検索結果を再読み込みするか、必要に応じて UI を更新します
                        alert(data.success);

                        // 削除された商品のIDに対応する行を非表示にする
                        $('#product-row-' + data.productId).hide();
                    },
                    error: function (xhr, status, error) {
                        alert('削除に失敗しました: ' + xhr.responseText);
                    }
                });
            }
        });
    });
</script>
@endsection
@if(count($products) > 0)
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
                    <td style="text-align:center">
                        <button class="btn btn-danger delete-btn" data-product-id="{{ $product->id }}">削除</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {!! $products->links('pagination::bootstrap-5') !!}
@else
    <p>検索結果が見つかりませんでした。</p>
@endif

<script>
$(document).ready(function () {
    // 削除ボタンのクリック時に非同期で商品を削除
    $(document).off('click', '.delete-btn').on('click', '.delete-btn', function (e) {
        e.preventDefault();
        var productId = $(this).data('product-id');

        if (confirm('削除しますか？')) {
            $.ajax({
                type: 'DELETE',
                url: '{{ url("/products/async-delete/") }}/' + productId,
                success: function (data) {
                    // 削除成功時の処理
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

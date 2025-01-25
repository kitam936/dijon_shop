<table>
    <thead>
        <tr>
            <th>商品ID</th>
            <th>商品名</th>
            <th>数量</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>
                    <form method="POST" action="{{ route('cart.add') }}">
                        @csrf
                        <input type="hidden" name="sku_id" value="{{ $product->id }}">
                        <select name="pcs">
                            @for ($i = 1; $i <= 9; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        <button type="submit">カートに追加</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<x-app-layout>
    <h2>棚卸結果</h2>
    <p>日付: {{ $header->inventory_date }}</p>
    <p>入力者: {{ $header->name }}</p>

    <table>
        <tr><th>商品コード</th><th>数量</th></tr>
        @foreach($header->details as $detail)
            <tr>
                <td>{{ $detail->sku_id }}</td>
                <td>{{ $detail->pcs }}</td>
            </tr>
        @endforeach
    </table>

    <a href="{{ route('inventory_dl',['id' => $header->id]) }}">CSVダウンロード</a>

</x-app-layout>

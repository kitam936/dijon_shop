<x-app-layout>
    <h2>棚卸内容確認</h2>
    <table>
        <tr>
            <th>商品コード</th>
            <th>数量</th>
            <th>入力者</th>
        </tr>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->sku_id }}</td>
                <td>{{ $item->pcs }}</td>
                <td>{{ $item->name }}</td>
            </tr>
        @endforeach
    </table>

    <form method="POST" action="{{ route('inventory_complete')}}">
        @csrf
        <input type="text" name="memo" placeholder="メモ">
        <button type="submit">棚卸を確定</button>
    </form>
</x-app-layout>

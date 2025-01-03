<p class="mb-4">{{ $user['name']}}　様</p>

<p class="mb-4">Dijon_Web_Shop のレポートにコメントが登録されました。</p>
<br><br>


コメント更新


<ul class="mb-4">
    {{-- <li>投稿日：{{ \Carbon\Carbon::parse($comment_info['updated_at'])->format("y/m/d H:i")}}</li> --}}
    <li>ReportId：{{ $comment_info['id'] }}</li>
    <li>店舗名：{{ $comment_info['shop_name'] }}</li>
</ul>

<br>

Reportのコメントが更新されました。<br><br>

アプリで確認をしてください。<br><br>



Dijon_Web_Shop


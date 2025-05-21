<x-app-layout>

    <h2 class="font-semibold text-xl mt-4 ml-4 text-indigo-800 leading-tight">
        バーコードSCAN
    </h2>

    <x-flash-message status="session('status')"/>

    <div class="ml-2 flex mb-4 mb-4 mt-4">
        <div class="ml-4 mt-2 md:mt-0 md:ml-8">
            <button type="button" class="w-24 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('analysis_index') }}'" >Menu</button>
        </div>
        <div class="ml-4 mt-2 md:ml-4 md:mt-0">
            <button type="button" class="w-24 text-center text-sm text-white bg-indigo-500 binventory-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('inventory_index') }}'" >入力状況</button>
        </div>
        <div class="ml-4 mt-2 md:ml-4 md:mt-0">
            <button type="button" class="w-24 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('inventory_result_index') }}'" >棚卸リスト</button>
        </div>
    </div>

    <div  class="mb-2 ml-12 h-6 text-sm w-60 bg-gray-100 rounded bg-opacity-50 focus:border-indigo-500 border focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-6 transition-colors duration-200 ease-in-out"> 作業者：{{ Auth::user()->name }}
    </div>

    <style>
        #reader {
            width: 100%;
            max-width: 600px;
            aspect-ratio: 4 / 3; /* 比率指定 */
            border: 1px solid #ccc;
            margin-bottom: 10px;
            position: relative;
            overflow: hidden;
        }

        #reader video,
        #reader canvas {
            width: 100% !important;
            height: auto !important;
            max-width: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }
    </style>

    @if($h_exist || $s_exist)
    <div class="ml-12 text-ml text-red-500">
        マスターに存在しない商品コードがあります<br>
        赤く表示された行を削除した上で<br>
        再度スキャンしてください。<br><br>
        読み込めない場合は手入力で<br>
        品番（6桁）カラー（2桁）サイズ（2桁）<br>
        の10桁数字を打ち込んで追加してください<br><br>
        それでもエラーが出る場合は一旦商品を隔離して<br>
        追って担当者に確認してください。
    </div>
    @else
    {{-- カメラ表示 --}}
    <div id="reader"  style="width:100%; max-width:600px; height:300px; border:1px solid #ccc; margin-bottom: 10px;"></div>
    @endif
    <br>



    {{-- 手入力フォーム --}}
    <form method="POST" action="{{ route('inventory_manual') }}" class="ml-2 mt-12 md:mt-24">
        @csrf
        <div style="display:flex; gap:10px; align-items: center;">
            <input type="text" name="raw_cd"class="rounded w-64 custom-placeholder"  placeholder="品番(6)色(2)SZ(2)の10桁" required>
            <input type="number" name="pcs" class="rounded w-16" value="1" min="1">
            <button type="submit" class="w-20 h-9 mt-1 items-right bg-blue-500 text-sm text-white ml-0 hover:bg-blue-600 rounded ">手入力追加</button>
        </div>
        <style>
            .custom-placeholder::placeholder {
                font-size: 0.9rem; /* 任意のサイズに変更 */
                color: #999;
            }
        </style>
    </form>

    <div class="flex">


    </div>

    <h3 class="ml-4 flex font-semibold text-ml text-indigo-800 leading-tight mt-4">
        新着順5件表示

        <div class="flex relative ml-20">
            <label for="total_pcs" class="leading-7 text-ml  text-indigo-800 ">棚卸数</label>
            @if($works_total)
            <div  id="total_pcs" name="total_pcs" value="{{$works_total->pcs}}" class="h-8 text-ml w-24 text-indigo-800 bg-gray-100 bg-opacity-50 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$works_total->pcs}}枚
            @else
            <div  id="total_pcs" name="total_pcs" class="h-8 text-ml w-24 bg-gray-100 bg-opacity-50 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out"> 0 枚
            </div>
            @endif
        </div>
    </h3>


    <div class="mt-2">

        <table class="ml-1 bg-white text-left table-auto whitespace-no-wrap">

        <thead>
            <tr>
                {{-- <th class="w-3/16 md:px-0 py-1 min-w-[100px]">CK</th> --}}
                <th class="w-3/16 pr-4 md:pr-0 py-1 text-center">品番　・　色　・　サイズ</th>


            </tr>
        </thead>
        <tbody>
            @foreach ($works as $work )
            <tr>
                <td class="w-5/16 md:px-2 py-1" >
                    <form method="POST" action="{{ route('inventory_update2', $work->id) }}">
                        @csrf
                        <div class="flex ml-1">
                            @if(is_null($work->hin_ck))
                            {{-- <input readonly value="{{ ($work->raw_cd) }}" class="rounded mr-2 bg-red-300 text-ml" style="width:120px; font-variant-numeric:tabular-nums"> --}}
                            <input readonly value="{{ substr($work->raw_cd,0,6) }}" class="rounded mr-2 bg-red-300 text-ml" style="width:120px; font-variant-numeric:tabular-nums">
                            @else
                            <input readonly value="{{ substr($work->raw_cd,0,6) }}" class="rounded mr-2 text-ml " style="width:120px; font-variant-numeric:tabular-nums">
                            @endif

                            @if(is_null($work->sku_ck))
                            <input readonly value="{{ substr($work->raw_cd,6,2) }}" class="rounded mr-2 bg-red-300 text-ml" style="width:60px; font-variant-numeric:tabular-nums">
                            <input readonly value="{{ substr($work->raw_cd,8,2) }}" class="rounded mr-2 bg-red-300 text-ml" style="width:60px; font-variant-numeric:tabular-nums">
                            @else
                            <input readonly value="{{ substr($work->raw_cd,6,2) }}" class="rounded mr-2 text-ml" style="width:60px; font-variant-numeric:tabular-nums">
                            <input readonly value="{{ substr($work->raw_cd,8,2) }}" class="rounded mr-2 text-ml" style="width:60px; font-variant-numeric:tabular-nums">
                            @endif

                        </div>
                    </form>
                </td>
                <td class="w-2/16 md:px-2 py-1">
                    <div>
                    <form method="POST" action="{{ route('inventory_destroy2', $work->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-12 h-9 mt-1 items-center bg-red-500 text-sm text-white ml-0 hover:bg-red-600 rounded " onclick="return confirm('削除しますか？')">削除</button>
                    </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>




    <script src="https://unpkg.com/@ericblade/quagga2/dist/quagga.min.js"></script>
    <script>
        Quagga.init({
            inputStream: {
                type: "LiveStream",
                constraints: {
                    facingMode: "environment",
                    type: "LiveStream",
                    width: { ideal: 1280 },   // 追加：幅の理想値 1280
                    height: { ideal: 720 }    // 追加：高さの理想値 720

                },
                target: document.querySelector('#reader')
            },
            decoder: {
                // readers: ["ean_reader", "ean_8_reader", "code_128_reader", "code_39_reader"]
                readers: ["ean_reader"]
            }
        }, function(err) {
            if (err) {
                console.error(err);
                alert("カメラ初期化失敗: " + err);
                return;
            }
            Quagga.start();
        });

        Quagga.onDetected(function(data) {
            const code = data.codeResult.code;
            console.log("Detected code:", code);

            // 読み取り直後に一時停止
            Quagga.stop();

            // データ送信
            fetch("{{ route('inventory_store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ raw_cd: code })
            }).then(() => {
                alert("商品コード「" + code + "」を登録しました。");
                location.reload(); // 読み取り完了後に画面更新（または Quagga.start() を再開してもOK）
            }).catch(error => {
                alert("登録に失敗しました。もう一度お試しください。");
                console.error(error);
                Quagga.start(); // 失敗時は再開
            });
        });
    </script>
</x-app-layout>

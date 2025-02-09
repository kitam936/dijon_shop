
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            自店商品別売上順<br>
        </h2>
        <div class="flex">
            <div class="flex">
                <div class="pl-2 mt-2 ml-4 ">
                    <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('analysis_index') }}'" >DataMenu</button>
                </div>
                <div class="pl-2 mt-2  ml-8 ">
                <button type="button" class="w-32 text-center text-sm text-white bg-blue-500 border-0 py-1 px-2 focus:outline-none hover:bg-blue-700 rounded " onclick="location.href='{{ route('sales_product_reset') }}'" >選択リセット</button>
                </div>
            </div>
        </div>

        {{-- <span class="items-center text-sm mt-2 text-gray-800 dark:text-gray-200 leading-tight" >　※Brand・店舗を選択してください　　　</span> --}}

        <form method="get" action="{{ route('my_sales_product')}}" class="mt-4">

            <div class="flex mb-2">
                <div>
                <label for="YM1" class="mr-3 leading-7 text-sm  text-gray-800 ">期間(年月)</label>
                <select class="w-36 h-8 rounded text-sm pt-1" id="YM1" name="YM1" type="number" class="border">
                    <option value="{{ $max_YM }}" @if(\Request::get('YM1') == '0') selected @endif >年月選択(from)</option>
                    @foreach ($YMs as $YM)
                        <option value="{{ $YM->YM }}" @if(\Request::get('YM1') == $YM->YM) selected @endif >{{ floor(($YM->YM)/100)%100 }}年{{ ($YM->YM)%100 }}月</option>
                    @endforeach
                </select>
                </div>
               <span class="items-center text-sm mt-4 mr-2 ml-0 md:mt-2 text-gray-800 leading-tight" >　　～</span>
                <div>
                 <label for="YM2" class="mr-0 leading-7 text-sm  text-gray-800 ">　</label>
                <select class="w-36 h-8 rounded text-sm pt-1" id="YM2" name="YM2" type="number" class="border">
                    <option value="{{ $max_YM }}" @if(\Request::get('YM2') == '0') selected @endif >年月選択(to)</option>
                    @foreach ($YMs as $YM)
                        <option value="{{ $YM->YM }}" @if(\Request::get('YM2') == $YM->YM) selected @endif >{{ floor(($YM->YM)/100)%100 }}年{{ ($YM->YM)%100 }}月</option>
                    @endforeach
                </select>

                </div>
            </div>

            <div class="md:flex">
            <div class="flex">
                <div>
                <label for="type3" class="mr-5 leading-7 text-sm  text-gray-800 ">商品区分</label>
                <select id="type3" name="type3" class="w-28 h-8 rounded text-sm pt-1 border mr-6 mb-2" type="text">
                    <option value="" @if(\Request::get('type3') == '0') selected @endif >品番(統合込)</option>
                    <option value="h" @if(\Request::get('type3') == "h") selected @endif>品番別</option>
                    <option value="s" @if(\Request::get('type3') == "s") selected @endif>SKU別</option>
                </select>
                </div>
            </div>
            </div>

            <div class="md:flex">
            <div class="flex">
                <div>
                <label for="brand_code" class="mr-3 leading-7 text-sm  text-gray-800 ">Brand指定</label>
                <select class="w-28 h-8 rounded text-sm pt-1 border mb-2 mr-6 " id="brand_code" name="brand_code" type="number" >
                    <option value="" @if(\Request::get('brand_code') == '0') selected @endif >選択なし</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" @if(\Request::get('brand_code') == $brand->id ) selected @endif >{{ $brand->brand_name  }}</option>
                    @endforeach
                </select>
                </div>
                <div>
                <label for="season_code" class="mr-4 leading-7 text-sm  text-gray-800 ">季節指定</label>
                <select class="w-28 h-8 rounded text-sm pt-1 border mb-2 mr-2 " id="season_code" name="season_code" type="number" >
                    <option value="" @if(\Request::get('season_code') == '0') selected @endif >選択なし</option>
                    @foreach ($seasons as $season)
                        <option value="{{ $season->season_id }}" @if(\Request::get('season_code') == $season->season_id ) selected @endif >{{ $season->season_name  }}</option>
                    @endforeach
                </select>
                </div>
            </div>

            <div class="flex md:ml-4">
                <div>
                <label for="unit_id" class="mr-2 leading-7 text-sm  text-gray-800 ">Unit指定　</label>
                <select class="w-28 h-8 rounded text-sm pt-1 border mb-2 mr-6 " id="unit_id" name="unit_id" >
                <option value="" @if(\Request::get('unit_id') == '0') selected @endif >選択なし</option>
                @foreach ($units as $unit)
                    <option value="{{ $unit->unit_code }}" @if(\Request::get('unit_id') == $unit->unit_code ) selected @endif >{{ $unit->id  }}</option>
                @endforeach
                </select>
                </div>

                <div>
                <label for="face" class="mr-0 leading-7 text-sm  text-gray-800 ">Face指定　</label>
                <select class="w-28 h-8 rounded border text-sm items-center pt-1" id="face" name="face" >
                    <option value="" @if(\Request::get('face') == '0') selected @endif >選択なし</option>
                    @foreach ($faces as $face)
                    <option value="{{ $face->face }}" @if(\Request::get('face') == $face->face ) selected @endif >{{ $face->face  }}</option>
                    @endforeach
                </select>
                </div>
            </div>
            </div>
        </form>


        <div class="ml-0 mt-3 py-0 md:w-2/3 border">
            <div class="md:flex w-full  sm:px-0 lg:px-0 border mt-0 ml-0 items-center">
                <div class='md:w-2/3 pl-0 border bg-gray-100 h-6 text-sm items-center'>
                    　　累計数：{{ number_format(($total->pcs_total))}}枚　　
                    累計額：{{ number_format(round($total->total)/1000) }}千　　
                </div>
                <div class='md:w-2/3 pl-0 border bg-gray-100 h-6 text-sm items-center'>
                    @if($total->pcs_total>0)
                    　平均単価：{{ number_format(($total->total/($total->pcs_total))) }}円　
                    @endif
                </div>
            </div>
        </div>

    </x-slot>

        <div class="py-6 border">
        <div class="md:w-2/3 sm:px-4 lg:px-4 border">
            <table class="mx-auto table-auto bg-white w-full text-center whitespace-no-wrap">
                <thead >
                <tr>
                    <th class="w-30 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">品番/SKU</th>
                    {{-- <th class="w-3/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">品番</th> --}}
                    <th class="w-60 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">品名</th>
                    <th class="w-30 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">売価</th>
                    <th class="w-10 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">数</th>
                    <th class="w-30 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">商品画像</th>
                </tr>
                </thead>

                <tbody>
                    @foreach ($datas as $data)
                <tr>
                    <td class="w-30 md:px-4 py-1 text-sm  text-indigo-700"><a href="{{ route('product_show',['hinban'=>$data->hinban_id]) }}" >{{ $data->code }}</a></td>
                    {{-- <td class="w-3/12 md:px-4 py-1 text-sm">{{ $data->hinban_id }}</td> --}}
                    <td class="w-60 md:px-4 py-1 text-sm">{{ Str::limit($data->hinban_name, 20, '...')}}</td>
                    <td class="w-30 pr-2 md:px-4 text-sm py-1 text-center"><span style="font-variant-numeric:tabular-nums"> {{ number_format(round($data->m_price))}}</span></td>
                    <td class="w-10 pr-1 md:px-4 text-sm py-1 text-right"><span style="font-variant-numeric:tabular-nums"> {{ number_format(round($data->pcs_total))}}</span></td>
                    {{-- <td class="w-3/15 pr-2 md:px-4 text-sm py-1 text-right"><span style="font-variant-numeric:tabular-nums"> {{ number_format(($data->total)/1000)}}</span></td> --}}
                    <td class="w-30 md:px-4 py-0">
                        <div class="ml-2 md:ml-12 items-right w-full md:w-2/3 ">
                        @if(\Request::get('type3') == "h" )
                        <x-image-thumbnail :filename="$data->filename"  />
                        @endif
                        @if(\Request::get('type3') == '0')
                        <x-image-thumbnail :filename="$data->filename"  />
                        @endif
                        @if(\Request::get('type3') == "s")
                        <x-sku_image-thumbnail :filename="$data->filename"  />
                        @endif
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>

            {{  $datas->appends([
                'YW1'=>\Request::get('YM1'),
                'YW2'=>\Request::get('YM2'),
                'type3'=>\Request::get('type3'),
                'brand_code'=>\Request::get('brand_code'),
                'season_code_id'=>\Request::get('season_code_id'),
                'unit_id'=>\Request::get('unit_id'),
                'face'=>\Request::get('face'),
            ])->links()}}
        </div>
        </div>


<script>

const type3 = document.getElementById('type3')
type3.addEventListener('change', function(){
this.form.submit()
    })


const YM1 = document.getElementById('YM1')
YM1.addEventListener('change', function(){
this.form.submit()
    })

const YM2 = document.getElementById('YM2')
YM2.addEventListener('change', function(){
this.form.submit()
})

const brand = document.getElementById('brand_code')
brand.addEventListener('change', function(){
this.form.submit()
})

const season = document.getElementById('season_code')
season.addEventListener('change', function(){
this.form.submit()
})

const unit = document.getElementById('unit_id')
unit.addEventListener('change', function(){
this.form.submit()
})

const face = document.getElementById('face')
face.addEventListener('change', function(){
this.form.submit()
})




</script>

</x-app-layout>




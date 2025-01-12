
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            社店商品在庫<br>
        </h2>
        <div class="flex">
        <div class="pl-2 mt-2 ml-12 ">
            <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('analysis_index') }}'" >DataMenu</button>
        </div>
        <div class="pl-2 mt-2  ml-20 ">
            <button type="button" class="w-32 text-center text-sm text-white bg-blue-500 border-0 py-1 px-2 focus:outline-none hover:bg-blue-700 rounded " onclick="location.href='{{ route('stocks_product_reset') }}'" >選択リセット</button>
        </div>
        </div>
        {{-- <span class="items-center text-sm mt-2 text-gray-800 dark:text-gray-200 leading-tight" >　※Brand・店舗を選択してください　　　</span> --}}

        <form method="get" action="{{ route('stocks_product')}}" class="mt-4">

            <div class="md:flex">
            <div class="flex">
                <div>
                <label for="type3" class="mr-5 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">商品区分</label>
                <select id="type3" name="type3" class="w-28 h-8 rounded text-sm pt-1 border mr-6 mb-2" type="text">
                    <option value="h" @if(\Request::get('type3') == '0' || \Request::get('type3') == "h") selected @endif >品番別</option>
                    <option value="s" @if(\Request::get('type3') == "s") selected @endif>シーズン別</option>
                    <option value="u" @if(\Request::get('type3') == "u") selected @endif>Unit別</option>
                    <option value="f" @if(\Request::get('type3') == "f") selected @endif>Face別</option>
                    {{-- <option value="h" @if(\Request::get('type3') == "h") selected @endif>品番別</option> --}}
                </select>
                </div>

                <div>
                <label for="type1" class="mr-4 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">社店種別</label>
                <select id="type1" name="type1" class="w-28 h-8 rounded text-sm pt-1 border mr-2 mb-2" type="text">
                    <option value="" @if(\Request::get('type1') == '0') selected @endif >全社店</option>
                    {{-- <option value="dry">dry</option> --}}
                    <option value="co" @if(\Request::get('type1') == "co") selected @endif>社別</option>
                    <option value="sh" @if(\Request::get('type1') == "sh") selected @endif>店別</option>
                    {{-- <option value="wet">wet</option> --}}
                </select>
                </div>
            </div>

            <div class="flex md:ml-4">
                @if(\Request::get('type1') == 'co' || \Request::get('type1') == 'sh')
                <div>
                <label for="co_id" class="mr-6 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">社を指定</label>
                <select class="w-28 h-8 rounded text-sm pt-1 border mb-2 mr-6 " id="co_id" name="co_id" >
                <option value="" @if(\Request::get('co_id') == '0') selected @endif >選択なし</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" @if(\Request::get('co_id') == $company->id ) selected @endif >{{ $company->co_name  }}</option>
                @endforeach
                </select>
                </div>
                @endif
                @if(\Request::get('type1') == 'sh')
                <div>
                <label for="sh_id" class="mr-5 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">店を指定</label>
                <select class="w-32 h-8 rounded border text-sm items-center pt-1" id="sh_id" name="sh_id" >
                    <option value="" @if(\Request::get('sh_id') == '0') selected @endif >選択なし</option>
                    @foreach ($shops as $shop)
                    <option value="{{ $shop->id }}" @if(\Request::get('sh_id') == $shop->id ) selected @endif >{{ $shop->shop_name  }}</option>
                    @endforeach
                </select>
                </div>
                @endif
            </div>
            </div>
            <div class="md:flex">
            <div class="flex">
                <div>
                <label for="brand_code" class="mr-3 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">Brand指定</label>
                <select class="w-28 h-8 rounded text-sm pt-1 border mb-2 mr-6 " id="brand_code" name="brand_code" type="number" >
                    <option value="" @if(\Request::get('brand_code') == '0') selected @endif >選択なし</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" @if(\Request::get('brand_code') == $brand->id ) selected @endif >{{ $brand->brand_name  }}</option>
                    @endforeach
                </select>
                </div>
                <div>
                <label for="season_code" class="mr-4 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">季節指定</label>
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
                <label for="unit_id" class="mr-2 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">Unit指定　</label>
                <select class="w-28 h-8 rounded text-sm pt-1 border mb-2 mr-6 " id="unit_id" name="unit_id" >
                <option value="" @if(\Request::get('unit_id') == '0') selected @endif >選択なし</option>
                @foreach ($units as $unit)
                    <option value="{{ $unit->id }}" @if(\Request::get('unit_id') == $unit->id ) selected @endif >{{ $unit->id  }}</option>
                @endforeach
                </select>
                </div>

                <div>
                <label for="face" class="mr-0 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">Face指定　</label>
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


        <div class="ml-0 mt-3 py-0 md:w-1/2 border">
            <div class=" w-full  sm:px-0 lg:px-0 border mt-0 ml-0">
                <div class='pl-2 border bg-gray-100 h-6 text-sm'>

                    在庫数：{{ number_format(($total->pcs_total))}}枚　
                    在庫額：{{ number_format(round($total->total)/1000) }}千円　
                    @if($total->pcs_total>0)
                    平均単価：{{ number_format(($total->total/($total->pcs_total))) }}円　
                    @endif
                </div>
            </div>
        </div>

    </x-slot>

        <div class="py-6 border">
        <div class="md:w-1/2 sm:px-4 lg:px-4 border">
            <table class="mx-auto table-auto bg-white w-full text-center whitespace-no-wrap">
                <thead >
                <tr>
                    <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">ID</th>
                    {{-- <th class="w-3/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">品番</th> --}}
                    <th class="w-5/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Name</th>
                    {{-- <th class="w-2/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">売価</th> --}}
                    <th class="w-1/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">在庫数</th>
                    <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">在庫額(千)</th>
                </tr>
                </thead>

                <tbody>
                    @foreach ($datas as $data)
                <tr>

                    <td class="w-3/12 md:px-4 py-1 text-sm">{{ $data->code }}</td>
                    <td class="w-5/14 md:px-4 py-1 text-sm">{{ Str::limit($data->name, 20, '...')}}</td>
                    {{-- @if($data->m_price)
                    <td class="w-2/14 pr-4 md:px-4 text-sm py-1 text-right"><span style="font-variant-numeric:tabular-nums"> {{ number_format(($data->m_price))}}</span></td>
                    @else
                    <td class="w-2/14 pr-4 md:px-4 text-sm py-1 text-right"><span style="font-variant-numeric:tabular-nums"> ----</span></td>
                    @endif --}}
                    <td class="w-1/14 pr-2 md:px-4 text-sm py-1 text-right"><span style="font-variant-numeric:tabular-nums"> {{ number_format(round($data->pcs_total))}}</span></td>
                    <td class="w-3/14 pr-4 md:px-4 text-sm py-1 text-right"><span style="font-variant-numeric:tabular-nums"> {{ number_format(($data->total)/1000)}}</span></td>

                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        </div>


<script>

const type3 = document.getElementById('type3')
type3.addEventListener('change', function(){
this.form.submit()
    })

const type1 = document.getElementById('type1')
type1.addEventListener('change', function(){
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

const company = document.getElementById('co_id')
company.addEventListener('change', function(){
this.form.submit()
})

const shop = document.getElementById('sh_id')
shop.addEventListener('change', function(){
this.form.submit()
})



</script>

</x-app-layout>




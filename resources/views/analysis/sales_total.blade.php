
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            社店累計売上順<br>
        </h2>
        <div class="flex">
            <div class="pl-2 mt-2 ml-4 ">
                <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('analysis_index') }}'" >Menu</button>
            </div>
            <div class="pl-2 mt-2  ml-8 ">
                <button type="button" class="w-32 text-center text-sm text-white bg-blue-500 border-0 py-1 px-2 focus:outline-none hover:bg-blue-700 rounded " onclick="location.href='{{ route('sales_total_reset') }}'" >選択リセット</button>
            </div>
        </div>

        {{-- <span class="items-center text-sm mt-2 text-gray-800 dark:text-gray-200 leading-tight" >　※Brand・店舗を選択してください　　　</span> --}}

        <form method="get" action="{{ route('sales_total')}}" class="mt-4">

            {{-- <div class="flex mb-2">
                <label for="YM1" class="mr-12 leading-7 text-sm  text-gray-800 ">期間</label>
                <select class="w-32 h-8 rounded text-sm pt-1" id="YM1" name="YM1" type="number" class="border">
                    <option value="" @if(\Request::get('YM1') == '0') selected @endif >{{ $max_YM }}直近月</option>
                    @foreach ($YMs as $YM)
                        <option value="{{ $YM->YM }}" @if(\Request::get('YM1') == $YM->YM) selected @endif >{{ floor(($YM->YM)/100)%100 }}年{{ ($YM->YM)%100 }}月</option>
                    @endforeach
                </select>
                <span class="items-center text-sm mt-2 text-gray-800 leading-tight" >　～　</span>
                <select class="w-32 h-8 rounded text-sm pt-1" id="YM2" name="YM2" type="number" class="border">
                    <option value="" @if(\Request::get('YM2') == '0') selected @endif >{{ $max_YM }}直近月</option>
                    @foreach ($YMs as $YM)
                        <option value="{{ $YM->YM }}" @if(\Request::get('YM2') == $YM->YM) selected @endif >{{ floor(($YM->YM)/100)%100 }}年{{ ($YM->YM)%100 }}月</option>
                    @endforeach
                </select>
                <span class="items-center text-sm mt-2" >　</span>
            </div> --}}
            <div class="flex mb-2">
                <div>
                <label for="YW1" class="items-center text-sm mt-1 mr-6" >期間  (週) </label>
                <select class="w-36 h-8 ml-0 md:ml-6 rounded text-sm items-center pt-1" id="YW1" name="YW1" type="number" >
                    <option value="{{ $max_YW }}" @if(\Request::get('YW1') == '0') selected @endif >週選択(from)</option>
                    @foreach ($YWs as $YW)
                        <option value="{{ $YW->YW }}" @if(\Request::get('YW1') == $YW->YW) selected @endif >{{ floor(($YW->YM)/100)%100 }}年{{ ($YW->YM)%100 }}月{{ ($YW->YW)%100 }}週</option>
                    @endforeach
                </select>
                </div>
                <span class="items-center text-sm mt-4 md:mt-2 text-gray-800 leading-tight" >　　～</span>
                <div>
                <label for="YW2" class="items-center text-sm mt-2 ml-2 text-gray-800 leading-tight" >　</label>
                <select class="w-36 h-8 ml-1 rounded text-sm items-center pt-1" id="YW2" name="YW2" type="number" class="border">
                    <option value="{{ $max_YW }}" @if(\Request::get('YW2') == '0') selected @endif >週選択(to)</option>
                    @foreach ($YWs as $YW)
                        <option value="{{ $YW->YW }}" @if(\Request::get('YW2') == $YW->YW) selected @endif >{{ floor(($YW->YM)/100)%100 }}年{{ ($YW->YM)%100 }}月{{ ($YW->YW)%100 }}週</option>
                    @endforeach
                </select>
                </div>
            </div>
            <div class="flex">
                <div class="mr-4">
                <label for="type1" class="mr-6 leading-7 text-sm  text-gray-800 ">社店種別</label>
                <select id="type1" name="type1" class="w-28 h-8 rounded text-sm pt-1 border mr-2 mb-2" type="text">
                    <option value="sh" @if(\Request::get('type1') == '0' || \Request::get('type1') == "sh") selected @endif >店別 </option>
                    {{-- <option value="dry">dry</option> --}}
                    <option value="co" @if(\Request::get('type1') == "co") selected @endif>社別</option>
                    {{-- <option value="sh" @if(\Request::get('type1') == "sh") selected @endif>店別</option> --}}
                    {{-- <option value="wet">wet</option> --}}
                </select>
                </div>
                <div>
                    <label for="area_id" class="mr-4 leading-7 text-sm  text-gray-800 ">エリア指定</label>
                    <select class="w-28 h-8 rounded text-sm pt-1 border mb-2 mr-1 " id="area_id" name="area_id" >
                    <option value="" @if(\Request::get('area_id') == '0') selected @endif >選択なし</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}" @if(\Request::get('area_id') == $area->id ) selected @endif >{{ $area->area_name  }}</option>
                    @endforeach
                    </select>
                </div>
            </div>

        <div class="md:flex">
            <div class="flex">
                <div >
                    <label for="brand_code" class="mr-4 leading-7 text-sm  text-gray-800 ">Brand指定</label>
                    <select class="w-28 h-8 rounded text-sm pt-1 border mb-2 mr-5 " id="brand_code" name="brand_code" type="number" >
                        <option value="" @if(\Request::get('brand_code') == '0') selected @endif >選択なし</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" @if(\Request::get('brand_code') == $brand->id ) selected @endif >{{ $brand->brand_name  }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="season_code" class="mr-5 leading-7 text-sm  text-gray-800 ">季節指定</label>
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
                    <label for="unit_id" class="mr-3 leading-7 text-sm  text-gray-800 ">Unit指定　</label>
                    <select class="w-28 h-8 rounded text-sm pt-1 border mb-2 mr-5 " id="unit_id" name="unit_id" >
                    <option value="" @if(\Request::get('unit_id') == '0') selected @endif >選択なし</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}" @if(\Request::get('unit_id') == $unit->id ) selected @endif >{{ $unit->id  }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="ml-1">
                    <label for="face" class="mr-1 leading-7 text-sm  text-gray-800 ">Face指定　</label>
                    <select class="w-28 h-8 rounded border text-sm items-center pt-1" id="face" name="face" >
                        <option value="" @if(\Request::get('face') == '0') selected @endif >選択なし</option>
                        @foreach ($faces as $face)
                        <option value="{{ $face->face }}" @if(\Request::get('face') == $face->face ) selected @endif >{{ $face->face  }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>



        {{-- <div class="ml-2 md:ml-4">
            <button type="button" class="w-20 h-8 bg-indigo-500 text-white ml-2 hover:bg-indigo-600 rounded" onclick="location.href='{{ route('sales_transition') }}'" class="mb-2 ml-2 text-right text-black bg-indigo-300 border-0 py-0 px-2 focus:outline-none hover:bg-indigo-300 rounded ">全表示</button>
        </div> --}}
        </form>

        <div class="ml-0 mt-3 py-0 md:w-2/3 border">
            <div class="md:flex w-full  sm:px-0 lg:px-0 border mt-0 ml-0 items-center">
                <div class='md:w-2/3 pl-0 border bg-gray-100 h-6 text-sm items-center'>
                    　当期累計：{{ number_format(round($total->total)/1000) }}千　
                    前期累計：{{ number_format(round($pv_total->total)/1000)}}千　
                </div>
                <div class='md:w-2/3 pl-0 border bg-gray-100 h-6 text-sm items-center'>
                    @if($pv_total->total>0)
                    　前期比　：{{ number_format(($total->total/$pv_total->total)*100)}}％
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
                    <th class="w-4/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Name</th>
                    <th class="w-3/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">当期売上(千)</th>
                    <th class="w-3/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">前期売上(千)</th>
                    <th class="w-2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">前期比</th>
                </tr>
                </thead>

                <tbody>
                    @foreach ($merged_data as $data)
                <tr>
                    <td class="w-4/12 md:px-4 py-1 text-sm text-left">{{ $data->name }}</td>

                    <td class="w-3/12 pr-4 md:px-4 py-1 text-sm text-right"><span style="font-variant-numeric:tabular-nums"> {{ number_format(round($data->total)/1000)}}</span></td>
                    <td class="w-3/12 pr-4 md:px-4 py-1 text-sm text-right"><span style="font-variant-numeric:tabular-nums"> {{ number_format(round($data->pv_total)/1000)}}</span></td>
                    @if($data->pv_total>0)
                    <td class="w-2/12 pr-2 md:px-4 py-1 text-sm text-right"><span style="font-variant-numeric:tabular-nums"> {{ number_format(($data->total/$data->pv_total)*100)}} %</span></td>
                    @else
                    <td class="w-2/12 pr-2 md:px-4 py-1 text-right"><span style="font-variant-numeric:tabular-nums"> --</span></td>
                    @endif
                </tr>
                @endforeach
                </tbody>
            </table>
            {{  $merged_data->appends([
                'YW1'=>\Request::get('YW1'),
                'YW2'=>\Request::get('YW2'),
                'type1'=>\Request::get('type1'),
                'brand_code'=>\Request::get('brand_code'),
                'season_code_id'=>\Request::get('season_code_id'),
                'unit_id'=>\Request::get('unit_id'),
                'face'=>\Request::get('face'),
            ])->links()}}
        </div>
        </div>


<script>


const type1 = document.getElementById('type1')
type1.addEventListener('change', function(){
this.form.submit()
    })

const YW1 = document.getElementById('YW1')
YW1.addEventListener('change', function(){
this.form.submit()
    })

const YW2 = document.getElementById('YW2')
YW2.addEventListener('change', function(){
this.form.submit()
})

const area = document.getElementById('area_id')
area.addEventListener('change', function(){
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




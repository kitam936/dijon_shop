
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            累計売上順位<br>
        </h2>
        <div class="pl-2 mt-0 md:mt-0 md:ml-60 ml-40 ">
            <button type="button" class="w-32 h-8 bg-indigo-500 text-white hover:bg-indigo-600 rounded lg:ml-2 " onclick="location.href='{{ route('analysis_index') }}'" >Menu</button>
        </div>

        {{-- <span class="items-center text-sm mt-2 text-gray-800 dark:text-gray-200 leading-tight" >　※Brand・店舗を選択してください　　　</span> --}}

        <form method="get" action="{{ route('sales_total')}}" class="mt-4">

            {{-- <div class="flex mb-2">
                <label for="YM1" class="mr-12 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">期間</label>
                <select class="w-32 h-8 rounded text-sm pt-1" id="YM1" name="YM1" type="number" class="border">
                    <option value="" @if(\Request::get('YM1') == '0') selected @endif >{{ $max_YM }}直近月</option>
                    @foreach ($YMs as $YM)
                        <option value="{{ $YM->YM }}" @if(\Request::get('YM1') == $YM->YM) selected @endif >{{ floor(($YM->YM)/100)%100 }}年{{ ($YM->YM)%100 }}月</option>
                    @endforeach
                </select>
                <span class="items-center text-sm mt-2 text-gray-800 dark:text-gray-200 leading-tight" >　～　</span>
                <select class="w-32 h-8 rounded text-sm pt-1" id="YM2" name="YM2" type="number" class="border">
                    <option value="" @if(\Request::get('YM2') == '0') selected @endif >{{ $max_YM }}直近月</option>
                    @foreach ($YMs as $YM)
                        <option value="{{ $YM->YM }}" @if(\Request::get('YM2') == $YM->YM) selected @endif >{{ floor(($YM->YM)/100)%100 }}年{{ ($YM->YM)%100 }}月</option>
                    @endforeach
                </select>
                <span class="items-center text-sm mt-2" >　</span>
            </div> --}}
            <div class="flex mb-2">
                <label for="YW1" class="items-center text-sm mt-1 " >期間  (週) </label>
                <select class="w-32 h-8 md:ml-8 rounded text-sm items-center pt-1" id="YW1" name="YW1" type="number" >
                    <option value="{{ $max_YW }}" @if(\Request::get('YW1') == '0') selected @endif >週選択(from)</option>
                    @foreach ($YWs as $YW)
                        <option value="{{ $YW->YW }}" @if(\Request::get('YW1') == $YW->YW) selected @endif >{{ floor(($YW->YM)/100)%100 }}年{{ ($YW->YM)%100 }}月{{ ($YW->YW)%100 }}週</option>
                    @endforeach
                </select>
                <label for="YW2" class="items-center text-sm mt-2 ml-2 text-gray-800 dark:text-gray-200 leading-tight" >～</label>
                <select class="w-32 h-8 ml-2 rounded text-sm items-center pt-1" id="YW2" name="YW2" type="number" class="border">
                    <option value="{{ $max_YW }}" @if(\Request::get('YW2') == '0') selected @endif >週選択(to)</option>
                    @foreach ($YWs as $YW)
                        <option value="{{ $YW->YW }}" @if(\Request::get('YW2') == $YW->YW) selected @endif >{{ floor(($YW->YM)/100)%100 }}年{{ ($YW->YM)%100 }}月{{ ($YW->YW)%100 }}週</option>
                    @endforeach
                </select>
            </div>
            <div class="flex">
                <label for="type1" class="mr-8 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">社店種別</label>
                <select id="type1" name="type1" class="w-28 h-8 rounded text-sm pt-1 border mr-2 mb-2" type="text">
                    <option value="sh" @if(\Request::get('type1') == '0') selected @endif >選択</option>
                    {{-- <option value="dry">dry</option> --}}
                    <option value="co" @if(\Request::get('type1') == "co") selected @endif>社別</option>
                    <option value="sh" @if(\Request::get('type1') == "sh") selected @endif>店別</option>
                    {{-- <option value="wet">wet</option> --}}
                </select>
            </div>

            <div class="flex">
                <label for="brand_code" class="mr-6 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">Brand指定</label>
                <select class="w-28 h-8 rounded text-sm pt-1 border mb-2 mr-5 " id="brand_code" name="brand_code" type="number" >
                    <option value="" @if(\Request::get('brand_code') == '0') selected @endif >選択なし</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" @if(\Request::get('brand_code') == $brand->id ) selected @endif >{{ $brand->brand_name  }}</option>
                    @endforeach
                </select>
                <label for="season_code" class="mr-5 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">季節指定</label>
                <select class="w-28 h-8 rounded text-sm pt-1 border mb-2 mr-2 " id="season_code" name="season_code" type="number" >
                    <option value="" @if(\Request::get('season_code') == '0') selected @endif >選択なし</option>
                    @foreach ($seasons as $season)
                        <option value="{{ $season->season_id }}" @if(\Request::get('season_code') == $season->season_id ) selected @endif >{{ $season->season_name  }}</option>
                    @endforeach
                </select>
            </div>


            <div class="flex">
                <label for="unit_id" class="mr-5 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">Unit指定　</label>
                <select class="w-28 h-8 rounded text-sm pt-1 border mb-2 mr-5 " id="unit_id" name="unit_id" >
                <option value="" @if(\Request::get('unit_id') == '0') selected @endif >選択なし</option>
                @foreach ($units as $unit)
                    <option value="{{ $unit->id }}" @if(\Request::get('unit_id') == $unit->id ) selected @endif >{{ $unit->id  }}</option>
                @endforeach
                </select>


                <label for="face" class="mr-1 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">Face指定　</label>
                <select class="w-28 h-8 rounded border text-sm items-center pt-1" id="face" name="face" >
                    <option value="" @if(\Request::get('face') == '0') selected @endif >選択なし</option>
                    @foreach ($faces as $face)
                    <option value="{{ $face->face }}" @if(\Request::get('face') == $face->face ) selected @endif >{{ $face->face  }}</option>
                    @endforeach
                </select>

            </div>



        {{-- <div class="ml-2 md:ml-4">
            <button type="button" class="w-20 h-8 bg-indigo-500 text-white ml-2 hover:bg-indigo-600 rounded" onclick="location.href='{{ route('sales_transition') }}'" class="mb-2 ml-2 text-right text-black bg-indigo-300 border-0 py-0 px-2 focus:outline-none hover:bg-indigo-300 rounded ">全表示</button>
        </div> --}}
        </form>

        <div class="ml-0 py-0 md:w-1/2 border">
            <div class=" w-full  sm:px-0 lg:px-0 border mt-0 ml-0">
                <div class='border bg-gray-100 h-6'>

                    当期累計：　{{ number_format(round($total->total)/1000) }}千円　　　
                    前期累計：　{{ number_format(round($pv_total->total)/1000)}}千円　

                </div>
            </div>
        </div>


    </x-slot>

        <div class="py-6 border">
        <div class="md:w-1/2 sm:px-4 lg:px-4 border">
            <table class="mx-auto table-auto bg-white w-full text-center whitespace-no-wrap">
                <thead >
                <tr>
                    <th class="w-1/4 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Name</th>
                    <th class="w-1/4 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">当期売上(千円)</th>
                    <th class="w-1/4 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">前期売上(千円)</th>
                    <th class="w-1/4 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">前期比(%)</th>
                </tr>
                </thead>

                <tbody>
                    @foreach ($merged_data as $data)
                <tr>
                    <td class="w-1/4 md:px-4 py-1">{{ $data->name }}</td>

                    <td class="w-1/4 pr-24 md:px-4 py-1 text-right"><span style="font-variant-numeric:tabular-nums"> {{ number_format(round($data->total)/1000)}}</span></td>
                    <td class="w-1/4 pr-24 md:px-4 py-1 text-right"><span style="font-variant-numeric:tabular-nums"> {{ number_format(round($data->pv_total)/1000)}}</span></td>
                    @if($data->pv_total>0)
                    <td class="w-1/4 pr-24 md:px-4 py-1 text-right"><span style="font-variant-numeric:tabular-nums"> {{ number_format(($data->total/$data->pv_total)*100)}}</span></td>
                    @else
                    <td class="w-1/4 pr-24 md:px-4 py-1 text-right"><span style="font-variant-numeric:tabular-nums"> --</span></td>
                    @endif
                </tr>
                @endforeach
                </tbody>
            </table>
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




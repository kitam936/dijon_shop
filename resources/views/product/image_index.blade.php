<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-2">
            画像リスト 　<br>
        </h2>
        <div class="md:flex  md:ml-24 mb-2">

            <div class="ml-4 flex mt-2 md:mt-0">
                <div class="ml-0 md:ml-4">
                    <button type="button" class="w-32 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('product_index') }}'" >商品リスト</button>
                </div>

            </div>
        </div>

        <form method="get" action="{{ route('image_index')}}" class="mt-4">

            <div class="lg:flex">
                <div class="md:flex">
                    <label for="year_code" class="items-center text-sm mt-2 text-gray-800 leading-tight" >年度CD：</label>
                    <select class="w-24 h-8 rounded text-sm pt-1 mr-2 mb-2" id="year_code" name="year_code" type="number" class="border">
                    <option value="" @if(\Request::get('year_code') == '0') selected @endif >指定なし</option>
                    @foreach ($years as $year)
                        <option value="{{ $year->year_code }}" @if(\Request::get('year_code') == $year->year_code ) selected @endif >{{ $year->year_code  }}</option>
                    @endforeach
                    </select>
                    <label for="brand_code" class="items-center text-sm mt-2  text-gray-800 leading-tight" >Brand：</label>
                    <select class="w-24 h-8 rounded text-sm pt-1 border mb-2 mr-4 " id="brand_code" name="brand_code" type="number" >
                    <option value="" @if(\Request::get('brand_code') == '0') selected @endif >指定なし</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" @if(\Request::get('brand_code') == $brand->id ) selected @endif >{{ $brand->id  }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="md:flex">
                    <label for="season_code" class="items-center text-sm mt-2 text-gray-800 leading-tight" >季節CD：</label>
                    <select class="w-24 h-8 rounded text-sm pt-1 mr-4 mb-2 border " id="season_code" name="season_code" type="number" >
                    <option value="" @if(\Request::get('season_code') == '0') selected @endif >指定なし</option>
                    @foreach ($seasons as $season)
                        <option value="{{ $season->season_id }}" @if(\Request::get('season_code') == $season->season_id ) selected @endif >{{ $season->season_name  }}</option>
                    @endforeach
                    </select>
                    <label for="unit_code" class="items-center text-sm mt-2  text-gray-800 leading-tight" >Unit：</label>
                    <select class="w-24 h-8 rounded text-sm pt-1 mr-4 mb-2 border " id="unit_code" name="unit_code" type="number" >
                    <option value="" @if(\Request::get('unit_code') == '0') selected @endif >指定なし</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}" @if(\Request::get('unit_code') == $unit->id ) selected @endif >{{ $unit->id  }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="flex">
                <div>
                    <label for="face" class="mr-4 md:mr-0 items-center text-sm mt-2  text-gray-800 leading-tight" >Face：</label>
                    <select class="w-24 h-8 rounded text-sm pt-1 mr-4 mb-2 border " id="face" name="face" type="text" >
                    <option value="" @if(\Request::get('face') == '0') selected @endif >指定なし</option>
                    @foreach ($faces as $face)
                        <option value="{{ $face->face }}" @if(\Request::get('face') == $face->face ) selected @endif >{{ $face->face  }}</option>
                    @endforeach
                    </select>
                </div>
                {{-- <div>
                <label for="type" class="mr-1 leading-7 text-sm  text-gray-800 ">表示：</label>
                <select id="type" name="type" class="w-24 h-8 rounded text-sm pt-1 border mr-6 mb-2" type="text">
                    <option value="" @if(\Request::get('type') == '0' ) selected @endif >選択</option>
                    <option value="a" @if(\Request::get('type') == "a") selected @endif >統合込</option>
                    <option value="h" @if(\Request::get('type') == "h") selected @endif>統合抜</option>

                </select>
                </div> --}}
                </div>
                </div>
                <div class="flex">
                    <label for="hinban_code" class="items-center text-sm mt-2 mr-6 text-gray-800 leading-tight" >品番：</label>
                    <input class="w-36 h-8 rounded text-sm pt-1" id="hinban_code" placeholder="品番入力（一部でも可）" name="hinban_code" type="number" class="border">
                    <div>
                    <button  type="button" class="w-12 h-8 ml-2 text-sm text-center text-gray-900 bg-gray-200 border-0 py-0 px-2 focus:outline-none hover:bg-gray-300 rounded">検索</button>
                    </div>
                    <div class="pl-2 mt-0 md:mt-0 md:ml-0 ml-0 ">
                        <button type="button" class="w-16 h-8 bg-blue-500 text-sm text-white ml-0 hover:bg-blue-600 rounded lg:ml-2 " onclick="location.href='{{ route('image_index') }}'" >全表示</button>
                    </div>
                </div>
        </form>

    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-flash-message status="session('status')"/>

                    <div class="flex flex-wrap">
                    @foreach ($images as $image )
                    <div class="w-1/2 md:w-1/4 p-2 md:p-4">
                    {{-- <a href="{{ route('image.edit',['image'=>$image->id]) }}"> --}}
                    <div class="border rounded-md p-0 md:p-0">
                        <div class="text-gray-700"> {{ $image->hinban_id  }}</div>
                        <div class="text-gray-700"> {{ Str::limit($image->hinban_name,16)  }}</div>
                        @if(($image->filename))
                        <a href="{{ route('image_show',['hinban'=>$image->hinban_id]) }}">
                        <x-image-thumbnail :filename="$image->filename"  />
                        </a>
                        @endif
                        @if(!($image->filename))
                        <x-image-thumbnail :filename="$image->filename"  />
                        @endif
                        <div class="flex">
                        {{-- <div class="text-gray-700"> {{ Str::limit($image->hinban_name,12)  }}</div> --}}
                        <div class="text-gray-700 ml-4 mr-4"> 売価　{{ $image->m_price  }}円</div>
                        @if ($login_user->role_id <= 2)
                        <a href="{{ route('admin.image_edit',['hinban'=>$image->hinban_id]) }}" ><span class="rounded text-red-500 ml-4 bg-gray-300 border-gray-800">削除</span></a>
                        @endif
                        </div>
                    </div>
                    {{-- </a> --}}
                    </div>
                    @endforeach
                    </div>
                    {{  $images->appends([
                        'year_code'=>\Request::get('year_code'),
                        'brand_code'=>\Request::get('brand_code'),
                        'season_code'=>\Request::get('season_code'),
                        'unit_code'=>\Request::get('unit_code'),
                        'face'=>\Request::get('face'),
                        'hinban_code'=>\Request::get('hinban_code'),
                        // 'type'=>\Request::get('type'),
                    ])->links()}}
                </div>
            </div>
        </div>
    </div>

    <script>
        const year = document.getElementById('year_code')
        year.addEventListener('change', function(){
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

        const unit = document.getElementById('unit_code')
        unit.addEventListener('change', function(){
        this.form.submit()
        })

        const face = document.getElementById('face')
        face.addEventListener('change', function(){
        this.form.submit()
        })

        const hinban = document.getElementById('hinban_code')
        hinban.addEventListener('change', function(){
        this.form.submit()
        })

        // const type = document.getElementById('type')
        // type.addEventListener('change', function(){
        // this.form.submit()
        // })

    </script>

</x-app-layout>

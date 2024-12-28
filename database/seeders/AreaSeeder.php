<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('areas')->insert([[
            'id' => 1,
            'area_name' => '北海道エリア',
            'area_info' => '北海道',
        ],
        [
            'id' => 2,
            'area_name' => '東北エリア',
            'area_info' => '青森・岩手・秋田・宮城・山形・福島',
        ],
        [
            'id' => 3,
            'area_name' => '北陸エリア',
            'area_info' => '新潟・富山・石川・福井',
        ],
        [
            'id' => 4,
            'area_name' => '関東エリア',
            'area_info' => '茨城・栃木・群馬・埼玉・東京・千葉・神奈川',
        ],
        [
            'id' => 5,
            'area_name' => '中部エリア',
            'area_info' => '長野・山梨・静岡・愛知・岐阜・三重',
        ],
        [
            'id' => 6,
            'area_name' => '近畿エリア',
            'area_info' => '滋賀・和歌山・京都・大阪・兵庫',
        ],
        [
            'id' => 7,
            'area_name' => '中国・四国エリア',
            'area_info' => '岡山・広島・鳥取・島根・山口・香川・徳島・愛媛・高知',
        ],
        [
            'id' => 8,
            'area_name' => '九州エリア',
            'area_info' => '福岡・大分・佐賀・熊本・長崎・宮崎・鹿児島・沖縄',
        ],
        [
            'id' => 9,
            'area_name' => '未回答',
            'area_info' => '選択されていません',
        ],
    ]);
    }
}

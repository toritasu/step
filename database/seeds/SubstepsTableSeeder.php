<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubstepsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [ 'order' => 1, 'title' => 'ProgateのPHPコースをやろう！'],
            [ 'order' => 2, 'title' => 'ドットインストールをやろう！'],
            [ 'order' => 3, 'title' => 'ウェブカツに仮⼊部してみよう！'],
            [ 'order' => 4, 'title' => '簡単なHTMLのサイトを作ってみよう！'],
            [ 'order' => 5, 'title' => 'ウェブカツに本⼊部しよう！']
        ];

        foreach (range(1,5) as $num) {
            foreach ($items as $item) {
                DB::table('substeps')->insert([
                    'step_id' => $num,
                    'order' => $item['order'],
                    'title' => $item['title'],
                    'description' => 'コツコツ頑張ろう！',
                    'link' => '模写ポータル「SHAKYO」',
                    'url' => 'https://shakyo.t-w-d.com/',
                    'delete_flg' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}

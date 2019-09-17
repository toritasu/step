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
            [ 'order' => 5, 'title' => 'ウェブカツに本⼊部しよう！'],
            [ 'order' => 6, 'title' => 'ウェブカツの卒業試験に合格しよう！'],
            [ 'order' => 7, 'title' => '１年程度の実務経験を積もう！'],
        ];

        foreach (range(1,5) as $num) {
            foreach ($items as $item) {
                DB::table('substeps')->insert([
                    'step_id' => $num,
                    'order' => $item['order'],
                    'title' => $item['title'],
                    'description' => 'コツコツ頑張ろう！',
                    'link' => '初心者向けプログラミング学習スクール「ウェブカツ!!」',
                    'url' => 'https://webukatu.com/',
                    'delete_flg' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}

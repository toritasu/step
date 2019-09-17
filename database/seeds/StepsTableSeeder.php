<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StepsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => 1,
            'title' => 'IT業界未経験から年収1000万のフリーランスエンジニアになる方法',
            'categories' => json_encode(array(1, 6)),
            'estimate' => 7,
            'image' => 'images/programming.jpeg',
            'description' => '年収1000万を、その先の世界を目にしたいか。なら、黙って俺についてこい。',
            'delete_flg' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        DB::table('steps')->insert($param);

        $param = [
            'user_id' => 2,
            'title' => 'ロードバイク初心者が１日200km走れるようになるまで',
            'categories' => json_encode(array(3, 4)),
            'estimate' => 6,
            'image' => 'images/sport.jpeg',
            'description' => '自己流で一番速かったら、それが一番カッコいいッショ!!',
            'delete_flg' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        DB::table('steps')->insert($param);

        $param = [
            'user_id' => 3,
            'title' => 'モテないブラザースの片割れが超絶美女と付き合うまで',
            'categories' => json_encode(array(8)),
            'estimate' => 3,
            'image' => 'images/love.jpeg',
            'description' => '美人は目の前にいるだけで相手にgiveしている。そして残酷なようだが、ブサイクはその逆なのだ。',
            'delete_flg' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        DB::table('steps')->insert($param);

        $param = [
            'user_id' => 4,
            'title' => '趣味レベルの同人作家が漫画で半年食う契約をゲットするまで',
            'categories' => json_encode(array(5)),
            'estimate' => 3,
            'image' => 'images/drawing.jpg',
            'description' => 'ご縁は大切にしましょう。',
            'delete_flg' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        DB::table('steps')->insert($param);

        $param = [
            'user_id' => 5,
            'title' => '七色の声を操るスター声優になる！',
            'categories' => json_encode(array(9)),
            'estimate' => 9,
            'image' => 'images/cooking.jpeg',
            'description' => '声優の山寺です。',
            'delete_flg' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        DB::table('steps')->insert($param);

    }
}

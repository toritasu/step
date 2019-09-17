<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $users = [
          [ 'name' => 'かずきち', 'email' => 'kazukichi@webukatu', 'intro' => 'オラオラオラ！俺様のお通りだ！', 'avater' => 'storage/images/avaters/user_1.jpg'],
          [ 'name' => 'くろまめ', 'email' => 'kuromame@webukatu', 'intro' => '自転車大好き！', 'avater' => 'storage/images/avaters/user_2.jpg'],
          [ 'name' => 'くりまる', 'email' => 'kurimaru@webukatu', 'intro' => '女の子大好き！', 'avater' => 'storage/images/avaters/user_3.jpg'],
          [ 'name' => 'とりたす', 'email' => 'toritasu@webukatu', 'intro' => 'お絵かきたのしす！', 'avater' => 'storage/images/avaters/user_4.jpg'],
          [ 'name' => '山寺宏一', 'email' => 'yamachan@webukatu', 'intro' => '声優の山寺です。こんにちは。', 'avater' => 'storage/images/avaters/user_5.jpg'],
      ];

      foreach ($users as $user) {
        DB::table('users')->insert([
          'name' => $user['name'],
          'email' => $user['email'],
          'password' => bcrypt('aaaaaa'),
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
          'introduction' => $user['intro'],
          'avater' => $user['avater'],
        ]);
      }
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    // Substepとの関係：一対多
    // 小STEPのインスタンスから紐づく小STEPクラスのリストを取得する
    public function substeps()
    {
        return $this->hasMany('App\Substep');
    }

    // カテゴリーの名前とデフォルト画像
    const CATEGORIES = [
        1 => [ 'label' => 'プログラミング', 'image' => 'images/programming.jpeg' ],
        2 => [ 'label' => '語学', 'image' => 'images/conversation.jpeg' ],
        3 => [ 'label' => 'トレーニング', 'image' => 'images/training.jpg' ],
        4 => [ 'label' => 'スポーツ', 'image' => 'images/sport.jpeg' ],
        5 => [ 'label' => 'イラスト・漫画', 'image' => 'images/drawing.jpg' ],
        6 => [ 'label' => '就職・転職', 'image' => 'images/recruit.jpg' ],
        7 => [ 'label' => '資格・試験', 'image' => 'images/exam.jpeg' ],
        8 => [ 'label' => '恋愛・婚活', 'image' => 'images/love.jpeg' ],
        9 => [ 'label' => 'その他', 'image' => 'images/stepdefault.jpg' ],
    ];

    // 目安達成時間のラベル
    const ESTIMATE = [
        1 => [ 'label' => '～数時間'],
        2 => [ 'label' => '～１日'],
        3 => [ 'label' => '～１週間'],
        4 => [ 'label' => '～１ヵ月'],
        5 => [ 'label' => '～３ヵ月'],
        6 => [ 'label' => '～６ヵ月'],
        7 => [ 'label' => '～１年'],
        8 => [ 'label' => '～２年'],
        9 => [ 'label' => '２年以上'],
    ];

    public function getCategoryLabelAttribute()
    {
        // カテゴリーのキーを取得
        $category = $this->attributes['category'];

        // 定義されていなければから文字を返す
        if (!isset(self::CATEGORIES[$category])){
            return '';
        }

        return self::CATEGORIES[$category]['label'];
    }

    public function getEstimateLabelAttribute()
    {
        // 目安達成時間のキーを取得
        $estimate = $this->attributes['estimate'];

        // 定義されていなければから文字を返す
        if (!isset(self::ESTIMATE[$estimate])){
            return '';
        }

        return self::ESTIMATE[$estimate]['label'];
    }
}

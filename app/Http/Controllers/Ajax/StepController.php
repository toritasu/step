<?php

namespace App\Http\Controllers\Ajax;

use App\Step;
use App\Substep;
use App\User;
use App\Challenge;
use App\ClearRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StepController extends Controller
{
    // STEPリスト読み込み時
    // Ajax通信でSTEPサムネイル用の情報を取得する
    public function index() {
        // Userテーブルと結合して抽出
        $steps = User::join('steps', 'users.id', '=', 'steps.user_id')
                    ->select('steps.id as id', 'categories', 'image', 'title', 'estimate', 'user_id', 'name', 'avater')
                    ->get();
        // カテゴリーリストをJSONから配列に変換
        foreach ($steps as $step) {
            $array = $step->categories;
            $step->categories = json_decode($array);
        }
        return $steps;
    }

    // Ajax通信でログイン中のユーザー情報を取得する
    public function user()
    {
        $user = Auth::user();
        return $user->id;
    }

    // 編集ページ用：STEP情報の取得
    public function detail(int $step_id)
    {
        $step = Step::find($step_id);
        $substeps = $step->substeps()->get();
        // カテゴリーリストをJSONから配列に変換
        $array = $step->categories;
        $step->categories = json_decode($array);
        return [ 'step' => $step, 'substeps' => $substeps ];
    }

    // 「挑戦する」ボタン押下時
    // Ajax通信で挑戦テーブルの紐付けを行う
    public function GoChallenge(int $step_id)
    {
        // Challengeモデルのインスタンスを作成する
        $challenge = new Challenge();
        // ログイン中のユーザーIDを取得
        $user_id = Auth::user()->id;
        // STEPIDはGETで取得（引数）

        // 挑戦情報をデータベースに書き込む
        $challenge->challenger_id = $user_id;
        $challenge->step_id = $step_id;
        $challenge->save();

        return 'You started challenge!!';
    }

    // 「挑戦をやめる」ボタン押下時
    // Ajax通信で挑戦テーブルの紐付けを解く
    public function AbortChallenge(int $step_id)
    {
        // ログイン中のユーザーIDを取得
        $user_id = Auth::user()->id;
        // STEPIDはGETで取得（引数）

        // ユーザーIDとSTEPIDが一致するレコードを削除
        $challenge = Challenge::where('step_id', $step_id)
                    ->where('challenger_id', $user_id)
                    ->delete();
                    
        return 'You aborted challenge!!';
    }

    // 小STEPを１つクリア
    public function clearSubstep(int $step_id, int $substep_id)
    {
        // ログイン中のユーザーIDを取得
        $user_id = Auth::user()->id;

        // ユーザーとSTEPを紐付けるchallengeテーブルを取得
        $challenge = Challenge::where('challenger_id', $user_id)
                        ->where('step_id', $step_id)
                        ->first();

        // challengeテーブルの進捗（progressカラム）を１増やす
        $challenge->increment('progress');

        // 全てのSTEPをクリアしたら挑戦ステータスを「達成」にする
        // goal_flgはフロント用
        $goal_flg = false;
        $substepCount = Substep::where('step_id', $step_id)->count();
        if ($challenge->progress > $substepCount) {
            $goal_flg = true;
            $challenge->status = 2;
            $challenge->save();
        }

        return [
            'url' => url()->previous(),
            'goal_flg' => $goal_flg,
        ];
    }
}
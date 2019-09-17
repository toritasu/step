<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Step;
use App\Substep;
use App\User;
use App\Challenge;
use App\Http\Requests\CreateStep;
use App\Http\Requests\EditStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StepController extends Controller
{
    // TOPページの表示
    public function index()
    {
        // TODO:ここで「挑戦者数TOP8のSTEP」に絞り込む？
        $steps = Step::all();

        return view('index', [
            'steps' => $steps,
        ]);
    }
    // STEP一覧ページの表示
    public function list()
    {
        $steps = Step::all();

        return view('steps/list', [
            'steps' => $steps,
        ]);
    }

    // STEP詳細ページの表示
    public function detail(int $id)
    { 
        // 選ばれたSTEPを取得する
        $target_step = Step::find($id);

        // カテゴリーリストをJSONから配列に変換
        $array = json_decode($target_step->categories);
        // target_stepはString形式のため上書きできないので別の変数を用意
        $categories = $array;

        // 選ばれたSTEPに紐づく小STEPを取得する
        $substeps = $target_step->substeps()->get();

        // 選ばれたSTEPを投稿したユーザーを取得する
        $poster = User::find($target_step->user_id);

        // 小STEPの件数を取得する（最後の「次へ進む」ボタンの名前を変えるのに使う）
        $substepCount = Substep::where('step_id', $id)->count();

        // ユーザー情報と挑戦データ（未ログインの場合はnull）
        $user = null;
        $challenge = null;
        //　ログイン中なら、ユーザーの挑戦データを取得する
        if( Auth::check() ){
          $user = Auth::user();
          $challenge = Challenge::where('step_id', $id)
                              ->where('challenger_id', $user->id)
                              ->first();
        }

        // 各変数をviewに渡す
        return view('steps/detail', [
            'user' => $user,
            'step' => $target_step,
            'substeps' => $substeps,
            'categories' => $categories,
            'poster' => $poster,
            'challenge' => $challenge,
            'substepCount' => $substepCount,
        ]);
    }

    // STEP投稿ページの表示
    public function showCreateForm()
    {
        return view('steps/create');
    }

    // STEPデータの投稿
    public function create(CreateStep $request)
    {
        // STEPモデルのインスタンスを作成する
        $step = new Step();
        // フォームの入力値を代入する TODO:他の入力値も入れる
        $step->title = $request->title;
        $step->categories = json_encode($request->categories);
        $step->description = $request->description;
        $step->estimate = $request->estimate;

        // 画像の保存
        if($request->has('image')) {
          // IDは未発行なので、日時とランダム数値でファイル名をつける
          $date = Carbon::now()->format('YmdHis');
          $rand = mt_rand(1,100);
          // storeAsメソッドでファイルを保存
          $filename = 'images/steps/'. $date . $rand . '.jpg';
          $request->image->storeAs('public', $filename);
          // URLをユーザーテーブルに保存
          $step->image = 'storage/'. $filename;
        // ファイルが入っていない場合は、カテゴリーに応じたデフォルト画像のURLを返す
        // （VueのpostedData.imageをhiddenで取得している）
        } else {
          $step->image = $request->imageDefault;
        }

        // TODO:ログイン中のユーザーのIDを代入する
        Auth::user()->steps()->save($step);

        // インスタンスの状態をデータベースに書き込む
        $step->save();

        // たった今作成した親STEPのIDを取得する（最新の１件）
        $created_step_id = \App\Step::latest()->first()->id;
        
        // substepデータを保存する
        for ($i = 0; $i < count($request->substep_titles); $i ++) {
          // タイトル欄が入力されている小STEPのみを保存
          if (isset($request->substep_titles[$i])) {
            $substep = new Substep();
            $substep->step_id = $created_step_id;
            $substep->order = $request->substep_orders[$i];
            $substep->title = $request->substep_titles[$i];
            $substep->description = $request->substep_descriptions[$i];
            $substep->link = $request->substep_links[$i];
            $substep->url = $request->substep_urls[$i];
            $substep->save();
          }
        }

        // STEP一覧ページへリダイレクト
        return redirect()->route('steps.list')
                ->with('message', 'STEPを投稿しました！');
    }
    
    // STEP編集ページの表示
    public function showEditForm(int $step_id)
    {
      $step = Step::find($step_id);
      $substeps = $step->substeps()->get();
      $categories = json_decode($step->categories);

      return view('steps/edit', [
        'step' => $step,
        'substeps' => $substeps,
        'categories' => $categories,
      ]);
    }
    // STEP編集処理の実行
    public function edit(int $id, CreateStep $request)
    {
      // リクエストされたIDで編集対象のSTEPデータを取得
      $step = Step::find($id);

      // フォームの入力値を代入する TODO:他の入力値も入れる
      $step->title = $request->title;
      $step->categories = json_encode($request->categories);
      $step->description = $request->description;
      $step->estimate = $request->estimate;

      // 画像の保存（新規作成と同じ処理。ただしリクエストがない場合は何もしない）
      if($request->has('image')) {
        $date = Carbon::now()->format('YmdHis');
        $rand = mt_rand(1,100);
        // storeAsメソッドでファイルを保存
        $filename = 'images/steps/'. $date . $rand . '.jpg';
        $request->image->storeAs('public', $filename);
        // URLをユーザーテーブルに保存
        $step->image = 'storage/'. $filename;
      }
      
      // インスタンスの状態をデータベースに書き込む
      $step->update();
      
      // substepデータを保存する
      // 1. まず親ステップに属するサブステップを全て削除する（重複を避ける）
      $step->substeps()->delete();
      // 2. 配列で渡ってきたフォーム入力値を、タイトルの件数だけ回す（＝タイトル未入力の欄は無視）
      for ($i = 0; $i < count($request->substep_titles); $i ++) {
        // タイトル欄が入力されている小STEPのみを保存
        if (isset($request->substep_titles[$i])) {
          $substep = new Substep();
          $substep->step_id = $id;
          $substep->order = $request->substep_orders[$i];
          $substep->title = $request->substep_titles[$i];
          $substep->description = $request->substep_descriptions[$i];
          $substep->link = $request->substep_links[$i];
          $substep->url = $request->substep_urls[$i];
          $substep->save();
        }
      }

      // 編集元のSTEP詳細ページへリダイレクト
      return redirect()->route('steps.detail', [
        'id' => $id,
      ]);

    }

    // STEP一覧ページの表示（Ajax版）
    public function ajax()
    {
        return view('steps/list');
    }
}

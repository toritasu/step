<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EditProfile;
use App\User;
use App\Step;
use App\Challenge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // TOP画面の表示
    public function index()
    {
        return view('index');
    }

        // マイページの表示
        public function mypage(int $user_id)
        {
            // 0. ログイン中のユーザーを取得
            $user = Auth::user();
    
            // 1. ユーザーが投稿したSTEPを抽出する
            $steps_by_user = User::where('user_id', $user->id)
                        ->join('steps', 'users.id', '=', 'steps.user_id')
                        ->select('steps.id as id', 'categories', 'image', 'title', 'estimate', 'user_id', 'name')
                        ->get();
    
            // 2. ユーザーが挑戦中のSTEPを抽出する
            // 2-1. 挑戦中のSTEPを抽出
            $steps_challenges = Challenge::where('challenger_id', $user->id)
                            ->join('steps', 'steps.id', '=', 'challenges.step_id')
                            ->join('users', 'users.id', '=', 'steps.id')
                            ->where('status', 1)
                            ->get();
            // 2-2. 達成済みのSTEPを抽出
            $steps_achived = Challenge::where('challenger_id', $user->id)
                            ->join('steps', 'steps.id', '=', 'challenges.step_id')
                            ->join('users', 'users.id', '=', 'steps.id')
                            ->where('status', 2)
                            ->get();
    
            return view('mypage', [
                'user' => $user,
                'steps_by_user' => $steps_by_user,
                'steps_challenges' => $steps_challenges,
                'steps_achived' => $steps_achived,
            ]);
        }
    
        // プロフィール編集ページの表示
        public function showProfileEditForm(int $user_id)
        {
            $user = User::find($user_id);
    
            return view('profile/edit', [
                'user' => $user,
            ]);
        }
    
        // プロフィール編集処理の実行
        public function profileEdit(int $user_id, EditProfile $request)
        {
            // 入力内容をユーザーテーブルに保存
            $user = User::find($user_id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->introduction = $request->introduction;
    
            // 画像の保存
            // storeAsメソッドでファイルを保存
            if($request->has('avater')) {
                $filename = 'images/avaters/user_'. Auth::user()->id.'.jpg';
                $request->avater->storeAs('public', $filename);
                // URLをユーザーテーブルに保存
                $user->avater = 'storage/'. $filename;
            }
    
            $user->save();
    
            return redirect()
                ->route('mypage', ['id' => $user_id,])
                ->with('message', 'プロフィールを更新しました！');
        }
}

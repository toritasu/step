<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class EditProfile extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'avater' => 'file|image|mimes:jpeg,png,jpeg,gif|max:2048',
            'name' => 'required|max:10',
            'email' => 'required|email',
            'introduction' => 'max:140',
        ];
    }
    public function attributes()
    {
        return [
            'avater' => 'アバター画像',
            'name' => 'ニックネーム',
            'email' => 'メールアドレス',
            'introduction' => '自己紹介文',
        ];
    }
}

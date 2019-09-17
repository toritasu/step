<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateStep extends FormRequest
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
            'title' => 'required|max:50',
            'categories' => 'required|between:1,9',
            'estimate' => 'between:1,9',
            'image' => 'file|image|mimes:jpeg,png,jpeg,gif|max:2048',
            'description' => 'max:500',
            'substep_titles.*' => 'max:30',
            'substep_descriptions.*' => 'max:140',
            'substeps_links.*' => 'max:30',
            'substeps_urls.*' => 'max:255',
            // 小STEPは一つ目のみ必須
            'substep_titles.0' => 'required|max:30',
        ];
    }
    public function attributes()
    {
        return [
            'title' => 'タイトル',
            'categories' => 'カテゴリー',
            'estimate' => '目安達成時間',
            'image' => 'イメージ画像',
            'description' => '概要',
            'substep_titles.0' => '１つ目の小さなSTEPのタイトル',
            'substep_titles.*' => '小さなSTEPのタイトル',
            'substep_descriptions.*' => '小さなSTEPの概要',
            'substeps_links.*' => '小さなSTEPの参考サイト名',
            'substeps_urls.*' => '小さなSTEPの参考サイトURL',
        ];
    }
}

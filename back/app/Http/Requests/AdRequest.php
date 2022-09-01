<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|max:200',
            'description' => 'required|max:1000',
            'price' => 'required|numeric|min:1',
            'photos' => 'max:3',
            'photos.*' => 'url',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название не указано',
            'name.max' => 'Указано слишком длинное название (макс. {n} символов)',
            'description.required' => 'Описание не указано',
            'description.max' => 'Указано слишком длинное описание (макс. {n} символов)',
            'price.*' => 'Не указана или указана некорректная цена',
            'photos.*' => 'Добавлено слишком много фото (макс. :max фото)',
            'photos.*.url' => 'Некорректный URL фото',
        ];
    }
}

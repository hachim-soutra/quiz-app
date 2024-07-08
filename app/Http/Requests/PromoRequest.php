<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return [
                'title' => ['required', 'unique:promos,title'],
                'price' => 'required',
                'select_quizzes' => 'required|min:1'
            ];
        } else {
            return [
                'title' => ['required', 'unique:promos,title,' . $this->route('promo')->id],
                'price' => 'required',
                'select_quizzes' => 'required|min:1'
            ];
        }
    }
}

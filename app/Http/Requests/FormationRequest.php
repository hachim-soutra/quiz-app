<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormationRequest extends FormRequest
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
                'title' => ['required', 'unique:formations,title'],
                'select_quizzes' => 'required|min:1',
                'payment_type' => 'required',
                'price' => 'required_if:payment_type,==,paid,numeric'
            ];
        } else {
            return [
                'title' => ['required', 'unique:formations,title,' . $this->route('formation')->id],
                'select_quizzes' => 'required|min:1',
                'payment_type' => 'required',
                'price' => 'required_if:payment_type,==,paid,numeric'
            ];
        }
    }
}

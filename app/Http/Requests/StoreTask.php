<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTask extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'priority' => 'required|string|in:low,medium,high',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'العنوان مطلوب',
            'description.required' => 'الوصف مطلوب',
            'priority.required' => 'الأولوية مطلوبة',
            'priority.in' => 'الأولوية يجب أن تكون: low, medium, high'
        ];
    }
}

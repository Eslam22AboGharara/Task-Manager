<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfileRequest extends FormRequest
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
            //
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'الاسم الأول مطلوب',
            'first_name.string' => 'الاسم الأول يجب أن يكون نصاً',
            'first_name.max' => 'الاسم الأول يجب ألا يتجاوز 50 حرفاً',

            'last_name.required' => 'الاسم الأخير مطلوب',
            'last_name.string' => 'الاسم الأخير يجب أن يكون نصاً',
            'last_name.max' => 'الاسم الأخير يجب ألا يتجاوز 50 حرفاً',

            'phone.string' => 'رقم الهاتف يجب أن يكون نصاً',
            'phone.max' => 'رقم الهاتف يجب ألا يتجاوز 20 حرفاً',

            'address.string' => 'العنوان يجب أن يكون نصاً',
            'address.max' => 'العنوان يجب ألا يتجاوز 255 حرفاً',

            'avatar.image' => 'الصورة يجب أن تكون ملف صورة',
            'avatar.mimes' => 'الصورة يجب أن تكون من نوع: jpeg, png, jpg, gif',
            'avatar.max' => 'حجم الصورة يجب ألا يتجاوز 2MB',
        ];
    }
}

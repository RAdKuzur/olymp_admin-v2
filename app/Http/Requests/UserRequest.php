<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|unique:user,email|max:255',
            'password' => 'required|string|min:8|max:255',
            'firstname' => 'required|string|max:100',
            'surname' => 'required|string|max:100',
            'patronymic' => 'nullable|string|max:100',
            'phone_number' => 'required|string|regex:/^\+?[0-9]{10,15}$/',
            'gender' => 'required|in:male,female,other',
            'role' => 'required|in:admin,user,moderator',
            'birthdate' => 'required|date|before_or_equal:today'
        ];
    }
}

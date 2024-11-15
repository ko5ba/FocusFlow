<?php

namespace App\Http\Requests\Auth;
use Illuminate\Validation\Rules;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|min:2|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::default()]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Введите имя',
            'name.min' => 'Минимальное количество символов не должно быть меньше 2',
            'name.max' => 'Максимальное количество символов не должно превышать 20',
            'email.required' => 'Введите почту',
            'email.email' => 'Введен неверный формат почты',
            'email.unique' => 'Данный адрес почты уже используется',
            'password.required' => 'Введите пароль',
            'password.confirmed' => 'Введенные пароли отличаются'
        ];
    }
}

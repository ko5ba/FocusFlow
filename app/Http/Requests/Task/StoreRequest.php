<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'priority' => 'nullable|in:Без приоритета,Низкий,Средний,Высокий',
            'date_deadline' => 'nullable|date|after_or_equal:today',
            'status' => 'nullable|in:Не выполнена,В работе,Завершена,Отложена',
            'category_id' => 'nullable|integer|exists:categories,id',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'integer|exists:tags,id'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Введите название',
            'title.max' => 'Количество символов не должно превышать 100',
            'priority.in' => 'Ошибка при выборе приоритета задачи',
            'date_deadline.date' => 'Неверный формат даты',
            'date_deadline.after_or_equal' => 'Ошибка выбора даты',
            'category_id.integer' => 'Ошибка при выборе категории задачи',
            'category_id.exists' => 'Такой категории не существует',
            'tag_ids.array' => 'Ошибка при добавлении тегов',
            'tag_ids.integer' => 'Ошибка при добавленни тегов',
            'tag_ids.exists' => 'Таких тегов не найдено'
        ];
    }
}

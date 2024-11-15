<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Работа',
            'Учёба',
            'Личное развитие',
            'Домашние дела',
            'Здоровье и фитнес',
            'Покупки',
            'Финансы',
            'Развлечения',
            'Путешествия',
            'Социальное',
            'Идеи',
            'Проекты',
            'Рутинные задачи',
            'Командная работа',
        ];

        foreach ($categories as $category) {
            Category::create(['title' => $category])->factory();
        }
    }
}

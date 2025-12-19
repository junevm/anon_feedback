<?php

namespace Database\Seeders;

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
            [
                'name' => 'Work Culture',
                'slug' => 'work-culture',
                'description' => 'Share feedback about company culture, values, and environment',
                'is_active' => true,
            ],
            [
                'name' => 'Management',
                'slug' => 'management',
                'description' => 'Feedback about leadership, management style, and decision-making',
                'is_active' => true,
            ],
            [
                'name' => 'Workload',
                'slug' => 'workload',
                'description' => 'Comments about work distribution, deadlines, and work-life balance',
                'is_active' => true,
            ],
            [
                'name' => 'Ethics',
                'slug' => 'ethics',
                'description' => 'Concerns about ethical practices and professional conduct',
                'is_active' => true,
            ],
            [
                'name' => 'Communication',
                'slug' => 'communication',
                'description' => 'Feedback on internal communication and transparency',
                'is_active' => true,
            ],
            [
                'name' => 'Career Development',
                'slug' => 'career-development',
                'description' => 'Comments about growth opportunities and professional development',
                'is_active' => true,
            ],
            [
                'name' => 'Benefits & Compensation',
                'slug' => 'benefits-compensation',
                'description' => 'Feedback about salary, benefits, and rewards',
                'is_active' => true,
            ],
            [
                'name' => 'Other',
                'slug' => 'other',
                'description' => 'General feedback that doesn\'t fit other categories',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}

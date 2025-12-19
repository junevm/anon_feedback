<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = \App\Models\Category::all();

        if ($categories->isEmpty()) {
            $this->command->warn('No categories found. Please run CategorySeeder first.');
            return;
        }

        $sampleFeedback = [
            'The team collaboration has improved significantly this quarter.',
            'I appreciate the flexibility in work hours.',
            'More transparent communication from leadership would be helpful.',
            'The recent project deadlines were quite challenging.',
            'Great to see focus on professional development opportunities.',
            'The new office space is much more comfortable.',
            'I think we need better tools for remote collaboration.',
            'The onboarding process could be more structured.',
            'Really enjoying the team-building activities.',
            'Work-life balance has been excellent lately.',
            'More clarity on project priorities would help.',
            'The mentorship program has been very valuable.',
            'I appreciate the open-door policy with management.',
            'The workload distribution seems uneven across teams.',
            'Recognition for good work could be more frequent.',
        ];

        foreach ($sampleFeedback as $content) {
            \App\Models\Feedback::create([
                'category_id' => $categories->random()->id,
                'content' => $content,
                'anonymous_token' => hash('sha256', uniqid(rand(), true)),
                'status' => collect(['pending', 'approved', 'approved', 'approved'])->random(),
            ]);
        }
    }
}

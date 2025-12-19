<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Feedback</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total_feedback'] }}</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Pending Review</div>
                        <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-500">{{ $stats['pending_feedback'] }}</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Approved</div>
                        <div class="text-3xl font-bold text-green-600 dark:text-green-500">{{ $stats['approved_feedback'] }}</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Flagged</div>
                        <div class="text-3xl font-bold text-red-600 dark:text-red-500">{{ $stats['flagged_feedback'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Recent Feedback -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Feedback</h3>
                    <div class="space-y-4">
                        @forelse($recentFeedback as $feedback)
                            <div class="border-l-4 @if($feedback->status === 'approved') border-green-500 @elseif($feedback->status === 'flagged') border-red-500 @else border-yellow-500 @endif pl-4 py-2">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ $feedback->category->name }}</span>
                                        <p class="text-gray-800 dark:text-gray-200 mt-1">{{ Str::limit($feedback->content, 100) }}</p>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded 
                                        @if($feedback->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                        @elseif($feedback->status === 'flagged') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @endif">
                                        {{ ucfirst($feedback->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400">No feedback yet.</p>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.moderation.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">View all feedback →</a>
                    </div>
                </div>
            </div>

            <!-- Category Distribution -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Feedback by Category</h3>
                    <div class="space-y-3">
                        @foreach($categoryDistribution as $category)
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-700 dark:text-gray-300">{{ $category->name }}</span>
                                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $category->feedback_count }}</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['total_feedback'] > 0 ? ($category->feedback_count / $stats['total_feedback'] * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

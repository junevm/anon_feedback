<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Analytics & Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Overall Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Feedback</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalFeedback }}</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Pending</div>
                        <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-500">{{ $pendingFeedback }}</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Approved</div>
                        <div class="text-3xl font-bold text-green-600 dark:text-green-500">{{ $approvedFeedback }}</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Flagged</div>
                        <div class="text-3xl font-bold text-red-600 dark:text-red-500">{{ $flaggedFeedback }}</div>
                    </div>
                </div>
            </div>

            <!-- Category Statistics -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Feedback by Category</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Approved</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Flagged</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Approval Rate</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($categoryStats as $category)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $category->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            {{ $category->total_feedback }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400">
                                            {{ $category->approved_feedback }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400">
                                            {{ $category->flagged_feedback }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            @if($category->total_feedback > 0)
                                                {{ number_format(($category->approved_feedback / $category->total_feedback) * 100, 1) }}%
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 7-Day Trend -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">7-Day Feedback Trend</h3>
                    @if($trendData->count() > 0)
                        <div class="space-y-4">
                            @foreach($trendData as $trend)
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($trend->date)->format('M d, Y') }}</span>
                                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $trend->count }} feedback</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                        <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $trendData->max('count') > 0 ? ($trend->count / $trendData->max('count') * 100) : 0 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No feedback data available for the past 7 days.</p>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex gap-4">
                <a href="{{ route('admin.reports.trends') }}" 
                    class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                    View Detailed Trends
                </a>
                <form method="POST" action="{{ route('admin.reports.generate') }}" class="inline">
                    @csrf
                    <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                        Generate Report
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

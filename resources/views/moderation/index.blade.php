<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-normal text-gray-900">Moderation</h1>
                <p class="mt-1 text-sm text-gray-600">Review and moderate feedback submissions</p>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tabs -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('admin.moderation.index', ['status' => 'pending']) }}"
                        class="@if($status === 'pending') border-blue-600 text-blue-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Pending
                        <span class="ml-2 py-0.5 px-2 rounded-full text-xs @if($status === 'pending') bg-blue-50 text-blue-700 @else bg-gray-100 text-gray-600 @endif">
                            {{ $counts['pending'] }}
                        </span>
                    </a>
                    <a href="{{ route('admin.moderation.index', ['status' => 'approved']) }}"
                        class="@if($status === 'approved') border-blue-600 text-blue-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Approved
                        <span class="ml-2 py-0.5 px-2 rounded-full text-xs @if($status === 'approved') bg-blue-50 text-blue-700 @else bg-gray-100 text-gray-600 @endif">
                            {{ $counts['approved'] }}
                        </span>
                    </a>
                    <a href="{{ route('admin.moderation.index', ['status' => 'flagged']) }}"
                        class="@if($status === 'flagged') border-blue-600 text-blue-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Flagged
                        <span class="ml-2 py-0.5 px-2 rounded-full text-xs @if($status === 'flagged') bg-blue-50 text-blue-700 @else bg-gray-100 text-gray-600 @endif">
                            {{ $counts['flagged'] }}
                        </span>
                    </a>
                </nav>
            </div>

            <!-- Feedback List -->
            <div class="space-y-4">
                @forelse($feedback as $item)
                    <div class="bg-white rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-50 text-blue-700">
                                            {{ $item->category->name }}
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            {{ $item->created_at->format('M d, Y') }} at {{ $item->created_at->format('g:i A') }}
                                        </span>
                                    </div>
                                    <p class="text-base text-gray-900 leading-relaxed">{{ $item->content }}</p>
                                    
                                    @if($item->moderation_note)
                                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Note:</span> {{ $item->moderation_note }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                                <span class="ml-6 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($item->status === 'approved') bg-green-50 text-green-700
                                    @elseif($item->status === 'flagged') bg-red-50 text-red-700
                                    @else bg-amber-50 text-amber-700 @endif">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2 pt-4 border-t border-gray-100">
                                @if($item->status !== 'approved')
                                    <form method="POST" action="{{ route('admin.moderation.approve', $item) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-full text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                            <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Approve
                                        </button>
                                    </form>
                                @endif

                                @if($item->status !== 'flagged')
                                    <form method="POST" action="{{ route('admin.moderation.flag', $item) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                            <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd"/>
                                            </svg>
                                            Flag
                                        </button>
                                    </form>
                                @endif

                                @if($item->status !== 'pending')
                                    <form method="POST" action="{{ route('admin.moderation.reset', $item) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                            <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                            </svg>
                                            Reset
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg border border-gray-200 p-12">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No {{ $status }} feedback</h3>
                            <p class="mt-1 text-sm text-gray-500">There are no feedback items with this status.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($feedback->hasPages())
                <div class="mt-6">
                    {{ $feedback->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

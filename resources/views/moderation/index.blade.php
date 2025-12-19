<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Feedback Moderation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Status Tabs -->
            <div class="mb-6">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8">
                        <a href="{{ route('admin.moderation.index', ['status' => 'pending']) }}"
                            class="@if($status === 'pending') border-blue-500 text-blue-600 dark:text-blue-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Pending ({{ $counts['pending'] }})
                        </a>
                        <a href="{{ route('admin.moderation.index', ['status' => 'approved']) }}"
                            class="@if($status === 'approved') border-blue-500 text-blue-600 dark:text-blue-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Approved ({{ $counts['approved'] }})
                        </a>
                        <a href="{{ route('admin.moderation.index', ['status' => 'flagged']) }}"
                            class="@if($status === 'flagged') border-blue-500 text-blue-600 dark:text-blue-400 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Flagged ({{ $counts['flagged'] }})
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Feedback List -->
            <div class="space-y-4">
                @forelse($feedback as $item)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                            {{ $item->category->name }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $item->created_at->format('M d, Y H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-gray-800 dark:text-gray-200 leading-relaxed">{{ $item->content }}</p>
                                    
                                    @if($item->moderation_note)
                                        <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-900 rounded">
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <strong>Note:</strong> {{ $item->moderation_note }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                                <span class="ml-4 px-3 py-1 rounded text-xs font-semibold
                                    @if($item->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                    @elseif($item->status === 'flagged') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @endif">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                @if($item->status !== 'approved')
                                    <form method="POST" action="{{ route('admin.moderation.approve', $item) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                                            ✓ Approve
                                        </button>
                                    </form>
                                @endif

                                @if($item->status !== 'flagged')
                                    <form method="POST" action="{{ route('admin.moderation.flag', $item) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                                            ⚠ Flag
                                        </button>
                                    </form>
                                @endif

                                @if($item->status !== 'pending')
                                    <form method="POST" action="{{ route('admin.moderation.reset', $item) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                                            ↺ Reset
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                            No {{ $status }} feedback found.
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $feedback->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

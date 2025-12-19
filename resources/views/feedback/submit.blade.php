<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Submit Anonymous Feedback') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <h3 class="font-semibold text-blue-900 dark:text-blue-200 mb-2">🔒 Your Privacy is Protected</h3>
                        <p class="text-sm text-blue-800 dark:text-blue-300">
                            Your feedback is completely anonymous. We do not store any identifying information that could trace back to you. 
                            This system is designed to encourage honest, constructive feedback without fear of repercussion.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('feedback.store') }}">
                        @csrf

                        <div class="mb-6">
                            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Feedback Category <span class="text-red-500">*</span>
                            </label>
                            <select id="category_id" name="category_id" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select a category...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Your Feedback <span class="text-red-500">*</span>
                            </label>
                            <textarea id="content" name="content" rows="8" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Share your honest feedback here... (minimum 10 characters)">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Please keep your feedback constructive and professional. Toxic or abusive language will be automatically flagged.
                            </p>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Submit Feedback Anonymously
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-6 bg-gray-50 dark:bg-gray-800/50 p-4 rounded-lg">
                <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Guidelines for Feedback:</h4>
                <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-300 space-y-1">
                    <li>Be honest and constructive</li>
                    <li>Focus on specific situations or behaviors</li>
                    <li>Avoid personal attacks or offensive language</li>
                    <li>Suggest improvements when possible</li>
                    <li>Remember that your feedback helps improve the workplace for everyone</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>

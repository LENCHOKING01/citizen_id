<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Application') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('applications.store') }}" class="space-y-6">
                        @csrf

                        <!-- Citizen Selection -->
                        <div>
                            <label for="citizen_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Select Citizen *
                            </label>
                            <select name="citizen_id" id="citizen_id"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="">Choose a citizen...</option>
                                @foreach($citizens as $citizen)
                                    <option value="{{ $citizen->id }}" {{ old('citizen_id', request('citizen_id')) == $citizen->id ? 'selected' : '' }}>
                                        {{ $citizen->first_name }} {{ $citizen->last_name }} (ID: {{ $citizen->id_number }})
                                    </option>
                                @endforeach
                            </select>
                            @error('citizen_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Application Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                Application Type *
                            </label>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input id="new_id" name="application_type" type="radio" value="new_id" 
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" checked>
                                    <label for="new_id" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        New ID Card
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="renewal" name="application_type" type="radio" value="renewal" 
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="renewal" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        ID Card Renewal
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="replacement" name="application_type" type="radio" value="replacement" 
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="replacement" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Lost/Damaged ID Replacement
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Application Notes
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Any additional notes or special requirements...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('applications.index') }}"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition-colors">
                                Cancel
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                                Create Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

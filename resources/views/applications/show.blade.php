<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Application Details') }} - #{{ $application->id }}
            </h2>
            <div class="space-x-2">
                @if($application->status !== 'printed')
                    <a href="{{ route('applications.edit', $application) }}" 
                        class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition-colors">
                        Update Status
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Application Status -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Application Status</h3>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full 
                            @if($application->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($application->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($application->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @elseif($application->status === 'printed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                            @endif">
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Submitted</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $application->submitted_at->format('F d, Y H:i') }}</p>
                        </div>
                        @if($application->approved_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Approved</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $application->approved_at->format('F d, Y H:i') }}</p>
                        </div>
                        @endif
                        @if($application->printed_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Printed</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $application->printed_at->format('F d, Y H:i') }}</p>
                        </div>
                        @endif
                    </div>

                    @if($application->rejection_reason)
                    <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                        <h4 class="text-sm font-medium text-red-800 dark:text-red-200">Rejection Reason</h4>
                        <p class="mt-1 text-sm text-red-700 dark:text-red-300">{{ $application->rejection_reason }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Citizen Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Citizen Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $application->citizen->first_name }} 
                                @if($application->citizen->middle_name) {{ $application->citizen->middle_name }} @endif 
                                {{ $application->citizen->last_name }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">ID Number</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $application->citizen->id_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Date of Birth</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($application->citizen->date_of_birth)->format('F d, Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Phone</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $application->citizen->phone }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Documents</h3>
                        @if($application->status === 'draft' || $application->status === 'pending')
                        <button onclick="document.getElementById('upload-modal').classList.remove('hidden')" 
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors">
                            Upload Document
                        </button>
                        @endif
                    </div>
                    
                    @if($application->documents->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($application->documents as $document)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $document->document_type }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $document->original_name }}</p>
                                        </div>
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" 
                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No documents uploaded</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload required documents to proceed with the application.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Biometric Data -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Biometric Data</h3>
                        @if($application->status === 'draft' || $application->status === 'pending')
                        <button onclick="document.getElementById('biometric-modal').classList.remove('hidden')" 
                            class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded transition-colors">
                            Capture Biometrics
                        </button>
                        @endif
                    </div>
                    
                    @if($application->biometrics)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Photo</label>
                                <div class="mt-2">
                                    <img src="{{ Storage::url($application->biometrics->photo_path) }}" 
                                        alt="Citizen Photo" class="w-32 h-32 object-cover rounded-lg border">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Fingerprint Status</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full text-xs">
                                        Captured
                                    </span>
                                </p>
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    Captured on {{ $application->biometrics->captured_at->format('F d, Y H:i') }}
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No biometric data captured</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Capture fingerprint and photo data to complete the application.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Processing History -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Processing History</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Application Created</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    By {{ $application->registrar->name }} on {{ $application->submitted_at->format('F d, Y H:i') }}
                                </p>
                            </div>
                        </div>
                        
                        @if($application->approved_at)
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Application Approved</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    By {{ $application->supervisor->name }} on {{ $application->approved_at->format('F d, Y H:i') }}
                                </p>
                            </div>
                        </div>
                        @endif
                        
                        @if($application->printed_at)
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M17 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">ID Card Printed</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    On {{ $application->printed_at->format('F d, Y H:i') }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Document Modal -->
    <div id="upload-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Upload Document</h3>
                <form method="POST" action="{{ route('applications.upload-document', $application) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="document_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Document Type</label>
                        <select name="document_type" id="document_type" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            <option value="">Select type...</option>
                            <option value="birth_certificate">Birth Certificate</option>
                            <option value="passport">Passport</option>
                            <option value="proof_of_address">Proof of Address</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="document" class="block text-sm font-medium text-gray-700 dark:text-gray-300">File</label>
                        <input type="file" name="document" id="document" accept=".pdf,.jpg,.jpeg,.png" required
                            class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="document.getElementById('upload-modal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Biometric Capture Modal -->
    <div id="biometric-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Capture Biometric Data</h3>
                <form method="POST" action="{{ route('applications.store-biometric', $application) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Photo</label>
                        <input type="file" name="photo" id="photo" accept="image/*" required
                            class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    </div>
                    <div class="mb-4">
                        <label for="fingerprint_data" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fingerprint Data</label>
                        <textarea name="fingerprint_data" id="fingerprint_data" rows="3" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                            placeholder="Fingerprint scan data..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="document.getElementById('biometric-modal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

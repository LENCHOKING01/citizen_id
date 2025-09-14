<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Biometric Data Capture') }} - Application #{{ $application->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl">
                <div class="bg-gradient-to-r from-green-600 to-blue-600 p-6">
                    <div class="flex items-center">
                        <div class="bg-white p-3 rounded-lg mr-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div class="text-white">
                            <h3 class="text-2xl font-bold">Biometric Data Capture</h3>
                            <p class="text-green-100">Citizen: {{ $application->citizen->first_name }} {{ $application->citizen->last_name }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <form action="{{ route('applications.store-biometric', $application) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <!-- Fingerprint Section -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="bg-blue-500 p-2 rounded-lg mr-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Fingerprint Capture</h4>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="fingerprint_data" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Fingerprint Data
                                    </label>
                                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400 mb-2">Place finger on scanner</p>
                                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="captureFingerprint()">
                                            Start Scan
                                        </button>
                                    </div>
                                    <textarea name="fingerprint_data" id="fingerprint_data" rows="3" 
                                              class="mt-2 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm" 
                                              placeholder="Fingerprint data will appear here..."></textarea>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Fingerprint Preview
                                    </label>
                                    <div id="fingerprint-preview" class="border border-gray-300 dark:border-gray-600 rounded-lg h-48 flex items-center justify-center bg-gray-50 dark:bg-gray-700">
                                        <span class="text-gray-400">No fingerprint captured</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Facial Recognition Section -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="bg-purple-500 p-2 rounded-lg mr-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Facial Recognition</h4>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="facial_recognition_data" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Facial Recognition Data
                                    </label>
                                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400 mb-2">Position face in camera</p>
                                        <button type="button" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded" onclick="captureFace()">
                                            Capture Face
                                        </button>
                                    </div>
                                    <textarea name="facial_recognition_data" id="facial_recognition_data" rows="3" 
                                              class="mt-2 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm" 
                                              placeholder="Facial recognition data will appear here..."></textarea>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Face Preview
                                    </label>
                                    <div id="face-preview" class="border border-gray-300 dark:border-gray-600 rounded-lg h-48 flex items-center justify-center bg-gray-50 dark:bg-gray-700">
                                        <span class="text-gray-400">No face captured</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Signature Section -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="bg-green-500 p-2 rounded-lg mr-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Digital Signature</h4>
                            </div>
                            
                            <div>
                                <label for="signature_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Upload Signature Image
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                            <label for="signature_image" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-green-600 hover:text-green-500">
                                                <span>Upload signature</span>
                                                <input id="signature_image" name="signature_image" type="file" class="sr-only" accept="image/*">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            PNG, JPG, JPEG up to 1MB
                                        </p>
                                    </div>
                                </div>
                                @error('signature_image')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('applications.show', $application) }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                                Save Biometric Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function captureFingerprint() {
            // Simulate fingerprint capture
            const fingerprintData = 'FP_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            document.getElementById('fingerprint_data').value = fingerprintData;
            document.getElementById('fingerprint-preview').innerHTML = 
                '<div class="text-green-600 font-semibold">Fingerprint Captured</div>';
        }

        function captureFace() {
            // Simulate facial recognition capture
            const faceData = 'FACE_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            document.getElementById('facial_recognition_data').value = faceData;
            document.getElementById('face-preview').innerHTML = 
                '<div class="text-purple-600 font-semibold">Face Captured</div>';
        }

        // File preview for signature
        document.getElementById('signature_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('img');
                    preview.src = e.target.result;
                    preview.className = 'max-w-full max-h-32 mx-auto';
                    const container = e.target.closest('.border-dashed').querySelector('.space-y-1');
                    container.innerHTML = '';
                    container.appendChild(preview);
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-app-layout>

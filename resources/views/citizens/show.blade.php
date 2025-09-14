<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 dark:text-white leading-tight">
                    Citizen Details - {{ $citizen->first_name }} {{ $citizen->last_name }}
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mt-1">View and manage citizen information</p>
            </div>
            <div class="flex space-x-3">
                <div class="flex space-x-2">
                    <a href="{{ route('citizens.id-card.view', $citizen) }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        View ID Card
                    </a>
                    <a href="{{ route('citizens.id-card', $citizen) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download PDF
                    </a>
                </div>
                <a href="{{ route('citizens.edit', $citizen) }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Citizen
                </a>
                <a href="{{ route('applications.create', ['citizen_id' => $citizen->id]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Application
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Main Citizen Information Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-2xl rounded-2xl border border-gray-200 dark:border-gray-700">
                <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-6">
                            <!-- Photo Section -->
                            <div class="flex-shrink-0">
                                @if($citizen->photo_path)
                                    <img src="{{ Storage::url($citizen->photo_path) }}" 
                                         alt="Citizen Photo" 
                                         class="w-32 h-32 rounded-xl object-cover border-4 border-white shadow-xl">
                                @else
                                    <div class="w-32 h-32 bg-gray-300 rounded-xl flex items-center justify-center border-4 border-white shadow-xl">
                                        <svg class="w-16 h-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Citizen Basic Info -->
                            <div class="text-white">
                                <h1 class="text-4xl font-bold mb-2">
                                    {{ $citizen->first_name }} 
                                    @if($citizen->middle_name) {{ $citizen->middle_name }} @endif 
                                    {{ $citizen->last_name }}
                                </h1>
                                <p class="text-blue-100 text-xl mb-4 font-semibold">ID: {{ $citizen->id_number }}</p>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-blue-200">Date of Birth:</span>
                                        <div class="font-semibold text-lg">{{ $citizen->date_of_birth->format('F j, Y') }}</div>
                                    </div>
                                    <div>
                                        <span class="text-blue-200">Age:</span>
                                        <div class="font-semibold text-lg">{{ $citizen->date_of_birth->age }} years</div>
                                    </div>
                                    <div>
                                        <span class="text-blue-200">Gender:</span>
                                        <div class="font-semibold text-lg">{{ ucfirst($citizen->gender) }}</div>
                                    </div>
                                    <div>
                                        <span class="text-blue-200">Nationality:</span>
                                        <div class="font-semibold text-lg">{{ $citizen->nationality }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- QR Code Section -->
                        <div class="flex-shrink-0">
                            @if($citizen->qr_code_path)
                                <div class="bg-white p-4 rounded-xl shadow-xl">
                                    <img src="{{ Storage::url($citizen->qr_code_path) }}" 
                                         alt="QR Code" 
                                         class="w-28 h-28">
                                    <p class="text-xs text-gray-600 text-center mt-2 font-semibold">Scan to Verify</p>
                                </div>
                            @else
                                <div class="bg-white p-4 rounded-xl shadow-xl">
                                    <div class="w-28 h-28 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <span class="text-gray-500 text-xs">No QR Code</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Photo Upload Section -->
                <div class="px-8 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <form action="{{ route('citizens.upload-photo', $citizen) }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-4">
                        @csrf
                        <input type="file" name="photo" accept="image/*" class="hidden" id="photo-upload" onchange="this.form.submit()">
                        <label for="photo-upload" class="cursor-pointer inline-flex items-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $citizen->photo_path ? 'Change Photo' : 'Upload Photo' }}
                        </label>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Upload a new photo for this citizen</span>
                    </form>
                </div>

                <!-- Detailed Information Grid -->
                <div class="px-8 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">
                            <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Phone Number</label>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $citizen->phone }}</p>
                        </div>
                        
                        @if($citizen->email)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">
                            <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Email Address</label>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $citizen->email }}</p>
                        </div>
                        @endif
                        
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">
                            <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Registration Date</label>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $citizen->created_at->format('F j, Y') }}</p>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 md:col-span-2 lg:col-span-3">
                            <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Address</label>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $citizen->address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Applications History -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Applications History</h3>
                    @if($citizen->applications->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Application ID
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Registrar
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Supervisor
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Submitted
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($citizen->applications as $application)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                #{{ $application->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($application->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($application->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @elseif($application->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                                    @endif">
                                                    {{ ucfirst($application->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $application->registrar->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $application->supervisor ? $application->supervisor->name : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $application->submitted_at->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('applications.show', $application) }}" 
                                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No applications</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This citizen has not submitted any ID applications yet.</p>
                            <div class="mt-6">
                                <a href="{{ route('applications.create', ['citizen_id' => $citizen->id]) }}" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Create First Application
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

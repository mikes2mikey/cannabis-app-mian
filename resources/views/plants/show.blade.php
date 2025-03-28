<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $plant->strain }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('plants.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    {{ __('Back to Plants') }}
                </a>
                @if (Auth::user()->isAdmin())
                    <a href="{{ route('plants.edit', $plant) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700">
                        {{ __('Edit Plant') }}
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Plant Details -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Plant Details</h3>
                            <div class="space-y-3">
                                <p><span class="font-medium">Status:</span> {{ ucfirst($plant->status) }}</p>
                                <p><span class="font-medium">Planted:</span> {{ $plant->planting_date->format('M d, Y') }}</p>
                                @if ($plant->harvest_date)
                                    <p><span class="font-medium">Harvested:</span> {{ $plant->harvest_date->format('M d, Y') }}</p>
                                @endif
                                <p><span class="font-medium">Members:</span>
                                    <ul class="list-disc list-inside ml-4 mt-1">
                                        @foreach ($plant->users as $user)
                                            <li>{{ $user->name }}</li>
                                        @endforeach
                                    </ul>
                                </p>
                                @if ($plant->notes)
                                    <p><span class="font-medium">Notes:</span> {{ $plant->notes }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Growth Log Form -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Add Growth Log</h3>
                            <form action="{{ route('growth-logs.store', $plant) }}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="date" :value="__('Date')" />
                                        <x-text-input id="date" type="date" name="date" class="block mt-1 w-full" required />
                                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="phase" :value="__('Growth Phase')" />
                                        <select id="phase" name="phase" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                            <option value="seedling">Seedling</option>
                                            <option value="vegetative">Vegetative</option>
                                            <option value="flowering">Flowering</option>
                                            <option value="harvest">Harvest</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('phase')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="height" :value="__('Height (cm)')" />
                                        <x-text-input id="height" type="number" step="0.01" name="height" class="block mt-1 w-full" />
                                        <x-input-error :messages="$errors->get('height')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="notes" :value="__('Notes')" />
                                        <textarea id="notes" name="notes" rows="3" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required></textarea>
                                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                                    </div>

                                    <div class="flex items-center justify-end">
                                        <x-primary-button>
                                            {{ __('Add Log') }}
                                        </x-primary-button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Growth Logs -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Growth Logs</h3>
                            @if ($plant->growthLogs->isEmpty())
                                <p class="text-gray-500">No growth logs recorded yet.</p>
                            @else
                                <div class="space-y-4">
                                    @foreach ($plant->growthLogs->sortByDesc('date') as $log)
                                        <div class="border rounded-lg p-4">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="font-medium">{{ $log->date->format('M d, Y') }}</p>
                                                    <p class="text-sm text-gray-600">Phase: {{ ucfirst($log->phase) }}</p>
                                                    @if ($log->height)
                                                        <p class="text-sm text-gray-600">Height: {{ $log->height }}cm</p>
                                                    @endif
                                                    <p class="mt-2">{{ $log->notes }}</p>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('growth-logs.edit', $log) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                                    <form action="{{ route('growth-logs.destroy', $log) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this log?')">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Plant Images -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Images</h3>
                            
                            <!-- Image Upload Form -->
                            <form action="{{ route('plant-images.store', $plant) }}" method="POST" enctype="multipart/form-data" class="mb-6">
                                @csrf
                                <div class="flex items-center space-x-4">
                                    <div class="flex-1">
                                        <input type="file" name="image" accept="image/*" class="w-full" required>
                                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                                    </div>
                                    <x-primary-button>
                                        {{ __('Upload') }}
                                    </x-primary-button>
                                </div>
                            </form>

                            <!-- Image Gallery -->
                            @if ($plant->images->isEmpty())
                                <p class="text-gray-500">No images uploaded yet.</p>
                            @else
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach ($plant->images as $image)
                                        <div class="relative group">
                                            <a href="{{ Storage::url($image->image_path) }}" target="_blank">
                                                <img src="{{ Storage::url($image->thumbnail_path) }}" alt="Plant image" class="w-full h-40 object-cover rounded-lg">
                                            </a>
                                            <form action="{{ route('plant-images.destroy', $image) }}" method="POST" class="absolute top-2 right-2 hidden group-hover:block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 text-white rounded-full p-1 hover:bg-red-700" onclick="return confirm('Are you sure you want to delete this image?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
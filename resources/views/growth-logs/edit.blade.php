<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Growth Log') }}
            </h2>
            <a href="{{ route('plants.show', $growthLog->plant) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                {{ __('Back to Plant') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('growth-logs.update', $growthLog) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="date" :value="__('Date')" />
                                <x-text-input id="date" type="date" name="date" :value="old('date', $growthLog->date->format('Y-m-d'))" class="block mt-1 w-full" required />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="phase" :value="__('Growth Phase')" />
                                <select id="phase" name="phase" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                    <option value="seedling" {{ old('phase', $growthLog->phase) === 'seedling' ? 'selected' : '' }}>Seedling</option>
                                    <option value="vegetative" {{ old('phase', $growthLog->phase) === 'vegetative' ? 'selected' : '' }}>Vegetative</option>
                                    <option value="flowering" {{ old('phase', $growthLog->phase) === 'flowering' ? 'selected' : '' }}>Flowering</option>
                                    <option value="harvest" {{ old('phase', $growthLog->phase) === 'harvest' ? 'selected' : '' }}>Harvest</option>
                                </select>
                                <x-input-error :messages="$errors->get('phase')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="height" :value="__('Height (cm)')" />
                                <x-text-input id="height" type="number" step="0.01" name="height" :value="old('height', $growthLog->height)" class="block mt-1 w-full" />
                                <x-input-error :messages="$errors->get('height')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="temperature" :value="__('Temperature (°C)')" />
                                <x-text-input id="temperature" type="number" step="0.1" name="temperature" :value="old('temperature', $growthLog->temperature)" class="block mt-1 w-full" />
                                <x-input-error :messages="$errors->get('temperature')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="humidity" :value="__('Humidity (%)')" />
                                <x-text-input id="humidity" type="number" step="0.1" name="humidity" :value="old('humidity', $growthLog->humidity)" class="block mt-1 w-full" />
                                <x-input-error :messages="$errors->get('humidity')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="notes" :value="__('Notes')" />
                                <textarea id="notes" name="notes" rows="3" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>{{ old('notes', $growthLog->notes) }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end space-x-4">
                                <form action="{{ route('growth-logs.destroy', $growthLog) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700" onclick="return confirm('Are you sure you want to delete this growth log?')">
                                        {{ __('Delete Log') }}
                                    </button>
                                </form>
                                <x-primary-button>
                                    {{ __('Update Log') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
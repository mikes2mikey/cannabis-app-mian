<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Plant') }}: {{ $plant->strain }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('plants.show', $plant) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    {{ __('Back to Plant') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600">{{ __('Whoops! Something went wrong.') }}</div>
                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="flex justify-end mb-4">
                        <form action="{{ route('plants.destroy', $plant) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700" onclick="return confirm('Are you sure you want to delete this plant?')">
                                {{ __('Delete Plant') }}
                            </button>
                        </form>
                    </div>

                    <form action="{{ route('plants.update', $plant) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="strain" :value="__('Strain')" />
                                <x-text-input id="strain" type="text" name="strain" :value="old('strain', $plant->strain)" class="block mt-1 w-full" required />
                                <x-input-error :messages="$errors->get('strain')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="planting_date" :value="__('Planting Date')" />
                                <x-text-input id="planting_date" type="date" name="planting_date" :value="old('planting_date', $plant->planting_date->format('Y-m-d'))" class="block mt-1 w-full" required />
                                <x-input-error :messages="$errors->get('planting_date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="harvest_date" :value="__('Harvest Date')" />
                                <x-text-input id="harvest_date" type="date" name="harvest_date" :value="old('harvest_date', optional($plant->harvest_date)->format('Y-m-d'))" class="block mt-1 w-full" />
                                <x-input-error :messages="$errors->get('harvest_date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                    <option value="growing" {{ old('status', $plant->status) === 'growing' ? 'selected' : '' }}>Growing</option>
                                    <option value="harvested" {{ old('status', $plant->status) === 'harvested' ? 'selected' : '' }}>Harvested</option>
                                    <option value="cancelled" {{ old('status', $plant->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="notes" :value="__('Notes')" />
                                <textarea id="notes" name="notes" rows="3" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ old('notes', $plant->notes) }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="user_ids" :value="__('Members')" />
                                <select id="user_ids" name="user_ids[]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" multiple size="5" required>
                                    @foreach ($members as $member)
                                        <option value="{{ $member->id }}" {{ in_array($member->id, old('user_ids', $plant->users->pluck('id')->toArray())) ? 'selected' : '' }}>
                                            {{ $member->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-sm text-gray-500">Hold Ctrl (Windows) or Cmd (Mac) to select multiple members. Selected members will be highlighted.</p>
                                <x-input-error :messages="$errors->get('user_ids')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end">
                                <x-primary-button>
                                    {{ __('Update Plant') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userSelect = document.getElementById('user_ids');
            
            // Add click event listener to handle Ctrl/Cmd + click
            userSelect.addEventListener('mousedown', function(e) {
                if (!e.ctrlKey && !e.metaKey && !e.shiftKey) {
                    const selectedOptions = Array.from(this.selectedOptions);
                    if (selectedOptions.length === 1 && selectedOptions[0] === e.target) {
                        e.preventDefault();
                        e.target.selected = !e.target.selected;
                    }
                }
            });

            // Add change event listener to ensure at least one member is selected
            userSelect.addEventListener('change', function() {
                const selectedOptions = Array.from(this.selectedOptions);
                if (selectedOptions.length === 0) {
                    alert('Please select at least one member.');
                }
            });
        });
    </script>
</x-app-layout> 
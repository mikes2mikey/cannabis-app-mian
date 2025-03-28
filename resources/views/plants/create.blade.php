<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Plant') }}
            </h2>
            <a href="{{ route('plants.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                {{ __('Back to Plants') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('plants.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="user_ids" :value="__('Members')" />
                                <select id="user_ids" name="user_ids[]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" multiple required>
                                    @foreach ($members as $member)
                                        <option value="{{ $member->id }}" {{ in_array($member->id, old('user_ids', [])) ? 'selected' : '' }}>
                                            {{ $member->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-sm text-gray-500">Hold Ctrl (Windows) or Cmd (Mac) to select multiple members</p>
                                <x-input-error :messages="$errors->get('user_ids')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="strain" :value="__('Strain')" />
                                <x-text-input id="strain" type="text" name="strain" :value="old('strain')" class="block mt-1 w-full" required />
                                <x-input-error :messages="$errors->get('strain')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="planting_date" :value="__('Planting Date')" />
                                <x-text-input id="planting_date" type="date" name="planting_date" :value="old('planting_date')" class="block mt-1 w-full" required />
                                <x-input-error :messages="$errors->get('planting_date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="notes" :value="__('Notes')" />
                                <textarea id="notes" name="notes" rows="3" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ old('notes') }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end">
                                <x-primary-button>
                                    {{ __('Create Plant') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
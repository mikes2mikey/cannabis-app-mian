<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Plants') }}
            </h2>
            @if (Auth::user()->isAdmin())
                <a href="{{ route('plants.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Add New Plant') }}
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($plants->isEmpty())
                        <p class="text-gray-500 text-center py-4">No plants found.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($plants as $plant)
                                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                    @if ($plant->images->isNotEmpty())
                                        <img src="{{ Storage::url($plant->images->first()->thumbnail_path) }}" alt="{{ $plant->strain }}" class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold mb-2">{{ $plant->strain }}</h3>
                                        <div class="text-sm text-gray-600 space-y-1">
                                            <p>Status: <span class="font-medium">{{ ucfirst($plant->status) }}</span></p>
                                            <p>Planted: {{ $plant->planting_date->format('M d, Y') }}</p>
                                            @if ($plant->harvest_date)
                                                <p>Harvested: {{ $plant->harvest_date->format('M d, Y') }}</p>
                                            @endif
                                            <p>Members: <span class="font-medium">{{ $plant->users->pluck('name')->join(', ') }}</span></p>
                                        </div>
                                        <div class="mt-4 flex justify-end space-x-2">
                                            <a href="{{ route('plants.show', $plant) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                                View Details
                                            </a>
                                            @if (Auth::user()->isAdmin())
                                                <a href="{{ route('plants.edit', $plant) }}" class="inline-flex items-center px-3 py-1 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700">
                                                    Edit
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">
                            {{ $plants->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
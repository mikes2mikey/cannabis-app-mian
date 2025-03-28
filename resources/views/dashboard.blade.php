<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }} {{ Auth::user()->isAdmin() ? '(Admin)' : '' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(Auth::user()->isAdmin())
                <!-- Admin Quick Links -->
                <div class="mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Admin Quick Links</h3>
                            <div class="flex flex-wrap gap-4">
                                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Manage Users
                                </a>
                                <a href="{{ route('admin.memberships.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Manage Memberships
                                </a>
                                <a href="{{ route('plants.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Add New Plant
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- QR Code Scanner Section for Admin -->
                <x-qr-scanner />
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Plant Statistics -->
                @if(Auth::user()->isAdmin())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-2">Total Users</h3>
                            <p class="text-3xl font-bold text-blue-600">
                                {{ \App\Models\User::count() }}
                            </p>
                        </div>
                    </div>
                @endif
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2">Total Plants</h3>
                        <p class="text-3xl font-bold text-green-600">
                            {{ Auth::user()->isAdmin() ? \App\Models\Plant::count() : Auth::user()->plants()->count() }}
                        </p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2">Growing</h3>
                        <p class="text-3xl font-bold text-blue-600">
                            {{ Auth::user()->isAdmin() 
                                ? \App\Models\Plant::where('status', 'growing')->count() 
                                : Auth::user()->plants()->where('status', 'growing')->count() }}
                        </p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2">Harvested</h3>
                        <p class="text-3xl font-bold text-yellow-600">
                            {{ Auth::user()->isAdmin() 
                                ? \App\Models\Plant::where('status', 'harvested')->count() 
                                : Auth::user()->plants()->where('status', 'harvested')->count() }}
                        </p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2">Growth Logs</h3>
                        <p class="text-3xl font-bold text-purple-600">
                            {{ Auth::user()->isAdmin() 
                                ? \App\Models\GrowthLog::count() 
                                : \App\Models\GrowthLog::whereIn('plant_id', Auth::user()->plants()->pluck('id'))->count() }}
                        </p>
                    </div>
                </div>
            </div>

            @if(Auth::user()->isAdmin())
                <!-- Recent Users Section (Admin Only) -->
                <div class="mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold">Recent Users</h3>
                                <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                            </div>
                            @php
                                $recentUsers = \App\Models\User::latest()->take(5)->get();
                            @endphp
                            @if ($recentUsers->isEmpty())
                                <p class="text-gray-500">No users found.</p>
                            @else
                                <div class="space-y-4">
                                    @foreach ($recentUsers as $user)
                                        <div class="flex justify-between items-center border-b pb-4 last:border-0">
                                            <div>
                                                <span class="font-medium">{{ $user->name }}</span>
                                                <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                                <p class="text-sm text-gray-600">Role: {{ ucfirst($user->role) }}</p>
                                            </div>
                                            <span class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Plants -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Recent Plants</h3>
                            <a href="{{ route('plants.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                        </div>
                        @php
                            $recentPlants = Auth::user()->isAdmin() 
                                ? \App\Models\Plant::with('users')->latest()->take(5)->get()
                                : Auth::user()->plants()->latest()->take(5)->get();
                        @endphp
                        @if ($recentPlants->isEmpty())
                            <p class="text-gray-500">No plants found.</p>
                        @else
                            <div class="space-y-4">
                                @foreach ($recentPlants as $plant)
                                    <div class="flex justify-between items-center border-b pb-4 last:border-0">
                                        <div>
                                            <a href="{{ route('plants.show', $plant) }}" class="font-medium hover:text-blue-600">{{ $plant->strain }}</a>
                                            @if (Auth::user()->isAdmin())
                                                <p class="text-sm text-gray-600">Members: {{ $plant->users->pluck('name')->join(', ') }}</p>
                                            @endif
                                            <p class="text-sm text-gray-600">Status: {{ ucfirst($plant->status) }}</p>
                                        </div>
                                        <span class="text-sm text-gray-500">{{ $plant->created_at->diffForHumans() }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Growth Logs -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Recent Growth Logs</h3>
                        @php
                            if (Auth::user()->isAdmin()) {
                                $recentLogs = \App\Models\GrowthLog::with(['plant.users'])
                                    ->latest()
                                    ->take(5)
                                    ->get();
                            } else {
                                $recentLogs = \App\Models\GrowthLog::whereIn('plant_id', Auth::user()->plants()->pluck('id'))
                                    ->with('plant')
                                    ->latest()
                                    ->take(5)
                                    ->get();
                            }
                        @endphp
                        @if ($recentLogs->isEmpty())
                            <p class="text-gray-500">No growth logs found.</p>
                        @else
                            <div class="space-y-4">
                                @foreach ($recentLogs as $log)
                                    <div class="flex justify-between items-start border-b pb-4 last:border-0">
                                        <div>
                                            <a href="{{ route('plants.show', $log->plant) }}" class="font-medium hover:text-blue-600">{{ $log->plant->strain }}</a>
                                            @if (Auth::user()->isAdmin())
                                                <p class="text-sm text-gray-600">Members: {{ $log->plant->users->pluck('name')->join(', ') }}</p>
                                            @endif
                                            <p class="text-sm text-gray-600">Phase: {{ ucfirst($log->phase) }}</p>
                                            @if ($log->height)
                                                <p class="text-sm text-gray-600">Height: {{ $log->height }}cm</p>
                                            @endif
                                            <p class="text-sm mt-1">{{ Str::limit($log->notes, 100) }}</p>
                                        </div>
                                        <span class="text-sm text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if(Auth::user()->isAdmin())
                <!-- Detailed Growth Logs Table (Admin Only) -->
                <div class="mt-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Detailed Growth Logs</h3>
                            @php
                                $detailedLogs = \App\Models\GrowthLog::with(['plant.users'])
                                    ->latest()
                                    ->take(10)
                                    ->get();
                            @endphp
                            @if ($detailedLogs->isEmpty())
                                <p class="text-gray-500">No growth logs found.</p>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white">
                                        <thead>
                                            <tr>
                                                <th class="py-2 px-4 border-b text-left">Date</th>
                                                <th class="py-2 px-4 border-b text-left">Plant</th>
                                                <th class="py-2 px-4 border-b text-left">Members</th>
                                                <th class="py-2 px-4 border-b text-left">Phase</th>
                                                <th class="py-2 px-4 border-b text-left">Height</th>
                                                <th class="py-2 px-4 border-b text-left">Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($detailedLogs as $log)
                                                <tr>
                                                    <td class="py-2 px-4 border-b">{{ $log->date->format('Y-m-d') }}</td>
                                                    <td class="py-2 px-4 border-b">
                                                        <a href="{{ route('plants.show', $log->plant) }}" class="text-blue-600 hover:text-blue-800">
                                                            {{ $log->plant->strain }}
                                                        </a>
                                                    </td>
                                                    <td class="py-2 px-4 border-b">{{ $log->plant->users->pluck('name')->join(', ') }}</td>
                                                    <td class="py-2 px-4 border-b">{{ ucfirst($log->phase) }}</td>
                                                    <td class="py-2 px-4 border-b">{{ $log->height ? $log->height . 'cm' : 'N/A' }}</td>
                                                    <td class="py-2 px-4 border-b">{{ Str::limit($log->notes, 50) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="bg-gradient-to-r from-indigo-700 via-purple-700 to-pink-700">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-white sm:text-3xl sm:truncate">
                        Admin Dashboard
                    </h2>
                    <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                        <div class="mt-2 flex items-center text-sm text-indigo-100">
                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ now()->format('F j, Y') }}
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="{{ route('admin.users.create') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Add User
                    </a>
                    <a href="{{ route('admin.memberships.create') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Add Membership
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Stats Section -->
            <div class="mb-10">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Analytics Overview</h3>
                <dl class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Total Users Stat -->
                    <div class="relative bg-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow rounded-lg overflow-hidden">
                        <dt>
                            <div class="absolute bg-indigo-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <p class="ml-16 text-sm font-medium text-gray-500 truncate">Total Users</p>
                        </dt>
                        <dd class="ml-16 pb-6 flex items-baseline sm:pb-7">
                            <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\User::count() }}</p>
                            <div class="absolute bottom-0 inset-x-0 bg-gray-50 px-4 py-4 sm:px-6">
                                <div class="text-sm">
                                    <a href="{{ route('admin.users.index') }}" class="font-medium text-indigo-600 hover:text-indigo-500"> View all<span class="sr-only"> users</span></a>
                                </div>
                            </div>
                        </dd>
                    </div>

                    <!-- Total Plants Stat -->
                    <div class="relative bg-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow rounded-lg overflow-hidden">
                        <dt>
                            <div class="absolute bg-green-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                            </div>
                            <p class="ml-16 text-sm font-medium text-gray-500 truncate">Total Plants</p>
                        </dt>
                        <dd class="ml-16 pb-6 flex items-baseline sm:pb-7">
                            <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Plant::count() }}</p>
                            <div class="absolute bottom-0 inset-x-0 bg-gray-50 px-4 py-4 sm:px-6">
                                <div class="text-sm">
                                    <a href="{{ route('plants.index') }}" class="font-medium text-indigo-600 hover:text-indigo-500"> View all<span class="sr-only"> plants</span></a>
                                </div>
                            </div>
                        </dd>
                    </div>

                    <!-- Harvested Plants Stat -->
                    <div class="relative bg-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow rounded-lg overflow-hidden">
                        <dt>
                            <div class="absolute bg-yellow-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <p class="ml-16 text-sm font-medium text-gray-500 truncate">Harvested Plants</p>
                        </dt>
                        <dd class="ml-16 pb-6 flex items-baseline sm:pb-7">
                            <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Plant::where('status', 'harvested')->count() }}</p>
                            <div class="absolute bottom-0 inset-x-0 bg-gray-50 px-4 py-4 sm:px-6">
                                <div class="text-sm">
                                    <a href="{{ route('plants.index') }}" class="font-medium text-indigo-600 hover:text-indigo-500"> View harvested<span class="sr-only"> plants</span></a>
                                </div>
                            </div>
                        </dd>
                    </div>

                    <!-- Growth Logs Stat -->
                    <div class="relative bg-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow rounded-lg overflow-hidden">
                        <dt>
                            <div class="absolute bg-purple-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <p class="ml-16 text-sm font-medium text-gray-500 truncate">Growth Logs</p>
                        </dt>
                        <dd class="ml-16 pb-6 flex items-baseline sm:pb-7">
                            <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\GrowthLog::count() }}</p>
                            <div class="absolute bottom-0 inset-x-0 bg-gray-50 px-4 py-4 sm:px-6">
                                <div class="text-sm">
                                    <button onclick="openLogsModal('all')" class="font-medium text-indigo-600 hover:text-indigo-500"> View details<span class="sr-only"> of growth logs</span></button>
                                </div>
                            </div>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Recent Users & Plants Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Recent Users -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Users</h3>
                            <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                View all
                            </a>
                        </div>
                    </div>
                    @php
                        $recentUsers = \App\Models\User::latest()->take(5)->get();
                    @endphp
                    @if ($recentUsers->isEmpty())
                        <div class="px-6 py-4 text-center">
                            <p class="text-gray-500 text-sm">No users found.</p>
                        </div>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($recentUsers as $user)
                                <li>
                                    <div class="px-6 py-4 flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-purple-500 to-indigo-500 flex items-center justify-center">
                                                <span class="text-white font-medium text-sm">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <div class="flex justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                                </div>
                                                <div class="flex flex-col items-end">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                                        {{ ucfirst($user->role) }}
                                                    </span>
                                                    <p class="mt-1 text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-6 py-2 bg-gray-50 flex justify-end">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-xs text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                                        <a href="{{ route('admin.users.show', $user) }}" class="text-xs text-indigo-600 hover:text-indigo-900">View Details</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Recent Plants -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Plants</h3>
                            <a href="{{ route('plants.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                View all
                            </a>
                        </div>
                    </div>
                    @php
                        $recentPlants = \App\Models\Plant::with('users')->latest()->take(5)->get();
                    @endphp
                    @if ($recentPlants->isEmpty())
                        <div class="px-6 py-4 text-center">
                            <p class="text-gray-500 text-sm">No plants found.</p>
                        </div>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($recentPlants as $plant)
                                <li>
                                    <div class="px-6 py-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <a href="{{ route('plants.show', $plant) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                                    {{ $plant->strain }}
                                                </a>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                                        $plant->status === 'growing' ? 'bg-green-100 text-green-800' : 
                                                        ($plant->status === 'harvested' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') 
                                                    }}">
                                                        {{ ucfirst($plant->status) }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs text-gray-500">
                                                    Planted: <span class="font-medium">{{ $plant->planting_date->format('M d, Y') }}</span>
                                                </p>
                                                @if($plant->harvest_date)
                                                    <p class="text-xs text-gray-500 mt-0.5">
                                                        Harvested: <span class="font-medium">{{ $plant->harvest_date->format('M d, Y') }}</span>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <p class="text-xs text-gray-600 flex items-center">
                                                <svg class="h-4 w-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                Members: <span class="ml-1">{{ $plant->users->pluck('name')->join(', ') }}</span>
                                            </p>
                                            @php
                                                $logs = \App\Models\GrowthLog::where('plant_id', $plant->id)->count();
                                            @endphp
                                            <p class="text-xs text-gray-600 flex items-center mt-1">
                                                <svg class="h-4 w-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                Growth Logs: <span class="ml-1">{{ $logs }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="px-6 py-2 bg-gray-50 flex justify-end">
                                        <a href="{{ route('plants.edit', $plant) }}" class="text-xs text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                                        <a href="{{ route('plants.show', $plant) }}" class="text-xs text-indigo-600 hover:text-indigo-900">View Details</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <!-- Detailed Growth Logs -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Growth Logs Summary</h3>
                        <p class="mt-1 text-sm text-gray-500">A comprehensive view of all plant growth activities.</p>
                    </div>
                    @php
                        $recentLogs = \App\Models\GrowthLog::with(['plant.users'])
                            ->latest()
                            ->get()
                            ->groupBy(function($log) {
                                return $log->plant->strain;
                            });
                    @endphp
                    @if ($recentLogs->isEmpty())
                        <div class="px-6 py-4 text-center">
                            <p class="text-gray-500 text-sm">No growth logs found.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Strain
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Latest Update
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Current Phase
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Members
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Logs
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($recentLogs as $strain => $logs)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $strain }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $logs->first()->date->format('M d, Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ $logs->first()->date->diffForHumans() }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ 
                                                    $logs->first()->phase === 'seedling' ? 'bg-green-100 text-green-800' : 
                                                    ($logs->first()->phase === 'vegetative' ? 'bg-blue-100 text-blue-800' : 
                                                     ($logs->first()->phase === 'flowering' ? 'bg-purple-100 text-purple-800' : 
                                                      'bg-yellow-100 text-yellow-800')) 
                                                }}">
                                                    {{ ucfirst($logs->first()->phase) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $logs->first()->plant->users->pluck('name')->join(', ') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $logs->count() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button type="button" onclick="openLogsModal('{{ $strain }}')" class="text-indigo-600 hover:text-indigo-900">
                                                    View Details
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Growth Log Details -->
    <div id="logsModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full sm:p-6">
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" onclick="closeLogsModal()" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div>
                    <div class="mt-3 text-center sm:mt-0 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">
                            Growth Log Details
                        </h3>
                        <div class="mt-4">
                            <div id="modalContent" class="mt-2 max-h-[70vh] overflow-y-auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const logsData = @json($recentLogs->map(function($logs) {
            return $logs->map(function($log) {
                return [
                    'date' => $log->date->format('Y-m-d'),
                    'phase' => ucfirst($log->phase),
                    'height' => $log->height ? $log->height . 'cm' : 'N/A',
                    'notes' => $log->notes,
                    'members' => $log->plant->users->pluck('name')->join(', '),
                    'temperature' => $log->temperature ? $log->temperature . '°C' : 'N/A',
                    'humidity' => $log->humidity ? $log->humidity . '%' : 'N/A'
                ];
            });
        }));

        function openLogsModal(strain) {
            const modal = document.getElementById('logsModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalContent = document.getElementById('modalContent');
            
            if (strain === 'all') {
                modalTitle.textContent = 'All Growth Logs';
                
                // Combine all logs data
                let allLogs = [];
                Object.keys(logsData).forEach(key => {
                    logsData[key].forEach(log => {
                        allLogs.push({...log, strain: key});
                    });
                });
                
                // Sort by date descending
                allLogs.sort((a, b) => new Date(b.date) - new Date(a.date));
                
                let content = `
                    <div class="mb-4">
                        <p class="text-sm text-gray-500">Displaying comprehensive data for all plant growth activities.</p>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Strain</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phase</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Height</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Temperature</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Humidity</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                `;
                
                allLogs.forEach(log => {
                    content += `
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">${log.strain}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">${log.date}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                    log.phase === 'Seedling' ? 'bg-green-100 text-green-800' : 
                                    (log.phase === 'Vegetative' ? 'bg-blue-100 text-blue-800' : 
                                     (log.phase === 'Flowering' ? 'bg-purple-100 text-purple-800' : 
                                      'bg-yellow-100 text-yellow-800'))
                                }">
                                    ${log.phase}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">${log.height}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">${log.temperature}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">${log.humidity}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">${log.notes}</td>
                        </tr>
                    `;
                });
                
                content += `
                        </tbody>
                    </table>
                `;
                
                modalContent.innerHTML = content;
            } else {
                modalTitle.textContent = `Growth Logs for ${strain}`;
                
                const logs = logsData[strain];
                let content = `
                    <div class="mb-4">
                        <p class="text-sm text-gray-500">Members: ${logs[0].members}</p>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phase</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Height</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Temperature</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Humidity</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                `;
                
                logs.forEach(log => {
                    content += `
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">${log.date}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                    log.phase === 'Seedling' ? 'bg-green-100 text-green-800' : 
                                    (log.phase === 'Vegetative' ? 'bg-blue-100 text-blue-800' : 
                                     (log.phase === 'Flowering' ? 'bg-purple-100 text-purple-800' : 
                                      'bg-yellow-100 text-yellow-800'))
                                }">
                                    ${log.phase}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">${log.height}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">${log.temperature}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">${log.humidity}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">${log.notes}</td>
                        </tr>
                    `;
                });
                
                content += `
                        </tbody>
                    </table>
                `;
                
                modalContent.innerHTML = content;
            }
            
            modal.classList.remove('hidden');
        }

        function closeLogsModal() {
            const modal = document.getElementById('logsModal');
            modal.classList.add('hidden');
        }

        // Close modal when clicking outside content
        document.getElementById('logsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLogsModal();
            }
        });

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLogsModal();
            }
        });
    </script>
</x-app-layout> 
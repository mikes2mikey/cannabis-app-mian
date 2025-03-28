<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Membership Plan Details') }}
            </h2>
            <div>
                <a href="{{ route('admin.memberships.edit', $plan->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                    Edit Plan
                </a>
                <a href="{{ route('admin.memberships.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Plan Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm mb-1">Plan Name</p>
                                <p class="font-medium">{{ $plan->name }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm mb-1">Description</p>
                                <p class="font-medium">{{ $plan->description }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm mb-1">Price</p>
                                <p class="font-medium">R{{ number_format($plan->price, 2) }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm mb-1">Duration</p>
                                <p class="font-medium">{{ $plan->duration_days }} days</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm mb-1">Status</p>
                                <p class="font-medium">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm mb-1">Created On</p>
                                <p class="font-medium">{{ $plan->created_at->format('F j, Y') }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm mb-1">Last Updated</p>
                                <p class="font-medium">{{ $plan->updated_at->format('F j, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Members with this Plan</h3>
                    </div>
                    
                    @php
                        $users = $plan->users()->latest()->get();
                    @endphp
                    
                    @if($users->isEmpty())
                        <p class="text-gray-500">No members are currently using this plan.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left">Name</th>
                                        <th class="py-2 px-4 border-b text-left">Email</th>
                                        <th class="py-2 px-4 border-b text-left">Joined</th>
                                        <th class="py-2 px-4 border-b text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td class="py-2 px-4 border-b">{{ $user->name }}</td>
                                            <td class="py-2 px-4 border-b">{{ $user->email }}</td>
                                            <td class="py-2 px-4 border-b">{{ $user->created_at->format('Y-m-d') }}</td>
                                            <td class="py-2 px-4 border-b">
                                                <a href="{{ route('admin.users.show', $user->id) }}" class="text-blue-600 hover:text-blue-800 mr-2">View</a>
                                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
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
</x-app-layout> 
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User Details') }}
            </h2>
            <div>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                    Edit User
                </a>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Account Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm mb-1">Username</p>
                                <p class="font-medium">{{ $user->name }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm mb-1">Email</p>
                                <p class="font-medium">{{ $user->email }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm mb-1">Role</p>
                                <p class="font-medium">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        
                        <div>
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm mb-1">Member Since</p>
                                <p class="font-medium">{{ $user->created_at->format('F j, Y') }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm mb-1">Last Updated</p>
                                <p class="font-medium">{{ $user->updated_at->format('F j, Y') }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm mb-1">Membership Plan</p>
                                <p class="font-medium">
                                    @if($user->membershipPlan)
                                        {{ $user->membershipPlan->name }} (R{{ number_format($user->membershipPlan->price, 2) }})
                                    @else
                                        No Membership Plan
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Profile Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Personal Information</h3>
                    
                    @if($user->profile)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm mb-1">First Name</p>
                                <p class="font-medium">{{ $user->profile->first_name ?? 'Not set' }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm mb-1">Last Name</p>
                                <p class="font-medium">{{ $user->profile->last_name ?? 'Not set' }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm mb-1">Phone Number</p>
                                <p class="font-medium">{{ $user->profile->phone_number ?? 'Not set' }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm mb-1">ID Number / Passport</p>
                                <p class="font-medium">{{ $user->profile->id_number ?? 'Not set' }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm mb-1">Date of Birth</p>
                                <p class="font-medium">
                                    {{ $user->profile->date_of_birth ? $user->profile->date_of_birth->format('F j, Y') : 'Not set' }}
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm mb-1">Address</p>
                                <p class="font-medium">{{ $user->profile->address ?? 'Not set' }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm mb-1">City</p>
                                <p class="font-medium">{{ $user->profile->city ?? 'Not set' }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm mb-1">Postal Code</p>
                                <p class="font-medium">{{ $user->profile->postal_code ?? 'Not set' }}</p>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-yellow-50 p-4 rounded-md">
                        <p class="text-yellow-700">
                            This user doesn't have a profile yet. <a href="{{ route('admin.users.edit', $user->id) }}" class="font-medium underline">Create one now</a>.
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">User's Plants</h3>
                        <a href="{{ route('plants.create') }}" class="text-blue-600 hover:text-blue-800 text-sm">Add Plant</a>
                    </div>
                    
                    @php
                        $plants = $user->plants()->latest()->get();
                    @endphp
                    
                    @if($plants->isEmpty())
                        <p class="text-gray-500">No plants found for this user.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left">Strain</th>
                                        <th class="py-2 px-4 border-b text-left">Planting Date</th>
                                        <th class="py-2 px-4 border-b text-left">Status</th>
                                        <th class="py-2 px-4 border-b text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($plants as $plant)
                                        <tr>
                                            <td class="py-2 px-4 border-b">{{ $plant->strain }}</td>
                                            <td class="py-2 px-4 border-b">{{ $plant->planting_date->format('Y-m-d') }}</td>
                                            <td class="py-2 px-4 border-b">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ $plant->status === 'growing' ? 'bg-green-100 text-green-800' : 
                                                       ($plant->status === 'harvested' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ ucfirst($plant->status) }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-4 border-b">
                                                <a href="{{ route('plants.show', $plant) }}" class="text-blue-600 hover:text-blue-800 mr-2">View</a>
                                                <a href="{{ route('plants.edit', $plant) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
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
<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
            <span class="block">Join Our Cannabis Membership</span>
        </h1>
        <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
            Become a member of our exclusive cannabis community. Fill out the form below to register and select your preferred membership plan.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-md bg-red-50 p-4 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        There were problems with your submission
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('register.public') }}" class="space-y-8">
        @csrf

        <!-- Membership Plans Section -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white">
                    Select Your Membership Plan
                </h2>
                <p class="mt-1 text-indigo-100">
                    Choose the membership that best fits your needs
                </p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    @foreach($membershipPlans as $plan)
                        <div class="relative border rounded-lg hover:border-indigo-500 transition-colors duration-200 
                              @if(old('membership_plan_id') == $plan->id) ring-2 ring-indigo-500 bg-indigo-50 @else border-gray-200 @endif">
                            <label class="flex flex-col h-full p-5 cursor-pointer">
                                <input type="radio" name="membership_plan_id" value="{{ $plan->id }}"
                                    @if(old('membership_plan_id') == $plan->id) checked @endif
                                    class="sr-only peer" required>
                                <span class="absolute inset-0 rounded-lg peer-checked:ring-2 peer-checked:ring-indigo-500 peer-checked:bg-indigo-50"></span>
                                
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="block text-lg font-semibold text-gray-900">{{ $plan->name }}</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 mt-2 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Recommended
                                        </span>
                                    </div>
                                    <div class="relative">
                                        <div class="w-5 h-5 border-2 rounded-full flex items-center justify-center peer-checked:border-indigo-500
                                             @if(old('membership_plan_id') == $plan->id) bg-indigo-500 border-indigo-500 @else border-gray-300 @endif">
                                            @if(old('membership_plan_id') == $plan->id)
                                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 16 16">
                                                    <circle cx="8" cy="8" r="3" />
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <span class="my-4 block text-2xl font-bold text-indigo-600">R{{ number_format($plan->price, 2) }}</span>
                                
                                <p class="text-gray-600 text-sm flex-grow">{{ $plan->description }}</p>
                                
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <ul class="space-y-2 text-sm text-gray-500">
                                        <li class="flex items-center">
                                            <svg class="h-4 w-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            {{ $plan->duration_days }} days membership
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="h-4 w-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Full access to all features
                                        </li>
                                    </ul>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
                
                <x-input-error class="mt-2" :messages="$errors->get('membership_plan_id')" />
            </div>
        </div>

        <!-- Form Sections -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
            <!-- Account Information -->
            <div class="border-b border-gray-200">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Account Information
                    </h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <!-- Username -->
                    <div>
                        <x-input-label for="name" :value="__('Username')" class="text-sm font-medium text-gray-700" />
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <x-text-input id="name" class="block mt-1 w-full pl-10" type="text" name="name" :value="old('name')" required autofocus placeholder="johndoe" />
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700" />
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <x-text-input id="email" class="block mt-1 w-full pl-10" type="email" name="email" :value="old('email')" required placeholder="john@example.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700" />
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <x-text-input id="password" class="block mt-1 w-full pl-10" type="password" name="password" required autocomplete="new-password" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-medium text-gray-700" />
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <x-text-input id="password_confirmation" class="block mt-1 w-full pl-10" type="password" name="password_confirmation" required />
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div>
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Personal Information
                    </h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <!-- First & Last Name -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="first_name" :value="__('First Name')" class="text-sm font-medium text-gray-700" />
                            <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name')" required placeholder="John" />
                            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                        </div>

                        <div>
                            <x-input-label for="last_name" :value="__('Last Name')" class="text-sm font-medium text-gray-700" />
                            <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name')" required placeholder="Doe" />
                            <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <x-input-label for="phone_number" :value="__('Phone Number')" class="text-sm font-medium text-gray-700" />
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full pl-10" :value="old('phone_number')" required placeholder="+27 12 345 6789" />
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- ID Number -->
                        <div>
                            <x-input-label for="id_number" :value="__('ID Number / Passport Number')" class="text-sm font-medium text-gray-700" />
                            <x-text-input id="id_number" name="id_number" type="text" class="mt-1 block w-full" :value="old('id_number')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('id_number')" />
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <x-input-label for="date_of_birth" :value="__('Date of Birth')" class="text-sm font-medium text-gray-700" />
                            <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full" :value="old('date_of_birth')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <x-input-label for="address" :value="__('Address')" class="text-sm font-medium text-gray-700" />
                        <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('address')" />
                    </div>

                    <!-- City & Postal Code -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="city" :value="__('City')" class="text-sm font-medium text-gray-700" />
                            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('city')" />
                        </div>

                        <div>
                            <x-input-label for="postal_code" :value="__('Postal Code')" class="text-sm font-medium text-gray-700" />
                            <x-text-input id="postal_code" name="postal_code" type="text" class="mt-1 block w-full" :value="old('postal_code')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Terms & Submit -->
        <div class="flex flex-col md:flex-row items-center justify-between rounded-lg bg-gray-50 p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center mb-4 md:mb-0">
                <a class="text-sm text-indigo-600 hover:text-indigo-500 underline font-medium" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>
            </div>

            <div>
                <button type="submit" class="group relative flex justify-center py-3 px-6 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </span>
                    {{ __('Proceed to Payment') }}
                </button>
            </div>
        </div>
    </form>
</x-guest-layout> 
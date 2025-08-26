@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Profile Settings
            </h1>
        </div>
    </div>

    <!-- Main Content -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6
                    @if (session('status') === 'profile-updated')
                        bg-green-100 border-l-4 border-green-500 p-4 mb-6"
                        x-data="{ show: true }"
                        x-show="show"
                        x-init="setTimeout(() => show = false, 3000)"
                    @endif">
                    @if (session('status') === 'profile-updated')
                        <p class="text-green-700">Profile updated successfully!</p>
                    @endif
                </div>

                <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Name -->
                        <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                {{ __('Name') }}
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                    class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                {{ __('Email') }}
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" 
                                    class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Update Section -->
                        <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <div class="sm:col-span-3">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    {{ __('Update Password') }}
                                </h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                    {{ __('Ensure your account is using a long, random password to stay secure.') }}
                                </p>
                            </div>
                        </div>

                        <!-- Current Password -->
                        <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <label for="current_password" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                {{ __('Current Password') }}
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <input id="current_password" name="current_password" type="password" 
                                    class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md">
                                @error('current_password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- New Password -->
                        <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <label for="password" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                {{ __('New Password') }}
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <input id="password" name="password" type="password" 
                                    class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md">
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                {{ __('Confirm Password') }}
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <input id="password_confirmation" name="password_confirmation" type="password" 
                                    class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Account Section -->
            <div class="mt-10 sm:mt-0">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            {{ __('Delete Account') }}
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                        </p>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <form method="POST" action="{{ route('profile.destroy') }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" 
                                    onclick="if(confirm('Are you sure you want to delete your account? This action cannot be undone.')) { this.form.submit(); }"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                {{ __('Delete Account') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

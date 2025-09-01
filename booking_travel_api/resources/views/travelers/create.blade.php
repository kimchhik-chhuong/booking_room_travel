@extends('layouts.dashboard')

@section('title', 'Add New Traveler')
@section('page-title', 'Add New Traveler')

@section('content')
<div class="min-h-screen">
    @include('partials.sidebar')
    @include('partials.header')

    <div class="ml-72 pt-32 p-8">
        <div class="max-w-4xl mx-auto">
            <div class="card-modern">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-slate-800 mb-6">Add New Traveler</h2>
                    
                    <form action="{{ route('travelers.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Booking Selection -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Booking *</label>
                                <select name="booking_id" required
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select a booking</option>
                                    @foreach(\App\Models\Booking::latest()->get() as $booking)
                                        <option value="{{ $booking->id }}">
                                            Booking #{{ $booking->id }} - {{ $booking->user->name ?? 'No User' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('booking_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- First Name -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">First Name *</label>
                                <input type="text" name="first_name" required
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ old('first_name') }}">
                                @error('first_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Last Name -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Last Name *</label>
                                <input type="text" name="last_name" required
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ old('last_name') }}">
                                @error('last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Email -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Email *</label>
                                <input type="email" name="email" required
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Phone -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Phone</label>
                                <input type="tel" name="phone"
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ old('phone') }}">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Nationality -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nationality</label>
                                <input type="text" name="nationality"
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ old('nationality') }}">
                                @error('nationality')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-4 mt-8">
                            <a href="{{ route('travelers.index') }}" 
                               class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                Save Traveler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
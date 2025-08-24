@extends('layouts.app')

@section('title', 'Edit Traveler')
@section('content')
<div class="max-w-2xl mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-4">Edit Traveler</h1>

    <form method="POST" action="{{ route('travelers.update', $traveler) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label>First Name</label>
            <input type="text" name="first_name" value="{{ old('first_name', $traveler->first_name) }}" class="border px-2 py-1 w-full rounded">
            @error('first_name') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label>Last Name</label>
            <input type="text" name="last_name" value="{{ old('last_name', $traveler->last_name) }}" class="border px-2 py-1 w-full rounded">
            @error('last_name') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $traveler->email) }}" class="border px-2 py-1 w-full rounded">
            @error('email') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label>Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $traveler->phone) }}" class="border px-2 py-1 w-full rounded">
            @error('phone') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label>Date of Birth</label>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $traveler->date_of_birth?->format('Y-m-d')) }}" class="border px-2 py-1 w-full rounded">
            @error('date_of_birth') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label>Passport Number</label>
            <input type="text" name="passport_number" value="{{ old('passport_number', $traveler->passport_number) }}" class="border px-2 py-1 w-full rounded">
            @error('passport_number') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label>Nationality</label>
            <input type="text" name="nationality" value="{{ old('nationality', $trav

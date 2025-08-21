@extends('layouts.app')

@section('title', 'Add Traveler')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Add Traveler</h1>

    @if($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('travelers.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block mb-1">Name</label>
            <input type="text" name="name" class="border px-2 py-1 w-full" value="{{ old('name') }}">
        </div>
        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="email" class="border px-2 py-1 w-full" value="{{ old('email') }}">
        </div>
        <div class="mb-4">
            <label class="block mb-1">Phone</label>
            <input type="text" name="phone_number" class="border px-2 py-1 w-full" value="{{ old('phone_number') }}">
        </div>
        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">Save</button>
    </form>
</div>
@endsection

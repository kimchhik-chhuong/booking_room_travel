@extends('layouts.dashboard')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold">{{ $package->title }}</h2>
    <p><strong>Location:</strong> {{ $package->location }}</p>
    <p><strong>Duration:</strong> {{ $package->duration }}</p>
    <p><strong>Price:</strong> ${{ $package->price }}</p>
    <p><strong>Status:</strong> {{ $package->status }}</p>
    @if($package->image)
        <img src="{{ asset('storage/' . $package->image) }}" class="w-64 mt-4">
    @endif
</div>
@endsection

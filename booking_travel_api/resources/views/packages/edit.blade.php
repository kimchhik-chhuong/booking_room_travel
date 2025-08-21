@extends('layouts.dashboard')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">Edit Package</h2>
    <form action="{{ route('packages.update', $package->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="text" name="title" value="{{ $package->title }}" class="input-modern mb-3" required>
        <input type="text" name="location" value="{{ $package->location }}" class="input-modern mb-3" required>
        <input type="text" name="duration" value="{{ $package->duration }}" class="input-modern mb-3" required>
        <input type="number" name="price" value="{{ $package->price }}" class="input-modern mb-3" required>
        
        <select name="status" class="input-modern mb-3" required>
            <option value="Active" {{ $package->status=='Active' ? 'selected' : '' }}>Active</option>
            <option value="Draft" {{ $package->status=='Draft' ? 'selected' : '' }}>Draft</option>
        </select>
        
        <input type="file" name="image" class="mb-3">
        
        <button class="btn-modern">Update</button>
    </form>
</div>
@endsection

@extends('layouts.dashboard')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">Add Package</h2>
    <form action="{{ route('packages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" name="title" placeholder="Title" class="input-modern mb-3" required>
        <input type="text" name="location" placeholder="Location" class="input-modern mb-3" required>
        <input type="text" name="duration" placeholder="Duration" class="input-modern mb-3" required>
        <input type="number" name="price" placeholder="Price" class="input-modern mb-3" required>
        
        <select name="status" class="input-modern mb-3" required>
            <option value="Active">Active</option>
            <option value="Draft">Draft</option>
        </select>
        
        <input type="file" name="image" class="mb-3">
        
        <button class="btn-modern">Save</button>
    </form>
</div>
@endsection

@extends('layouts.dashboard')

@section('title', 'Add Deal')
@section('page-title', 'Add New Deal')
@section('page-subtitle', 'Create a special travel offer.')

@section('content')
<div class="min-h-screen bg-gray-50">
    @include('partials.sidebar')
    @include('partials.header')

    <div class="ml-64 mr-80 p-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <form action="{{ route('deals.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700">Deal Name</label>
                    <input type="text" name="name" class="w-full border rounded p-2 mt-1" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Discount</label>
                    <input type="text" name="discount" class="w-full border rounded p-2 mt-1" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Applicable Packages</label>
                    <input type="text" name="packages" class="w-full border rounded p-2 mt-1" required>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700">Start Date</label>
                        <input type="date" name="start_date" class="w-full border rounded p-2 mt-1" required>
                    </div>
                    <div>
                        <label class="block text-gray-700">End Date</label>
                        <input type="date" name="end_date" class="w-full border rounded p-2 mt-1" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Status</label>
                    <select name="status" class="w-full border rounded p-2 mt-1" required>
                        <option value="Active">Active</option>
                        <option value="Expired">Expired</option>
                    </select>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Create Deal
                    </button>
                </div>
            </form>
        </div>
    </div>

    @include('partials.right-sidebar')
</div>
@endsection

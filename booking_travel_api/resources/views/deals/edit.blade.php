@extends('layouts.dashboard')

@section('title', 'Edit Deal')
@section('page-title', 'Edit Deal')
@section('page-subtitle', 'Update existing deal details.')

@section('content')
<div class="min-h-screen bg-gray-50">
    @include('partials.sidebar')
    @include('partials.header')

    <div class="ml-64 mr-80 p-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <form action="{{ route('deals.update', $deal->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Deal Name --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1">Deal Name</label>
                    <input type="text" name="name" value="{{ old('name', $deal->name) }}"
                           class="w-full border rounded p-2" required>
                </div>

                {{-- Discount --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1">Discount</label>
                    <input type="text" name="discount" value="{{ old('discount', $deal->discount) }}"
                           class="w-full border rounded p-2" required>
                </div>

                {{-- Packages --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1">Applicable Packages</label>
                    <input type="text" name="packages" value="{{ old('packages', $deal->packages) }}"
                           class="w-full border rounded p-2" required>
                </div>

                {{-- Dates --}}
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $deal->start_date) }}"
                               class="w-full border rounded p-2" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $deal->end_date) }}"
                               class="w-full border rounded p-2" required>
                    </div>
                </div>

                {{-- Status --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1">Status</label>
                    <select name="status" class="w-full border rounded p-2" required>
                        <option value="Active" {{ $deal->status == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Expired" {{ $deal->status == 'Expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>

                {{-- Submit --}}
                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                        Update Deal
                    </button>
                </div>
            </form>
        </div>
    </div>

    @include('partials.right-sidebar')
</div>
@endsection

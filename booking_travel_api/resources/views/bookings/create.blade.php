@extends('layouts.dashboard')

@section('title', 'Add Booking')
@section('page-title', 'Add New Booking')
@section('page-subtitle', 'Create a new travel booking.')

@section('content')
<div class="min-h-screen ml-72 p-8">
    <div class="card-modern p-8">
        <h3 class="text-2xl font-bold text-dark-800 mb-6">Add New Booking</h3>
        <form action="{{ route('bookings.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-dark-600 mb-1" for="customer">Customer</label>
                    <input type="text" name="customer" id="customer" class="input-modern w-full" placeholder="Enter customer name" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-600 mb-1" for="email">Email</label>
                    <input type="email" name="email" id="email" class="input-modern w-full" placeholder="Enter email" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-600 mb-1" for="package">Package</label>
                    <input type="text" name="package" id="package" class="input-modern w-full" placeholder="Enter package name" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-600 mb-1" for="dates">Dates</label>
                    <input type="text" name="dates" id="dates" class="input-modern w-full" placeholder="e.g., Aug 15 - Aug 22" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-600 mb-1" for="amount">Amount</label>
                    <input type="text" name="amount" id="amount" class="input-modern w-full" placeholder="e.g., $2,450" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-600 mb-1" for="status">Status</label>
                    <select name="status" id="status" class="input-modern w-full" required>
                        <option value="Confirmed">Confirmed</option>
                        <option value="Pending">Pending</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end space-x-4">
                <a href="{{ route('bookings.index') }}" class="btn-modern bg-red-500 text-white">Cancel</a>
                <button type="submit" class="btn-modern bg-emerald-500 text-white">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
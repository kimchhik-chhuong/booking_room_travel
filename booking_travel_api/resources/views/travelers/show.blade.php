@extends('layouts.app')

@section('title', 'Traveler Details - Travelie')

@section('content')
<div class="container mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Traveler Details</h2>

    {{-- Traveler Info --}}
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <div class="flex items-center space-x-6">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($traveler->full_name) }}&background=random&size=80" 
                 alt="{{ $traveler->full_name }}" class="w-20 h-20 rounded-full">
            <div>
                <h3 class="text-xl font-semibold">{{ $traveler->full_name }}</h3>
                <p class="text-gray-500">{{ $traveler->email }}</p>
                <p class="text-gray-500">{{ $traveler->phone ?? 'No phone provided' }}</p>
                <p class="text-gray-500">Status: 
                    <span class="font-semibold text-{{ $traveler->status == 'active' ? 'green' : 'gray' }}-600">
                        {{ ucfirst($traveler->status) }}
                    </span>
                </p>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <p><strong>Date of Birth:</strong> {{ $traveler->date_of_birth?->format('d M Y') ?? 'N/A' }}</p>
            <p><strong>Passport Number:</strong> {{ $traveler->passport_number ?? 'N/A' }}</p>
            <p><strong>Nationality:</strong> {{ $traveler->nationality ?? 'N/A' }}</p>
            <p><strong>Address:</strong> {{ $traveler->address ?? 'N/A' }}</p>
        </div>

        <div class="mt-4 flex space-x-4">
            <a href="{{ route('travelers.edit', $traveler) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Edit Traveler</a>
            <form action="{{ route('travelers.destroy', $traveler) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this traveler?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete Traveler</button>
            </form>
        </div>
    </div>

    {{-- Traveler Bookings --}}
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h3 class="text-xl font-semibold mb-4">Bookings</h3>

        @if($traveler->bookings->isEmpty())
            <p class="text-gray-500">No bookings found for this traveler.</p>
        @else
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2">Package</th>
                        <th class="border px-4 py-2">Booking Date</th>
                        <th class="border px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($traveler->bookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">{{ $booking->package?->name ?? 'N/A' }}</td>
                            <td class="border px-4 py-2">{{ $booking->created_at->format('d M Y') }}</td>
                            <td class="border px-4 py-2">{{ ucfirst($booking->status) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Traveler Messages --}}
    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-xl font-semibold mb-4">Messages</h3>

        @if($traveler->messages->isEmpty())
            <p class="text-gray-500">No messages from this traveler.</p>
        @else
            <ul class="space-y-2">
                @foreach($traveler->messages as $message)
                    <li class="border rounded p-3">
                        <p class="text-gray-700">{{ $message->content }}</p>
                        <p class="text-sm text-gray-400 mt-1">Sent on {{ $message->created_at->format('d M Y H:i') }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection

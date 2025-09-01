@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Traveler Details</h4>
                    <div class="btn-group">
                        <a href="{{ route('travelers.edit', $traveler->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('travelers.index') }}" class="btn btn-sm btn-secondary ml-2">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-4 font-weight-bold">Full Name:</div>
                        <div class="col-md-8">{{ $traveler->first_name }} {{ $traveler->last_name }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 font-weight-bold">Email:</div>
                        <div class="col-md-8">
                            <a href="mailto:{{ $traveler->email }}">{{ $traveler->email }}</a>
                        </div>
                    </div>

                    @if($traveler->phone)
                    <div class="row mb-4">
                        <div class="col-md-4 font-weight-bold">Phone:</div>
                        <div class="col-md-8">
                            <a href="tel:{{ $traveler->phone }}">{{ $traveler->phone }}</a>
                        </div>
                    </div>
                    @endif

                    @if($traveler->date_of_birth)
                    <div class="row mb-4">
                        <div class="col-md-4 font-weight-bold">Date of Birth:</div>
                        <div class="col-md-8">{{ $traveler->date_of_birth->format('F j, Y') }}</div>
                    </div>
                    @endif

                    @if($traveler->nationality)
                    <div class="row mb-4">
                        <div class="col-md-4 font-weight-bold">Nationality:</div>
                        <div class="col-md-8">{{ $traveler->nationality }}</div>
                    </div>
                    @endif

                    @if($traveler->passport_number)
                    <div class="row mb-4">
                        <div class="col-md-4 font-weight-bold">Passport Number:</div>
                        <div class="col-md-8">{{ $traveler->passport_number }}</div>
                    </div>
                    @endif

                    @if($traveler->passport_expiry)
                    <div class="row mb-4">
                        <div class="col-md-4 font-weight-bold">Passport Expiry:</div>
                        <div class="col-md-8">
                            {{ $traveler->passport_expiry->format('F j, Y') }}
                            @if($traveler->passport_expiry->isPast())
                                <span class="badge badge-danger ml-2">Expired</span>
                            @elseif($traveler->passport_expiry->diffInMonths(now()) < 6)
                                <span class="badge badge-warning ml-2">Expiring Soon</span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4 font-weight-bold">Created At:</div>
                        <div class="col-md-8">{{ $traveler->created_at->format('F j, Y \a\t g:i A') }}</div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <small class="text-muted">
                        Last Updated: {{ $traveler->updated_at->diffForHumans() }}
                    </small>
                    <form action="{{ route('travelers.destroy', $traveler->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this traveler? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Delete Traveler
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

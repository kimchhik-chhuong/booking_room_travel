{{-- resources/views/packages/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Packages Dashboard')

@section('content')
<div class="container">
    <h1 class="my-4">📦 Packages Dashboard</h1>

    {{-- Statistics --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Packages</div>
                <div class="card-body">
                    <h4 class="card-title">{{ $totalPackages }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">New This Month</div>
                <div class="card-body">
                    <h4 class="card-title">{{ $newThisMonth }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Packages Table --}}
    <div class="card">
        <div class="card-header">
            Package List
        </div>
        <div class="card-body">
            @if($packages->count() > 0)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Package Name</th>
                            <th>Price</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($packages as $package)
                            <tr>
                                <td>{{ $package->id }}</td>
                                <td>{{ $package->name }}</td>
                                <td>${{ number_format($package->price, 2) }}</td>
                                <td>{{ $package->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">No packages available.</p>
            @endif
        </div>
    </div>
</div>
@endsection

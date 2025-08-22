@extends('layouts.dashboard')

@section('title', 'Create Deal')
@section('page-title', 'New Deal')
@section('page-subtitle', 'Add a new promotional deal.')

@section('content')
<div class="ml-72 p-8">
    <div class="card-modern p-8">
        <form action="{{ route('deals.store') }}" method="POST">
            @csrf
            @include('deals.form', ['deal' => null])
            <div class="flex justify-end mt-6">
                <a href="{{ route('deals.index') }}" class="btn-modern bg-slate-200 text-dark-700 mr-2">Cancel</a>
                <button type="submit" class="btn-modern bg-primary-600 text-white">Save Deal</button>
            </div>
        </form>
    </div>
</div>
@endsection

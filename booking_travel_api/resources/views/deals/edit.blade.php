@extends('layouts.dashboard')

@section('title', 'Edit Deal')
@section('page-title', 'Edit Deal')
@section('page-subtitle', 'Update the selected deal.')

@section('content')
<div class="ml-72 p-8">
    <div class="card-modern p-8">
        <form action="{{ route('deals.update', $deal->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('deals.form', ['deal' => $deal])
            <div class="flex justify-end mt-6">
                <a href="{{ route('deals.index') }}" class="btn-modern bg-slate-200 text-dark-700 mr-2">Cancel</a>
                <button type="submit" class="btn-modern bg-primary-600 text-white">Update Deal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.dashboard')

@section('title', $title ?? 'Deal Form')

@section('content')
<div class="min-h-screen">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-72 p-8">
        <div class="card-modern">
            <div class="p-8 border-b border-slate-200">
                <h3 class="text-2xl font-bold text-dark-800 mb-2">{{ $title }}</h3>
                <p class="text-slate-600">{{ $title === 'Create New Deal' ? 'Add a new special offer or discount' : 'Update the deal details' }}</p>
            </div>

            <div class="p-8">
                <form action="{{ $action }}" method="POST" class="space-y-6">
                    @csrf
                    @method($method)
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($formData as $field => $options)
                            <div class="form-group {{ $options['type'] === 'textarea' ? 'md:col-span-2' : '' }}">
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    {{ $options['label'] }}
                                    @if(isset($options['required']) && $options['required'])
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                
                                @if($options['type'] === 'select')
                                    <select name="{{ $field }}" 
                                            class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            {{ isset($options['required']) && $options['required'] ? 'required' : '' }}>
                                        @foreach($options['options'] as $value => $label)
                                            <option value="{{ $value }}" {{ (isset($deal) && $deal->$field === $value) || old($field) === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                @elseif($options['type'] === 'textarea')
                                    <textarea name="{{ $field }}" 
                                              rows="4"
                                              class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                              placeholder="{{ $options['placeholder'] ?? '' }}"
                                              {{ isset($options['required']) && $options['required'] ? 'required' : '' }}>{{ old($field) ?? ($deal->$field ?? '') }}</textarea>
                                @else
                                    <input type="{{ $options['type'] }}" 
                                           name="{{ $field }}" 
                                           value="{{ old($field) ?? ($deal->$field ?? '') }}" 
                                           class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="{{ $options['placeholder'] ?? '' }}"
                                           {{ isset($options['required']) && $options['required'] ? 'required' : '' }}
                                           {{ $options['type'] === 'number' ? 'min="0"' : '' }}
                                           {{ $options['type'] === 'date' ? 'min="' . now()->format('Y-m-d') . '"' : '' }}>
                                @endif
                                
                                @error($field)
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200 mt-8">
                        <a href="{{ route('deals.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            {{ $title === 'Create New Deal' ? 'Create Deal' : 'Update Deal' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .btn {
        @apply px-4 py-2 rounded-md font-medium transition-colors duration-200;
    }
    
    .btn-primary {
        @apply bg-blue-600 text-white hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
    
    .btn-secondary {
        @apply bg-slate-200 text-slate-800 hover:bg-slate-300 focus:ring-2 focus:ring-offset-2 focus:ring-slate-500;
    }
</style>
@endpush
@endsection

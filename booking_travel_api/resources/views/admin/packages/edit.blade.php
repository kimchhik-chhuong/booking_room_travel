@extends('layouts.dashboard')

@section('title', 'Edit Package')
@section('page-title', 'Edit Package')
@section('page-subtitle', 'Update package information and settings.')

@section('content')
<div class="min-h-screen">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <div class="ml-72 p-8">
        <form method="POST" action="{{ route('admin.packages.update', $package) }}" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="card-modern p-8">
                <h3 class="text-2xl font-bold text-dark-800 mb-6">Basic Information</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-dark-700 mb-2">Package Title *</label>
                        <input type="text" name="title" value="{{ old('title', $package->title) }}" 
                               class="input-modern w-full @error('title') border-red-500 @enderror" 
                               placeholder="Enter package title" required>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Category *</label>
                        <select name="category_id" class="input-modern w-full @error('category_id') border-red-500 @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $package->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Destination *</label>
                        <select name="destination_id" class="input-modern w-full @error('destination_id') border-red-500 @enderror" required>
                            <option value="">Select Destination</option>
                            @foreach($destinations as $destination)
                                <option value="{{ $destination->id }}" {{ old('destination_id', $package->destination_id) == $destination->id ? 'selected' : '' }}>
                                    {{ $destination->name }}, {{ $destination->country }}
                                </option>
                            @endforeach
                        </select>
                        @error('destination_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-dark-700 mb-2">Short Description *</label>
                        <textarea name="short_description" rows="3" 
                                  class="input-modern w-full @error('short_description') border-red-500 @enderror" 
                                  placeholder="Brief description of the package" required>{{ old('short_description', $package->short_description) }}</textarea>
                        @error('short_description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-dark-700 mb-2">Full Description *</label>
                        <textarea name="description" rows="6" 
                                  class="input-modern w-full @error('description') border-red-500 @enderror" 
                                  placeholder="Detailed description of the package" required>{{ old('description', $package->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pricing & Duration -->
            <div class="card-modern p-8">
                <h3 class="text-2xl font-bold text-dark-800 mb-6">Pricing & Duration</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Price *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-500">$</span>
                            <input type="number" name="price" value="{{ old('price', $package->price) }}" step="0.01" min="0"
                                   class="input-modern w-full pl-8 @error('price') border-red-500 @enderror" 
                                   placeholder="0.00" required>
                        </div>
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Original Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-dark-500">$</span>
                            <input type="number" name="original_price" value="{{ old('original_price', $package->original_price) }}" step="0.01" min="0"
                                   class="input-modern w-full pl-8 @error('original_price') border-red-500 @enderror" 
                                   placeholder="0.00">
                        </div>
                        @error('original_price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Duration (Days) *</label>
                        <input type="number" name="duration_days" value="{{ old('duration_days', $package->duration_days) }}" min="1" max="365"
                               class="input-modern w-full @error('duration_days') border-red-500 @enderror" 
                               placeholder="7" required>
                        @error('duration_days')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Duration (Nights) *</label>
                        <input type="number" name="duration_nights" value="{{ old('duration_nights', $package->duration_nights) }}" min="0" max="364"
                               class="input-modern w-full @error('duration_nights') border-red-500 @enderror" 
                               placeholder="6" required>
                        @error('duration_nights')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Min Participants *</label>
                        <input type="number" name="min_participants" value="{{ old('min_participants', $package->min_participants) }}" min="1"
                               class="input-modern w-full @error('min_participants') border-red-500 @enderror" required>
                        @error('min_participants')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Max Participants *</label>
                        <input type="number" name="max_participants" value="{{ old('max_participants', $package->max_participants) }}" min="1"
                               class="input-modern w-full @error('max_participants') border-red-500 @enderror" required>
                        @error('max_participants')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Difficulty Level *</label>
                        <select name="difficulty_level" class="input-modern w-full @error('difficulty_level') border-red-500 @enderror" required>
                            <option value="easy" {{ old('difficulty_level', $package->difficulty_level) === 'easy' ? 'selected' : '' }}>Easy</option>
                            <option value="moderate" {{ old('difficulty_level', $package->difficulty_level) === 'moderate' ? 'selected' : '' }}>Moderate</option>
                            <option value="challenging" {{ old('difficulty_level', $package->difficulty_level) === 'challenging' ? 'selected' : '' }}>Challenging</option>
                        </select>
                        @error('difficulty_level')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Advance Booking (Days)</label>
                        <input type="number" name="advance_booking_days" value="{{ old('advance_booking_days', $package->advance_booking_days) }}" min="0" max="365"
                               class="input-modern w-full @error('advance_booking_days') border-red-500 @enderror">
                        @error('advance_booking_days')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Current Images -->
            <div class="card-modern p-8">
                <h3 class="text-2xl font-bold text-dark-800 mb-6">Current Images</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Current Featured Image -->
                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Current Featured Image</label>
                        @if($package->featured_image)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $package->featured_image) }}" alt="Featured Image" 
                                     class="w-full h-48 object-cover rounded-lg">
                                <div class="absolute top-2 right-2">
                                    <span class="bg-green-500 text-white px-2 py-1 rounded text-xs">Current</span>
                                </div>
                            </div>
                        @else
                            <div class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                                <span class="text-gray-500">No featured image</span>
                            </div>
                        @endif
                    </div>

                    <!-- Current Gallery -->
                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Current Gallery</label>
                        @if($package->gallery && count($package->gallery) > 0)
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($package->gallery as $image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="Gallery" 
                                         class="w-full h-24 object-cover rounded-lg">
                                @endforeach
                            </div>
                        @else
                            <div class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                                <span class="text-gray-500">No gallery images</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Upload New Images -->
                <h4 class="text-lg font-semibold text-dark-800 mb-4">Upload New Images (Optional)</h4>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">New Featured Image</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary-500 transition-colors">
                            <input type="file" name="featured_image" accept="image/*" class="hidden" id="featured-image">
                            <label for="featured-image" class="cursor-pointer">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                <p class="text-gray-600">Click to upload new featured image</p>
                                <p class="text-sm text-gray-500 mt-2">PNG, JPG, WEBP up to 2MB</p>
                            </label>
                        </div>
                        @error('featured_image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">New Gallery Images</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary-500 transition-colors">
                            <input type="file" name="gallery[]" accept="image/*" multiple class="hidden" id="gallery-images">
                            <label for="gallery-images" class="cursor-pointer">
                                <i class="fas fa-images text-4xl text-gray-400 mb-4"></i>
                                <p class="text-gray-600">Click to upload new gallery images</p>
                                <p class="text-sm text-gray-500 mt-2">Multiple images, max 10 files</p>
                            </label>
                        </div>
                        @error('gallery')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Package Details -->
            <div class="card-modern p-8">
                <h3 class="text-2xl font-bold text-dark-800 mb-6">Package Details</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Accommodation Type</label>
                        <input type="text" name="accommodation_type" value="{{ old('accommodation_type', $package->accommodation_type) }}"
                               class="input-modern w-full @error('accommodation_type') border-red-500 @enderror" 
                               placeholder="e.g., Luxury Resort, Hotel">
                        @error('accommodation_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Meal Plan</label>
                        <select name="meal_plan" class="input-modern w-full @error('meal_plan') border-red-500 @enderror">
                            <option value="">Select Meal Plan</option>
                            <option value="breakfast" {{ old('meal_plan', $package->meal_plan) === 'breakfast' ? 'selected' : '' }}>Breakfast Only</option>
                            <option value="half-board" {{ old('meal_plan', $package->meal_plan) === 'half-board' ? 'selected' : '' }}>Half Board</option>
                            <option value="full-board" {{ old('meal_plan', $package->meal_plan) === 'full-board' ? 'selected' : '' }}>Full Board</option>
                            <option value="all-inclusive" {{ old('meal_plan', $package->meal_plan) === 'all-inclusive' ? 'selected' : '' }}>All Inclusive</option>
                        </select>
                        @error('meal_plan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Transportation</label>
                        <input type="text" name="transportation" value="{{ old('transportation', $package->transportation) }}"
                               class="input-modern w-full @error('transportation') border-red-500 @enderror" 
                               placeholder="e.g., Private car, Bus">
                        @error('transportation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Inclusions & Exclusions -->
            <div class="card-modern p-8">
                <h3 class="text-2xl font-bold text-dark-800 mb-6">Inclusions & Exclusions</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">What's Included</label>
                        <div id="inclusions-container">
                            @if($package->inclusions)
                                @foreach($package->inclusions as $inclusion)
                                <div class="inclusion-item flex items-center space-x-2 mb-2">
                                    <input type="text" name="inclusions[]" value="{{ $inclusion }}" class="input-modern flex-1">
                                    <button type="button" onclick="removeItem(this)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                @endforeach
                            @else
                                <div class="inclusion-item flex items-center space-x-2 mb-2">
                                    <input type="text" name="inclusions[]" class="input-modern flex-1" placeholder="e.g., Accommodation">
                                    <button type="button" onclick="removeItem(this)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addInclusion()" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                            <i class="fas fa-plus mr-1"></i> Add Inclusion
                        </button>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">What's Excluded</label>
                        <div id="exclusions-container">
                            @if($package->exclusions)
                                @foreach($package->exclusions as $exclusion)
                                <div class="exclusion-item flex items-center space-x-2 mb-2">
                                    <input type="text" name="exclusions[]" value="{{ $exclusion }}" class="input-modern flex-1">
                                    <button type="button" onclick="removeItem(this)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                @endforeach
                            @else
                                <div class="exclusion-item flex items-center space-x-2 mb-2">
                                    <input type="text" name="exclusions[]" class="input-modern flex-1" placeholder="e.g., International flights">
                                    <button type="button" onclick="removeItem(this)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addExclusion()" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                            <i class="fas fa-plus mr-1"></i> Add Exclusion
                        </button>
                    </div>
                </div>
            </div>

            <!-- Highlights -->
            <div class="card-modern p-8">
                <h3 class="text-2xl font-bold text-dark-800 mb-6">Package Highlights</h3>
                
                <div id="highlights-container">
                    @if($package->highlights)
                        @foreach($package->highlights as $highlight)
                        <div class="highlight-item flex items-center space-x-2 mb-2">
                            <input type="text" name="highlights[]" value="{{ $highlight }}" class="input-modern flex-1">
                            <button type="button" onclick="removeItem(this)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        @endforeach
                    @else
                        <div class="highlight-item flex items-center space-x-2 mb-2">
                            <input type="text" name="highlights[]" class="input-modern flex-1" placeholder="e.g., Visit famous landmarks">
                            <button type="button" onclick="removeItem(this)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                </div>
                <button type="button" onclick="addHighlight()" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                    <i class="fas fa-plus mr-1"></i> Add Highlight
                </button>
            </div>

            <!-- Amenities -->
            <div class="card-modern p-8">
                <h3 class="text-2xl font-bold text-dark-800 mb-6">Amenities</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($amenities as $amenity)
                    <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" 
                               class="rounded border-gray-300" 
                               {{ $package->amenities->contains($amenity->id) ? 'checked' : '' }}>
                        <div class="flex items-center space-x-2">
                            @if($amenity->icon)
                                <i class="{{ $amenity->icon }} text-primary-600"></i>
                            @endif
                            <span class="text-sm font-medium text-dark-700">{{ $amenity->name }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Settings -->
            <div class="card-modern p-8">
                <h3 class="text-2xl font-bold text-dark-800 mb-6">Package Settings</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Status</label>
                        <select name="status" class="input-modern w-full @error('status') border-red-500 @enderror" required>
                            <option value="draft" {{ old('status', $package->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $package->status) === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ old('status', $package->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Available From</label>
                        <input type="date" name="available_from" value="{{ old('available_from', $package->available_from?->format('Y-m-d')) }}"
                               class="input-modern w-full @error('available_from') border-red-500 @enderror">
                        @error('available_from')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-2">Available Until</label>
                        <input type="date" name="available_until" value="{{ old('available_until', $package->available_until?->format('Y-m-d')) }}"
                               class="input-modern w-full @error('available_until') border-red-500 @enderror">
                        @error('available_until')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <div class="flex flex-wrap gap-4">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300" {{ old('is_active', $package->is_active) ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-dark-700">Active</span>
                        </label>

                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300" {{ old('is_featured', $package->is_featured) ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-dark-700">Featured</span>
                        </label>

                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="is_popular" value="1" class="rounded border-gray-300" {{ old('is_popular', $package->is_popular) ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-dark-700">Popular</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Tags -->
            <div class="card-modern p-8">
                <h3 class="text-2xl font-bold text-dark-800 mb-6">Tags</h3>
                
                <div>
                    <label class="block text-sm font-medium text-dark-700 mb-2">Package Tags</label>
                    <input type="text" name="tags_input" id="tags-input" 
                           value="{{ $package->tags ? implode(', ', $package->tags) : '' }}"
                           class="input-modern w-full" 
                           placeholder="Enter tags separated by commas (e.g., luxury, beach, honeymoon)">
                    <p class="text-sm text-gray-500 mt-1">Separate tags with commas</p>
                </div>
            </div>

            <!-- Cancellation Policy -->
            <div class="card-modern p-8">
                <h3 class="text-2xl font-bold text-dark-800 mb-6">Cancellation Policy</h3>
                
                <div>
                    <label class="block text-sm font-medium text-dark-700 mb-2">Cancellation Policy</label>
                    <textarea name="cancellation_policy" rows="4" 
                              class="input-modern w-full @error('cancellation_policy') border-red-500 @enderror" 
                              placeholder="Describe the cancellation policy for this package">{{ old('cancellation_policy', $package->cancellation_policy) }}</textarea>
                    @error('cancellation_policy')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.packages.show', $package) }}" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Package
                </a>
                
                <div class="flex items-center space-x-4">
                    <button type="submit" name="action" value="save_draft" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-save mr-2"></i> Save as Draft
                    </button>
                    <button type="submit" name="action" value="save_publish" class="btn-modern">
                        <i class="fas fa-check mr-2"></i> Update & Publish
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Dynamic form fields
function addInclusion() {
    const container = document.getElementById('inclusions-container');
    const div = document.createElement('div');
    div.className = 'inclusion-item flex items-center space-x-2 mb-2';
    div.innerHTML = `
        <input type="text" name="inclusions[]" class="input-modern flex-1" placeholder="e.g., Accommodation">
        <button type="button" onclick="removeItem(this)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function addExclusion() {
    const container = document.getElementById('exclusions-container');
    const div = document.createElement('div');
    div.className = 'exclusion-item flex items-center space-x-2 mb-2';
    div.innerHTML = `
        <input type="text" name="exclusions[]" class="input-modern flex-1" placeholder="e.g., International flights">
        <button type="button" onclick="removeItem(this)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function addHighlight() {
    const container = document.getElementById('highlights-container');
    const div = document.createElement('div');
    div.className = 'highlight-item flex items-center space-x-2 mb-2';
    div.innerHTML = `
        <input type="text" name="highlights[]" class="input-modern flex-1" placeholder="e.g., Visit famous landmarks">
        <button type="button" onclick="removeItem(this)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeItem(button) {
    button.parentElement.remove();
}

// Tags handling
document.getElementById('tags-input').addEventListener('blur', function() {
    const tags = this.value.split(',').map(tag => tag.trim()).filter(tag => tag);
    
    // Create hidden inputs for tags
    const existingTagInputs = document.querySelectorAll('input[name="tags[]"]');
    existingTagInputs.forEach(input => input.remove());
    
    tags.forEach(tag => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'tags[]';
        input.value = tag;
        this.parentElement.appendChild(input);
    });
});

// Form submission handling
document.querySelector('form').addEventListener('submit', function(e) {
    const submitButton = e.submitter;
    if (submitButton && submitButton.name === 'action') {
        if (submitButton.value === 'save_draft') {
            document.querySelector('select[name="status"]').value = 'draft';
        } else if (submitButton.value === 'save_publish') {
            document.querySelector('select[name="status"]').value = 'published';
        }
    }
});

// Image preview
document.getElementById('featured-image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const label = document.querySelector('label[for="featured-image"]');
            label.innerHTML = `
                <img src="${e.target.result}" class="w-full h-48 object-cover rounded-lg mb-4">
                <p class="text-gray-600">Click to change featured image</p>
            `;
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
@endsection

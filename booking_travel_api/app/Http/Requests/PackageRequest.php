<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PackageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $packageId = $this->route('package')?->id;

        return [
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'destination_id' => 'required|exists:destinations,id',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0|gte:price',
            'duration_days' => 'required|integer|min:1|max:365',
            'duration_nights' => 'required|integer|min:0|lt:duration_days',
            'min_participants' => 'required|integer|min:1',
            'max_participants' => 'required|integer|gte:min_participants',
            'difficulty_level' => 'required|in:easy,moderate,challenging',
            'featured_image' => $this->isMethod('POST') ? 'required|image|mimes:jpeg,png,jpg,webp|max:2048' : 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery' => 'nullable|array|max:10',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'inclusions' => 'nullable|array',
            'inclusions.*' => 'string|max:255',
            'exclusions' => 'nullable|array',
            'exclusions.*' => 'string|max:255',
            'itinerary' => 'nullable|array',
            'itinerary.*.day' => 'required|integer|min:1',
            'itinerary.*.title' => 'required|string|max:255',
            'itinerary.*.description' => 'required|string',
            'itinerary.*.meals' => 'nullable|array',
            'itinerary.*.accommodation' => 'nullable|string|max:255',
            'highlights' => 'nullable|array',
            'highlights.*' => 'string|max:255',
            'requirements' => 'nullable|array',
            'requirements.*' => 'string|max:255',
            'accommodation_type' => 'nullable|string|max:100',
            'meal_plan' => 'nullable|in:breakfast,half-board,full-board,all-inclusive',
            'transportation' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'status' => 'required|in:draft,published,archived',
            'available_from' => 'nullable|date|after_or_equal:today',
            'available_until' => 'nullable|date|after:available_from',
            'available_dates' => 'nullable|array',
            'available_dates.*' => 'date|after_or_equal:today',
            'advance_booking_days' => 'required|integer|min:0|max:365',
            'cancellation_policy' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'meta_data' => 'nullable|array',
            'meta_data.meta_title' => 'nullable|string|max:60',
            'meta_data.meta_description' => 'nullable|string|max:160',
            'meta_data.meta_keywords' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Package title is required.',
            'short_description.required' => 'Short description is required.',
            'description.required' => 'Package description is required.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category is invalid.',
            'destination_id.required' => 'Please select a destination.',
            'destination_id.exists' => 'Selected destination is invalid.',
            'price.required' => 'Package price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'original_price.gte' => 'Original price must be greater than or equal to the current price.',
            'duration_days.required' => 'Duration in days is required.',
            'duration_nights.lt' => 'Duration nights must be less than duration days.',
            'max_participants.gte' => 'Maximum participants must be greater than or equal to minimum participants.',
            'featured_image.required' => 'Featured image is required.',
            'featured_image.image' => 'Featured image must be a valid image file.',
            'gallery.max' => 'You can upload maximum 10 gallery images.',
            'available_until.after' => 'Available until date must be after available from date.',
        ];
    }

    protected function prepareForValidation()
    {
        // Convert string arrays to actual arrays for JSON fields
        if ($this->has('inclusions') && is_string($this->inclusions)) {
            $this->merge(['inclusions' => json_decode($this->inclusions, true)]);
        }

        if ($this->has('exclusions') && is_string($this->exclusions)) {
            $this->merge(['exclusions' => json_decode($this->exclusions, true)]);
        }

        if ($this->has('highlights') && is_string($this->highlights)) {
            $this->merge(['highlights' => json_decode($this->highlights, true)]);
        }

        if ($this->has('requirements') && is_string($this->requirements)) {
            $this->merge(['requirements' => json_decode($this->requirements, true)]);
        }

        if ($this->has('tags') && is_string($this->tags)) {
            $this->merge(['tags' => json_decode($this->tags, true)]);
        }

        if ($this->has('itinerary') && is_string($this->itinerary)) {
            $this->merge(['itinerary' => json_decode($this->itinerary, true)]);
        }

        if ($this->has('meta_data') && is_string($this->meta_data)) {
            $this->merge(['meta_data' => json_decode($this->meta_data, true)]);
        }
    }
}

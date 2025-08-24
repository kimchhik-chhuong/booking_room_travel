<div class="mb-4">
    <label class="block text-sm font-medium text-dark-600">Deal Name</label>
    <input type="text" name="title" value="{{ old('title', $deal->title ?? '') }}" class="input-modern w-full" required>
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-dark-600">Discount</label>
    <input type="text" name="discount" value="{{ old('discount', $deal->discount ?? '') }}" class="input-modern w-full" required>
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-dark-600">Description</label>
    <textarea name="description" rows="4" class="input-modern w-full" required>{{ old('description', $deal->description ?? '') }}</textarea>
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-dark-600">Promo Code</label>
    <input type="text" name="code" value="{{ old('code', $deal->code ?? '') }}" class="input-modern w-full" required>
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-dark-600">Valid Until</label>
    <input type="date" name="valid_until" value="{{ old('valid_until', isset($deal->valid_until) ? \Carbon\Carbon::parse($deal->valid_until)->format('Y-m-d') : '') }}" class="input-modern w-full" required>
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-dark-600">Usage Limit</label>
    <input type="number" name="limit" value="{{ old('limit', $deal->limit ?? '') }}" class="input-modern w-full" required>
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-dark-600">Status</label>
    <select name="status" class="input-modern w-full" required>
        <option value="Active" {{ old('status', $deal->status ?? '') == 'Active' ? 'selected' : '' }}>Active</option>
        <option value="Scheduled" {{ old('status', $deal->status ?? '') == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
        <option value="Expired" {{ old('status', $deal->status ?? '') == 'Expired' ? 'selected' : '' }}>Expired</option>
    </select>
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-dark-600">Gradient Color</label>
    <select name="color" class="input-modern w-full" required>
        <option value="from-orange-400 to-pink-500" {{ old('color', $deal->color ?? '') == 'from-orange-400 to-pink-500' ? 'selected' : '' }}>Orange → Pink</option>
        <option value="from-blue-400 to-purple-500" {{ old('color', $deal->color ?? '') == 'from-blue-400 to-purple-500' ? 'selected' : '' }}>Blue → Purple</option>
        <option value="from-emerald-400 to-blue-500" {{ old('color', $deal->color ?? '') == 'from-emerald-400 to-blue-500' ? 'selected' : '' }}>Emerald → Blue</option>
    </select>
</div>

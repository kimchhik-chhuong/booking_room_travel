{{-- resources/views/components/feature-icon.blade.php --}}
@props(['icon' => 'star'])

<div class="w-12 h-12 flex items-center justify-center rounded-full bg-indigo-100 text-indigo-600">
    {{-- Using FontAwesome icons, you can change "fa" to "fas" or "fa-solid" depending on version --}}
    <i class="fa fa-{{ $icon }} text-xl"></i>
</div>

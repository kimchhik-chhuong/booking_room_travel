@if($adventures->isEmpty())
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No adventures found</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by creating a new adventure.</p>
        <div class="mt-6">
            <a href="{{ route('adventures.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                New Adventure
            </a>
        </div>
    </div>
@else
    <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8">
        @foreach($adventures as $adventure)
            <div class="group relative bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                <div class="aspect-w-3 aspect-h-2 bg-gray-200 group-hover:opacity-75">
                    @if($adventure->image_url)
                        @php
                            // Check if the URL is already a full URL
                            $imageUrl = filter_var($adventure->image_url, FILTER_VALIDATE_URL) 
                                ? $adventure->image_url 
                                : (strpos($adventure->image_url, 'storage/') === 0 
                                    ? asset($adventure->image_url) 
                                    : asset('storage/' . ltrim($adventure->image_url, '/')));
                        @endphp
                        <img src="{{ $imageUrl }}" alt="{{ $adventure->name }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    <div class="absolute top-2 right-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $adventure->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($adventure->status) }}
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                <a href="{{ route('adventures.show', $adventure) }}" class="hover:text-indigo-600 transition-colors">
                                    {{ $adventure->name }}
                                </a>
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $adventure->province->name }}
                            </p>
                        </div>
                        <div class="ml-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('adventures.edit', $adventure) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span class="sr-only">Edit</span>
                                </a>
                                <form action="{{ route('adventures.destroy', $adventure) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this adventure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span class="sr-only">Delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500 line-clamp-2">
                        {{ $adventure->description }}
                    </p>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-sm text-gray-500">
                            Created {{ $adventure->created_at->diffForHumans() }}
                        </span>
                        <a href="{{ route('adventures.show', $adventure) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            View details <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($adventures->hasPages())
        <div class="mt-8">
            {{ $adventures->links() }}
        </div>
    @endif
@endif

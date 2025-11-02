<!-- Property Showcase Section -->
<section class="py-16 relative overflow-hidden bg-blue-50 dark:bg-gray-900">

    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <h2 class="text-4xl font-extrabold text-center mb-4">Available Properties</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $listings = App\Models\Listing::where('status', 'active')->take(6)->get();
            @endphp
            @forelse($listings as $listing)
            <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:scale-105">
                <div class="relative">
                    @if($listing->images && count($listing->images) > 0)
                        <img src="{{ asset('storage/' . $listing->images[0]) }}" alt="{{ $listing->title }}" class="w-full h-48 object-cover">
                    @else
                        <img src="{{ asset('storage/img/homess.png') }}" alt="{{ $listing->title }}" class="w-full h-48 object-cover">
                    @endif
                    <div class="absolute top-3 left-3 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-semibold">Available</div>
                    <div class="absolute top-3 right-3 bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-semibold">Verified</div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $listing->title }}</h3>
                        <span class="text-lg font-semibold text-blue-600">₱{{ number_format($listing->price, 0) }}/mo</span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                        {{ $listing->location }}
                    </p>
                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                        <span>{{ $listing->room_count }} Bed{{ $listing->room_count > 1 ? 's' : '' }}</span>
                        <span>{{ $listing->bathroom_count }} Bath{{ $listing->bathroom_count > 1 ? 's' : '' }}</span>
                        <span>{{ rand(600, 3000) }} sqft</span>
                    </div>
                    <a href="{{ route('property-details', $listing->id) }}" class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold py-2 px-4 rounded-lg hover:from-blue-700 hover:to-purple-700 transition duration-300">View Details</a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500 dark:text-gray-400 text-lg">No properties available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

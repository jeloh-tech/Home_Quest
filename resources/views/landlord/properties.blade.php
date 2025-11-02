@extends('layouts.landlord')

@section('title', 'My Properties')

@section('content')
<div class="fixed inset-0 bg-gradient-to-br from-blue-50 via-white to-indigo-50 -z-10"></div>
<div class="relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-4 sm:mb-0">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">My Properties</h1>
                    <p class="text-lg text-gray-600">Manage your property listings</p>
                </div>
                <a href="{{ route('landlord.add-post') }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New Property
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-center">
                <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
                <button type="button" class="ml-auto text-green-600 hover:text-green-800" onclick="this.parentElement.style.display='none'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if($listings->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-12 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-6">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No Properties Yet</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">You haven't created any property listings yet. Start building your portfolio by adding your first property.</p>
                <a href="{{ route('landlord.add-post') }}"
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Your First Property
                </a>
            </div>
        @else
            <!-- Search and Filter Section -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <input type="text" id="searchInput"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
                                   placeholder="Search properties...">
                            <svg class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">{{ $listings->total() }}</span> properties found
                    </div>
                </div>
            </div>

            <!-- Properties Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="propertiesContainer">
                @foreach($listings as $listing)
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300 property-card"
                         data-title="{{ strtolower($listing->title) }}" data-location="{{ strtolower($listing->location) }}">

                        <!-- Property Image -->
                        <div class="relative">
                            @if(!empty($listing->images) && is_array($listing->images) && count($listing->images) > 0)
                                <img src="{{ asset('storage/' . $listing->images[0]) }}"
                                     alt="{{ $listing->title }}"
                                     class="w-full h-48 object-cover">
                                @if($listing->status === 'banned')
                                    <div class="absolute inset-0 bg-red-500 bg-opacity-80 flex items-center justify-center">
                                        <span class="text-white text-2xl font-bold tracking-wider">BANNED</span>
                                    </div>
                                @endif
                                @if(count($listing->images) > 1)
                                    <div class="absolute top-3 right-3 bg-black bg-opacity-70 text-white text-xs font-semibold px-2 py-1 rounded-full flex items-center space-x-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>{{ count($listing->images) }}</span>
                                    </div>
                                @endif
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                    <div class="text-center text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <p class="text-sm font-medium">No Image</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Status Badge -->
                            @if($listing->status === 'banned')
                                <div class="absolute top-3 left-3 bg-red-500 text-white text-xs font-semibold px-3 py-1 rounded-full flex items-center space-x-1 shadow-md">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                    </svg>
                                    <span>Banned</span>
                                </div>
                            @elseif($listing->status === 'rented')
                                <div class="absolute top-3 left-3 bg-red-600 text-white text-xs font-semibold px-3 py-1 rounded-full flex items-center space-x-1 shadow-md">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Rented</span>
                                </div>
                            @else
                                <div class="absolute top-3 left-3 bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full flex items-center space-x-1 shadow-md">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Active</span>
                                </div>
                            @endif
                        </div>

                        <!-- Property Details -->
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-xl font-bold text-gray-900 line-clamp-1 flex-1">{{ $listing->title }}</h3>
                                <div class="flex items-center space-x-3 ml-4">
                                    <div class="flex items-center space-x-1 text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                        </svg>
                                        <span>{{ $listing->liked_by_count }}</span>
                                    </div>
                                    <div class="flex items-center space-x-1 text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                        </svg>
                                        <span>{{ $listing->favorited_by_count }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-baseline space-x-1 mb-3">
                                <span class="text-2xl font-bold text-blue-600">₱{{ number_format($listing->price, 2) }}</span>
                                <span class="text-sm text-gray-600">/month</span>
                            </div>

                            <div class="flex items-center text-gray-600 mb-3">
                                <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-sm">{{ $listing->location }}</span>
                            </div>

                            <div class="flex items-center space-x-4 mb-4 text-sm text-gray-600">
                                @if($listing->room_count > 0)
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                                        </svg>
                                        <span>{{ $listing->room_count }} Rooms</span>
                                    </div>
                                @endif
                                @if($listing->bathroom_count > 0)
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        <span>{{ $listing->bathroom_count }} Baths</span>
                                    </div>
                                @endif
                            </div>

                            @if($listing->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $listing->description }}</p>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex space-x-3 mb-4">
                                <a href="{{ route('landlord.properties.edit', $listing->id) }}"
                                   class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                                @if($listing->pending_applications_count > 0 || ($listing->rental_applications_count > 0 && $listing->tenant_id))
                                    <a href="{{ route('landlord.properties.applications', $listing->id) }}"
                                       class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Applications
                                        @if($listing->tenant_id)
                                            <span class="ml-1 bg-white bg-opacity-20 text-xs px-2 py-0.5 rounded-full">1</span>
                                        @elseif($listing->rental_applications_count > 0)
                                            <span class="ml-1 bg-white bg-opacity-20 text-xs px-2 py-0.5 rounded-full">{{ $listing->rental_applications_count }}</span>
                                        @endif
                                    </a>
                                @else
                                <button type="button" data-property-id="{{ $listing->id }}" data-property-title="{{ $listing->title }}"
                                        onclick="confirmDelete(this)"
                                        class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete
                                </button>
                                @endif
                            </div>

                            <div class="text-center text-gray-500 text-xs border-t border-gray-100 pt-3">
                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Created {{ $listing->created_at->format('M j, Y') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($listings->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $listings->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden" id="deleteModal" role="dialog" aria-modal="true" aria-labelledby="deleteModalLabel">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6">
        <h2 class="text-xl font-semibold mb-4" id="deleteModalLabel">Confirm Delete</h2>
        <p class="mb-4">Are you sure you want to delete "<span id="deletePropertyTitle" class="font-semibold"></span>"?</p>
        <p class="text-red-600 text-sm mb-6">This action cannot be undone.</p>
        <div class="flex justify-end space-x-4">
            <button type="button" id="cancelDeleteBtn" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">Cancel</button>
            <form id="deleteForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">Delete Property</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    console.log('Document ready - checking delete buttons');
    console.log('Number of delete buttons found:', $('button[data-property-id]').length);
    $('button[data-property-id]').each(function(index) {
        console.log('Delete button ' + index + ':', this);
        console.log('Button text:', $(this).text().trim());
        console.log('Button onclick:', $(this).attr('onclick'));
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase();
        $('.property-card').each(function() {
            var title = $(this).data('title');
            var location = $(this).data('location');
            if (title.includes(searchTerm) || location.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Search button click
    $('#searchBtn').on('click', function() {
        $('#searchInput').trigger('keyup');
    });

    // Modal functionality
    $('#cancelDeleteBtn').on('click', function() {
        $('#deleteModal').addClass('hidden');
    });

    // Close modal when clicking outside
    $('#deleteModal').on('click', function(e) {
        if (e.target === this) {
            $(this).addClass('hidden');
        }
    });

    // Form submit debugging
    $('#deleteForm').on('submit', function(e) {
        console.log('Delete form submitted');
        console.log('Form action:', $(this).attr('action'));
        console.log('Form method:', $(this).attr('method'));
        console.log('Form data:', $(this).serialize());
    });
});

// Delete confirmation function
function confirmDelete(button) {
    console.log('confirmDelete triggered for property:', button);
    var propertyId = $(button).data('property-id');
    var propertyTitle = $(button).data('property-title');
    console.log('Property ID:', propertyId, 'Title:', propertyTitle);
    $('#deletePropertyTitle').text(propertyTitle);
    var actionUrl = '{{ url("landlord/properties") }}/' + propertyId;
    console.log('Setting form action to:', actionUrl);
    $('#deleteForm').attr('action', actionUrl);
    console.log('Form action set, showing modal');
    $('#deleteModal').removeClass('hidden');
    console.log('Modal should now be visible. Modal element:', $('#deleteModal')[0]);
    console.log('Modal has hidden class?', $('#deleteModal').hasClass('hidden'));
}
</script>
@endsection

 @extends('layouts.landlord')

@section('content')
<style>
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}
body {
    background: linear-gradient(to bottom right, #dbeafe, #ffffff, #e0e7ff) !important;
    background-attachment: fixed !important;
    height: 100vh !important;
}
main {
    background: transparent !important;
    padding: 0 !important;
    min-height: 100vh !important;
}
.form-container {
    overflow-y: auto;
    scrollbar-width: none;
    -ms-overflow-style: none;
}
.form-container::-webkit-scrollbar {
    display: none;
}
</style>
<div class="relative z-10">
<div class="w-full h-screen">
    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  

    @if(auth()->user()->canPostListings())
        <!-- Post Creation Form for Verified Landlords -->
<div class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 rounded-lg shadow-sm border border-gray-200 p-6 h-screen min-w-full form-container">
            <h2 class="text-xl font-semibold mb-4">Create New Property Listing</h2>
            <p class="text-gray-600 mb-6">Fill in the details below to create a new property listing.</p>

            <!-- Property Listing Form -->
            <form method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Property Title -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Property Title *</label>
                    <input type="text" id="title" name="title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Modern 2BR Apartment in BGC" required>
                </div>

                <!-- Price and Location Side by Side -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Monthly Rent (₱) *</label>
                        <input type="number" id="price" name="price" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0" step="0.01" required>
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location *</label>
                        <select id="location" name="location" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">Select Barangay</option>
                            <option value="Progressive">Progressive</option>
                            <option value="Paradise">Paradise</option>
                            <option value="Smart">Smart</option>
                            <option value="Flourishing">Flourishing</option>
                        </select>
                    </div>
                </div>

                <!-- Property Type and Room/Bathroom Count -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="property_type" class="block text-sm font-medium text-gray-700 mb-1">Property Type *</label>
                        <select id="property_type" name="property_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">Select Property Type</option>
                            <option value="House">House</option>
                            <option value="Boarding House">Boarding House</option>
                            <option value="Apartment">Apartment</option>
                            <option value="Condo">Condo</option>
                        </select>
                    </div>
                    <div>
                        <label for="room_count" class="block text-sm font-medium text-gray-700 mb-1">Number of Rooms</label>
                        <input type="number" id="room_count" name="room_count" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0" min="0">
                    </div>
                    <div>
                        <label for="bathroom_count" class="block text-sm font-medium text-gray-700 mb-1">Number of Bathrooms</label>
                        <input type="number" id="bathroom_count" name="bathroom_count" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0" min="0">
                    </div>
                </div>

                <!-- Main Property Image -->
                <div class="mb-4">
                    <label for="main_image" class="block text-sm font-medium text-gray-700 mb-1">Main Image *</label>
                    <div class="flex items-center space-x-4">
                        <label for="main_image" class="cursor-pointer inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 text-sm font-semibold rounded-md hover:bg-blue-100 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Choose Main Image
                        </label>
                        <span id="main_image_filename" class="text-sm text-gray-500">No file chosen</span>
                    </div>
                    <input type="file" id="main_image" name="main_image" accept="image/*" class="hidden" required>
                    <div id="main_image_preview" class="mt-2">
                        <img src="" alt="Main Image Preview" class="w-16 h-16 rounded-md hidden object-cover border border-gray-200" />
                    </div>
                </div>

                <!-- Additional Property Images -->
                <div class="mb-6">
                    <label for="additional_images" class="block text-sm font-medium text-gray-700 mb-1">Additional Images</label>
                    <div class="flex items-center space-x-4">
                        <label for="additional_images" class="cursor-pointer inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 text-sm font-semibold rounded-md hover:bg-blue-100 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Choose Additional Images
                        </label>
                        <span id="additional_images_filename" class="text-sm text-gray-500">No files chosen</span>
                    </div>
                    <input type="file" id="additional_images" name="additional_images[]" multiple accept="image/*" class="hidden">
                    <div id="additional_images_preview" class="mt-2 flex flex-wrap gap-2"></div>
                    <p class="text-xs text-gray-500 mt-1">Upload additional images of the property (max 4 images, each max 5MB)</p>
                </div>

                <!-- Amenities -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amenities</label>
                    <p class="text-xs text-gray-500 mb-2">Select all amenities that apply to your property</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="amenities[]" value="Wi-Fi" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Wi-Fi</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="amenities[]" value="Laundry" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Laundry</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="amenities[]" value="Air Conditioning" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Air Conditioning</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="amenities[]" value="Parking" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Parking</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="amenities[]" value="Store" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Store</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="amenities[]" value="Others" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Others</span>
                        </label>
                    </div>
                </div>

                <!-- Property Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Property Description *</label>
                    <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Describe your property in detail. Include amenities, features, and any other information that would be helpful for potential renters..." required></textarea>
                    <p class="text-xs text-gray-500 mt-1">Provide a detailed description of your property to attract potential renters.</p>
                </div>

                <!-- Property Location Map -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Property Location</label>
                    <p class="text-xs text-gray-500 mb-2">Click on the map to set the property location</p>
                    <div id="map" class="w-full h-96 border border-gray-300 rounded-md"></div>
                    <!-- Hidden inputs for latitude and longitude -->
                    <input type="hidden" id="latitude" name="latitude">
                    <input type="hidden" id="longitude" name="longitude">
                    <p class="text-sm text-gray-600 mt-2">Please click on the map above to select the exact location of your property. This will help potential renters find your listing easily.</p>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        Create Listing
                    </button>
                </div>
            </form>
        </div>
    @else
        <!-- Verification Required Message for Unverified Landlords -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Verification Required</h3>
                    <p class="text-sm text-yellow-700 mt-1">
                        You need to verify your account before you can create property listings.
                        <a href="#" onclick="proceedToVerification()" class="text-blue-600 hover:text-blue-800 underline">Click here to submit verification</a>.
                    </p>
                </div>
            </div>
        </div>
        @endif



    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize the map centered on Gonzaga, Cagayan
            var map = L.map('map').setView([18.257893, 121.994644], 15);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                minZoom: 12,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Restrict map bounds to Cagayan, Gonzaga area only
            var southWest = L.latLng(18.20, 121.90);
            var northEast = L.latLng(18.30, 122.05);
            var bounds = L.latLngBounds(southWest, northEast);

            map.setMaxBounds(bounds);
            map.on('drag', function() {
                map.panInsideBounds(bounds, { animate: false });
            });

            // Add a default marker for Gonzaga center
            var marker = L.marker([18.257893, 121.994644]).addTo(map);

            // Barangay coordinates
            const barangayCoords = {
                'Progressive': [18.264505, 121.991013],
                'Paradise': [18.260825, 121.991313],
                'Smart': [18.260152, 121.996589],
                'Flourishing': [18.259793, 121.995530]
            };

            // Function to update marker and hidden inputs
            function onMapClick(e) {
                var lat = e.latlng.lat.toFixed(6);
                var lng = e.latlng.lng.toFixed(6);

                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            }

            // Update map location when barangay is selected
            const locationSelect = document.getElementById('location');
            locationSelect.addEventListener('change', function () {
                const selectedBarangay = this.value;
                if (selectedBarangay && barangayCoords[selectedBarangay]) {
                    const coords = barangayCoords[selectedBarangay];
                    const latLng = L.latLng(coords[0], coords[1]);
                    map.setView(latLng, 18);
                    if (marker) {
                        marker.setLatLng(latLng);
                    } else {
                        marker = L.marker(latLng).addTo(map);
                    }
                    document.getElementById('latitude').value = coords[0].toFixed(6);
                    document.getElementById('longitude').value = coords[1].toFixed(6);
                }
            });

            map.on('click', onMapClick);

            // Image preview for main image
            const mainImageInput = document.getElementById('main_image');
            const mainImagePreview = document.querySelector('#main_image_preview img');

            mainImageInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        mainImagePreview.src = e.target.result;
                        mainImagePreview.classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                } else {
                    mainImagePreview.src = '';
                    mainImagePreview.classList.add('hidden');
                }
            });

            // Image preview for additional images
            const additionalImagesInput = document.getElementById('additional_images');
            const additionalImagesPreview = document.getElementById('additional_images_preview');

            additionalImagesInput.addEventListener('change', function () {
                const files = this.files;
                const maxImages = 4;

                // Validate maximum number of images
                if (files.length > maxImages) {
                    alert(`You can only upload a maximum of ${maxImages} additional images. Please select ${maxImages} or fewer images.`);
                    this.value = ''; // Clear the input
                    additionalImagesPreview.innerHTML = '';
                    return;
                }

                additionalImagesPreview.innerHTML = '';
                if (files.length > 0) {
                    // Create main grid container with flexbox for better wrapping
                    const gridContainer = document.createElement('div');
                    gridContainer.classList.add('flex', 'flex-wrap', 'gap-2');

                    Array.from(files).forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.classList.add('w-20', 'h-20', 'rounded-md', 'object-cover', 'flex-shrink-0');
                            gridContainer.appendChild(img);
                        }
                        reader.readAsDataURL(file);
                    });

                    // Add grid to preview container
                    additionalImagesPreview.appendChild(gridContainer);
                }
            });
        });


    </script>

    <!-- Include verification.js script -->
    <script src="{{ asset('js/verification.js') }}"></script>

    @if(!auth()->user()->verification_status || (!auth()->user()->valid_id_path && !auth()->user()->valid_id_back_path))
    <script>
        // Show verification required message for unverified users
        document.addEventListener('DOMContentLoaded', function() {
            window.showVerificationRequiredMessage();
        });
    </script>
    @endif
@endsection

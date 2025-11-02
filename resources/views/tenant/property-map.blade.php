@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 w-64 h-full bg-white dark:bg-gray-800 shadow-lg border-r border-gray-200 dark:border-gray-700 overflow-y-auto z-10">
        @include('tenant.sidebar')
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 ml-64">


        <!-- Map Container -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden mb-8">
            <div class="p-6">
                <div id="map" class="w-full rounded-lg" style="height: 600px;"></div>
            </div>
        </div>

        <!-- Property List -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Properties on Map</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($properties as $property)
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="mb-3">
                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $property['title'] }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $property['address'] }}</p>
                            <p class="text-sm font-semibold text-green-600 dark:text-green-400">₱{{ number_format($property['price'], 2) }}/month</p>
                        </div>
                        <a href="{{ route('tenant.property-details', ['id' => $property['id']]) }}" class="inline-block px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                            View Details
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Property map script loaded');

        // Check if map container exists
        const mapContainer = document.getElementById('map');
        if (!mapContainer) {
            console.error('Map container not found');
            return;
        }

        console.log('Map container found, initializing map...');

        try {
            // Get properties data from PHP
            const propertiesData = @json($properties);
            const properties = Array.isArray(propertiesData) ? propertiesData : [];

            console.log('Properties loaded:', properties.length);
            console.log('Properties data:', properties);

            // Initialize the map centered at average lat/lng or default location
            let mapCenter = [18.2, 121.994644]; // Default to Gonzaga, Cagayan, Philippines

            if (properties.length > 0) {
                let validProperties = properties.filter(p => p.lat && p.lng);
                console.log('Valid properties with coordinates:', validProperties.length);
                if (validProperties.length > 0) {
                    let latSum = 0;
                    let lngSum = 0;
                    validProperties.forEach(p => {
                        latSum += parseFloat(p.lat);
                        lngSum += parseFloat(p.lng);
                    });
                    mapCenter = [latSum / validProperties.length, lngSum / validProperties.length];
                }
            }

            console.log('Map center:', mapCenter);

            // Initialize the map
            const map = L.map('map').setView(mapCenter, 17);
            console.log('Map object created:', map);

            // Add OpenStreetMap tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            console.log('Tile layer added');

            // Always add a default marker at the center
            const defaultMarker = L.marker(mapCenter).addTo(map);
            defaultMarker.bindPopup('<strong>Map Center</strong><br/>This is the center of the map area.');
            console.log('Default marker added at center');

            // Add markers for each property
            let markerCount = 0;
            properties.forEach(property => {
                if (property.lat && property.lng && property.title) {
                    try {
                        const marker = L.marker([parseFloat(property.lat), parseFloat(property.lng)]).addTo(map);
                        const popupContent = `
                            <div style="min-width: 200px;">
                                <strong>${property.title}</strong><br/>
                                ${property.address || 'Address not specified'}<br/>
                                <span style="color: #059669; font-weight: bold;">₱${parseFloat(property.price).toLocaleString('en-PH', {minimumFractionDigits: 2})}/month</span><br/>
                                <a href="/tenant/property-details/${property.id}" style="color: #2563eb; text-decoration: underline;">View Details</a>
                            </div>
                        `;
                        marker.bindPopup(popupContent);
                        markerCount++;
                        console.log('Property marker added:', property.title);
                    } catch (error) {
                        console.error('Error creating marker for property:', property, error);
                    }
                }
            });

            console.log('Total markers added:', markerCount + 1); // +1 for default marker

            // Force map to resize and redraw
            setTimeout(() => {
                map.invalidateSize();
                console.log('Map size invalidated');
            }, 100);

        } catch (error) {
            console.error('Error initializing map:', error);
            // Fallback: show error message
            mapContainer.innerHTML = '<div style="padding: 20px; text-align: center; color: #ef4444;">Error loading map. Please refresh the page.<br/><small>Check console for details.</small></div>';
        }
    });
</script>
@endpush

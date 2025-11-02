<!-- Map Section -->
<section class="py-16 relative overflow-hidden z-[1000] bg-blue-50 dark:bg-gray-900">

    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <h2 class="text-4xl font-extrabold text-center mb-8">Find Properties Near You</h2>
        <div class="text-center mb-12">
            <p class="text-gray-600 dark:text-gray-300 text-lg md:text-xl max-w-3xl mx-auto">Explore available rental properties across different locations with our interactive map. Discover your perfect home in Gonzaga, Cagayan.</p>
        </div>

        <!-- Enhanced Professional Search Bar -->
        <div class="max-w-3xl mx-auto mb-4">
            <form method="GET" action="{{ route('listings') }}" class="flex items-center bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden border border-gray-200 dark:border-gray-700 backdrop-blur-sm">
                <div class="flex-1 flex items-center px-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="location" placeholder="Search for properties in Gonzaga, Cagayan..." class="w-full bg-transparent text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none text-lg font-medium">
                    </div>
                </div>
                <button type="submit" class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 hover:from-blue-700 hover:via-purple-700 hover:to-indigo-700 text-white px-8 py-4 font-semibold transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:scale-105">
                    <span class="mr-2">Search</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Enhanced Map Container with Professional Design -->
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <!-- Map Header with Stats -->
            <div class="absolute top-4 left-4 z-10 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-xl px-4 py-2 shadow-lg">
                <div class="flex items-center space-x-4 text-sm">
                    <div class="flex items-center space-x-1">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">4 Barangays</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">50+ Properties</span>
                    </div>
                </div>
            </div>

            <!-- Map Controls -->
            <div class="absolute top-4 right-4 z-10 flex flex-col space-y-2">
                <button id="locateBtn" class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm hover:bg-white dark:hover:bg-gray-800 p-3 rounded-xl shadow-lg transition-all duration-300 hover:scale-110" title="Find my location">
                    <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </button>
                <button id="fullscreenBtn" class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm hover:bg-white dark:hover:bg-gray-800 p-3 rounded-xl shadow-lg transition-all duration-300 hover:scale-110" title="Fullscreen">
                    <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 3l-6 6m0 0V4m0 5h5M3 21l6-6m0 0v5m0-5H4"></path>
                    </svg>
                </button>
            </div>

            <div id="map" style="height: 500px; width: 100%;"></div>
        </div>

        <!-- Enhanced CTA Section -->
        <div class="text-center mt-12">
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('listings') }}" class="inline-flex items-center bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 text-white font-semibold py-4 px-10 rounded-full hover:from-blue-700 hover:via-purple-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Browse All Listings
                </a>
                <div class="flex items-center text-gray-600 dark:text-gray-400 text-sm">
                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Updated daily • Verified properties
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Initialize the map centered on Gonzaga, Cagayan with enhanced styling
    var map = L.map('map', {
        zoomControl: false,
        attributionControl: false
    }).setView([18.257893, 121.994644], 15);

    // Add custom styled OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        minZoom: 12,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Add custom zoom control
    L.control.zoom({
        position: 'bottomright'
    }).addTo(map);

    // Add attribution control in bottom left
    L.control.attribution({
        position: 'bottomleft'
    }).addTo(map);

    // Restrict map bounds to Cagayan, Gonzaga area only
    var southWest = L.latLng(18.20, 121.90);
    var northEast = L.latLng(18.30, 122.05);
    var bounds = L.latLngBounds(southWest, northEast);

    map.setMaxBounds(bounds);
    map.on('drag', function() {
        map.panInsideBounds(bounds, { animate: false });
    });

    // Enhanced Barangay coordinates with property counts
    const barangayCoords = {
        'Progressive': { coords: [18.264505, 121.991013], properties: 15, color: '#3B82F6' },
        'Paradise': { coords: [18.260825, 121.991313], properties: 12, color: '#8B5CF6' },
        'Smart': { coords: [18.260152, 121.996589], properties: 18, color: '#06B6D4' },
        'Flourishing': { coords: [18.259793, 121.995530], properties: 10, color: '#10B981' }
    };

    // Create custom markers for each barangay
    const barangayMarkers = {};

    Object.entries(barangayCoords).forEach(([barangay, data]) => {
        // Create custom icon
        const customIcon = L.divIcon({
            html: `<div style="background-color: ${data.color}; width: 40px; height: 40px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 10px rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 14px;">${data.properties}</div>`,
            className: 'custom-marker',
            iconSize: [40, 40],
            iconAnchor: [20, 20]
        });

        const marker = L.marker(data.coords, { icon: customIcon }).addTo(map)
            .bindPopup(`
                <div class="p-3 min-w-48">
                    <h3 class="font-bold text-lg text-gray-800 mb-2">${barangay}</h3>
                    <p class="text-gray-600 mb-3">Gonzaga, Cagayan</p>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm text-gray-500">Available Homes:</span>
                        <span class="font-semibold text-blue-600">${data.properties}</span>
                    </div>
                    <a href="{{ route('listings') }}?location=${barangay}" class="inline-block w-full text-center bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:from-blue-600 hover:to-purple-600 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105" style="color: white;">
                        View Properties
                    </a>
                </div>
            `, {
                className: 'custom-popup',
                maxWidth: 250
            });

        barangayMarkers[barangay] = marker;
    });

    // Current location marker (Gonzaga Center)
    var currentMarker = L.marker([18.257893, 121.994644], {
        icon: L.divIcon({
            html: '<div style="background-color: #EF4444; width: 30px; height: 30px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 10px rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 12px;">📍</div>',
            className: 'current-location-marker',
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        })
    }).addTo(map);

    // Enhanced search functionality
    document.querySelector('form[action*="listings"]').addEventListener('submit', function(e) {
        e.preventDefault();

        const searchInput = this.querySelector('input[name="location"]');
        const searchTerm = searchInput.value.trim().toLowerCase();

        // Check if search term matches a barangay
        let foundBarangay = null;
        for (const [barangay, data] of Object.entries(barangayCoords)) {
            if (barangay.toLowerCase() === searchTerm) {
                foundBarangay = barangay;
                break;
            }
        }

        if (foundBarangay) {
            const data = barangayCoords[foundBarangay];

            // Close current popup
            map.closePopup();

            // Pan to the barangay location with animation
            map.flyTo(data.coords, 16, {
                duration: 1.5,
                easeLinearity: 0.25
            });

            // Open the barangay popup after animation
            setTimeout(() => {
                barangayMarkers[foundBarangay].openPopup();
            }, 1500);

            // Clear search input
            searchInput.value = '';
        } else {
            // Show error feedback
            searchInput.style.borderColor = '#EF4444';
            searchInput.style.backgroundColor = '#FEF2F2';
            setTimeout(() => {
                searchInput.style.borderColor = '';
                searchInput.style.backgroundColor = '';
                searchInput.focus();
            }, 2000);
        }
    });

    // Location button functionality
    document.getElementById('locateBtn').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;

                // Check if user is within Gonzaga bounds
                if (bounds.contains([userLat, userLng])) {
                    map.flyTo([userLat, userLng], 16, {
                        duration: 1.5
                    });

                    // Add user location marker
                    L.marker([userLat, userLng], {
                        icon: L.divIcon({
                            html: '<div style="background-color: #10B981; width: 25px; height: 25px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center; color: white; font-size: 10px;">📍</div>',
                            className: 'user-location-marker',
                            iconSize: [25, 25],
                            iconAnchor: [12.5, 12.5]
                        })
                    }).addTo(map)
                        .bindPopup('Your Location')
                        .openPopup();
                } else {
                    alert('Your location is outside the Gonzaga area. The map will center on Gonzaga town center.');
                    map.flyTo([18.257893, 121.994644], 15, {
                        duration: 1.5
                    });
                }
            }, function(error) {
                alert('Unable to retrieve your location. Please check your browser settings.');
            });
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    });

    // Fullscreen button functionality
    document.getElementById('fullscreenBtn').addEventListener('click', function() {
        const mapContainer = document.getElementById('map').parentElement;

        if (!document.fullscreenElement) {
            mapContainer.requestFullscreen().then(() => {
                map.invalidateSize();
            });
        } else {
            document.exitFullscreen().then(() => {
                map.invalidateSize();
            });
        }
    });

    // Handle fullscreen change
    document.addEventListener('fullscreenchange', function() {
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
    });

    // Add custom CSS for popups
    const style = document.createElement('style');
    style.textContent = `
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .leaflet-popup-content {
            margin: 0;
        }
        .custom-marker {
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }
        .custom-marker:hover {
            transform: scale(1.1);
        }
    `;
    document.head.appendChild(style);
</script>

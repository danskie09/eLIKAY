<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>eLIKAY</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <!-- Add Tailwind CSS and Heroicons CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/heroicons@2.0.18/24/outline/index.js"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm fixed w-full z-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h1 class="text-2xl font-bold text-gray-900">eLIKAY</h1>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="flex items-center">
                    @csrf
                    <button type="submit" class="inline-flex items-center space-x-2 text-gray-600 hover:text-gray-900 px-3 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>{{ __('Log Out') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="pt-16 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-lg p-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Map and Status -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Map Container -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div id="map" class="h-[600px] w-full"></div>
                    </div>

                    <!-- Status Legend -->
                    <div class="bg-white rounded-xl shadow-sm p-4">
                        <h3 class="font-semibold mb-3 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Location Status</span>
                        </h3>
                        <div class="flex space-x-6">
                            <span class="flex items-center">
                                <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>Active
                            </span>
                            <span class="flex items-center">
                                <span class="w-3 h-3 rounded-full bg-gray-400 mr-2"></span>Inactive
                            </span>
                            <span class="flex items-center">
                                <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>Alert
                            </span>
                        </div>
                    </div>

                    <!-- Route Alternatives -->
                    <div id="routeAlternatives" class="bg-white rounded-xl shadow-sm p-6 hidden">
                        <h3 class="font-semibold mb-4 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            <span>Alternative Routes</span>
                        </h3>
                        <div id="routesList" class="space-y-2"></div>
                    </div>
                </div>

                <!-- Right Column - Forms -->
                <div class="space-y-6">
                    <!-- Current Location Button -->
                    <button onclick="getCurrentLocation()" type="button" 
                        class="w-full bg-black text-white px-4 py-3 rounded-xl hover:bg-gray-800 transition-colors flex items-center justify-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Get Current Location</span>
                    </button>

                    <!-- Location Form -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold mb-4 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Add Location</span>
                        </h2>
                        <form method="POST" action="{{ route('locations.store') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Location Name</label>
                                <input type="text" id="name" name="name" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-black focus:border-black">
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="status" name="status" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-black focus:border-black">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="alert">Alert</option>
                                </select>
                            </div>
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea id="description" name="description" rows="3"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-black focus:border-black"></textarea>
                            </div>
                            <input type="hidden" id="latitude" name="latitude">
                            <input type="hidden" id="longitude" name="longitude">
                            <button type="submit" 
                                class="w-full bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors">
                                Save Location
                            </button>
                        </form>
                    </div>

                    <!-- Route Planning Form -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold mb-4 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            <span>Plan Route</span>
                        </h2>
                        <form id="routeForm" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Start Location</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="start" name="start" required
                                        class="pl-10 mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-black focus:border-black">
                                    <input type="hidden" id="start_lat" name="start_lat">
                                    <input type="hidden" id="start_lng" name="start_lng">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">End Location</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="end" name="end" required
                                        class="pl-10 mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-black focus:border-black">
                                    <input type="hidden" id="end_lat" name="end_lat">
                                    <input type="hidden" id="end_lng" name="end_lng">
                                </div>
                            </div>
                            <div class="flex space-x-4">
                                <button type="submit" 
                                    class="flex-1 bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors flex items-center justify-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                    <span>Find Safe Routes</span>
                                </button>
                                <select id="routePreference" class="rounded-lg border-gray-300">
                                    <option value="balanced">Balanced</option>
                                    <option value="safer">Prefer Safer</option>
                                    <option value="shorter">Prefer Shorter</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const map = L.map('map').setView([9.3068, 123.3072], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        
        let marker = null;
        let markers = [];

        // Define custom icons for different statuses
        const icons = {
            active: L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background-color: #4CAF50; width: 15px; height: 15px; border-radius: 50%; border: 2px solid white;"></div>`,
                iconSize: [15, 15]
            }),
            inactive: L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background-color: #808080; width: 15px; height: 15px; border-radius: 50%; border: 2px solid white;"></div>`,
                iconSize: [15, 15]
            }),
            alert: L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background-color: #ff0000; width: 15px; height: 15px; border-radius: 50%; border: 2px solid white;"></div>`,
                iconSize: [15, 15]
            })
        };

        // Function to load and display saved locations
        function loadSavedLocations() {
            // Clear existing markers
            markers.forEach(marker => map.removeLayer(marker));
            markers = [];

            fetch('/locations')
                .then(response => response.json())
                .then(locations => {
                    locations.forEach(location => {
                        const marker = L.marker([location.latitude, location.longitude], {
                            icon: icons[location.status]
                        })
                        .addTo(map)
                        .bindPopup(`
                            <b>${location.name}</b><br>
                            Status: ${location.status}<br>
                            ${location.description || ''}
                        `);
                        markers.push(marker);
                    });
                });
        }

        // Load saved locations when page loads
        loadSavedLocations();

        // Refresh locations after adding a new one
        document.querySelector('form').addEventListener('submit', function() {
            setTimeout(loadSavedLocations, 1000); // Reload after 1 second
        });

        // Add geocoding control
        L.Control.geocoder({
            defaultMarkGeocode: false
        })
        .on('markgeocode', function(e) {
            const {center, name} = e.geocode;
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker(center).addTo(map);
            map.fitBounds(e.geocode.bbox);
            
            // Update form coordinates and name
            document.getElementById('latitude').value = center.lat;
            document.getElementById('longitude').value = center.lng;
            document.getElementById('name').value = name;
        })
        .addTo(map);

        function getCurrentLocation() {
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    if (marker) {
                        map.removeLayer(marker);
                    }
                    marker = L.marker([lat, lng]).addTo(map);
                    map.setView([lat, lng], 16);
                    
                    // Update form coordinates
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;

                    // Reverse geocode to get address
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                        .then(response => response.json())
                        .then(data => {
                            const locationName = data.display_name.split(',')[0];
                            document.getElementById('name').value = locationName;
                        });
                });
            } else {
                alert("Geolocation is not supported by your browser");
            }
        }

        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker(e.latlng).addTo(map);
            
            // Update form coordinates
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;

            // Reverse geocode to get address
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}`)
                .then(response => response.json())
                .then(data => {
                    const locationName = data.display_name.split(',')[0];
                    document.getElementById('name').value = locationName;
                });
        });

        let routeLayer = null;
        const avoidanceRadius = 0.1; // 100 meters radius around marked locations

        // Initialize geocoding controls for start and end locations
        const startGeocoder = L.Control.geocoder({
            defaultMarkGeocode: false,
            placeholder: 'Search start location...',
            collapsed: false
        })
        .on('markgeocode', function(e) {
            document.getElementById('start').value = e.geocode.name;
            document.getElementById('start_lat').value = e.geocode.center.lat;
            document.getElementById('start_lng').value = e.geocode.center.lng;
        });

        const endGeocoder = L.Control.geocoder({
            defaultMarkGeocode: false,
            placeholder: 'Search end location...',
            collapsed: false
        })
        .on('markgeocode', function(e) {
            document.getElementById('end').value = e.geocode.name;
            document.getElementById('end_lat').value = e.geocode.center.lat;
            document.getElementById('end_lng').value = e.geocode.center.lng;
        });

        // Add custom controls to the map
        const startControl = L.Control.extend({
            onAdd: function() {
                const container = L.DomUtil.create('div');
                container.appendChild(startGeocoder.onAdd(map));
                return container;
            }
        });

        const endControl = L.Control.extend({
            onAdd: function() {
                const container = L.DomUtil.create('div');
                container.appendChild(endGeocoder.onAdd(map));
                return container;
            }
        });

        new startControl({ position: 'topleft' }).addTo(map);
        new endControl({ position: 'topleft' }).addTo(map);

        // A* pathfinding helper functions
        function heuristic(point1, point2) {
            return L.latLng(point1[0], point1[1]).distanceTo(L.latLng(point2[0], point2[1]));
        }

        function calculateSafetyScore(route, markers) {
            let score = 100;
            route.forEach(point => {
                markers.forEach(marker => {
                    const distance = L.latLng(marker.getLatLng()).distanceTo(L.latLng(point[0], point[1]));
                    if (distance < avoidanceRadius * 1000) {
                        const impact = (avoidanceRadius * 1000 - distance) / (avoidanceRadius * 1000);
                        score -= impact * 20; // Reduce score based on proximity
                    }
                });
            });
            return Math.max(0, score);
        }

        function generateGridPoints(bounds, gridSize) {
            const points = [];
            const latStep = (bounds.getNorth() - bounds.getSouth()) / gridSize;
            const lngStep = (bounds.getEast() - bounds.getWest()) / gridSize;

            for (let lat = bounds.getSouth(); lat <= bounds.getNorth(); lat += latStep) {
                for (let lng = bounds.getWest(); lng <= bounds.getEast(); lng += lngStep) {
                    points.push([lat, lng]);
                }
            }
            return points;
        }

        async function findAlternativeRoutes(start, end, preference = 'balanced') {
            const gridPoints = generateGridPoints(
                L.latLngBounds([start, end]).pad(0.2),
                20 // Grid size
            );

            const routes = [];
            const waypoints = [
                start,
                end,
                ...gridPoints.filter(() => Math.random() < 0.3) // Randomly select some grid points as waypoints
            ];

            // Generate multiple route options using different waypoint combinations
            for (let i = 0; i < Math.min(5, waypoints.length - 1); i++) {
                const selectedWaypoints = [
                    start,
                    ...waypoints.slice(2).sort(() => Math.random() - 0.5).slice(0, i),
                    end
                ];

                try {
                    const response = await fetch(
                        `https://router.project-osrm.org/route/v1/driving/` +
                        selectedWaypoints.map(p => `${p[1]},${p[0]}`).join(';') +
                        '?geometries=geojson&alternatives=true'
                    );
                    const data = await response.json();

                    if (data.routes) {
                        data.routes.forEach(route => {
                            const coords = route.geometry.coordinates.map(c => [c[1], c[0]]);
                            const safetyScore = calculateSafetyScore(coords, markers);
                            const distance = route.distance;
                            
                            let routeScore;
                            switch (preference) {
                                case 'safer':
                                    routeScore = safetyScore * 0.8 + (1 - distance/10000) * 0.2;
                                    break;
                                case 'shorter':
                                    routeScore = safetyScore * 0.2 + (1 - distance/10000) * 0.8;
                                    break;
                                default: // balanced
                                    routeScore = safetyScore * 0.5 + (1 - distance/10000) * 0.5;
                            }

                            routes.push({
                                coords,
                                safetyScore,
                                distance,
                                routeScore
                            });
                        });
                    }
                } catch (error) {
                    console.error('Error fetching route:', error);
                }
            }

            // Sort routes by score and return top 3
            return routes
                .sort((a, b) => b.routeScore - a.routeScore)
                .slice(0, 3);
        }

        // Add this after other const declarations at the top of the script
        const routeColors = [
            { base: '#3388ff', highlight: '#0055cc' }, // blue
            { base: '#33cc33', highlight: '#009900' }, // green
            { base: '#ff6666', highlight: '#cc0000' }, // red
            { base: '#9933ff', highlight: '#6600cc' }, // purple
            { base: '#ff9933', highlight: '#cc6600' }  // orange
        ];

        // Update route form submission handler
        document.getElementById('routeForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const start = [
                parseFloat(document.getElementById('start_lat').value),
                parseFloat(document.getElementById('start_lng').value)
            ];
            const end = [
                parseFloat(document.getElementById('end_lat').value),
                parseFloat(document.getElementById('end_lng').value)
            ];

            // Clear existing routes
            if (routeLayer) {
                map.removeLayer(routeLayer);
            }

            const preference = document.getElementById('routePreference').value;
            const routes = await findAlternativeRoutes(start, end, preference);

            // Display routes
            const routesList = document.getElementById('routesList');
            routesList.innerHTML = '';
            document.getElementById('routeAlternatives').classList.remove('hidden');

            routes.forEach((route, index) => {
                const safetyColor = route.safetyScore > 80 ? '#4CAF50' : 
                                  route.safetyScore > 60 ? '#FFA500' : '#FF0000';
                
                // Use distinct colors for each route
                const routeColor = routeColors[index % routeColors.length];

                // Add route to map with hover effect
                const routePath = L.geoJSON({
                    type: 'Feature',
                    geometry: {
                        type: 'LineString',
                        coordinates: route.coords.map(c => [c[1], c[0]])
                    }
                }, {
                    style: {
                        color: routeColor.base,
                        weight: 5,
                        opacity: 0.7
                    }
                }).addTo(map);

                // Add hover effects
                routePath.on('mouseover', function() {
                    this.setStyle({
                        color: routeColor.highlight,
                        weight: 7,
                        opacity: 0.9
                    });
                }).on('mouseout', function() {
                    this.setStyle({
                        color: routeColor.base,
                        weight: 5,
                        opacity: 0.7
                    });
                });

                // Add route info to list with matching colors
                const routeElement = document.createElement('div');
                routeElement.className = 'p-4 bg-white rounded-lg shadow-sm cursor-pointer hover:bg-gray-50 transition-colors';
                routeElement.innerHTML = `
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="font-semibold" style="color: ${routeColor.base}">Route ${index + 1}</span>
                            <div class="text-sm text-gray-600">
                                Distance: ${(route.distance/1000).toFixed(1)}km
                                <br>
                                Safety Score: ${route.safetyScore.toFixed(0)}%
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 rounded-full" style="background-color: ${safetyColor}"></div>
                            <div class="w-3 h-3 rounded-full" style="background-color: ${routeColor.base}"></div>
                        </div>
                    </div>
                `;

                // Highlight route on list item hover
                routeElement.addEventListener('mouseover', () => {
                    routePath.setStyle({
                        color: routeColor.highlight,
                        weight: 7,
                        opacity: 0.9
                    });
                });
                routeElement.addEventListener('mouseout', () => {
                    routePath.setStyle({
                        color: routeColor.base,
                        weight: 5,
                        opacity: 0.7
                    });
                });

                routesList.appendChild(routeElement);
            });

            // Fit map to show all routes
            const bounds = L.latLngBounds([start, end]);
            routes.forEach(route => {
                route.coords.forEach(coord => bounds.extend(coord));
            });
            map.fitBounds(bounds);
        });
    </script>
</body>
</html>
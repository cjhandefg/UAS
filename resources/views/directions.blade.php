@extends('adminlte::page')

@section('title', 'Get Directions')

@section('content')
<!-- Add custom nav -->
<nav class="custom-nav">
    <div class="nav-brand">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="nav-logo">
        <span>Warung Wisata Kuliner</span>
    </div>
    <div class="nav-links">
        <a href="/" class="nav-link">
            <i class="fas fa-home"></i> Home
        </a>
        <a href="javascript:history.back()" class="nav-link">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</nav>

<div class="container-fluid" style="margin-top: 74px;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Directions to {{ $marker->name }}</h3>
            <div class="card-tools">
                <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Restaurant Details Section -->
            <div class="restaurant-details mb-4">
                <h5>{{ $marker->name }}</h5>
                <p><strong>Cuisine:</strong> {{ $marker->cuisine_type }}</p>
                <p><strong>Price Range:</strong> {{ $marker->price_range }}</p>
                <p><strong>Rating:</strong> {{ $marker->rating }} / 5</p>
                <p><strong>Operating Hours:</strong> {{ $marker->operating_hours }}</p>
                <p><strong>Description:</strong> {{ $marker->description }}</p>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <h5>Google Maps Route</h5>
                    <div id="googleMap" style="height: 400px;"></div>
                </div>
                <div class="col-md-6 mb-4">
                    <h5>OpenStreetMap Route</h5>
                    <div id="leafletMap" style="height: 400px;"></div>
                </div>
            </div>

            <!-- Directions Instructions Section -->
            <div id="directionsInstructions" class="mt-4">
                <h5>Directions Instructions</h5>
                <div id="instructionsContent">
                    <!-- Instructions will be populated here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/shared-styles.css') }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
<style>
.leaflet-routing-container {
    background: white;
    padding: 10px;
    max-height: 300px;
    overflow-y: auto;
}
</style>
@endsection

@section('js')
<!-- Load Google Maps API first -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4lKVb0eLSNyhEO-C_8JoHhAvba6aZc3U&libraries=places"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let userLocation = null;
    const markerLocation = {
        lat: {{ $marker->latitude }},
        lng: {{ $marker->longitude }}
    };

    // Get user location
    navigator.geolocation.getCurrentPosition(
        position => {
            userLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            initializeMaps();
        },
        error => {
            console.error("Location error:", error);
            userLocation = {lat: -8.7961228, lng: 115.1735968};
            initializeMaps();
        }
    );

    function initializeMaps() {
        // Google Maps
        const googleMap = new google.maps.Map(document.getElementById('googleMap'), {
            center: markerLocation,
            zoom: 13
        });

        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer();
        directionsRenderer.setMap(googleMap);

        const request = {
            origin: userLocation,
            destination: markerLocation,
            travelMode: google.maps.TravelMode.DRIVING
        };

        directionsService.route(request, (result, status) => {
            if (status === google.maps.DirectionsStatus.OK) {
                directionsRenderer.setDirections(result);
                // Populate directions instructions
                const directions = result.routes[0].legs[0].steps.map(step => step.instructions).join('<br>');
                document.getElementById('instructionsContent').innerHTML = directions;
            } else {
                console.error(`Error fetching directions ${result}`);
            }
        });

        // Leaflet Map
        const leafletMap = L.map('leafletMap').setView([markerLocation.lat, markerLocation.lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(leafletMap);

        L.Routing.control({
            waypoints: [
                L.latLng(userLocation.lat, userLocation.lng),
                L.latLng(markerLocation.lat, markerLocation.lng)
            ],
            routeWhileDragging: true,
            lineOptions: {
                styles: [{color: '#006D4E', weight: 4}]
            },
            createMarker: function() { return null; } // Disable marker creation
        }).addTo(leafletMap);
    }
});
</script>
@endsection
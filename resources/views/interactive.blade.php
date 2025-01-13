@extends('layouts.master')

@section('title', 'Interactive Map')

@section('content')
<div class="container-fluid px-4">
  <div class="row g-4">
    <!-- Maps Section -->
    <div class="col-lg-8">
      <div class="row g-4">
        <!-- Leaflet Map -->
        <div class="col-md-6">
          <div class="card h-100">
            <div class="card-header bg-white py-3">
              <h2 class="h5 mb-0">Leaflet Map</h2>
            </div>
            <div id="leaflet-map" style="height: 600px;"></div>
          </div>
        </div>

        <!-- Google Map -->
        <div class="col-md-6">
          <div class="card h-100">
            <div class="card-header bg-white py-3">
              <h2 class="h5 mb-0">Google Maps</h2>
            </div>
            <div id="google-map" style="height: 600px;"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Form Section -->
    <div class="col-lg-4">
      <div class="card" style="height: calc(600px + 3.5rem);">
        <div class="card-header bg-white py-3">
          <h2 class="h5 mb-0">Tambah Data Restoran</h2>
        </div>
        <div class="card-body overflow-auto">
          @include('layouts.marker-form')
        </div>
      </div>
    </div>
  </div>

  <!-- Table Section -->
  <div class="row mt-4">
    <div class="col-12">
      @include('layouts.markers-table')
    </div>
  </div>
</div>

    <!-- Footer Info -->
    <div class="card shadow-sm mt-4">
        <div class="card-body text-center">
            <h3 class="h5 text-primary mb-2">Informasi Tambahan</h3>
            <p class="text-muted mb-0">Data lokasi kampus diperbarui terakhir pada Desember 2024</p>
        </div>
    </div>
</div>

<x-edit-marker-modal />
@endsection

@section('css')
<style>
.card {
    border: none;
    border-radius: 0.5rem;
}
.card-header {
    border-bottom: 1px solid rgba(0,0,0,0.1);
}
.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}
.form-control {
    border-radius: 0.375rem;
}
.btn {
    border-radius: 0.375rem;
    padding: 0.5rem 1rem;
}
</style>
@endsection

@section('scripts')
<script src="{{ asset('js/maps.js') }}"></script>
<script>
function initPlacesAutocomplete() {
    const input = document.getElementById('searchPlace');
    const searchBtn = document.createElement('button');
    searchBtn.className = 'btn btn-primary mt-2';
    searchBtn.textContent = 'Search Location';
    input.parentNode.insertBefore(searchBtn, input.nextSibling);

    const autocomplete = new google.maps.places.Autocomplete(input);
    
    const handlePlaceSelection = () => {
        const place = autocomplete.getPlace();
        if (!place.geometry) {
            alert('Please select a location from the dropdown');
            return;
        }
        
        // Auto-fill form
        document.getElementById('markerName').value = place.name;
        document.getElementById('markerLat').value = place.geometry.location.lat();
        document.getElementById('markerLng').value = place.geometry.location.lng();
        document.getElementById('description').value = place.formatted_address;
        
        if (place.opening_hours) {
            document.getElementById('operatingHours').value = 
                place.opening_hours.weekday_text?.join(', ') || '';
        }

        if (place.rating) {
            document.getElementById('rating').value = place.rating;
        }

        const priceMap = {
            0: '$',
            1: '$',
            2: '$$',
            3: '$$$',
            4: '$$$'
        };
        if (place.price_level !== undefined) {
            document.getElementById('priceRange').value = priceMap[place.price_level];
        }

        // Center maps
        leafletMap.setView([place.geometry.location.lat(), place.geometry.location.lng()], 15);
        googleMap.setCenter(place.geometry.location);
        googleMap.setZoom(15);

        // Add markers
        const marker = L.marker([place.geometry.location.lat(), place.geometry.location.lng()]).addTo(leafletMap);
        new google.maps.Marker({
            position: place.geometry.location,
            map: googleMap,
            title: place.name
        });
    };

    searchBtn.addEventListener('click', handlePlaceSelection);
    autocomplete.addListener('place_changed', handlePlaceSelection);
    input.addEventListener('keyup', (e) => {
        if (e.key === 'Enter') {
            handlePlaceSelection();
        }
    });
}

document.getElementById('editMarkerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('editMarkerId').value;

    fetch(`/api/markers/${id}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            name: document.getElementById('editMarkerName').value,
            latitude: parseFloat(document.getElementById('editMarkerLat').value),
            longitude: parseFloat(document.getElementById('editMarkerLng').value),
            description: document.getElementById('editDescription').value || null,
            cuisine_type: document.getElementById('editCuisineType').value,
            price_range: document.getElementById('editPriceRange').value,
            rating: parseFloat(document.getElementById('editRating').value),
            operating_hours: document.getElementById('editOperatingHours').value,
        }),
    })
    .then(response => response.json())
    .then(data => {
        alert('Marker updated successfully!');
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update marker');
    });
});

function editMarker(id, name, lat, lng, description, cuisineType, priceRange, rating, operatingHours) {
    document.getElementById('editMarkerId').value = id;
    document.getElementById('editMarkerName').value = name;
    document.getElementById('editMarkerLat').value = lat;
    document.getElementById('editMarkerLng').value = lng;
    document.getElementById('editDescription').value = description || '';
    document.getElementById('editCuisineType').value = cuisineType || '';
    document.getElementById('editPriceRange').value = priceRange || '$';
    document.getElementById('editRating').value = rating || 5;
    document.getElementById('editOperatingHours').value = operatingHours || '';

    const modalElement = document.getElementById('editMarkerModal');
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
}

function resetLeafletMap() {
  if (window.mapManager && window.mapManager.leafletMap) {
    window.mapManager.leafletMap.setView(
      [CONFIG.defaultView.lat, CONFIG.defaultView.lng],
      CONFIG.defaultView.zoom
    );
  } else {
    console.error('Leaflet Map not initialized');
  }
}

function resetGoogleMap() {
  if (window.mapManager && window.mapManager.googleMap) {
    window.mapManager.googleMap.setCenter({ 
      lat: CONFIG.defaultView.lat, 
      lng: CONFIG.defaultView.lng 
    });
    window.mapManager.googleMap.setZoom(CONFIG.defaultView.zoom);
  } else {
    console.error('Google Map not initialized');
  }
}
  </script>

@endsection
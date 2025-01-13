// Configuration
const CONFIG = {
  defaultView: {
    lat: -8.7,
    lng: 115.2,
    zoom: 10
  },
  tileLayer: {
    url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
    attribution: 'Tiles &copy; Esri &mdash; Source: Esri'
  },
};

class MapManager {
  constructor() {
    this.leafletMap = null;
    this.googleMap = null;
    this.drawControl = null;
    this.markers = new Map();
    this.autocomplete = null;
  }

  async initialize() {
    try {
      await this.loadDependencies();
      await this.initializeMaps();
      await this.initializeControls();
      this.setupEventListeners();
      await this.loadExistingData();
    } catch (error) {
      console.error('Map initialization failed:', error);
      throw error;
    }
  }

  async loadDependencies() {
    // Load Leaflet Draw if not already loaded
    if (!L.Control.Draw) {
      await this.loadScript('https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js');
      await this.loadStylesheet('https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css');
    }
    const scripts = [
      'https://unpkg.com/leaflet@1.7.1/dist/leaflet.js',
      'https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js'
    ];
  
    const styles = [
      'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css',
      'https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css'
    ];
    try {
      for (const script of scripts) {
        await this.loadScript(script);
      }
  
      for (const style of styles) {
        await this.loadStylesheet(style);
      }
  
      console.log("All Dependencies Loaded");
    } catch (error) {
      console.error("Dependency Loading Failed:", error);
    }
  }

// Helper methods for dynamic script/style loading
loadScript(src) {
  return new Promise((resolve, reject) => {
    const script = document.createElement('script');
    script.src = src;
    script.onload = () => resolve();
    script.onerror = () => reject(new Error(`Script load error for ${src}`));
    document.head.appendChild(script);
  });
}

loadStylesheet(href) {
  return new Promise((resolve, reject) => {
    const link = document.createElement('link');
    link.href = href;
    link.rel = 'stylesheet';
    link.onload = () => resolve();
    link.onerror = () => reject(new Error(`Stylesheet load error for ${href}`));
    document.head.appendChild(link);
  });
}

  async initializeMaps() {
    // Initialize Leaflet Map
    this.leafletMap = L.map('leaflet-map').setView(
      [CONFIG.defaultView.lat, CONFIG.defaultView.lng],
      CONFIG.defaultView.zoom
    );
    
    L.tileLayer(CONFIG.tileLayer.url, {
      attribution: CONFIG.tileLayer.attribution
    }).addTo(this.leafletMap);

    // Initialize Google Map
    const googleMapDiv = document.getElementById('google-map');
    if (googleMapDiv) {
      this.googleMap = new google.maps.Map(googleMapDiv, {
        center: { 
          lat: CONFIG.defaultView.lat, 
          lng: CONFIG.defaultView.lng 
        },
        zoom: CONFIG.defaultView.zoom
      });
    }
  }

  initializeControls() {
    // Initialize Leaflet Draw Control
    this.drawControl = new L.Control.Draw({
      draw: {
        marker: {
          // Optional: Customize marker icon or behavior
          icon: new L.Icon.Default(), // Use default Leaflet marker icon
        },
        polyline: false,
        polygon: false,
        rectangle: false,
        circle: false,
        circlemarker: false
      }
    });
    
    this.leafletMap.addControl(this.drawControl);
      // Add event listener for draw:created event
  this.leafletMap.on('draw:created', this.handleDrawCreated.bind(this));


    // Initialize Places Autocomplete
    const input = document.getElementById('searchPlace');
    if (input && window.google && window.google.maps && window.google.maps.places) {
      this.autocomplete = new google.maps.places.Autocomplete(input);
      this.autocomplete.addListener('place_changed', () => this.handlePlaceSelection());
    }
  }

  setupEventListeners() {
    // Form submission handlers
    this.setupFormListeners();
    
    // Map event listeners
    this.leafletMap.on('draw:created', (e) => this.handleDrawCreated(e));
    
    // Table event listeners
    this.setupTableListeners();
  }

  setupFormListeners() {
    const markerForm = document.getElementById('markerForm');
    const editMarkerForm = document.getElementById('editMarkerForm');

    if (markerForm) {
      markerForm.addEventListener('submit', (e) => this.handleMarkerSubmit(e));
    }

    if (editMarkerForm) {
      editMarkerForm.addEventListener('submit', (e) => this.handleEditMarkerSubmit(e));
    }
  }

  setupTableListeners() {
    const markersTableBody = document.getElementById('markersTableBody');
    if (!markersTableBody) {
      console.error('Markers table body not found');
      return;
    }
  
    markersTableBody.addEventListener('click', (e) => {
      const target = e.target;
      
      if (target.matches('[data-action="edit-marker"]')) {
        const row = target.closest('tr');
        if (row) {
          const id = row.dataset.id;
          this.openEditMarkerModal(id);
        }
      }
      
      if (target.matches('[data-action="delete-marker"]')) {
        const row = target.closest('tr');
        if (row) {
          const id = row.dataset.id;
          this.deleteMarker(id);
        }
      }
    });
  }

  async loadExistingData() {
    try {
      const markersResponse = await fetch('/api/markers');
      const markers = await markersResponse.json();
      console.log('Loaded Markers:', markers); // Add this line
      markers.forEach(marker => this.addMarkerToMaps(marker));
      this.updateMarkersTable();
  
      // Log the markers Map after adding
      console.log('Markers Map:', this.markers);
    } catch (error) {
      console.error('Error loading existing data:', error);
      throw error;
    }
  }

  // Marker Methods
  async handleMarkerSubmit(event) {
    event.preventDefault();
    
    try {
      const formData = new FormData(event.target);
      const response = await fetch('/api/markers', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': this.getCSRFToken(),
          'Accept': 'application/json'
        },
        body: formData
      });
  
      if (!response.ok) {
        const error = await response.json();
        throw new Error(error.message || 'Failed to create marker');
      }
  
      const marker = await response.json();
      this.addMarkerToMaps(marker);
      this.updateMarkersTable();
      event.target.reset();
      
    } catch (error) {
      console.error('Error creating marker:', error);
      alert(error.message || 'Failed to create marker');
    }
  }
  
  addMarkerToMaps(markerData) {
    // Create Leaflet marker
    const leafletMarker = L.marker([markerData.latitude, markerData.longitude])
      .addTo(this.leafletMap)
      .bindPopup(this.createMarkerPopup(markerData));

    // Create Google marker
    const googleMarker = new google.maps.Marker({
      position: { 
        lat: parseFloat(markerData.latitude), 
        lng: parseFloat(markerData.longitude) 
      },
      map: this.googleMap,
      title: markerData.name
    });

    // Create info window
    const infoWindow = new google.maps.InfoWindow({
      content: this.createMarkerPopup(markerData),
      maxWidth: 300
    });

    googleMarker.addListener('click', () => {
      infoWindow.open(this.googleMap, googleMarker);
    });

    // Store references
    this.markers.set(markerData.id, {
      leaflet: leafletMarker,
      google: googleMarker,
      data: markerData,
      infoWindow
    });
  }

  createMarkerPopup(markerData) {
    return `
      <div class="marker-popup" style="min-width: 200px;">
        <h3 class="font-bold text-lg mb-2">${markerData.name}</h3>
        ${markerData.image_path ? 
          `<img src="/storage/${markerData.image_path}" alt="${markerData.name}" 
           style="width: 100%; height: 150px; object-fit: cover; margin-bottom: 10px;">` : ''}
        <p class="mb-2">${markerData.description || ''}</p>
        <div class="grid grid-cols-2 gap-2 text-sm">
          <div>
            <strong>Cuisine:</strong><br>
            ${markerData.cuisine_type || 'N/A'}
          </div>
          <div>
            <strong>Price:</strong><br>
            ${markerData.price_range || 'N/A'}
          </div>
          <div>
            <strong>Rating:</strong><br>
            ${this.createRatingStars(markerData.rating)}
          </div>
          <div>
            <strong>Hours:</strong><br>
            ${markerData.operating_hours || 'N/A'}
          </div>
        </div>
      </div>
    `;
  }

  createRatingStars(rating) {
    if (!rating) return 'Not rated';
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;
    return '★'.repeat(fullStars) + (hasHalfStar ? '½' : '') + ` (${rating})`;
  }

  // UI Update Methods
  updateMarkersTable() {
    const tableBody = document.getElementById('markersTableBody');
    if (!tableBody) {
      console.error('Markers table body not found');
      return;
    }
  
    let html = '';
    this.markers.forEach((marker, id) => {
      console.log('Marker in table update:', marker.data); // Log each marker
      html += `
        <tr data-id="${marker.data.id}">
          <td>${marker.data.name}</td>
          <td>${marker.data.latitude}</td>
          <td>${marker.data.longitude}</td>
          <td>
            <button data-action="edit-marker" class="btn btn-primary btn-sm">Edit</button>
            <button data-action="delete-marker" class="btn btn-danger btn-sm">Delete</button>
          </td>
        </tr>
      `;
    });
    
    tableBody.innerHTML = html;
  }

  createMarkerTableRow(marker) {
    return `
      <tr data-id="${marker.id}">
        <td>${marker.name}</td>
        <td>${marker.latitude}</td>
        <td>${marker.longitude}</td>
        <td>
          <button data-action="edit-marker" class="btn btn-primary btn-sm">Edit</button>
          <button data-action="delete-marker" class="btn btn-danger btn-sm">Delete</button>
        </td>
      </tr>
    `;
  }


  // Places Autocomplete Handler
  handlePlaceSelection() {
    const place = this.autocomplete.getPlace();
    if (!place.geometry) return;

    const latLng = {
      lat: place.geometry.location.lat(),
      lng: place.geometry.location.lng()
    };

    // Update form fields
    document.getElementById('markerName').value = place.name;
    document.getElementById('markerLat').value = latLng.lat;
    document.getElementById('markerLng').value = latLng.lng;

    // Update maps
    this.leafletMap.setView([latLng.lat, latLng.lng], 15);
    this.googleMap.setCenter(latLng);
    this.googleMap.setZoom(15);
  }

  // Utility Methods
  getCSRFToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  }

  showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal && window.bootstrap) {
      const bsModal = new bootstrap.Modal(modal);
      bsModal.show();
    }
  }

// ... (previous code remains the same) ...

hideModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal && window.bootstrap) {
    const bsModal = bootstrap.Modal.getInstance(modal);
    if (bsModal) bsModal.hide();
  }
}

// Delete Methods
async deleteMarker(id) {
  if (!confirm('Are you sure?')) return;

  try {
    const response = await fetch(`/api/markers/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': this.getCSRFToken(),
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    });

    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.message || 'Failed to delete');
    }

    const marker = this.markers.get(id);
    if (marker) {
      marker.leaflet.remove();
      marker.google.setMap(null);
      marker.infoWindow.close();
      this.markers.delete(id);
      this.updateMarkersTable();
    }

  } catch (error) {
    console.error('Error deleting marker:', error);
    alert(error.message || 'Failed to delete marker');
  }
}

// Edit Methods
openEditMarkerModal(id) {
  console.log('Attempting to edit marker with ID:', id);
  console.log('Current markers:', this.markers);

  const marker = this.markers.get(parseInt(id)); // Use parseInt to ensure number comparison
  if (!marker) {
    console.error('Marker not found', id);
    alert(`Marker with ID ${id} not found. Please refresh the page.`);
    return;
  }

  const form = document.getElementById('editMarkerForm');
  if (!form) {
    console.error('Edit marker form not found');
    return;
  }

  // Populate form fields
  form.elements.editMarkerId.value = id;
  form.elements.editMarkerName.value = marker.data.name;
  form.elements.editMarkerLat.value = marker.data.latitude;
  form.elements.editMarkerLng.value = marker.data.longitude;
  form.elements.editDescription.value = marker.data.description || '';
  form.elements.editCuisineType.value = marker.data.cuisine_type || '';
  form.elements.editPriceRange.value = marker.data.price_range || '$';
  form.elements.editRating.value = marker.data.rating || '';
  form.elements.editOperatingHours.value = marker.data.operating_hours || '';

  // Show modal
  this.showModal('editMarkerModal');
}

  // Update edit marker handler
async handleEditMarkerSubmit(event) {
  event.preventDefault();
  const form = event.target;
  const markerId = form.querySelector('input[name="marker_id"]').value;

  try {
    const formData = new FormData(form);
    
    // Log the form data being sent
    console.log('Updating marker with data:', Object.fromEntries(formData));

    const response = await fetch(`/api/markers/${markerId}`, {
      method: 'PUT', // or 'PATCH' depending on your backend
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: formData
    });

    // Log the full response
    const responseData = await response.json();
    console.log('Full response:', responseData);

    // Check if the response is successful
    if (!response.ok) {
      throw new Error(responseData.message || 'Failed to update marker');
    }

    // Update the marker in the map
    const updatedMarker = responseData.marker || responseData;
    this.updateMarkerInMap(updatedMarker);

    // Close the modal
    this.closeEditMarkerModal();

    // Show success toast
    this.showToast('Marker updated successfully');

  } catch (error) {
    console.error('Detailed Error updating marker:', error);
    
    // More informative error handling
    const errorMessage = error.message || 'An unexpected error occurred';
    this.showToast(`Error: ${errorMessage}`, 'error');
  }
}

// Add a method to show toast notifications
showToast(message, type = 'success') {
  // Create a toast element if it doesn't exist
  let toastContainer = document.getElementById('toast-container');
  if (!toastContainer) {
    toastContainer = document.createElement('div');
    toastContainer.id = 'toast-container';
    toastContainer.style.position = 'fixed';
    toastContainer.style.top = '20px';
    toastContainer.style.right = '20px';
    toastContainer.style.zIndex = '1000';
    document.body.appendChild(toastContainer);
  }

  // Create toast element
  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  toast.textContent = message;
  toast.style.backgroundColor = type === 'success' ? 'green' : 'red';
  toast.style.color = 'white';
  toast.style.padding = '10px';
  toast.style.marginBottom = '10px';
  toast.style.borderRadius = '5px';

  // Add toast to container
  toastContainer.appendChild(toast);

  // Remove toast after 3 seconds
  setTimeout(() => {
    toast.remove();
  }, 3000);
}

// Method to update marker in map
updateMarkerInMap(updatedMarker) {
  // Find and update the marker in the markers Map
  const existingMarker = this.markers.get(updatedMarker.id);
  if (existingMarker) {
    // Update marker data
    existingMarker.data = updatedMarker;
    
    // Update marker position if latitude/longitude changed
    if (existingMarker.marker) {
      existingMarker.marker.setLatLng([
        updatedMarker.latitude, 
        updatedMarker.longitude
      ]);
    }

    // Optionally update the markers table
    this.updateMarkersTable();
  }
}

// Draw Event Handler
handleDrawCreated(event) {
  const layer = event.layer;
  const type = event.layerType;

  if (type === 'marker') {
    const latLng = layer.getLatLng();
    
    // Prompt for marker details
    const name = prompt('Enter marker name:');
    
    if (name) {
      // Create a marker object directly instead of using FormData
      const markerData = {
        name: name,
        latitude: latLng.lat,
        longitude: latLng.lng,
        description: prompt('Enter description (optional):') || '',
        cuisine_type: prompt('Enter cuisine type (optional):') || 'Unknown',
        price_range: prompt('Enter price range ($-$$$$):') || '$',
        rating: prompt('Enter rating (1-5):') || '3',
        operating_hours: prompt('Enter operating hours (optional):') || 'Not specified'
      };

      // Call marker submission method with the data object
      this.submitMarker(markerData)
        .then(response => {
          // Add the marker to the map
          this.leafletMap.addLayer(layer);
          console.log('Marker created successfully', response);
        })
        .catch(error => {
          console.error('Error creating marker:', error);
          alert('Failed to create marker. Please try again.');
        });
    }
  }
}

// New method to handle marker submission via AJAX
submitMarker(markerData) {
  return fetch('/markers', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify(markerData)
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.json();
  });
}

handleDrawnMarker(layer) {
  const latLng = layer.getLatLng();
  const name = prompt('Enter marker name:');
  
  if (name) {
    const markerData = {
      name,
      latitude: latLng.lat,
      longitude: latLng.lng
    };

    // Add marker using the form submit handler
    const form = document.getElementById('markerForm');
    if (form) {
      form.elements.markerName.value = name;
      form.elements.markerLat.value = latLng.lat;
      form.elements.markerLng.value = latLng.lng;
      form.dispatchEvent(new Event('submit'));
    }
  }
}
}

document.addEventListener('DOMContentLoaded', () => {
  try {
    const mapManager = new MapManager();
    mapManager.initialize()
      .then(() => {
        // Explicitly make it global if needed
        window.mapManager = mapManager;
      })
      .catch(error => {
        console.error('Failed to initialize maps:', error);
        alert('Failed to load maps. Please refresh the page.');
      });
  } catch (error) {
    console.error('Map manager initialization error:', error);
  }
});
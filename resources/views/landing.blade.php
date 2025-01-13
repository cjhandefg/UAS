@extends('adminlte::page')

@section('title', 'Warung Wisata Kuliner')

@section('content_header')
@stop

@section('content')
<!-- Custom Navigation -->
<nav class="custom-nav">
    <div class="nav-brand">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="nav-logo">
        <span>Warung Wisata Kuliner</span>
    </div>
    <div class="nav-links">
        <a href="#" class="nav-link" data-section="home">
            <i class="fas fa-home"></i> Home
        </a>
        <a href="#" class="nav-link" data-section="favorites">
            <i class="fas fa-heart"></i> Favorites
        </a>
        <a href="#" class="nav-link">
            <i class="fas fa-info-circle"></i> About Us
        </a>
    </div>
</nav>

<!-- Home Section -->
<div id="home-section">
    <!-- Hero Section -->
    <div class="hero-overlay">
        <div class="text-white text-center">
            <h1 class="hero-title mb-4">What would you like today?</h1>
            <button class="btn btn-outline-light btn-lg px-5 hero-btn">Discover Flavors</button>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="container my-4">
        <h4>Filter Restaurants</h4>
        <form id="filterForm" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="cuisineType" class="form-label">Cuisine Type</label>
                    <select id="cuisineType" class="form-select">
                        <option value="">All</option>
                        <option value="Indonesian">Indonesian</option>
                        <option value="Chinese">Chinese</option>
                        <option value="Western">Western</option>
                        <option value="Japanese">Japanese</option>
                        <option value="Korean">Korean</option>
                        <option value="Thai">Thai</option>
                        <option value="Indian">Indian</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="priceRange" class="form-label">Price Range</label>
                    <select id="priceRange" class="form-select">
                        <option value="">All</option>
                        <option value="$">$ (Budget)</option>
                        <option value="$$">$$ (Mid-Range)</option>
                        <option value="$$$">$$$ (High-End)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="rating" class="form-label">Rating</label>
                    <select id="rating" class="form-select">
                        <option value="">All</option>
                        <option value="1">1 Star</option>
                        <option value="2">2 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="5">5 Stars</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Apply Filters</button>
        </form>
    </div>

    <!-- Featured Section -->
    <section id="featured" class="content py-5">
        <div class="container-fluid">
            <div class="section-header text-center mb-5">
                <h2 class="display-4">Popular Today</h2>
                <p class="text-muted">Discover the most loved dishes in your area</p>
            </div>
            
            <div class="row" id="restaurantList">
                @foreach($restaurants as $restaurant)
                <div class="col-lg-4 mb-4 restaurant-card" data-restaurant="{{ json_encode($restaurant) }}">
                    <div class="card restaurant-card h-100">
                        <div class="card-favorite">
                            <button class="btn-favorite" data-restaurant-id="{{ $restaurant->id }}">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                        <img src="{{ asset('storage/' . $restaurant->image_path) }}" class="card-img-top restaurant-img" alt="{{ $restaurant->name }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title mb-0">{{ $restaurant->name }}</h5>
                                <span class="badge bg-success">Open</span>
                            </div>
                            <p class="card-text text-muted">{{ $restaurant->cuisine_type }} • {{ $restaurant->price_range }}</p>
                            <div class="rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $restaurant->rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @elseif($i - 0.5 <= $restaurant->rating)
                                        <i class="fas fa-star-half-alt text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                                <span class="ml-2">{{ $restaurant->rating }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>

<!-- Favorites Section -->
<div id="favorites-section" style="display: none;">
    <div class="container-fluid py-5">
        <div class="section-header text-center mb-5">
            <h2 class="display-4">Your Favorites</h2>
            <p class="text-muted">Restaurants you've marked as favorites</p>
        </div>
        <div id="favorites-container" class="row">
            <!-- Favorites will be populated here -->
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<link rel="stylesheet" href="{{ asset('css/shared-styles.css') }}">
<style>
    .main-header, .main-sidebar, [class*="sidebar-dark-"] {
        display: none !important;
    }

    .content-wrapper {
        margin-left: 0 !important;
        background: white !important;
    }

    .custom-nav {
        background: #006D4E;
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
    }

    .nav-brand {
        display: flex;
        align-items: center;
        color: white;
    }

    .nav-logo {
        height: 40px;
        margin-right: 1rem;
    }

    .nav-links {
        display: flex;
        gap: 2rem;
    }

    .nav-link {
        color: white;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
    }

    .nav-link:hover {
        color: #e0e0e0;
    }

    .nav-link.active {
        color: #4CAF50;
    }

    .hero-overlay {
        margin-top: 74px;
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
            url('{{ asset('images/hero-food.jpg') }}') no-repeat center center;
        background-size: cover;
        height: 90vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hero-title {
        font-size: 4rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .card-favorite {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 1;
    }

    .btn-favorite {
        background: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }

    .btn-favorite:hover {
        transform: scale(1.1);
    }

    .btn-favorite i {
        font-size: 1.2rem;
        color: #ff4081;
    }

    .favorite-active i {
        font-weight: 900;
    }

    .restaurant-img {
        width: 100%; /* Make the image take the full width of the card */
        height: 200px; /* Set a fixed height */
        object-fit: cover; /* Ensure the image covers the area without distortion */
        border-radius: 8px; /* Optional: Add rounded corners */
  }
</style>
@stop

@section('js')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4lKVb0eLSNyhEO-C_8JoHhAvba6aZc3U&libraries=places"></script>
<script>
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const cuisineType = document.getElementById('cuisineType').value;
        const priceRange = document.getElementById('priceRange').value;
        const rating = document.getElementById('rating').value;

        const restaurantCards = document.querySelectorAll('.restaurant-card');
        restaurantCards.forEach(card => {
            const restaurantData = JSON.parse(card.getAttribute('data-restaurant'));
            
            // Ensure restaurantData is not null and has the expected properties
            const matchesCuisine = restaurantData && restaurantData.cuisine_type 
                ? (cuisineType ? restaurantData.cuisine_type === cuisineType : true) 
                : true;
            const matchesPrice = restaurantData && restaurantData.price_range 
                ? (priceRange ? restaurantData.price_range === priceRange : true) 
                : true;
            const matchesRating = restaurantData && restaurantData.rating 
                ? (rating ? restaurantData.rating >= rating : true) 
                : true;

            if (matchesCuisine && matchesPrice && matchesRating) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>
<script>
// Initialize maps and variables
let modalMap;
let modalLeafletMap = null;
let activeMarker;
let activeLeafletMarker;

document.addEventListener('DOMContentLoaded', function() {
    // Remove sidebar and init UI
    document.body.classList.remove('sidebar-mini');
    document.body.classList.add('sidebar-collapse');
    
    // Init navigation
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            showSection(this.getAttribute('data-section') || 'home');
        });
    });

    // Init discover button
    const discoverBtn = document.querySelector('.hero-btn');
    if (discoverBtn) {
        discoverBtn.addEventListener('click', scrollToFeatured);
    }

    // Init restaurant cards
    document.querySelectorAll('.restaurant-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (!e.target.closest('.btn-favorite')) {
                const restaurantData = JSON.parse(this.getAttribute('data-restaurant'));
                showRestaurantDetails(restaurantData);
            }
        });
    });

    // Load favorites from localStorage
    loadFavorites();
});

// Favorites handling
function loadFavorites() {
    const favorites = getFavorites();
    
    // Update UI for all favorite buttons
    document.querySelectorAll('.btn-favorite').forEach(btn => {
        const restaurantId = parseInt(btn.getAttribute('data-restaurant-id'));
        if (favorites.includes(restaurantId)) {
            btn.classList.add('favorite-active');
            btn.querySelector('i').classList.replace('far', 'fas');
        }
        
        // Add click handler
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleFavorite(restaurantId);
        });
    });
}

function getFavorites() {
    return JSON.parse(localStorage.getItem('favorites') || '[]');
}

function toggleFavorite(restaurantId) {
    const favorites = getFavorites();
    const index = favorites.indexOf(restaurantId);
    const btn = event.currentTarget;
    
    if (index === -1) {
        favorites.push(restaurantId);
        btn.classList.add('favorite-active');
        btn.querySelector('i').classList.replace('far', 'fas');
    } else {
        favorites.splice(index, 1);
        btn.classList.remove('favorite-active');
        btn.querySelector('i').classList.replace('fas', 'far');
    }
    
    localStorage.setItem('favorites', JSON.stringify(favorites));
    
    // Update favorites section if visible
    if (document.getElementById('favorites-section').style.display === 'block') {
        displayFavorites();
    }
}

function displayFavorites() {
    const favorites = getFavorites();
    const container = document.getElementById('favorites-container');
    
    if (favorites.length === 0) {
        container.innerHTML = '<div class="col-12 text-center"><p>No favorites yet!</p></div>';
        return;
    }

    // Get all restaurant cards
    const allRestaurants = Array.from(document.querySelectorAll('.restaurant-card'))
        .map(card => JSON.parse(card.getAttribute('data-restaurant')))
        .filter(restaurant => restaurant !== null); // Ensure restaurant is not null
    
    // Filter favorite restaurants
    const favoriteRestaurants = allRestaurants.filter(restaurant => 
        restaurant && favorites.includes(restaurant.id) // Check if restaurant is valid
    );
    
    // Generate HTML for favorite restaurants
    container.innerHTML = favoriteRestaurants.map(restaurant => `
        <div class="col-lg-4 mb-4">
            <div class="card restaurant-card" data-restaurant='${JSON.stringify(restaurant)}'>
                <div class="card-favorite">
                    <button class="btn-favorite favorite-active" data-restaurant-id="${restaurant.id}">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
                <img src="/storage/${restaurant.image_path}" class="card-img-top restaurant-img" alt="${restaurant.name}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title mb-0">${restaurant.name}</h5>
                        <span class="badge bg-success">Open</span>
                    </div>
                    <p class="card-text text-muted">${restaurant.cuisine_type} • ${restaurant.price_range}</p>
                    <div class="rating">
                        ${generateRatingStars(restaurant.rating)}
                        <span class="ml-2">${restaurant.rating}</span>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
    
    // Reinitialize click handlers for the new cards
    initFavoriteCardHandlers();
}

function generateRatingStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            stars += '<i class="fas fa-star text-warning"></i>';
        } else if (i - 0.5 <= rating) {
            stars += '<i class="fas fa-star-half-alt text-warning"></i>';
        } else {
            stars += '<i class="far fa-star text-warning"></i>';
        }
    }
    return stars;
}

function initFavoriteCardHandlers() {
    document.querySelectorAll('#favorites-container .restaurant-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (!e.target.closest('.btn-favorite')) {
                const restaurantData = JSON.parse(this.getAttribute('data-restaurant'));
                showRestaurantDetails(restaurantData);
            }
        });
        
        const favoriteBtn = card.querySelector('.btn-favorite');
        if (favoriteBtn) {
            favoriteBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const restaurantId = parseInt(this.getAttribute('data-restaurant-id'));
                toggleFavorite(restaurantId);
            });
        }
    });
}

function showRestaurantDetails(restaurant) {
    const modalElement = document.getElementById('restaurantModal');
    if (!modalElement) return;
    
    const modal = new bootstrap.Modal(modalElement);
    
    modalElement.querySelector('.modal-title').textContent = restaurant.name;
    
    const imgElement = document.getElementById('restaurantImage');
    if (imgElement) {
        imgElement.src = `/storage/${restaurant.image_path}`;
    }
    
    const detailsElement = document.getElementById('restaurantDetails');
    if (detailsElement) {
        const favorites = getFavorites();
        const isFavorite = favorites.includes(restaurant.id);
        
        detailsElement.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>${restaurant.name}</h4>
                <button class="btn-favorite ${isFavorite ? 'favorite-active' : ''}" data-restaurant-id="${restaurant.id}">
                    <i class="${isFavorite ? 'fas' : 'far'} fa-heart"></i>
                </button>
            </div>
            <h6>Cuisine: ${restaurant.cuisine_type}</h6>
            <h6>Price: ${restaurant.price_range}</h6>
            <h6>Rating: ${generateRatingStars(restaurant.rating)} ${restaurant.rating}</h6>
            <h6>Hours: ${restaurant.operating_hours}</h6>
            <p class="mt-3">${restaurant.description || ''}</p>
            <div class="mt-4">
                <a href="/markers/${restaurant.id}/directions" class="btn btn-success" id="getDirectionsBtn">
                    <i class="fas fa-directions"></i> Get Directions
                </a>
            </div>
        `;
        
        const favoriteBtn = detailsElement.querySelector('.btn-favorite');
        favoriteBtn.addEventListener('click', function() {
            const restaurantId = parseInt(this.getAttribute('data-restaurant-id'));
            toggleFavorite(restaurantId);
        });
    }
    
    modal.show();
    
    modalElement.addEventListener('shown.bs.modal', function () {
        initializeModalMaps(restaurant);
    });
}

function initializeModalMaps(restaurant) {
    const lat = parseFloat(restaurant.latitude);
    const lng = parseFloat(restaurant.longitude);
    const position = { lat, lng };

    // Function to create custom popup content
    function createMarkerPopup(restaurant) {
    return `
        <div class="marker-popup" style="min-width: 100px; display: flex; align-items: stretch;">
            ${restaurant.image_path ? 
                `<div style="width: 150px; margin-right: 15px;">
                    <img src="/storage/${restaurant.image_path}" alt="${restaurant.name}" 
                    style="width: 150px; height: 150px; object-fit: cover; border-radius: 8px;">
                </div>` : ''}
            <div style="flex-grow: 1;">
                <h3 class="font-bold text-lg mb-2">${restaurant.name}</h3>
                <p class="mb-2 text-sm">${restaurant.description || ''}</p>
                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <div style="flex: 1; min-width: 120px;">
                        <strong>Cuisine:</strong><br>
                        ${restaurant.cuisine_type || 'N/A'}
                    </div>
                    <div style="flex: 1; min-width: 120px;">
                        <strong>Price:</strong><br>
                        ${restaurant.price_range || 'N/A'}
                    </div>
                    <div style="flex: 1; min-width: 120px;">
                        <strong>Rating:</strong><br>
                        ${createRatingStars(restaurant.rating)}
                    </div>
                    <div style="flex: 1; min-width: 120px;">
                        <strong>Hours:</strong><br>
                        ${restaurant.operating_hours || 'N/A'}
                    </div>
                </div>
            </div>
        </div>
    `;
}

    function createRatingStars(rating) {
        if (!rating) return 'Not rated';
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        return '★'.repeat(fullStars) + (hasHalfStar ? '½' : '') + ` (${rating})`;
    }

    // Initialize Google Maps
    if (!modalMap) {
        modalMap = new google.maps.Map(document.getElementById('modalMap'), {
            center: position,
            zoom: 15
        });
    } else {
        modalMap.setCenter(position);
        modalMap.setZoom(15);
    }

    if (activeMarker) {
        activeMarker.setMap(null);
    }

    // Create Google Maps marker with custom info window
    const infoWindow = new google.maps.InfoWindow({
        content: createMarkerPopup(restaurant),
        maxWidth: 300
    });

    activeMarker = new google.maps.Marker({
        position: position,
        map: modalMap,
        title: restaurant.name,
        animation: google.maps.Animation.DROP
    });

    activeMarker.addListener('click', () => {
        infoWindow.open(modalMap, activeMarker);
    });

    // Initialize Leaflet Map
    if (!modalLeafletMap) {
        // Wait for the container to be ready
        setTimeout(() => {
            modalLeafletMap = L.map('modalLeafletMap').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(modalLeafletMap);
            
            const leafletMarker = L.marker([lat, lng])
                .addTo(modalLeafletMap)
                .bindPopup(createMarkerPopup(restaurant), {
                    className: 'custom-popup',
                    maxWidth: 300
                });
            
            leafletMarker.openPopup();
        }, 100);
    } else {
        modalLeafletMap.setView([lat, lng], 15);
        modalLeafletMap.invalidateSize();
    }
}

function showSection(section) {
    document.getElementById('home-section').style.display = section === 'home' ? 'block' : 'none';
    document.getElementById('favorites-section').style.display = section === 'favorites' ? 'block' : 'none';
    
    if (section === 'favorites') {
        displayFavorites();
    }

    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('data-section') === section) {
            link.classList.add('active');
        }
    });
}

function scrollToFeatured() {
    document.getElementById('featured').scrollIntoView({
        behavior: 'smooth'
    });
}
</script>
@include('restaurants.restaurant-modal')
@stop
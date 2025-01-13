<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Warung Wisata Kuliner')</title>

  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Leaflet.js CDN -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Leaflet Draw -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />

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

    .map-container {
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      border-radius: 0.75rem;
      overflow: hidden;
    }

    .map-container:hover {
      transform: scale(1.02);
      transition: transform 0.3s ease-in-out;
    }

    body {
      background-color: #f4f6f9;
      padding-top: 74px; /* Account for fixed navbar */
    }

    .coordinate-cell {
      transition: background-color 0.3s ease;
      cursor: pointer;
    }

    .coordinate-cell:hover {
      background-color: #f8f9fa;
    }

    .card {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 1rem;
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

    .restaurant-card {
        transition: transform 0.3s;
        cursor: pointer;
    }

    .restaurant-card:hover {
        transform: translateY(-5px);
    }

    .restaurant-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px 8px 0 0;
    }

    @media (max-width: 768px) {
        .custom-nav {
            padding: 0.5rem 1rem;
        }
        
        .nav-links {
            gap: 1rem;
        }
        
        .nav-logo {
            height: 30px;
        }
    }
  </style>

  @yield('css')
</head>

<body class="sidebar-collapse">
  <!-- Navigation -->
  <nav class="custom-nav">
    <div class="nav-brand">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="nav-logo">
        <span>Warung Wisata Kuliner</span>
    </div>
    <div class="nav-links">
        <a href="/" class="nav-link @if(request()->is('/')) active @endif">
            <i class="fas fa-home"></i> Home
        </a>
        <a href="#" class="nav-link @if(request()->is('#')) active @endif">
            <i class="fas fa-map-marked-alt"></i> Interactive Map
        </a>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="wrapper">
    <div class="content-wrapper">
      <div class="content">
        <div class="container-fluid">
          @yield('content')
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4lKVb0eLSNyhEO-C_8JoHhAvba6aZc3U&libraries=places"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

  @yield('scripts')
</body>

</html>
<div class="modal fade" id="restaurantModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-4">
          <!-- Left side - Image and Details -->
          <div class="col-md-4">
            <img id="restaurantImage" class="img-fluid rounded mb-3" alt="Restaurant">
          </div>
          <div class="col-md-8">
            <div id="restaurantDetails" class="px-3"></div>
          </div>
        </div>
        <!-- Bottom Maps -->
        <div class="row">
          <div class="col-md-6">
            <h6 class="mb-2">Google Maps</h6>
            <div id="modalMap" style="height: 400px;" class="mb-3"></div>
          </div>
          <div class="col-md-6">
            <h6 class="mb-2">Leaflet Map</h6>
            <div id="modalLeafletMap" style="height: 400px;"></div>
          </div>
        </div>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- This should be in your edit-marker-modal.blade.php -->
 
<div class="modal fade" id="editMarkerModal" tabindex="-1" aria-labelledby="editMarkerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMarkerModalLabel">Edit Restaurant Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="editMarkerForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT"> <!-- This line is important -->
            <input type="hidden" id="editMarkerId">
                    
                    <div class="mb-3">
                        <label for="editMarkerName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="editMarkerName" required>
                    </div>

                    <div class="mb-3">
                        <label for="editMarkerLat" class="form-label">Latitude</label>
                        <input type="number" step="any" class="form-control" id="editMarkerLat" required>
                    </div>

                    <div class="mb-3">
                        <label for="editMarkerLng" class="form-label">Longitude</label>
                        <input type="number" step="any" class="form-control" id="editMarkerLng" required>
                    </div>

                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editDescription"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="editCuisineType" class="form-label">Cuisine Type</label>
                        <input type="text" class="form-control" id="editCuisineType" required>
                    </div>

                    <div class="mb-3">
                        <label for="editPriceRange" class="form-label">Price Range</label>
                        <select class="form-control" id="editPriceRange" required>
                            <option value="$">$</option>
                            <option value="$$">$$</option>
                            <option value="$$$">$$$</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editRating" class="form-label">Rating</label>
                        <input type="number" min="1" max="5" step="0.1" class="form-control" id="editRating" required>
                    </div>

                    <div class="mb-3">
                        <label for="editOperatingHours" class="form-label">Operating Hours</label>
                        <input type="text" class="form-control" id="editOperatingHours" required>
                    </div>

                    <div class="mb-3">
                        <label for="editImage" class="form-label">New Image (optional)</label>
                        <input type="file" class="form-control" id="editImage" accept="image/*">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
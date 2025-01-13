<div class="card mb-4">
    <div class="card-header bg-white">
        <h3 class="text-xl font-semibold text-blue-700 text-center">Tambah Data Restoran</h3>
    </div>
    <div class="card-body">
        <form id="markerForm">
            <!-- Google Places Autocomplete -->
            <div class="form-group mb-3">
                <label class="form-label">Cari Restoran di Google Maps</label>
                <input 
                    id="searchPlace" 
                    class="form-control" 
                    type="text" 
                    placeholder="Cari restoran..."
                >
            </div>

            <!-- Nama Restoran -->
            <div class="form-group mb-3">
                <label class="form-label">Nama Restoran</label>
                <input 
                    type="text" 
                    id="markerName" 
                    name="name" 
                    class="form-control" 
                    required
                >
            </div>

            <!-- Deskripsi -->
            <div class="form-group mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea 
                    id="description" 
                    name="description" 
                    class="form-control" 
                    rows="3"
                ></textarea>
            </div>

            <!-- Koordinat -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Latitude</label>
                    <input 
                        type="number" 
                        id="markerLat" 
                        name="latitude" 
                        class="form-control" 
                        step="any" 
                        required
                    >
                </div>
                <div class="col-md-6">
                    <label class="form-label">Longitude</label>
                    <input 
                        type="number" 
                        id="markerLng" 
                        name="longitude" 
                        class="form-control" 
                        step="any" 
                        required
                    >
                </div>
            </div>

            <!-- Jenis Masakan -->
            <div class="form-group mb-3">
                <label class="form-label">Jenis Masakan</label>
                <select name="cuisine_type" id="cuisineType" class="form-control" required>
                    <option value="">Pilih jenis masakan...</option>
                    <option value="Indonesian">Indonesian</option>
                    <option value="Chinese">Chinese</option>
                    <option value="Western">Western</option>
                    <option value="Japanese">Japanese</option>
                    <option value="Korean">Korean</option>
                    <option value="Thai">Thai</option>
                    <option value="Indian">Indian</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <!-- Range Harga -->
            <div class="form-group mb-3">
                <label class="form-label">Range Harga</label>
                <select name="price_range" id="priceRange" class="form-control" required>
                    <option value="">Pilih range harga...</option>
                    <option value="$">$ (Budget)</option>
                    <option value="$$">$$ (Mid-Range)</option>
                    <option value="$$$">$$$ (High-End)</option>
                </select>
            </div>

            <!-- Rating -->
            <div class="form-group mb-3">
                <label class="form-label">Rating (1-5)</label>
                <input 
                    type="number" 
                    id="rating" 
                    name="rating" 
                    class="form-control" 
                    min="1" 
                    max="5" 
                    step="0.1"
                    required
                >
            </div>

            <!-- Jam Operasional -->
            <div class="form-group mb-3">
                <label class="form-label">Jam Operasional</label>
                <input 
                    type="text" 
                    id="operatingHours" 
                    name="operating_hours" 
                    class="form-control" 
                    placeholder="Contoh: 10:00 - 22:00"
                    required
                >
            </div>

            <!-- Gambar -->
            <div class="form-group mb-3">
                <label class="form-label">Foto Restoran</label>
                <input 
                    type="file" 
                    id="restaurantImage" 
                    name="image" 
                    class="form-control" 
                    accept="image/*"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary w-100">Tambah Restoran</button>
        </form>
    </div>
</div>
@php($status = $station->fuelStatus)

<div class="row g-4">
    <div class="col-12">
        <div class="form-section">
            <div class="form-section-title">Basic Information</div>
            <div class="form-section-copy">Define the station name, dealer, and location used in both admin and public views.</div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <label for="name" class="form-label fw-semibold">Station Name</label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $station->name) }}"
                        required
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-4">
                    <label for="dealer" class="form-label fw-semibold">Dealer</label>
                    <input
                        type="text"
                        name="dealer"
                        id="dealer"
                        class="form-control @error('dealer') is-invalid @enderror"
                        value="{{ old('dealer', $station->dealer) }}"
                        placeholder="Jamuna / Meghna / Padma"
                    >
                    @error('dealer')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-4">
                    <label for="location" class="form-label fw-semibold">Location</label>
                    <input
                        type="text"
                        name="location"
                        id="location"
                        class="form-control @error('location') is-invalid @enderror"
                        value="{{ old('location', $station->location) }}"
                        required
                    >
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-section">
            <div class="form-section-title">Fuel Availability</div>
            <div class="form-section-copy">Toggle current availability so the public dashboard stays accurate in real time.</div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="switch-card">
                        <div class="form-check form-switch m-0">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                role="switch"
                                id="octane"
                                name="octane"
                                value="1"
                                @checked(old('octane', $status?->octane))
                            >
                            <label class="form-check-label fw-semibold" for="octane">Octane Available</label>
                        </div>
                        <div class="small text-secondary mt-2">Enable this if octane is currently available at the station.</div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="switch-card">
                        <div class="form-check form-switch m-0">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                role="switch"
                                id="petrol"
                                name="petrol"
                                value="1"
                                @checked(old('petrol', $status?->petrol))
                            >
                            <label class="form-check-label fw-semibold" for="petrol">Petrol Available</label>
                        </div>
                        <div class="small text-secondary mt-2">Enable this if petrol is currently available at the station.</div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="switch-card">
                        <div class="form-check form-switch m-0">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                role="switch"
                                id="diesel"
                                name="diesel"
                                value="1"
                                @checked(old('diesel', $status?->diesel))
                            >
                            <label class="form-check-label fw-semibold" for="diesel">Diesel Available</label>
                        </div>
                        <div class="small text-secondary mt-2">Enable this if diesel is currently available at the station.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-actions">
    <div class="d-flex gap-2 flex-wrap">
        <button type="submit" class="btn btn-primary rounded-4 px-4 flex-grow-1">{{ $buttonText }}</button>
        <a href="{{ route('admin.stations.index') }}" class="btn btn-outline-secondary rounded-4 px-4 flex-grow-1">Cancel</a>
    </div>
</div>

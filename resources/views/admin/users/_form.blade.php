<div class="row g-4">
    <div class="col-12 col-xl-7">
        <div class="form-section">
            <div class="form-section-title">User Profile</div>
            <div class="form-section-copy">Create a login for a station manager or another administrator.</div>
            <div class="row g-4">
                <div class="col-md-6">
                    <label for="name" class="form-label fw-semibold">Full Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="role" class="form-label fw-semibold">Role</label>
                    <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="{{ \App\Models\User::ROLE_MANAGER }}" @selected(old('role', $user->role) === \App\Models\User::ROLE_MANAGER)>Station Manager</option>
                        <option value="{{ \App\Models\User::ROLE_ADMIN }}" @selected(old('role', $user->role) === \App\Models\User::ROLE_ADMIN)>Administrator</option>
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="password" class="form-label fw-semibold">Password {{ $isEdit ? '(leave blank to keep current)' : '' }}</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" {{ $isEdit ? '' : 'required' }}>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" {{ $isEdit ? '' : 'required' }}>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-5">
        <div class="form-section">
            <div class="form-section-title">Station Assignment</div>
            <div class="form-section-copy">Choose the stations this user can update. Leave empty for admin users if they should manage everything.</div>
            <div class="station-check-summary">
                <div>
                    <div class="form-label fw-semibold mb-1">Assigned Stations</div>
                    <div class="station-check-meta">Select one or many stations from the themed checklist below.</div>
                </div>
                <span class="station-tag">{{ count(old('station_ids', $assignedStationIds)) }} selected</span>
            </div>
            <div class="station-checklist">
                @forelse ($stations as $station)
                    @php($checked = in_array($station->id, old('station_ids', $assignedStationIds)))
                    <label class="station-check-item" for="station_{{ $station->id }}">
                        <input class="station-check-input" type="checkbox" name="station_ids[]" id="station_{{ $station->id }}" value="{{ $station->id }}" @checked($checked)>
                        <span class="station-check-card">
                            <span class="station-check-indicator"><i class="bi bi-check-lg"></i></span>
                            <span class="station-check-content">
                                <span class="station-check-title d-block">{{ $station->name }}</span>
                                <span class="station-check-copy d-block">{{ $station->location }}</span>
                            </span>
                        </span>
                    </label>
                @empty
                    <div class="small text-secondary">No stations available for assignment.</div>
                @endforelse
            </div>
            @error('station_ids')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror
            @error('station_ids.*')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="form-actions">
    <div class="d-flex gap-2 flex-wrap">
        <button type="submit" class="btn btn-primary rounded-4 px-4 flex-grow-1">{{ $buttonText }}</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-4 px-4 flex-grow-1">Cancel</a>
    </div>
</div>

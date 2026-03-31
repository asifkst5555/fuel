@extends('layouts.admin')

@section('page_title', 'User Management')
@section('page_copy', 'Create admins and station managers, then assign specific stations to each account.')

@section('page_actions')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary rounded-4 px-4 fw-semibold"><i class="bi bi-person-plus me-1"></i>Add User</a>
@endsection

@section('content')
    <div class="table-card">
        <div class="panel-header">
            <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                <div>
                    <h2 class="h4 fw-bold mb-1">Users & Station Assignments</h2>
                    <p class="text-secondary mb-0">Non-admin users will only see and update the stations assigned here.</p>
                </div>
                <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex gap-2 flex-wrap w-100" style="max-width: 360px;">
                    <input type="text" name="search" value="{{ $search }}" class="form-control rounded-4 flex-grow-1" placeholder="Search name or email">
                    <button type="submit" class="btn btn-outline-secondary rounded-4">Search</button>
                </form>
            </div>
        </div>
        <div class="panel-body pt-4">
            @if ($users->count())
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead><tr><th>User</th><th>Role</th><th>Assigned Stations</th><th>Updated</th><th class="text-end">Actions</th></tr></thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td><div class="fw-bold">{{ $user->name }}</div><div class="small text-secondary">{{ $user->email }}</div></td>
                                    <td><span class="admin-badge {{ $user->isAdmin() ? 'admin-badge-success' : 'admin-badge-danger' }}">{{ $user->isAdmin() ? 'Administrator' : 'Station Manager' }}</span></td>
                                    <td>
                                        @if ($user->isAdmin())
                                            <div class="small text-secondary">All stations</div>
                                        @elseif ($user->stations->isNotEmpty())
                                            @foreach ($user->stations as $station)
                                                <div class="small fw-semibold mb-1">{{ $station->name }}</div>
                                            @endforeach
                                        @else
                                            <div class="small text-secondary">No station assigned</div>
                                        @endif
                                    </td>
                                    <td><div class="fw-semibold">{{ $user->updated_at->diffForHumans() }}</div><div class="small text-secondary">{{ $user->updated_at->format('d M Y, h:i A') }}</div></td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2 flex-wrap">
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary rounded-4">Edit</a>
                                            @if (! $user->is(auth()->user()))
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?');">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger rounded-4">Delete</button></form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $users->links() }}</div>
            @else
                <div class="empty-state">
                    <div class="fs-1 mb-3 text-secondary"><i class="bi bi-people"></i></div>
                    <h3 class="h4 fw-bold mb-2">No users found</h3>
                    <p class="text-secondary mb-4">Create your first station manager and assign stations from here.</p>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary rounded-4 px-4">Create User</a>
                </div>
            @endif
        </div>
    </div>
@endsection

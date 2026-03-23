@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">User Management</h3>
            <div class="text-muted">Manage system users and permissions</div>
        </div>
        <div>
            <a href="{{ route('users.create') }}" class="btn btn-success me-2">
                <i class="fas fa-user-plus"></i> Add New User
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Users Table -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Active Users ({{ $users->total() }})</h5>
        </div>
        <div class="card-body">
            @if ($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                        @if ($user->id === Auth::id())
                                            <span class="badge bg-info ms-2">You</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'warehouse_manager' ? 'warning' : 'info') }}">
                                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($user->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if ($user->id !== Auth::id())
                                                <!-- Toggle Active Status -->
                                                <form action="{{ route('users.toggle-active', $user->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-{{ $user->is_active ? 'warning' : 'success' }} btn-sm" 
                                                            title="{{ $user->is_active ? 'Deactivate' : 'Activate' }} user">
                                                        <i class="fas fa-{{ $user->is_active ? 'lock' : 'unlock' }}"></i>
                                                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>

                                                <!-- Delete User -->
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;" 
                                                      onsubmit="return confirm('Are you sure you want to delete {{ $user->name }}? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete user">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-secondary">Current User</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i> No users found.
                </div>
            @endif
        </div>
    </div>

</div>

<style>
.table-hover tbody tr {
    transition: background-color 0.2s ease;
}

.table-hover tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.15) !important;
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.2);
}

/* Light mode override */
[data-theme="light"] .table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.08) !important;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}
</style>
@endsection

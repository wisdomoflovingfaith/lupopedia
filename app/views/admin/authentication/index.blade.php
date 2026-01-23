@extends('layouts.admin')

@section('title', 'Authentication Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>Authentication Management</h1>
            <p class="text-muted">Manage unified authentication between Lupopedia and Crafty Syntax</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Mappings</h5>
                    <h3>{{ $stats['total_mappings'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Active Sessions</h5>
                    <h3>{{ $stats['active_sessions'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Lupopedia Users</h5>
                    <h3>{{ $stats['total_lupo_users'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Crafty Operators</h5>
                    <h3>{{ $stats['total_crafty_operators'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs" id="authTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="mappings-tab" data-toggle="tab" href="#mappings" role="tab">
                User Mappings
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="unmapped-tab" data-toggle="tab" href="#unmapped" role="tab">
                Unmapped Users
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="sessions-tab" data-toggle="tab" href="#sessions" role="tab">
                Active Sessions
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="sync-tab" data-toggle="tab" href="#sync" role="tab">
                Synchronization
            </a>
        </li>
    </ul>

    <div class="tab-content" id="authTabContent">
        <!-- User Mappings Tab -->
        <div class="tab-pane fade show active" id="mappings" role="tabpanel">
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">User Identity Mappings</h5>
                    <a href="{{ route('admin.authentication.mapping') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Mapping
                    </a>
                </div>
                <div class="card-body">
                    @if($mappings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Lupopedia User</th>
                                        <th>Crafty Operator</th>
                                        <th>Mapping Type</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mappings as $mapping)
                                        <tr>
                                            <td>
                                                <strong>{{ $mapping->lupo_name ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $mapping->lupo_email }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ $mapping->crafty_name ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $mapping->crafty_email }}</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $mapping->mapping_type == 'manual' ? 'primary' : ($mapping->mapping_type == 'auto' ? 'success' : 'info') }}">
                                                    {{ ucfirst($mapping->mapping_type) }}
                                                </span>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($mapping->created_at)->format('M j, Y g:i A') }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-danger" onclick="deleteMapping({{ $mapping->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5>No User Mappings Found</h5>
                            <p class="text-muted">Create your first user mapping to get started.</p>
                            <a href="{{ route('admin.authentication.mapping') }}" class="btn btn-primary">
                                Create Mapping
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Unmapped Users Tab -->
        <div class="tab-pane fade" id="unmapped" role="tabpanel">
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Unmapped Lupopedia Users</h5>
                        </div>
                        <div class="card-body">
                            @if($unmappedUsers->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($unmappedUsers as $user)
                                                <tr>
                                                    <td>{{ $user->name ?? 'N/A' }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary" onclick="mapUser({{ $user->id }}, 'lupo')">
                                                            Map User
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">All Lupopedia users are mapped.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Unmapped Crafty Operators</h5>
                        </div>
                        <div class="card-body">
                            @if($unmappedOperators->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($unmappedOperators as $operator)
                                                <tr>
                                                    <td>{{ $operator->crafty_name ?? 'N/A' }}</td>
                                                    <td>{{ $operator->crafty_email }}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-success" onclick="mapUser({{ $operator->operatorid }}, 'crafty')">
                                                            Map Operator
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">All Crafty Syntax operators are mapped.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Sessions Tab -->
        <div class="tab-pane fade" id="sessions" role="tabpanel">
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Active Sessions</h5>
                </div>
                <div class="card-body">
                    @if($activeSessions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>System Context</th>
                                        <th>Session ID</th>
                                        <th>Expires</th>
                                        <th>Last Activity</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeSessions as $session)
                                        <tr>
                                            <td>
                                                <strong>{{ $session->user_name ?? 'Unknown' }}</strong><br>
                                                <small class="text-muted">{{ $session->user_email }}</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $session->system_context == 'lupopedia' ? 'primary' : 'success' }}">
                                                    {{ ucfirst($session->system_context) }}
                                                </span>
                                            </td>
                                            <td><code>{{ substr($session->session_id, 0, 8) }}...</code></td>
                                            <td>{{ \Carbon\Carbon::parse($session->expires_at)->format('M j, Y g:i A') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($session->updated_at)->diffForHumans() }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning" onclick="terminateSession('{{ $session->session_id }}')">
                                                    <i class="fas fa-sign-out-alt"></i> Terminate
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-clock fa-3x text-muted mb-3"></i>
                            <h5>No Active Sessions</h5>
                            <p class="text-muted">There are currently no active unified sessions.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Synchronization Tab -->
        <div class="tab-pane fade" id="sync" role="tabpanel">
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">User Synchronization</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Sync Status</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td>Last Sync:</td>
                                    <td>{{ $lastSync ? \Carbon\Carbon::parse($lastSync)->format('M j, Y g:i A') : 'Never' }}</td>
                                </tr>
                                <tr>
                                    <td>Auto Sync:</td>
                                    <td><span class="badge badge-secondary">Disabled</span></td>
                                </tr>
                                <tr>
                                    <td>Sync Frequency:</td>
                                    <td>Manual</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Manual Sync</h6>
                            <p class="text-muted">Trigger manual synchronization between systems:</p>
                            <div class="btn-group" role="group">
                                <button class="btn btn-outline-primary" onclick="synchronizeUsers('bidirectional')">
                                    <i class="fas fa-sync"></i> Bidirectional
                                </button>
                                <button class="btn btn-outline-info" onclick="synchronizeUsers('lupo_to_crafty')">
                                    <i class="fas fa-arrow-right"></i> Lupo → Crafty
                                </button>
                                <button class="btn btn-outline-success" onclick="synchronizeUsers('crafty_to_lupo')">
                                    <i class="fas fa-arrow-left"></i> Crafty → Lupo
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div id="syncResults" class="d-none">
                        <h6>Sync Results</h6>
                        <div class="alert alert-info">
                            <div id="syncResultsContent"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteMapping(mappingId) {
    if (confirm('Are you sure you want to delete this user mapping?')) {
        axios.delete(`/admin/authentication/mapping/${mappingId}`)
            .then(response => {
                if (response.data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + response.data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting mapping');
            });
    }
}

function terminateSession(sessionId) {
    if (confirm('Are you sure you want to terminate this session?')) {
        axios.delete(`/admin/authentication/session/${sessionId}`)
            .then(response => {
                if (response.data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + response.data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error terminating session');
            });
    }
}

function synchronizeUsers(syncType) {
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Syncing...';
    btn.disabled = true;

    axios.post('/admin/authentication/synchronize', { sync_type: syncType })
        .then(response => {
            if (response.data.success) {
                const results = response.data.results;
                let resultsHtml = `
                    <strong>Sync completed successfully!</strong><br>
                    Users synced: ${results.users_synced}<br>
                    Operators synced: ${results.operators_synced}<br>
                    Mappings created: ${results.mappings_created}
                `;
                
                if (results.errors.length > 0) {
                    resultsHtml += '<br><strong>Errors:</strong><br>' + results.errors.join('<br>');
                }
                
                document.getElementById('syncResultsContent').innerHTML = resultsHtml;
                document.getElementById('syncResults').classList.remove('d-none');
            } else {
                alert('Error: ' + response.data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error during synchronization');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
}

function mapUser(userId, type) {
    // This would open a modal or redirect to mapping page with pre-filled data
    window.location.href = `/admin/authentication/mapping?${type}_id=${userId}`;
}
</script>
@endpush

    @extends('layouts.landlord')

@section('title', 'Maintenance Requests')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card text-white shadow" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1 text-white"><i class="fas fa-tools mr-2"></i>Maintenance Requests</h2>
                            <p class="mb-0 opacity-75">Manage and track maintenance issues across your properties</p>
                        </div>
                        <div class="text-right">
                            <small class="opacity-75">Last updated: {{ now()->format('M d, Y H:i') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Issues
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Open Issues
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['open'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                In Progress
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['in_progress'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cog fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Resolved
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['resolved'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Maintenance Issues</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Actions:</div>
                            <a class="dropdown-item" href="#"><i class="fas fa-download fa-sm fa-fw mr-2 text-gray-400"></i>Export Report</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-print fa-sm fa-fw mr-2 text-gray-400"></i>Print List</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <!-- Filters -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter"></i> Filter Issues</h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" class="mb-0">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="all">All Status</option>
                                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="priority" class="form-label">Priority</label>
                                        <select name="priority" id="priority" class="form-control">
                                            <option value="all">All Priorities</option>
                                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                            <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="category" class="form-label">Category</label>
                                        <select name="category" id="category" class="form-control">
                                            <option value="all">All Categories</option>
                                            <option value="plumbing" {{ request('category') == 'plumbing' ? 'selected' : '' }}>Plumbing</option>
                                            <option value="electrical" {{ request('category') == 'electrical' ? 'selected' : '' }}>Electrical</option>
                                            <option value="structural" {{ request('category') == 'structural' ? 'selected' : '' }}>Structural</option>
                                            <option value="appliance" {{ request('category') == 'appliance' ? 'selected' : '' }}>Appliance</option>
                                            <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="search" class="form-label">Search</label>
                                        <div class="input-group">
                                            <input type="text" name="search" id="search" class="form-control" placeholder="Search issues..." value="{{ request('search') }}">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary mr-2">
                                            <i class="fas fa-filter"></i> Apply Filters
                                        </button>
                                        <a href="{{ route('landlord.maintenance-requests') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Clear Filters
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Issues Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Property</th>
                                    <th>Location</th>
                                    <th>Tenant</th>
                                    <th>Priority</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($issues as $issue)
                                <tr>
                                    <td>
                                        <strong>{{ $issue->title }}</strong>
                                        <br>
                                        <small class="text-muted">{{ Str::limit($issue->description, 50) }}</small>
                                    </td>
                                    <td>{{ $issue->listing->title }}</td>
                                    <td>{{ $issue->location ?: 'Not specified' }}</td>
                                    <td>{{ $issue->tenant->name }}</td>
                                    <td>
                                        <span class="badge badge-{{ $issue->priority == 'urgent' ? 'danger' : ($issue->priority == 'high' ? 'warning' : ($issue->priority == 'medium' ? 'info' : 'secondary')) }}">
                                            {{ ucfirst($issue->priority) }}
                                        </span>
                                    </td>
                                    <td>{{ ucfirst($issue->category) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $issue->status == 'open' ? 'warning' : ($issue->status == 'in_progress' ? 'info' : 'success') }}">
                                            {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ $issue->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if($issue->status == 'open')
                                            <button class="btn btn-sm btn-success" data-issue-id="{{ $issue->id }}" onclick="acceptIssue(this)" title="Accept Issue">
                                                <i class="fas fa-check"></i> Accept
                                            </button>
                                            @endif
                                            @if($issue->status == 'in_progress')
                                            <button class="btn btn-sm btn-primary" data-issue-id="{{ $issue->id }}" onclick="resolveIssue(this)" title="Resolve Issue">
                                                <i class="fas fa-check-circle"></i> Resolve
                                            </button>
                                            @endif
                                            <button class="btn btn-sm btn-info" data-issue-id="{{ $issue->id }}" data-tenant-name="{{ $issue->tenant->name }}" onclick="messageTenant(this)" title="Message Tenant">
                                                <i class="fas fa-envelope"></i> Message
                                            </button>
                                            <button class="btn btn-sm btn-secondary" data-issue-id="{{ $issue->id }}" onclick="viewIssueDetails(this)" title="View Details">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No maintenance requests found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    {{ $issues->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Message Tenant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <form id="messageForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Resolve Modal -->
<div class="modal fade" id="resolveModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Resolve Issue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <form id="resolveForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="resolution_notes">Resolution Notes (Optional)</label>
                        <textarea class="form-control" id="resolution_notes" name="resolution_notes" rows="4" placeholder="Describe how the issue was resolved..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Mark as Resolved</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Issue Details Modal -->
<div class="modal fade" id="issueDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Issue Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body" id="issueDetailsContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function acceptIssue(button) {
    const issueId = button.dataset.issueId;
    if (confirm('Are you sure you want to accept this maintenance request?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/landlord/issues/' + issueId + '/accept';

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);

        document.body.appendChild(form);
        form.submit();
    }
}

function resolveIssue(button) {
    const issueId = button.dataset.issueId;
    $('#resolveForm').attr('action', '/landlord/issues/' + issueId + '/resolve');
    $('#resolveModal').modal('show');
}

function messageTenant(button) {
    const issueId = button.dataset.issueId;
    const tenantName = button.dataset.tenantName;
    $('#messageModal .modal-title').text('Message ' + tenantName);
    $('#messageForm').attr('action', '/landlord/issues/' + issueId + '/message');
    $('#messageModal').modal('show');
}

function viewIssueDetails(button) {
    const issueId = button.dataset.issueId;

    // Show loading state
    $('#issueDetailsContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
    $('#issueDetailsModal').modal('show');

    // Make AJAX request to get issue details
    $.ajax({
        url: '/landlord/issues/' + issueId + '/details',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const issue = response.issue;

                // Build the modal content
                let content = `
                    <div class="row">
                        <div class="col-md-8">
                            <h6><strong>Title:</strong> ${issue.title || 'Not specified'}</h6>
                            <p><strong>Description:</strong> ${issue.description || 'Not specified'}</p>
                            <p><strong>Location:</strong> ${issue.location || 'Not specified'}</p>
                            <p><strong>Category:</strong> ${issue.category ? issue.category.charAt(0).toUpperCase() + issue.category.slice(1) : 'Not specified'}</p>
                            <p><strong>Priority:</strong>
                                <span class="badge badge-${issue.priority == 'urgent' ? 'danger' : (issue.priority == 'high' ? 'warning' : (issue.priority == 'medium' ? 'info' : 'secondary'))}">
                                    ${issue.priority ? issue.priority.charAt(0).toUpperCase() + issue.priority.slice(1) : 'Not specified'}
                                </span>
                            </p>
                            <p><strong>Status:</strong>
                                <span class="badge badge-${issue.status == 'open' ? 'warning' : (issue.status == 'in_progress' ? 'info' : 'success')}">
                                    ${issue.status ? issue.status.charAt(0).toUpperCase() + issue.status.slice(1).replace('_', ' ') : 'Not specified'}
                                </span>
                            </p>
                            <p><strong>Created:</strong> ${issue.created_at || 'Not specified'}</p>
                            <p><strong>Updated:</strong> ${issue.updated_at || 'Not specified'}</p>
                            ${issue.resolved_at ? `<p><strong>Resolved:</strong> ${issue.resolved_at}</p>` : ''}
                            ${issue.resolution_notes ? `<p><strong>Resolution Notes:</strong> ${issue.resolution_notes}</p>` : ''}

                            <h6 class="mt-3"><strong>Preferred Contact Method</strong></h6>
                            <p>${issue.contact_method || 'Not specified'}</p>

                            <h6 class="mt-3"><strong>Available Times for Property Access</strong></h6>
                            <p>${issue.available_times || 'Not specified'}</p>

                            <h6 class="mt-3"><strong>Photo</strong></h6>
                            ${issue.photos && Array.isArray(issue.photos) && issue.photos.length > 0 ?
                                issue.photos.map((photo, index) => `<a href="/storage/image/${photo}" target="_blank" class="text-blue-600 hover:text-blue-800">View Photo ${index + 1}</a><br>`).join('') :
                                '<p>No photo uploaded</p>'
                            }
                        </div>
                        <div class="col-md-4">
                            <h6><strong>Tenant Information</strong></h6>
                            <p><strong>Name:</strong> ${issue.tenant && issue.tenant.name ? issue.tenant.name : 'Not specified'}</p>
                            <p><strong>Email:</strong> ${issue.tenant && issue.tenant.email ? issue.tenant.email : 'Not specified'}</p>
                            <p><strong>Phone:</strong> ${issue.tenant && issue.tenant.phone ? issue.tenant.phone : 'Not specified'}</p>

                            <h6 class="mt-3"><strong>Property Information</strong></h6>
                            <p><strong>Title:</strong> ${issue.listing && issue.listing.title ? issue.listing.title : 'Not specified'}</p>
                            <p><strong>Location:</strong> ${issue.listing && issue.listing.location ? issue.listing.location : 'Not specified'}</p>
                        </div>
                    </div>
                `;

                $('#issueDetailsContent').html(content);
            } else {
                $('#issueDetailsContent').html('<div class="alert alert-danger">Failed to load issue details.</div>');
            }
        },
        error: function() {
            $('#issueDetailsContent').html('<div class="alert alert-danger">Error loading issue details. Please try again.</div>');
        }
    });
}

// Clear form when modals are hidden
$('#messageModal').on('hidden.bs.modal', function() {
    $('#messageForm')[0].reset();
});

$('#resolveModal').on('hidden.bs.modal', function() {
    $('#resolveForm')[0].reset();
});
</script>
@endsection

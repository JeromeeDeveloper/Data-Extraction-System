@extends('layouts.app')

@section('content')
<div class="main-content">
<div>
	<h2>Branch Profile</h2>

	@if(session('success'))
		<div class="alert alert-success">{{ session('success') }}</div>
	@endif

	<div class="card">
		<div class="card-header d-flex justify-content-between align-items-center">
			<span>Branches</span>
			<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBranchModal">
				<i class="fas fa-plus"></i> Add Branch
			</button>
		</div>
		<div class="card-body">
			<form method="GET" action="{{ route('branches.index') }}" class="row g-2 mb-3">
				<div class="col-md-4">
					<input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Search branch code or name...">
				</div>
				<div class="col-md-2 d-grid">
					<button class="btn btn-outline-primary">Search</button>
				</div>
				<div class="col-md-2 d-grid">
					<a href="{{ route('branches.index') }}" class="btn btn-outline-secondary">Reset</a>
				</div>
			</form>

			<table class="table table-bordered align-middle">
				<thead>
					<tr>
						<th style="width: 200px;">Branch Code</th>
						<th>Branch Name</th>
						<th style="width: 200px;">Actions</th>
					</tr>
				</thead>
				<tbody>
					@forelse($branches as $branch)
						@php $modalId = 'edit-branch-'.$branch->id; @endphp
						<tr>
							<td class="align-middle">{{ $branch->branch_code }}</td>
							<td class="align-middle">{{ $branch->branch_name }}</td>
							<td>
								<div class="d-flex gap-2">
									<button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">Edit</button>
									<form method="POST" action="{{ route('branches.destroy', $branch) }}" class="delete-form">
										@csrf
										@method('DELETE')
										<button type="button" class="btn btn-outline-danger delete-btn">Delete</button>
									</form>
								</div>

								<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
									<div class="modal-dialog">
										<form method="POST" action="{{ route('branches.update', $branch) }}">
											@csrf
											@method('PUT')
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title">Edit Branch</h5>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
												<div class="modal-body">
													<div class="mb-3">
														<label class="form-label">Branch Code</label>
														<input type="text" name="branch_code" value="{{ $branch->branch_code }}" class="form-control" required>
													</div>
													<div class="mb-2">
														<label class="form-label">Branch Name</label>
														<input type="text" name="branch_name" value="{{ $branch->branch_name }}" class="form-control" required>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
													<button type="submit" class="btn btn-primary">Save Changes</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="3" class="text-center text-muted">No branches found.</td>
						</tr>
					@endforelse
				</tbody>
			</table>

			<div class="d-flex justify-content-between align-items-center">
				<div class="text-muted">Showing {{ $branches->firstItem() ?? 0 }} - {{ $branches->lastItem() ?? 0 }} of {{ $branches->total() }}</div>
				{{ $branches->links() }}
			</div>
		</div>
	</div>
</div>

<!-- Add Branch Modal -->
<div class="modal fade" id="addBranchModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<form method="POST" action="{{ route('branches.store') }}">
			@csrf
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Add Branch</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="mb-3">
						<label class="form-label">Branch Code</label>
						<input type="text" name="branch_code" class="form-control @error('branch_code') is-invalid @enderror" value="{{ old('branch_code') }}" required>
						@error('branch_code')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
					<div class="mb-2">
						<label class="form-label">Branch Name</label>
						<input type="text" name="branch_name" class="form-control @error('branch_name') is-invalid @enderror" value="{{ old('branch_name') }}" required>
						@error('branch_name')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Add Branch</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.delete-form');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection

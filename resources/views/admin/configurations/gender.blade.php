@extends('layouts.app')

@section('content')
<div class="main-content">
<div>
	<h2>Gender Configuration</h2>

	@if(session('success'))
		<div class="alert alert-success">{{ session('success') }}</div>
	@endif

	<div class="card mb-4">
		<div class="card-header">Add Gender Configuration</div>
		<div class="card-body">
			<form method="POST" action="{{ route('configurations.gender.store') }}">
				@csrf
				<div class="row g-3">
					<div class="col-md-4">
						<label class="form-label">Gender Code</label>
						<input type="text" name="gender_code" class="form-control @error('gender_code') is-invalid @enderror" value="{{ old('gender_code') }}" required>
						@error('gender_code')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
					<div class="col-md-6">
						<label class="form-label">Gender</label>
						<input type="text" name="gender" class="form-control @error('gender') is-invalid @enderror" value="{{ old('gender') }}" required>
						@error('gender')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
					<div class="col-md-2 d-flex align-items-end">
						<button type="submit" class="btn btn-primary">Add</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="card">
		<div class="card-header">Gender Configurations</div>
		<div class="card-body">
			<form method="GET" action="{{ route('configurations.gender') }}" class="row g-2 mb-3">
				<div class="col-md-4">
					<input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Search gender code or gender...">
				</div>
				<div class="col-md-2 d-grid">
					<button class="btn btn-outline-primary">Search</button>
				</div>
				<div class="col-md-2 d-grid">
					<a href="{{ route('configurations.gender') }}" class="btn btn-outline-secondary">Reset</a>
				</div>
			</form>
			<table class="table table-bordered align-middle">
				<thead>
					<tr>
						<th style="width: 200px;">Gender Code</th>
						<th>Gender</th>
						<th style="width: 200px;">Actions</th>
					</tr>
				</thead>
				<tbody>
					@forelse($genders as $gender)
						@php $modalId = 'edit-gender-'.$gender->id; @endphp
						<tr>
							<td class="align-middle">{{ $gender->gender_code }}</td>
							<td class="align-middle">{{ $gender->gender }}</td>
							<td>
								<div class="d-flex gap-2">
									<button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">Edit</button>
									<form method="POST" action="{{ route('configurations.gender.delete', $gender) }}" class="delete-form">
										@csrf
										@method('DELETE')
										<button type="button" class="btn btn-outline-danger delete-btn">Delete</button>
									</form>
								</div>

								<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
									<div class="modal-dialog">
											<form method="POST" action="{{ route('configurations.gender.update', $gender) }}">
												@csrf
												@method('PUT')
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title">Edit Gender Configuration</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
														<div class="mb-3">
															<label class="form-label">Gender Code</label>
															<input type="text" name="gender_code" value="{{ $gender->gender_code }}" class="form-control" required>
														</div>
														<div class="mb-2">
															<label class="form-label">Gender</label>
															<input type="text" name="gender" value="{{ $gender->gender }}" class="form-control" required>
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
							<td colspan="3" class="text-center text-muted">No gender configurations yet.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
			<div class="d-flex justify-content-between align-items-center">
				<div class="text-muted">Showing {{ $genders->firstItem() ?? 0 }} - {{ $genders->lastItem() ?? 0 }} of {{ $genders->total() }}</div>
				{{ $genders->links() }}
			</div>
		</div>
	</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete buttons
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

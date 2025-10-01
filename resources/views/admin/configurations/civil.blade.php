@extends('layouts.app')

@section('content')
<div class="main-content">
<div>
	<h2>Civil Status Configuration</h2>

	@if(session('success'))
		<div class="alert alert-success">{{ session('success') }}</div>
	@endif

	<div class="card mb-4">
		<div class="card-header">Add Civil Status Configuration</div>
		<div class="card-body">
			<form method="POST" action="{{ route('configurations.civil.store') }}">
				@csrf
				<div class="row g-3">
											<div class="col-md-4">
												<label class="form-label">CISA Code</label>
												<input type="text" name="cisa_code" class="form-control @error('cisa_code') is-invalid @enderror" value="{{ old('cisa_code') }}" required>
												@error('cisa_code')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>
											<div class="col-md-6">
												<label class="form-label">Description</label>
												<input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}" required>
												@error('description')
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
		<div class="card-header">Civil Status Configurations</div>
		<div class="card-body">
			<form method="GET" action="{{ route('configurations.civil') }}" class="row g-2 mb-3">
				<div class="col-md-4">
					<input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Search CISA code or description...">
				</div>
				<div class="col-md-2 d-grid">
					<button class="btn btn-outline-primary">Search</button>
				</div>
				<div class="col-md-2 d-grid">
					<a href="{{ route('configurations.civil') }}" class="btn btn-outline-secondary">Reset</a>
				</div>
			</form>
			<table class="table table-bordered align-middle">
				<thead>
							<tr>
								<th style="width: 200px;">CISA Code</th>
								<th>Description</th>
								<th>MBWIN Codes</th>
								<th style="width: 200px;">Actions</th>
							</tr>
				</thead>
				<tbody>
					@forelse($civils as $civil)
						@php $modalId = 'edit-civil-'.$civil->id; @endphp
							<tr>
								<td class="align-middle">{{ $civil->cisa_code }}</td>
								<td class="align-middle">{{ $civil->description }}</td>
							<td>
								@if($civil->mbwinCodes->isEmpty())
									<span class="text-muted">No MBWIN codes yet</span>
								@else
									<div class="d-flex flex-wrap gap-2">
										@foreach($civil->mbwinCodes as $mbwin)
											<form method="POST" action="{{ route('configurations.civil.mbwin.delete', [$civil, $mbwin]) }}">
												@csrf
												@method('DELETE')
												<span class="badge bg-secondary">{{ $mbwin->mbwin_code }}</span>
												<button class="btn btn-sm btn-outline-danger ms-2">Remove</button>
											</form>
										@endforeach
									</div>
								@endif
							</td>
							<td>
								<div class="d-flex gap-2 align-items-start flex-wrap">
									<form class="d-flex gap-2 flex-grow-1" method="POST" action="{{ route('configurations.civil.mbwin.store', $civil) }}">
										@csrf
										<div class="flex-grow-1">
											<input type="text" name="mbwin_code" class="form-control @error('mbwin_code') is-invalid @enderror" placeholder="Enter MBWIN Code" required>
											@error('mbwin_code')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>
										<button type="submit" class="btn btn-outline-primary">Add MBWIN</button>
									</form>
									<button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">Edit</button>
									<form method="POST" action="{{ route('configurations.civil.delete', $civil) }}" class="delete-form">
										@csrf
										@method('DELETE')
										<button type="button" class="btn btn-outline-danger delete-btn">Delete</button>
									</form>
								</div>

								<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
									<div class="modal-dialog">
											<form method="POST" action="{{ route('configurations.civil.update', $civil) }}">
												@csrf
												@method('PUT')
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title">Edit Civil Status Configuration</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
											<div class="mb-3">
												<label class="form-label">CISA Code</label>
												<input type="text" name="cisa_code" value="{{ $civil->cisa_code }}" class="form-control" required>
											</div>
											<div class="mb-2">
												<label class="form-label">Description</label>
												<input type="text" name="description" value="{{ $civil->description }}" class="form-control" required>
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
							<td colspan="3" class="text-center text-muted">No civil status configurations yet.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
			<div class="d-flex justify-content-between align-items-center">
				<div class="text-muted">Showing {{ $civils->firstItem() ?? 0 }} - {{ $civils->lastItem() ?? 0 }} of {{ $civils->total() }}</div>
				{{ $civils->links() }}
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

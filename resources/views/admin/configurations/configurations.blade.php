@extends('layouts.app')

@section('content')
<div class="main-content">
<div>
	<h2>Product Configuration</h2>

	@if(session('success'))
		<div class="alert alert-success">{{ session('success') }}</div>
	@endif

	<div class="card mb-4">
		<div class="card-header">Add CISA Product</div>
		<div class="card-body">
			<form method="POST" action="{{ route('configurations.products.store') }}">
				@csrf
				<div class="row g-3">
					<div class="col-md-3">
						<label class="form-label">CISA Code</label>
						<input type="text" name="cisa_code" class="form-control @error('cisa_code') is-invalid @enderror" value="{{ old('cisa_code') }}" required>
						@error('cisa_code')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
					<div class="col-md-5">
						<label class="form-label">Description</label>
						<input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}" required>
						@error('description')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
					<div class="col-md-3">
						<label class="form-label">Type</label>
						<select name="type" class="form-select @error('type') is-invalid @enderror" required>
							<option value="installment" {{ old('type')==='installment' ? 'selected' : '' }}>Installment</option>
							<option value="non-installment" {{ old('type')==='non-installment' ? 'selected' : '' }}>Non-Installment</option>
						</select>
						@error('type')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
					<div class="col-md-1 d-flex align-items-end">
						<button type="submit" class="btn btn-primary">Add</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="card">
		<div class="card-header">CISA Products and GL Code Mapping</div>
		<div class="card-body">
			<form method="GET" action="{{ route('configurations.index') }}" class="row g-2 mb-3">
				<div class="col-md-4">
					<input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Search CISA code, description, or type...">
				</div>
				<div class="col-md-2 d-grid">
					<button class="btn btn-outline-primary">Search</button>
				</div>
				<div class="col-md-2 d-grid">
					<a href="{{ route('configurations.index') }}" class="btn btn-outline-secondary">Reset</a>
				</div>
			</form>
			<table class="table table-bordered align-middle" style="table-layout: fixed;">
				<thead>
					<tr>
						<th>CISA Code</th>
						<th>Description</th>
						<th>Type</th>
						<th>GL Codes</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					@forelse($products as $product)
						@php $modalId = 'edit-product-'.$product->id; @endphp
						<tr>
							<td class="align-middle">{{ $product->cisa_code }}</td>
							<td class="align-middle">{{ $product->description }}</td>
							<td class="text-capitalize align-middle">{{ str_replace('-', ' ', $product->type) }}</td>
							<td>
								@if($product->glCodes->isEmpty())
									<span class="text-muted">No GL codes yet</span>
								@else
									<div class="d-flex flex-wrap gap-2">
										@foreach($product->glCodes as $gc)
											<form method="POST" action="{{ route('configurations.products.glCodes.delete', [$product, $gc]) }}">
												@csrf
												@method('DELETE')
												<span class="badge bg-secondary">{{ $gc->gl_code }}</span>
												<button class="btn btn-sm btn-outline-danger ms-2">Remove</button>
											</form>
										@endforeach
									</div>
								@endif
							</td>
							<td>
								<div class="d-flex gap-2 align-items-start flex-wrap">
									<form class="d-flex gap-2 flex-grow-1" method="POST" action="{{ route('configurations.products.glCodes.store', $product) }}">
										@csrf
										<div class="flex-grow-1">
											<input type="text" name="gl_code" class="form-control @error('gl_code') is-invalid @enderror" placeholder="Enter GL Code" required>
											@error('gl_code')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>
										<button type="submit" class="btn btn-outline-primary">Add GL</button>
									</form>
									<button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">Edit</button>
									<form method="POST" action="{{ route('configurations.products.delete', $product) }}" class="delete-form">
										@csrf
										@method('DELETE')
										<button type="button" class="btn btn-outline-danger delete-btn">Delete</button>
									</form>
								</div>

								<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title">Edit CISA Product</h5>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<form method="POST" action="{{ route('configurations.products.update', $product) }}">
												@csrf
												@method('PUT')
												<div class="modal-body">
													<div class="mb-3">
														<label class="form-label">CISA Code</label>
														<input type="text" name="cisa_code" value="{{ $product->cisa_code }}" class="form-control" required>
													</div>
													<div class="mb-3">
														<label class="form-label">Description</label>
														<input type="text" name="description" value="{{ $product->description }}" class="form-control" required>
													</div>
													<div class="mb-2">
														<label class="form-label">Type</label>
														<select name="type" class="form-select" required>
															<option value="installment" {{ $product->type === 'installment' ? 'selected' : '' }}>Installment</option>
															<option value="non-installment" {{ $product->type === 'non-installment' ? 'selected' : '' }}>Non-Installment</option>
														</select>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
													<button type="submit" class="btn btn-primary">Save Changes</button>
												</div>
											</form>
										</div>
									</div>
								</div>
						</td>
						</tr>
					@empty
						<tr>
							<td colspan="5" class="text-center text-muted">No CISA products configured yet.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
			<div class="d-flex justify-content-between align-items-center">
				<div class="text-muted">Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() }}</div>
				{{ $products->links() }}
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
                text: "This will delete the CISA product and remove all its GL mappings. You won't be able to revert this!",
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



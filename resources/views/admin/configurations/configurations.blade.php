@extends('layouts.app')

@section('content')
<div class="main-content">
<div>
	<h2>Configurations - CISA Products</h2>

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
						<input type="text" name="cisa_code" class="form-control" required>
					</div>
					<div class="col-md-5">
						<label class="form-label">Description</label>
						<input type="text" name="description" class="form-control" required>
					</div>
					<div class="col-md-3">
						<label class="form-label">Type</label>
						<select name="type" class="form-select" required>
							<option value="installment">Installment</option>
							<option value="non-installment">Non-Installment</option>
						</select>
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
			<table class="table table-bordered align-middle" style="table-layout: fixed;">
				<thead>
					<tr>
						<th style="width: 160px;">CISA Code</th>
						<th style="width: 35%;">Description</th>
						<th style="width: 180px;">Type</th>
						<th style="width: 25%;">GL Codes</th>
						<th style="width: 350px;">Edit / Add GL Code</th>
					</tr>
				</thead>
				<tbody>
					@forelse($products as $product)
						<tr>
							@php $formId = 'edit-form-'.$product->id; @endphp
						<td>
							<input form="{{ $formId }}" type="text" name="cisa_code" value="{{ $product->cisa_code }}" class="form-control form-control-sm w-100">
						</td>
						<td>
							<input form="{{ $formId }}" type="text" name="description" value="{{ $product->description }}" class="form-control form-control-sm w-100">
						</td>
						<td class="text-capitalize">
							<select form="{{ $formId }}" name="type" class="form-select form-select-sm w-100">
									<option value="installment" {{ $product->type === 'installment' ? 'selected' : '' }}>Installment</option>
									<option value="non-installment" {{ $product->type === 'non-installment' ? 'selected' : '' }}>Non-Installment</option>
								</select>
							<form id="{{ $formId }}" method="POST" action="{{ route('configurations.products.update', $product) }}" class="mt-2 d-flex justify-content-end">
									@csrf
									@method('PUT')
								<button class="btn btn-sm btn-success px-3">Save</button>
								</form>
							</td>
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
							<div class="d-flex gap-2 align-items-start">
								<form class="d-flex gap-2 flex-grow-1" method="POST" action="{{ route('configurations.products.glCodes.store', $product) }}">
									@csrf
									<input type="text" name="gl_code" class="form-control" placeholder="Enter GL Code" required>
									<button type="submit" class="btn btn-outline-primary">Add GL</button>
								</form>
								<form method="POST" action="{{ route('configurations.products.delete', $product) }}" onsubmit="return confirm('Delete this CISA product? This will remove its GL mappings too.');">
									@csrf
									@method('DELETE')
									<button class="btn btn-outline-danger">Delete</button>
								</form>
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
		</div>
	</div>
</div>
</div>
@endsection



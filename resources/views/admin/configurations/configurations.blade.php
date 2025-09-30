@extends('layouts.app')

@section('content')
<div class="main-content">
<div class="container">
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
			<table class="table table-bordered align-middle">
				<thead>
					<tr>
						<th style="width: 120px;">CISA Code</th>
						<th>Description</th>
						<th style="width: 160px;">Type</th>
						<th>GL Codes</th>
						<th style="width: 320px;">Add GL Code</th>
					</tr>
				</thead>
				<tbody>
					@forelse($products as $product)
						<tr>
							<td>{{ $product->cisa_code }}</td>
							<td>{{ $product->description }}</td>
							<td class="text-capitalize">{{ str_replace('-', ' ', $product->type) }}</td>
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
								<form class="row g-2" method="POST" action="{{ route('configurations.products.glCodes.store', $product) }}">
									@csrf
									<div class="col-8">
										<input type="text" name="gl_code" class="form-control" placeholder="Enter GL Code" required>
									</div>
									<div class="col-4">
										<button type="submit" class="btn btn-outline-primary w-100">Add GL</button>
									</div>
								</form>
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



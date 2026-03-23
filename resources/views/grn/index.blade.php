@extends('layouts.app')

@section('content')
<div class="container">
	<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
		<div>
			<h3 class="mb-1">GRN List</h3>
			<div class="text-muted">Goods Received Notes</div>
		</div>
		<div class="d-flex gap-2">
			<a href="{{ route('grn.create') }}" class="btn btn-primary">
				<i class="fas fa-plus-circle"></i> New GRN
			</a>
		</div>
	</div>

	<div class="card shadow-sm">
		<div class="card-body">
			@if (session('error'))
				<div class="alert alert-danger">
					{{ session('error') }}
				</div>
			@endif

			<form method="GET" action="{{ route('grn.index') }}" class="row g-2 mb-3">
				<div class="col-12 col-md-8">
					<input
						type="text"
						name="search"
						class="form-control"
						placeholder="Search by Barcode, GRN No, or Item Code"
						value="{{ request('search') }}"
					>
				</div>
				<div class="col-12 col-md-4 d-flex gap-2">
					<button type="submit" class="btn btn-primary w-100">
						<i class="fas fa-search"></i> Search
					</button>
					<a href="{{ route('grn.index') }}" class="btn btn-secondary w-100">
						Clear
					</a>
				</div>
			</form>

			<!-- Print Selected GRNs Section -->
			<form id="printSelectedForm" method="POST" action="{{ route('grn.print-selected') }}" class="mb-3" style="display: none;">
				@csrf
				<div class="d-flex gap-2 align-items-center p-3 bg-light rounded" style="background: rgba(0, 0, 0, 0.05) !important;">
					<span class="flex-grow-1">
						<strong><span id="selectedCount">0</span> GRN(s) selected</strong>
					</span>
					<button type="submit" class="btn btn-success">
						<i class="fas fa-qrcode"></i> Print QR Stickers
					</button>
					<button type="button" class="btn btn-outline-secondary" onclick="clearSelection()">
						Clear Selection
					</button>
				</div>
				<input type="hidden" id="selectedBarcodes" name="barcodes" value="">
			</form>

			@if (session('success'))
				<div class="alert alert-success">
					{{ session('success') }}
				</div>
			@endif

			@if (session('print_barcodes'))
				<div class="alert alert-info d-flex flex-wrap align-items-center justify-content-between gap-2">
					<div>
						QR stickers are ready for the GRN items you just saved.
					</div>
					<a href="{{ route('grn.print-stickers') }}" class="btn btn-outline-primary">
						<i class="fas fa-qrcode"></i> Print QR Stickers
					</a>
				</div>
			@endif

			@if ($grns->count() === 0)
				<div class="text-center py-5">
					<div class="mb-2"><i class="fas fa-inbox fa-2x text-muted"></i></div>
					<div class="text-muted">No GRNs found.</div>
				</div>
			@else
				<div class="table-responsive">
					<table class="table table-striped align-middle">
						<thead class="table-light">
							<tr>
								<th style="width: 50px;">
									<input type="checkbox" id="selectAllCheckbox" class="form-check-input" onchange="toggleSelectAll(this)">
								</th>
								<th>Barcode</th>
								<th>GRN No</th>
								<th>GRN Date</th>
								<th>Item Code</th>
								<th>Warehouse</th>
								<th class="text-end">Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($grns as $grn)
								@php
									$barcode = $grn->GBARCODE ?? $grn->gbarcode ?? null;
									$grnNo = $grn->GRNNO ?? $grn->grnno ?? null;
									$grnDate = $grn->GDTE ?? $grn->gdte ?? null;
									$itemCode = $grn->GITMCODE ?? $grn->gitmcode ?? null;
									$whsCode = $grn->WHSCODE ?? $grn->whscode ?? null;
								@endphp
								<tr>
									<td>
										@if (!empty($barcode))
											<input type="checkbox" class="form-check-input grn-checkbox" value="{{ $barcode }}" onchange="updateSelection()">
										@endif
									</td>
									<td>{{ $barcode }}</td>
									<td>{{ $grnNo }}</td>
									<td>{{ $grnDate }}</td>
									<td>{{ $itemCode }}</td>
									<td>{{ $whsCode }}</td>
									<td class="text-end">
										@if (!empty($barcode))
											<a href="{{ route('grn.show', ['gbarcode' => $barcode]) }}" class="btn btn-sm btn-outline-primary">
												View
											</a>
											<a href="{{ route('grn.print', ['gbarcode' => $barcode]) }}" class="btn btn-sm btn-outline-secondary">
												Print
											</a>
										@else
											<span class="text-muted">N/A</span>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>

				<div class="d-flex justify-content-center mt-3">
					{{ $grns->withQueryString()->links('pagination::bootstrap-5') }}
				</div>
			@endif
		</div>
	</div>
</div>

<script>
function updateSelection() {
	const checkboxes = document.querySelectorAll('.grn-checkbox');
	const selectedCheckboxes = document.querySelectorAll('.grn-checkbox:checked');
	const printForm = document.getElementById('printSelectedForm');
	const selectedBarcodes = document.getElementById('selectedBarcodes');
	const selectedCount = document.getElementById('selectedCount');
	const selectAllCheckbox = document.getElementById('selectAllCheckbox');

	// Update select all checkbox
	selectAllCheckbox.checked = checkboxes.length > 0 && checkboxes.length === selectedCheckboxes.length;
	selectAllCheckbox.indeterminate = selectedCheckboxes.length > 0 && selectedCheckboxes.length < checkboxes.length;

	// Update hidden input with selected barcodes
	const barcodes = Array.from(selectedCheckboxes).map(cb => cb.value);
	selectedBarcodes.value = barcodes.join(',');

	// Update selected count and show/hide print form
	selectedCount.textContent = barcodes.length;
	if (barcodes.length > 0) {
		printForm.style.display = 'block';
	} else {
		printForm.style.display = 'none';
	}
}

function toggleSelectAll(checkbox) {
	const checkboxes = document.querySelectorAll('.grn-checkbox');
	checkboxes.forEach(cb => {
		cb.checked = checkbox.checked;
	});
	updateSelection();
}

function clearSelection() {
	document.querySelectorAll('.grn-checkbox').forEach(cb => {
		cb.checked = false;
	});
	document.getElementById('selectAllCheckbox').checked = false;
	updateSelection();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
	updateSelection();
});
</script>

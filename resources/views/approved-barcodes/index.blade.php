@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Approved Barcodes</h3>
            <div class="text-muted">Viewing barcodes with sold and approved dates</div>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('approved-barcodes.index') }}" method="GET" class="row g-3">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Search by Barcode, Invoice No, or Item Name..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Approved Barcodes List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Barcode</th>
                            <th>Invoice No</th>
                            <th>Item Name</th>
                            <th>Sold date</th>
                            <th>Approved date</th>
                            <th>Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($approvedIssues as $issue)
                            <tr>
                                <td>{{ ($approvedIssues->firstItem() ?? 1) + $loop->index }}</td>
                                <td><code>{{ $issue->ibarcode }}</code></td>
                                <td>{{ $issue->invno }}</td>
                                <td>{{ $issue->itmnme }}</td>
                                <td>{{ $issue->saledtme ? $issue->saledtme->format('Y-m-d H:i') : 'N/A' }}</td>
                                <td>{{ $issue->iaprdte ? $issue->iaprdte->format('Y-m-d H:i') : 'N/A' }}</td>
                                <td>
                                    <small class="text-muted">{{ $issue->iremark ?: '-' }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No approved barcodes found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($approvedIssues->hasPages())
            <div class="card-footer">
                {{ $approvedIssues->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('extra-css')
<style>
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
    }
    code {
        color: #fff;
        font-weight: 700;
    }
</style>
@endsection

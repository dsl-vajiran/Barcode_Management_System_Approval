@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Dashboard</h3>
            <div class="text-muted">Welcome back, {{ Auth::user()->name }}</div>
        </div>
        <div class="badge rounded-pill text-bg-primary px-3 py-2">
            {{ $role_label }}
        </div>
    </div>



    <h4 class="mb-3">Barcode Approval</h4>
    <div class="row g-4">
        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('barcode-approval.index') }}" class="feature-card card h-100 text-decoration-none">
                <div class="card-body">
                    <div class="feature-icon">✅</div>
                    <h5 class="card-title">Barcode Approval</h5>
                    <p class="text-muted mb-0">Approve barcode records and statuses.</p>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('approved-barcodes.index') }}" class="feature-card card h-100 text-decoration-none">
                <div class="card-body">
                    <div class="feature-icon">📜</div>
                    <h5 class="card-title">View Approved Barcode</h5>
                    <p class="text-muted mb-0">Search and view details of all approved barcodes.</p>
                </div>
            </a>
        </div>
    </div>

    @if (Auth::user()->role === 'admin')
        <h4 class="mb-3 mt-5">Administration</h4>
        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('users.index') }}" class="feature-card card h-100 text-decoration-none">
                    <div class="card-body">
                        <div class="feature-icon">👥</div>
                        <h5 class="card-title">User Management</h5>
                        <p class="text-muted mb-0">Manage system users and permissions.</p>
                    </div>
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@section('extra-css')
<style>
    .feature-card {
        background: var(--bs-card-bg);
        border: 1px solid var(--bs-border-color);
        color: var(--bs-body-color);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .feature-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        background: var(--bs-secondary-bg);
    }

    .feature-card .card-title {
        color: var(--bs-emphasis-color);
        font-weight: 600;
    }

    .feature-card .text-muted {
        color: var(--bs-secondary-color) !important;
        opacity: 0.85;
    }

    .feature-icon {
        font-size: 26px;
        margin-bottom: 10px;
    }

    .disabled-card {
        opacity: 0.55;
        cursor: not-allowed;
    }
</style>
@endsection

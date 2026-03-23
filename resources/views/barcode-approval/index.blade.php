@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Barcode Approval</h3>
            <div class="text-muted">Scan or enter barcode to approve</div>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Compact Approval Form -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Barcode Approval</h5>
        </div>
        <div class="card-body">
            <form id="approvalForm" method="POST" action="{{ route('barcode-approval.store') }}">
                @csrf
                <input type="hidden" id="ibarcode" name="ibarcode">

                <div class="row g-3">
                    <!-- Barcode Input -->
                    <div class="col-md-4">
                        <label for="barcodeInput" class="form-label">Barcode <span class="text-danger">*</span></label>
                        <input type="text" 
                               id="barcodeInput" 
                               class="form-control" 
                               placeholder="Scan barcode..." 
                               autofocus>
                    </div>

                    <!-- Invoice Number (Read-only) -->
                    <div class="col-md-4">
                        <label for="displayInvoiceNumber" class="form-label">
                            Invoice Number
                            <i class="fas fa-check-circle text-success ms-1 d-none" id="invoiceFoundIcon"></i>
                        </label>
                        <input type="text" 
                               id="displayInvoiceNumber" 
                               class="form-control" 
                               readonly>
                    </div>

                    <!-- Dealer (Read-only) -->
                    <div class="col-md-4">
                        <label for="dealerInput" class="form-label">Dealer</label>
                        <input type="text" 
                               id="dealerInput" 
                               class="form-control" 
                               readonly>
                    </div>

                    <!-- Sale Date -->
                    <div class="col-md-4">
                        <label for="saleDateInput" class="form-label">Sale Date <span class="text-danger">*</span></label>
                        <input type="datetime-local" 
                               id="saleDateInput" 
                               name="saledtme" 
                               class="form-control" 
                               required>
                    </div>

                    <!-- End Customer Name -->
                    <div class="col-md-4">
                        <label for="endCustomerNameInput" class="form-label">Customer Name</label>
                        <input type="text" 
                               id="endCustomerNameInput" 
                               name="fncusnm" 
                               class="form-control" 
                               placeholder="Customer name">
                    </div>

                    <!-- End Customer Contact -->
                    <div class="col-md-4">
                        <label for="endCustomerContactInput" class="form-label">Customer Contact</label>
                        <input type="text" 
                               id="endCustomerContactInput" 
                               name="fncustp" 
                               class="form-control" 
                               placeholder="Phone/Email">
                    </div>

                    <!-- Remark -->
                    <div class="col-md-4">
                        <label for="remarkInput" class="form-label">Remark</label>
                        <input type="text" 
                               id="remarkInput" 
                               name="iremark" 
                               class="form-control" 
                               placeholder="Optional remarks">
                    </div>

                    <!-- Messages and Spinner -->
                    <div class="col-12">
                        <div id="searchError" class="alert alert-danger d-none mb-0"></div>
                        <div id="saleDateError" class="alert alert-danger d-none mt-2 mb-0"></div>
                        <div id="loadingSpinner" class="spinner-border text-primary d-none" role="status">
                            <span class="visually-hidden">Searching...</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-success me-2" id="submitBtn" disabled>
                            <i class="fas fa-check-circle"></i> Save & Approve
                        </button>
                        <button type="button" class="btn btn-secondary" id="clearFormBtn">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const barcodeInput = document.getElementById('barcodeInput');
    const searchError = document.getElementById('searchError');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const clearFormBtn = document.getElementById('clearFormBtn');
    const submitBtn = document.getElementById('submitBtn');
    const invoiceFoundIcon = document.getElementById('invoiceFoundIcon');
    const saleDateInput = document.getElementById('saleDateInput');
    const saleDateError = document.getElementById('saleDateError');

    function toDateTimeLocalValue(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    function getSaleDateBounds() {
        const now = new Date();
        const minDate = new Date(now);
        minDate.setFullYear(minDate.getFullYear() - 2);
        return { minDate, maxDate: now };
    }

    function applySaleDateBounds() {
        const bounds = getSaleDateBounds();
        saleDateInput.min = toDateTimeLocalValue(bounds.minDate);
        saleDateInput.max = toDateTimeLocalValue(bounds.maxDate);
    }

    function validateSaleDateRange() {
        saleDateError.classList.add('d-none');

        if (!saleDateInput.value) {
            return false;
        }

        const selected = new Date(saleDateInput.value);
        const bounds = getSaleDateBounds();

        if (selected < bounds.minDate || selected > bounds.maxDate) {
            saleDateError.textContent = 'Sale date must be within the last 2 years and cannot be later than today.';
            saleDateError.classList.remove('d-none');
            return false;
        }

        return true;
    }

    applySaleDateBounds();

    // Handle barcode input (enter key or automatic scan completion)
    barcodeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchBarcode();
        }
    });

    // Auto-search when barcode input has sufficient length (for scanner)
    barcodeInput.addEventListener('input', function(e) {
        const barcode = e.target.value.trim();
        
        // Disable submit button when barcode changes
        submitBtn.disabled = true;
        invoiceFoundIcon.classList.add('d-none');
        
        if (window.searchTimeout) {
            clearTimeout(window.searchTimeout);
        }
        
        // Wait 500ms after user stops typing before searching
        if (barcode.length >= 8) {
            window.searchTimeout = setTimeout(() => {
                searchBarcode();
            }, 500);
        }
    });

    function searchBarcode() {
        const barcode = barcodeInput.value.trim();

        if (!barcode) {
            showError('Please enter a barcode');
            return;
        }

        searchError.classList.add('d-none');
        loadingSpinner.classList.remove('d-none');
        submitBtn.disabled = true;

        fetch('{{ route("barcode-approval.search-barcode") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ barcode: barcode })
        })
        .then(response => response.json())
        .then(data => {
            loadingSpinner.classList.add('d-none');

            if (data.error) {
                showError(data.error);
                submitBtn.disabled = true;
                invoiceFoundIcon.classList.add('d-none');
                clearFields();
            } else {
                searchError.classList.add('d-none');
                populateForm(data.data);
                submitBtn.disabled = false;
                invoiceFoundIcon.classList.remove('d-none');
            }
        })
        .catch(error => {
            loadingSpinner.classList.add('d-none');
            showError('Error searching barcode: ' + error.message);
            submitBtn.disabled = true;
        });
    }

    function showError(message) {
        searchError.textContent = message;
        searchError.classList.remove('d-none');
    }

    function populateForm(data) {
        // Set hidden barcode field
        document.getElementById('ibarcode').value = data.ibarcode || '';
        
        // Display invoice number
        document.getElementById('displayInvoiceNumber').value = data.invno || 'N/A';
        
        // Display dealer name
        document.getElementById('dealerInput').value = data.dealer || 'N/A';
        
        // Set current datetime as default for sale date within the allowed window
        applySaleDateBounds();
        saleDateInput.value = saleDateInput.max;
        validateSaleDateRange();
        
        // Focus on the customer name field
        document.getElementById('endCustomerNameInput').focus();
    }

    function clearFields() {
        document.getElementById('displayInvoiceNumber').value = '';
        document.getElementById('dealerInput').value = '';
        document.getElementById('ibarcode').value = '';
    }

    function clearForm() {
        document.getElementById('approvalForm').reset();
        searchError.classList.add('d-none');
        saleDateError.classList.add('d-none');
        submitBtn.disabled = true;
        invoiceFoundIcon.classList.add('d-none');
        applySaleDateBounds();
        barcodeInput.focus();
    }

    // Before form submission, set N/A for empty customer fields
    document.getElementById('approvalForm').addEventListener('submit', function(e) {
        if (!validateSaleDateRange()) {
            e.preventDefault();
            saleDateInput.focus();
            return;
        }

        const customerName = document.getElementById('endCustomerNameInput');
        const customerContact = document.getElementById('endCustomerContactInput');
        
        if (!customerName.value.trim()) {
            customerName.value = 'N/A';
        }
        if (!customerContact.value.trim()) {
            customerContact.value = 'N/A';
        }
    });

    saleDateInput.addEventListener('change', validateSaleDateRange);

    clearFormBtn.addEventListener('click', clearForm);
});
</script>

<style>
.text-danger {
    color: #dc3545;
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.g-3 {
    --bs-gutter-y: 0.75rem;
}

#displayInvoiceNumber,
#dealerInput {
    background-color: var(--bs-secondary-bg);
    color: var(--bs-body-color);
    font-weight: 500;
}

#loadingSpinner {
    width: 1.5rem;
    height: 1.5rem;
}
</style>
@endsection

@extends('layouts.app')

@section('content')
<div class="container-fluid my-5 px-md-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-9">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-plus-circle"></i> Create New GRN</h4>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading">Error!</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('grn.store') }}" method="POST" id="grnForm">
                        @csrf

                        <!-- G.R.N. No -->
                        <div class="mb-3">
                            <label for="grnno" class="form-label">
                                <strong>G.R.N. No <span class="text-danger">*</span></strong>
                            </label>
                            <input 
                                type="text" 
                                class="form-control @error('grnno') is-invalid @enderror" 
                                id="grnno" 
                                name="grnno" 
                                placeholder="Enter GRN Number"
                                value="{{ old('grnno') }}"
                                required
                            >
                            @error('grnno')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- GRN Date -->
                        <div class="mb-3">
                            <label for="gdte" class="form-label">
                                <strong>GRN Date <span class="text-danger">*</span></strong>
                            </label>
                            <input 
                                type="date" 
                                class="form-control @error('gdte') is-invalid @enderror" 
                                id="gdte" 
                                name="gdte"
                                value="{{ old('gdte', date('Y-m-d')) }}"
                                required
                            >
                            @error('gdte')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Item Entry -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="mb-3">Add Item To GRN</h6>
                                <div class="row g-3">
                                    <div class="col-12 col-md-5">
                                        <label for="gitmcode" class="form-label">
                                            <strong>Item Code <span class="text-danger">*</span></strong>
                                        </label>
                                        <div class="typeahead">
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="gitmcode"
                                                placeholder="Type item code"
                                                autocomplete="off"
                                            >
                                            <div class="typeahead-menu d-none" id="itemCodeMenu"></div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label for="serial" class="form-label">
                                            <strong>Scanned Serial <span class="text-danger">*</span></strong>
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="serial"
                                            placeholder="Scan serial"
                                            autocomplete="off"
                                        >
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label for="gbarcode" class="form-label">
                                            <strong>New Barcode</strong>
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="gbarcode"
                                            placeholder="Auto-generated"
                                            readonly
                                        >
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    <button type="button" class="btn btn-primary" id="addItemBtn">
                                        <i class="fas fa-plus"></i> Add Item
                                    </button>
                                    <div class="text-muted small align-self-center" id="itemHint">
                                        Generated barcode format: SERIAL-ItemCode-GRNNO
                                    </div>
                                </div>
                                <div class="text-danger small mt-2 d-none" id="itemError"></div>
                            </div>
                        </div>

                        <!-- Remark (Optional) -->
                        <div class="mb-3">
                            <label for="gremark" class="form-label">
                                <strong>Remark <span class="text-muted">(Optional)</span></strong>
                            </label>
                            <textarea 
                                class="form-control @error('gremark') is-invalid @enderror" 
                                id="gremark" 
                                name="gremark"
                                rows="3"
                                placeholder="Enter any remarks (max 250 characters)"
                                maxlength="250"
                            >{{ old('gremark') }}</textarea>
                            @error('gremark')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Warehouse Code (Optional) -->
                        <div class="mb-4">
                            <label for="whscode" class="form-label">
                                <strong>Warehouse Code <span class="text-muted">(Optional)</span></strong>
                            </label>
                            <div class="typeahead">
                                <input 
                                    type="text" 
                                    class="form-control @error('whscode') is-invalid @enderror" 
                                    id="whscode" 
                                    name="whscode" 
                                    placeholder="Type warehouse code"
                                    value="{{ old('whscode') }}"
                                    maxlength="10"
                                    autocomplete="off"
                                >
                                <div class="typeahead-menu d-none" id="whsCodeMenu"></div>
                            </div>
                            @error('whscode')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Items List -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">GRN Item List <span class="badge bg-secondary" id="itemCount">0</span></h6>
                                    <div class="d-none" id="printSelectedSection">
                                        <button type="button" class="btn btn-sm btn-success" id="printSelectedBtn">
                                            <i class="fas fa-qrcode"></i> Print QR (<span id="printCount">0</span>)
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary ms-1" id="clearSelectionBtn">
                                            Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped align-middle" id="itemsTable">
                                        <thead>
                                            <tr>
                                                <th style="width:40px;"><input type="checkbox" class="form-check-input" id="selectAllItems"></th>
                                                <th>Item Code</th>
                                                <th>Serial</th>
                                                <th>Generated Barcode</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="text-muted" id="emptyRow">
                                                <td colspan="5">No items added yet.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Unsaved items warning -->
                        <div class="alert alert-warning d-none" id="unsavedWarning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Unsaved items!</strong> You have items added to the list. Please save or you will lose them.
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Save GRN Items
                            </button>
                            <a href="{{ route('grn.index') }}" class="btn btn-secondary btn-lg" id="cancelBtn">
                                <i class="fas fa-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        margin-top: 20px;
    }

    .card-header {
        border-radius: 0.375rem 0.375rem 0 0;
        padding: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: inherit;
    }

    .text-danger {
        font-weight: bold;
    }

    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-weight: 600;
    }

    .typeahead {
        position: relative;
    }

    .typeahead-menu {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        z-index: 20;
        background: rgba(15, 23, 42, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 8px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
        max-height: 220px;
        overflow-y: auto;
    }

    .typeahead-item {
        padding: 10px 12px;
        cursor: pointer;
        color: #fff;
        font-size: 0.9rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .typeahead-item:last-child {
        border-bottom: none;
    }

    .typeahead-item:hover,
    .typeahead-item.active {
        background: rgba(255, 255, 255, 0.12);
    }

    .typeahead-item span {
        display: block;
        font-size: 0.75rem;
        opacity: 0.75;
    }
</style>

<script>
    (function() {
        var items = [];
        var itemsTable = document.getElementById('itemsTable');
        var emptyRow = document.getElementById('emptyRow');
        var itemError = document.getElementById('itemError');
        var addBtn = document.getElementById('addItemBtn');
        var grnNoInput = document.getElementById('grnno');
        var itemCodeInput = document.getElementById('gitmcode');
        var serialInput = document.getElementById('serial');
        var barcodeInput = document.getElementById('gbarcode');
        var form = document.getElementById('grnForm');
        var oldItems = @json(old('items', []));
        var itemCodeMenu = document.getElementById('itemCodeMenu');
        var searchTimer = null;
        var lastQuery = '';
        var itemOptions = [];

        var normalize = function(value) {
            return (value || '').trim();
        };

        var buildBarcode = function() {
            var serial = normalize(serialInput.value);
            var itemCode = normalize(itemCodeInput.value);
            var grnNo = normalize(grnNoInput.value);
            if (!serial || !itemCode || !grnNo) {
                barcodeInput.value = '';
                return;
            }
            barcodeInput.value = serial + '-' + itemCode + '-' + grnNo;
        };

        var showMenu = function() {
            itemCodeMenu.classList.remove('d-none');
        };

        var renderItemOptions = function(items) {
            itemOptions = items;
            itemCodeMenu.innerHTML = '';

            if (!items.length) {
                var empty = document.createElement('div');
                empty.className = 'typeahead-item';
                empty.textContent = 'No matches found';
                itemCodeMenu.appendChild(empty);
                showMenu();
                return;
            }

            items.forEach(function(item) {
                var option = document.createElement('div');
                option.className = 'typeahead-item';
                option.setAttribute('data-code', item.code);
                option.innerHTML =
                    '<strong>' + item.code + '</strong>' +
                    (item.name ? '<span>' + item.name + '</span>' : '');
                itemCodeMenu.appendChild(option);
            });

            showMenu();
        };

        var fetchItemCodes = function(query) {
            if (!query) {
                query = '';
            }

            if (query === lastQuery) {
                return;
            }

            lastQuery = query;
            fetch('{{ route('grn.item-codes') }}?q=' + encodeURIComponent(query))
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    renderItemOptions(Array.isArray(data.items) ? data.items : []);
                })
                .catch(function() {
                    itemCodeMenu.innerHTML = '';
                    var error = document.createElement('div');
                    error.className = 'typeahead-item';
                    error.textContent = 'Unable to load item codes';
                    itemCodeMenu.appendChild(error);
                    showMenu();
                });
        };

        var unsavedWarning = document.getElementById('unsavedWarning');
        var itemCountBadge = document.getElementById('itemCount');
        var printSelectedSection = document.getElementById('printSelectedSection');
        var printSelectedBtn = document.getElementById('printSelectedBtn');
        var clearSelectionBtn = document.getElementById('clearSelectionBtn');
        var selectAllItems = document.getElementById('selectAllItems');
        var printCountSpan = document.getElementById('printCount');
        var isSaving = false;

        var updateSelectionUI = function() {
            var checkboxes = itemsTable.querySelectorAll('.item-select');
            var checked = itemsTable.querySelectorAll('.item-select:checked');
            var count = checked.length;

            printCountSpan.textContent = count;
            if (count > 0) {
                printSelectedSection.classList.remove('d-none');
            } else {
                printSelectedSection.classList.add('d-none');
            }

            selectAllItems.checked = checkboxes.length > 0 && checkboxes.length === checked.length;
            selectAllItems.indeterminate = count > 0 && count < checkboxes.length;
        };

        var renderItems = function() {
            var tbody = itemsTable.querySelector('tbody');
            tbody.innerHTML = '';
            itemCountBadge.textContent = items.length;

            if (!items.length) {
                tbody.appendChild(emptyRow);
                emptyRow.classList.remove('d-none');
                unsavedWarning.classList.add('d-none');
                printSelectedSection.classList.add('d-none');
                selectAllItems.checked = false;
                return;
            }

            emptyRow.classList.add('d-none');
            unsavedWarning.classList.remove('d-none');

            items.forEach(function(item, index) {
                var row = document.createElement('tr');
                row.innerHTML =
                    '<td><input type="checkbox" class="form-check-input item-select" data-index="' + index + '" data-barcode="' + item.gbarcode + '"></td>' +
                    '<td>' + item.gitmcode + '</td>' +
                    '<td>' + item.serial + '</td>' +
                    '<td>' + item.gbarcode + '</td>' +
                    '<td class="text-end">' +
                        '<button type="button" class="btn btn-sm btn-outline-secondary" data-index="' + index + '">Remove</button>' +
                    '</td>' +
                    '<input type="hidden" name="items[' + index + '][gitmcode]" value="' + item.gitmcode + '">' +
                    '<input type="hidden" name="items[' + index + '][serial]" value="' + item.serial + '">' +
                    '<input type="hidden" name="items[' + index + '][gbarcode]" value="' + item.gbarcode + '">';
                tbody.appendChild(row);
            });

            updateSelectionUI();
        };

        var showError = function(message) {
            itemError.textContent = message;
            itemError.classList.remove('d-none');
        };

        var clearError = function() {
            itemError.textContent = '';
            itemError.classList.add('d-none');
        };

        var addItem = function() {
            clearError();
            var itemCode = normalize(itemCodeInput.value);
            var serial = normalize(serialInput.value);
            var grnNo = normalize(grnNoInput.value);
            var barcode = normalize(barcodeInput.value);

            if (!grnNo) {
                showError('Please enter the GRN number before adding items.');
                return;
            }

            if (!itemCode || !serial || !barcode) {
                showError('Please select an item code and scan a serial to generate the barcode.');
                return;
            }

            var exists = items.some(function(item) {
                return item.gbarcode === barcode;
            });

            if (exists) {
                showError('Duplicate barcode detected. Please scan a unique serial.');
                return;
            }

            items.push({
                gitmcode: itemCode,
                serial: serial,
                gbarcode: barcode
            });

            serialInput.value = '';
            barcodeInput.value = '';
            renderItems();
            serialInput.focus();
        };

        itemCodeInput.addEventListener('change', buildBarcode);
        itemCodeInput.addEventListener('input', function() {
            buildBarcode();
            var query = normalize(itemCodeInput.value);
            if (searchTimer) {
                window.clearTimeout(searchTimer);
            }
            searchTimer = window.setTimeout(function() {
                fetchItemCodes(query);
            }, 250);
        });
        itemCodeInput.addEventListener('focus', function() {
            if (itemOptions.length) {
                showMenu();
            } else {
                fetchItemCodes('');
            }
        });
        serialInput.addEventListener('input', buildBarcode);
        grnNoInput.addEventListener('input', buildBarcode);

        addBtn.addEventListener('click', addItem);

        itemsTable.addEventListener('click', function(event) {
            if (event.target.matches('button[data-index]')) {
                var index = Number(event.target.getAttribute('data-index'));
                if (!Number.isNaN(index)) {
                    items.splice(index, 1);
                    renderItems();
                }
            }
        });

        itemCodeMenu.addEventListener('click', function(event) {
            var target = event.target.closest('.typeahead-item');
            if (!target) {
                return;
            }
            var code = target.getAttribute('data-code');
            if (!code) {
                return;
            }
            itemCodeInput.value = code;
            buildBarcode();
            itemCodeMenu.classList.add('d-none');
            serialInput.focus();
        });

        document.addEventListener('click', function(event) {
            if (!event.target.closest('.typeahead')) {
                itemCodeMenu.classList.add('d-none');
                whsCodeMenu.classList.add('d-none');
            }
        });

        // ---- Warehouse Code Typeahead ----
        var whsCodeInput = document.getElementById('whscode');
        var whsCodeMenu = document.getElementById('whsCodeMenu');
        var whsSearchTimer = null;
        var whsLastQuery = '';
        var whsOptions = [];

        var showWhsMenu = function() {
            whsCodeMenu.classList.remove('d-none');
        };

        var renderWhsOptions = function(items) {
            whsOptions = items;
            whsCodeMenu.innerHTML = '';

            if (!items.length) {
                var empty = document.createElement('div');
                empty.className = 'typeahead-item';
                empty.textContent = 'No warehouses found';
                whsCodeMenu.appendChild(empty);
                showWhsMenu();
                return;
            }

            items.forEach(function(item) {
                var option = document.createElement('div');
                option.className = 'typeahead-item';
                option.setAttribute('data-code', item.code);
                option.innerHTML =
                    '<strong>' + item.code + '</strong>' +
                    (item.name ? '<span>' + item.name + '</span>' : '');
                whsCodeMenu.appendChild(option);
            });

            showWhsMenu();
        };

        var fetchWhsCodes = function(query) {
            if (!query) query = '';
            if (query === whsLastQuery) return;
            whsLastQuery = query;

            fetch('{{ route("grn.warehouse-codes") }}?q=' + encodeURIComponent(query))
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    renderWhsOptions(Array.isArray(data.items) ? data.items : []);
                })
                .catch(function() {
                    whsCodeMenu.innerHTML = '';
                    var error = document.createElement('div');
                    error.className = 'typeahead-item';
                    error.textContent = 'Unable to load warehouses';
                    whsCodeMenu.appendChild(error);
                    showWhsMenu();
                });
        };

        whsCodeInput.addEventListener('input', function() {
            var query = normalize(whsCodeInput.value);
            if (whsSearchTimer) window.clearTimeout(whsSearchTimer);
            whsSearchTimer = window.setTimeout(function() {
                fetchWhsCodes(query);
            }, 250);
        });

        whsCodeInput.addEventListener('focus', function() {
            if (whsOptions.length) {
                showWhsMenu();
            } else {
                fetchWhsCodes('');
            }
        });

        whsCodeMenu.addEventListener('click', function(event) {
            var target = event.target.closest('.typeahead-item');
            if (!target) return;
            var code = target.getAttribute('data-code');
            if (!code) return;
            whsCodeInput.value = code;
            whsCodeMenu.classList.add('d-none');
        });

        form.addEventListener('submit', function(event) {
            if (!items.length) {
                event.preventDefault();
                showError('Please add at least one item before saving.');
            } else {
                isSaving = true;
            }
        });

        // Prevent leaving page with unsaved items
        window.addEventListener('beforeunload', function(event) {
            if (items.length > 0 && !isSaving) {
                event.preventDefault();
                event.returnValue = 'You have unsaved GRN items. Are you sure you want to leave?';
                return event.returnValue;
            }
        });

        // Select All checkbox
        selectAllItems.addEventListener('change', function() {
            var checkboxes = itemsTable.querySelectorAll('.item-select');
            checkboxes.forEach(function(cb) { cb.checked = selectAllItems.checked; });
            updateSelectionUI();
        });

        // Individual checkbox change
        itemsTable.addEventListener('change', function(event) {
            if (event.target.classList.contains('item-select')) {
                updateSelectionUI();
            }
        });

        // Clear selection
        clearSelectionBtn.addEventListener('click', function() {
            itemsTable.querySelectorAll('.item-select').forEach(function(cb) { cb.checked = false; });
            selectAllItems.checked = false;
            updateSelectionUI();
        });

        // Print selected QR stickers
        printSelectedBtn.addEventListener('click', function() {
            var checked = itemsTable.querySelectorAll('.item-select:checked');
            if (!checked.length) return;

            var barcodes = Array.from(checked).map(function(cb) { return cb.getAttribute('data-barcode'); });

            // Open print in new window via hidden form
            var printForm = document.createElement('form');
            printForm.method = 'POST';
            printForm.action = '{{ route('grn.print-selected') }}';
            printForm.target = '_blank';

            var csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            printForm.appendChild(csrf);

            var barcodesInput = document.createElement('input');
            barcodesInput.type = 'hidden';
            barcodesInput.name = 'barcodes';
            barcodesInput.value = barcodes.join(',');
            printForm.appendChild(barcodesInput);

            document.body.appendChild(printForm);
            printForm.submit();
            document.body.removeChild(printForm);
        });

        // Cancel button warns if unsaved items
        document.getElementById('cancelBtn').addEventListener('click', function(event) {
            if (items.length > 0) {
                if (!confirm('You have ' + items.length + ' unsaved GRN item(s). Are you sure you want to leave?')) {
                    event.preventDefault();
                }
            }
        });

        if (Array.isArray(oldItems) && oldItems.length) {
            items = oldItems.map(function(item) {
                return {
                    gitmcode: normalize(item.gitmcode),
                    serial: normalize(item.serial),
                    gbarcode: normalize(item.gbarcode)
                };
            }).filter(function(item) {
                return item.gitmcode && item.serial && item.gbarcode;
            });
            renderItems();
        }
    })();
</script>
@endsection

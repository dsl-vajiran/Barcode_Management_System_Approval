<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Details - DSL Barcode System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1f3a 0%, #16213e 50%, #0f3460 100%);
            background-attachment: fixed;
            min-height: 100vh;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        .navbar {
            background: linear-gradient(135deg, #1a1f3a 0%, #16213e 50%, #0f3460 100%);
            color: white;
            padding: 0 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 10;
        }

        .navbar-brand {
            font-size: 22px;
            font-weight: 700;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 1000px;
            margin: 60px auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        .details-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .details-card h1 {
            color: white;
            margin-bottom: 5px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .barcode-ref {
            color: rgba(255, 255, 255, 0.6);
            font-size: 13px;
            margin-bottom: 30px;
        }

        .type-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            margin-left: 10px;
        }

        .type-grn {
            background: rgba(76, 175, 80, 0.3);
            color: #c8e6c9;
            border: 1px solid rgba(76, 175, 80, 0.4);
        }

        .type-issue {
            background: rgba(33, 150, 243, 0.3);
            color: #90caf9;
            border: 1px solid rgba(33, 150, 243, 0.4);
        }

        .type-item {
            background: rgba(255, 152, 0, 0.3);
            color: #ffe0b2;
            border: 1px solid rgba(255, 152, 0, 0.4);
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .detail-section {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 20px;
        }

        .detail-section:last-child {
            border-bottom: none;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .detail-label {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 40%;
        }

        .detail-value {
            color: white;
            font-size: 14px;
            word-break: break-word;
            text-align: right;
            width: 60%;
        }

        .detail-value.empty {
            color: rgba(255, 255, 255, 0.5);
            font-style: italic;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-issued, .status-approved, .status-active {
            background: rgba(76, 175, 80, 0.3);
            color: #c8e6c9;
            border: 1px solid rgba(76, 175, 80, 0.4);
        }

        .status-not-issued, .status-not-approved, .status-inactive {
            background: rgba(255, 152, 0, 0.3);
            color: #ffe0b2;
            border: 1px solid rgba(255, 152, 0, 0.4);
        }

        .status-sold {
            background: rgba(76, 175, 80, 0.3);
            color: #c8e6c9;
            border: 1px solid rgba(76, 175, 80, 0.4);
        }

        .status-not-sold {
            background: rgba(244, 67, 54, 0.3);
            color: #ffcdd2;
            border: 1px solid rgba(244, 67, 54, 0.4);
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            justify-content: center;
        }

        .btn {
            padding: 10px 25px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.1));
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.2));
            border-color: rgba(255, 255, 255, 0.6);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .container {
                margin: 40px auto;
            }

            .details-card {
                padding: 25px;
            }

            .details-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .detail-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .detail-label {
                width: 100%;
                margin-bottom: 5px;
            }

            .detail-value {
                width: 100%;
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-brand">🔋 DSL Barcode System</div>
        <div class="navbar-right">
            <a href="{{ route('barcode.index') }}" class="back-btn">← Back to Search</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="details-card">
            <h1>Barcode Details
                @if(isset($type))
                    <span class="type-badge type-{{ $type }}">{{ strtoupper($type) }}</span>
                @endif
            </h1>
            
            @if(isset($type))
                @if($type == 'grn')
                    <div class="barcode-ref">Barcode: {{ $grn->gbarcode ?? 'N/A' }}</div>
                @elseif($type == 'issue')
                    <div class="barcode-ref">Barcode: {{ $issue->ibarcode ?? 'N/A' }}</div>
                @elseif($type == 'item')
                    <div class="barcode-ref">Item Code: {{ $item->itmcode ?? 'N/A' }}</div>
                @endif
            @else
                <div class="barcode-ref">Reference: {{ $issue->ibarcode ?? $grn->gbarcode ?? $item->itmcode ?? 'N/A' }}</div>
            @endif

            <div class="details-grid">
                @if(isset($type) && $type == 'grn')
                    <!-- GRN Details -->
                    @if(isset($grn))
                        <!-- GRN Information -->
                        <div class="detail-section">
                            <div class="detail-row">
                                <span class="detail-label">Barcode</span>
                                <span class="detail-value">{{ $grn->gbarcode ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">GRN Number</span>
                                <span class="detail-value">{{ $grn->grnno ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">GRN Date</span>
                                <span class="detail-value">{{ $grn->gdte ? \Carbon\Carbon::parse($grn->gdte)->format('Y-m-d') : '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Created By</span>
                                <span class="detail-value">{{ $grn->gcrtusr ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Created Date</span>
                                <span class="detail-value">{{ $grn->gcrtdtme ? \Carbon\Carbon::parse($grn->gcrtdtme)->format('Y-m-d H:i') : '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Status</span>
                                <span class="detail-value">
                                    <span class="status-badge {{ $grn->gchact == 1 ? 'status-active' : 'status-inactive' }}">
                                        {{ $grn->gchact == 1 ? 'Active' : 'Inactive' }}
                                    </span>
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Printed</span>
                                <span class="detail-value">{{ $grn->gchprt == 1 ? 'Yes' : 'No' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Warehouse Code</span>
                                <span class="detail-value">{{ $grn->whscode ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Remarks</span>
                                <span class="detail-value">{{ $grn->gremark ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Item Information from Item Master -->
                        @if(isset($item) && $item)
                            <div class="detail-section">
                                <div class="detail-row">
                                    <span class="detail-label">Item Code</span>
                                    <span class="detail-value">{{ $item->itmcode ?? '-' }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Item Name</span>
                                    <span class="detail-value">{{ $item->itmnme ?? '-' }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Item Model</span>
                                    <span class="detail-value">{{ $item->itmmod ?? '-' }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Item Ampere</span>
                                    <span class="detail-value">{{ $item->itmamp ?? '-' }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Brand</span>
                                    <span class="detail-value">{{ $item->brand ?? '-' }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Full Warranty</span>
                                    <span class="detail-value">{{ $item->f_war ?? '-' }} months</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Pro-Rate Warranty</span>
                                    <span class="detail-value">{{ $item->pa_war ?? '-' }} months</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Phase</span>
                                    <span class="detail-value">{{ $item->prphase ?? '-' }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Remarks</span>
                                    <span class="detail-value">{{ $item->remark ?? '-' }}</span>
                                </div>
                            </div>
                        @endif
                    @endif

                @elseif(isset($type) && $type == 'issue')
                    <!-- Issue Details -->
                    @if(isset($issue))
                        <!-- Item Information -->
                        <div class="detail-section">
                            <div class="detail-row">
                                <span class="detail-label">Barcode</span>
                                <span class="detail-value">{{ $issue->ibarcode ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Item Name</span>
                                <span class="detail-value">{{ $issue->itmnme ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Item Model</span>
                                <span class="detail-value">{{ $issue->itmmod ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Item Ampere</span>
                                <span class="detail-value">{{ $issue->itmamp ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Brand</span>
                                <span class="detail-value">{{ $issue->brand ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Warranty Information -->
                        <div class="detail-section">
                            <div class="detail-row">
                                <span class="detail-label">Full Warranty</span>
                                <span class="detail-value">{{ $issue->f_war ?? '-' }} months</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Pro-Rate Warranty</span>
                                <span class="detail-value">{{ $issue->pa_war ?? '-' }} months</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Phase</span>
                                <span class="detail-value">{{ $issue->prphase ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Remark</span>
                                <span class="detail-value">{{ $issue->remark ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Issue Information -->
                        <div class="detail-section">
                            <div class="detail-row">
                                <span class="detail-label">Status</span>
                                <span class="detail-value">
                                    @if ($issue->isudtme)
                                        <span class="status-badge status-issued">Issued</span>
                                    @else
                                        <span class="status-badge status-not-issued">Not Issued</span>
                                    @endif
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Issue Date</span>
                                <span class="detail-value">{{ $issue->isudtme ? \Carbon\Carbon::parse($issue->isudtme)->format('Y-m-d H:i') : '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Invoice No</span>
                                <span class="detail-value">{{ $issue->invno ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Location</span>
                                <span class="detail-value">{{ $issue->location ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Issue Remarks</span>
                                <span class="detail-value">{{ $issue->iremark ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- GRN Information (if available) -->
                        @if(isset($grn) && $grn)
                            <div class="detail-section">
                                <div class="detail-row">
                                    <span class="detail-label">GRN Number</span>
                                    <span class="detail-value">{{ $grn->grnno ?? '-' }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">GRN Date</span>
                                    <span class="detail-value">{{ $grn->gdte ? \Carbon\Carbon::parse($grn->gdte)->format('Y-m-d') : '-' }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Sale Information -->
                        <div class="detail-section">
                            <div class="detail-row">
                                <span class="detail-label">Selling Status</span>
                                <span class="detail-value">
                                    @if ($issue->ichsale)
                                        <span class="status-badge status-sold">Sold</span>
                                    @else
                                        <span class="status-badge status-not-sold">Not Sold</span>
                                    @endif
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Sale Date</span>
                                <span class="detail-value">{{ $issue->saledtme ? \Carbon\Carbon::parse($issue->saledtme)->format('Y-m-d H:i') : '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Final Customer</span>
                                <span class="detail-value">{{ $issue->fncusnm ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Customer Type</span>
                                <span class="detail-value">{{ $issue->fncustp ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Approval -->
                        <div class="detail-section">
                            <div class="detail-row">
                                <span class="detail-label">Approval Status</span>
                                <span class="detail-value">
                                    @if ($issue->ichapr)
                                        <span class="status-badge status-approved">Approved</span>
                                    @else
                                        <span class="status-badge status-not-approved">Not Approved</span>
                                    @endif
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Approval Date</span>
                                <span class="detail-value">{{ $issue->iaprdte ? \Carbon\Carbon::parse($issue->iaprdte)->format('Y-m-d H:i') : '-' }}</span>
                            </div>
                        </div>
                    @endif

                @elseif(isset($type) && $type == 'item')
                    <!-- Item Master Details -->
                    @if(isset($item))
                        <div class="detail-section">
                            <div class="detail-row">
                                <span class="detail-label">Item Code</span>
                                <span class="detail-value">{{ $item->itmcode ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Item Name</span>
                                <span class="detail-value">{{ $item->itmnme ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Item Model</span>
                                <span class="detail-value">{{ $item->itmmod ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Item Ampere</span>
                                <span class="detail-value">{{ $item->itmamp ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Brand</span>
                                <span class="detail-value">{{ $item->brand ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Full Warranty</span>
                                <span class="detail-value">{{ $item->f_war ?? '-' }} months</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Pro-Rate Warranty</span>
                                <span class="detail-value">{{ $item->pa_war ?? '-' }} months</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Phase</span>
                                <span class="detail-value">{{ $item->prphase ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Remarks</span>
                                <span class="detail-value">{{ $item->remark ?? '-' }}</span>
                            </div>
                        </div>
                    @endif

                @else
                    <!-- Fallback for old format -->
                    @if(isset($issue))
                        <div class="detail-section">
                            <div class="detail-row">
                                <span class="detail-label">Barcode</span>
                                <span class="detail-value">{{ $issue->ibarcode ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Item Name</span>
                                <span class="detail-value">{{ $issue->itmnme ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Item Model</span>
                                <span class="detail-value">{{ $issue->itmmod ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Brand</span>
                                <span class="detail-value">{{ $issue->brand ?? '-' }}</span>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <div class="action-buttons">
                <a href="{{ route('barcode.index') }}" class="btn btn-primary">← Search Another</a>
            </div>
        </div>
    </div>
</body>
</html>

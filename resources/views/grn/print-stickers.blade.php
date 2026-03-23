<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GRN QR Stickers</title>
    <style>
        :root {
            --label-border: rgba(0, 0, 0, 0.12);
            --label-text: #111827;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: var(--label-text);
            padding: 24px;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .btn {
            background: #1f2937;
            color: #fff;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-secondary {
            background: #4b5563;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px;
        }

        .sticker {
            border: 1px dashed var(--label-border);
            background: #fff;
            padding: 12px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            min-height: 180px;
        }

        .qr {
            width: 120px;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .label {
            text-align: center;
            font-size: 12px;
            line-height: 1.4;
        }

        .label strong {
            display: block;
            font-size: 13px;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .actions {
                display: none;
            }

            .sticker {
                border: 1px solid #d1d5db;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="actions">
        <div>
            <strong>GRN QR Stickers</strong>
            <div style="font-size: 12px; color: #6b7280;">Serial + Item Code + GRN No encoded</div>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn" onclick="window.print()">Print</button>
            <a class="btn btn-secondary" href="{{ route('grn.index') }}">Back to GRN List</a>
        </div>
    </div>

    <div class="grid">
        @foreach ($stickers as $sticker)
            <div class="sticker">
                <div class="qr">
                    {!! QrCode::size(120)->margin(1)->generate($sticker['barcode']) !!}
                </div>
                <div class="label">
                    <strong>{{ $sticker['barcode'] }}</strong>
                    <div>Serial: {{ $sticker['serial'] ?: '-' }}</div>
                    <div>Item: {{ $sticker['item_code'] ?: '-' }}</div>
                    <div>GRN: {{ $sticker['grn_no'] ?: '-' }}</div>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>

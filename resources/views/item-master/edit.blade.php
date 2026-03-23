<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item Master - DSL Barcode System</title>
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

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 600;
        }

        .user-details {
            font-size: 13px;
        }

        .user-role {
            opacity: 0.8;
            font-size: 12px;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        .page-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .page-header h1 {
            color: white;
            margin-bottom: 10px;
        }

        .page-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }

        .page-header .item-code {
            background: rgba(76, 175, 80, 0.3);
            color: #81c784;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-top: 10px;
        }

        .menu {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .menu button {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.7), rgba(56, 142, 60, 0.7));
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .menu button:hover {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.9), rgba(56, 142, 60, 0.9));
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
        }

        .menu button.secondary {
            background: linear-gradient(135deg, rgba(33, 150, 243, 0.7), rgba(25, 103, 210, 0.7));
        }

        .menu button.secondary:hover {
            background: linear-gradient(135deg, rgba(33, 150, 243, 0.9), rgba(25, 103, 210, 0.9));
            box-shadow: 0 8px 20px rgba(33, 150, 243, 0.3);
        }

        .menu button.cancel {
            background: linear-gradient(135deg, rgba(244, 67, 54, 0.7), rgba(211, 47, 47, 0.7));
        }

        .menu button.cancel:hover {
            background: linear-gradient(135deg, rgba(244, 67, 54, 0.9), rgba(211, 47, 47, 0.9));
            box-shadow: 0 8px 20px rgba(244, 67, 54, 0.3);
        }

        .form-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            color: white;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            color: white;
            font-size: 14px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: all 0.3s;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        textarea:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(76, 175, 80, 0.6);
            box-shadow: 0 0 15px rgba(76, 175, 80, 0.2);
        }

        input::placeholder,
        textarea::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .read-only {
            background: rgba(0, 0, 0, 0.3) !important;
            color: rgba(255, 255, 255, 0.7) !important;
            cursor: not-allowed !important;
        }

        .read-only:focus {
            border-color: rgba(255, 255, 255, 0.3) !important;
            box-shadow: none !important;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 35px;
            justify-content: center;
        }

        .submit-btn {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.8), rgba(56, 142, 60, 0.8));
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 12px 40px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, rgba(76, 175, 80, 1), rgba(56, 142, 60, 1));
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
        }

        .cancel-btn {
            background: linear-gradient(135deg, rgba(244, 67, 54, 0.8), rgba(211, 47, 47, 0.8));
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 12px 40px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .cancel-btn:hover {
            background: linear-gradient(135deg, rgba(244, 67, 54, 1), rgba(211, 47, 47, 1));
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(244, 67, 54, 0.3);
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: white;
        }

        .alert-error {
            background: rgba(244, 67, 54, 0.3);
            border: 1px solid rgba(244, 67, 54, 0.6);
        }

        .error-message {
            color: #ff6b6b;
            font-size: 12px;
            margin-top: 5px;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="navbar-brand">DSL Barcode System</div>
        <div class="navbar-right">
            <div class="user-info">
                <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                <div class="user-details">
                    <div>{{ Auth::user()->name }}</div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <a href="{{ route('item.index') }}" class="back-btn">← Back to Item Master</a>

        <!-- Page Header -->
        <div class="page-header">
            <h1>Edit Item</h1>
            <p>Update item details in the inventory system</p>
            <div class="item-code">Item Code: {{ $item->itmcode }}</div>
        </div>

        <!-- Menu -->
        <div class="menu">
            <button type="button" onclick="document.querySelector('form').submit()">UPDATE</button>
            <a href="{{ route('item.index') }}" style="text-decoration: none;">
                <button type="button" class="cancel">CANCEL</button>
            </a>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert alert-error">
                <strong>Validation Errors:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Item Master Form -->
        <div class="form-container">
            <form method="POST" action="{{ route('item.update', $item->itmcode) }}" id="itemForm">
                @csrf
                @method('PUT')

                <!-- IMAS CODE (Read Only) -->
                <div class="form-group">
                    <label for="imas_code">IMAS CODE</label>
                    <input type="text" id="imas_code" name="itmcode" value="{{ $item->itmcode }}" class="read-only" readonly>
                    <small style="color: rgba(255, 255, 255, 0.6); display: block; margin-top: 5px;">Item code cannot be changed</small>
                </div>

                <!-- ITEM NAME -->
                <div class="form-group">
                    <label for="item_name">ITEM NAME <span style="color: red;">*</span></label>
                    <input type="text" id="item_name" name="itmnme" value="{{ old('itmnme', $item->itmnme) }}" placeholder="Enter item name" required>
                    @error('itmnme')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- ITEM MODEL & AMPERE -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="item_model">ITEM MODEL</label>
                        <input type="text" id="item_model" name="itmmod" value="{{ old('itmmod', $item->itmmod) }}" placeholder="Enter item model">
                        @error('itmmod')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="ampere">AMPERE</label>
                        <input type="text" id="ampere" name="itmamp" value="{{ old('itmamp', $item->itmamp) }}" placeholder="Enter ampere rating">
                        @error('itmamp')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- WARRANTY DETAILS -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="full_warranty">FULL WARRANTY (Months)</label>
                        <input type="number" id="full_warranty" name="f_war" value="{{ old('f_war', $item->f_war) }}" placeholder="Enter warranty period in months" min="0">
                        @error('f_war')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="pro_rata_warranty">PRO-RATA WARRANTY (Months)</label>
                        <input type="number" id="pro_rata_warranty" name="pa_war" value="{{ old('pa_war', $item->pa_war) }}" placeholder="Enter pro-rata period in months" min="0">
                        @error('pa_war')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- BRAND & PRINTED TERM -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="brand">BRAND</label>
                        <input type="text" id="brand" name="brand" value="{{ old('brand', $item->brand) }}" placeholder="Enter brand name">
                        @error('brand')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="printed_term">PRINTED TERM</label>
                        <input type="text" id="printed_term" name="prphase" value="{{ old('prphase', $item->prphase) }}" placeholder="Enter printed term details">
                        @error('prphase')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- REMARKS -->
                <div class="form-group">
                    <label for="remarks">REMARKS</label>
                    <textarea id="remarks" name="remark" placeholder="Enter any additional remarks or notes">{{ old('remark', $item->remark) }}</textarea>
                    @error('remark')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="submit-btn">UPDATE ITEM</button>
                    <a href="{{ route('item.index') }}" class="cancel-btn">CANCEL</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

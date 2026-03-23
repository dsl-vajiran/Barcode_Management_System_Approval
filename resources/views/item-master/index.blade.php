<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Master - DSL Barcode System</title>
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
            max-width: 1200px;
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

        .search-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            padding: 20px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            margin-bottom: 30px;
        }

        .search-input {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            color: white;
            font-size: 14px;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .table-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: rgba(0, 0, 0, 0.2);
        }

        th {
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.9);
        }

        tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .action-btn {
            padding: 6px 12px;
            margin-right: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .edit-btn {
            background: rgba(33, 150, 243, 0.7);
            color: white;
        }

        .edit-btn:hover {
            background: rgba(33, 150, 243, 0.9);
        }

        .delete-btn {
            background: rgba(244, 67, 54, 0.7);
            color: white;
        }

        .delete-btn:hover {
            background: rgba(244, 67, 54, 0.9);
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: white;
        }

        .alert-success {
            background: rgba(76, 175, 80, 0.3);
            border: 1px solid rgba(76, 175, 80, 0.6);
        }

        .alert-error {
            background: rgba(244, 67, 54, 0.3);
            border: 1px solid rgba(244, 67, 54, 0.6);
        }

        .empty-message {
            text-align: center;
            padding: 40px;
            color: rgba(255, 255, 255, 0.7);
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
            padding: 20px;
        }

        .pagination a, .pagination span {
            padding: 8px 12px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 5px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-decoration: none;
            transition: all 0.3s;
        }

        .pagination a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .pagination .active {
            background: rgba(76, 175, 80, 0.7);
            border-color: rgba(76, 175, 80, 0.7);
        }

        .pagination .disabled {
            opacity: 0.5;
            cursor: not-allowed;
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
        <a href="{{ route('dashboard') }}" class="back-btn">← Back to Dashboard</a>

        <!-- Page Header -->
        <div class="page-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1>Item Master</h1>
                    <p>Manage all items in the inventory system</p>
                </div>
                <div style="background: linear-gradient(135deg, #ff6b35, #f7931e); padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; color: white; display: flex; align-items: center; gap: 8px;">
                    <span style="width: 8px; height: 8px; background: #4cff4c; border-radius: 50%; animation: pulse 2s infinite;"></span>
                    SAP HANA
                </div>
            </div>
        </div>
        <style>
            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.5; }
            }
        </style>

        <!-- Menu -->
        <div class="menu">
            <a href="{{ route('item.create') }}" style="text-decoration: none;">
                <button>+ CREATE</button>
            </a>
            <button onclick="document.getElementById('searchForm').scrollIntoView({behavior: 'smooth'})" class="secondary">SEARCH</button>
        </div>

        <!-- Success Message -->
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @endif

        <!-- Search Section -->
        <div class="search-container" id="searchForm">
            <form method="GET" action="{{ route('item.index') }}" style="display: flex; gap: 10px;">
                <input type="text" name="search" class="search-input" placeholder="Search by IMAS Code, Item Name, Model, or Brand...">
                <button type="submit" style="background: linear-gradient(135deg, rgba(76, 175, 80, 0.7), rgba(56, 142, 60, 0.7)); border: 1px solid rgba(255, 255, 255, 0.3); color: white; padding: 12px 30px; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s; backdrop-filter: blur(5px);">Search</button>
            </form>
        </div>

        <!-- Items Table -->
        <div class="table-container">
            @if ($items->count())
                <table>
                    <thead>
                        <tr>
                            <th>IMAS CODE</th>
                            <th>ITEM NAME</th>
                            <th>ITEM MODEL</th>
                            <th>BRAND</th>
                            <th>AMPERE</th>
                            <th>FULL WARRANTY</th>
                            <th>PRO-RATA WARRANTY</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td><strong>{{ $item->itmcode }}</strong></td>
                                <td>{{ $item->itmnme }}</td>
                                <td>{{ $item->itmmod ?? '-' }}</td>
                                <td>{{ $item->brand ?? '-' }}</td>
                                <td>{{ $item->itmamp ?? '-' }}</td>
                                <td>{{ $item->f_war ?? '-' }} months</td>
                                <td>{{ $item->pa_war ?? '-' }} months</td>
                                <td>
                                    <a href="{{ route('item.edit', $item->itmcode) }}" style="text-decoration: none;">
                                        <button class="action-btn edit-btn">EDIT</button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Pagination -->
                <div class="pagination">
                    {{ $items->onEachSide(1)->links('pagination.item-master') }}
                </div>
            @else
                <div class="empty-message">
                    <p>No items found. Create your first item to get started!</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>

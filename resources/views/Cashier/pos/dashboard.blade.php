<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Dashboard - Minimart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
        }
        
        .navbar {
            background: linear-gradient(135deg, #2563eb, #fbbf24);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }
        
        .navbar h1 {
            font-size: 24px;
        }
        
        .navbar .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logout-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .container {
            padding: 30px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .stat-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            background: linear-gradient(135deg, #2563eb, #fbbf24);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }
        
        .stat-info h3 {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .stat-info .stat-value {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
        }
        
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #fbbf24;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        
        .btn {
            background: linear-gradient(135deg, #2563eb, #fbbf24);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
        
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
        }
        
        .badge-cash {
            background: #10b981;
            color: white;
        }
        
        .badge-card {
            background: #3b82f6;
            color: white;
        }
        
        .badge-gcash {
            background: #8b5cf6;
            color: white;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1><i class="fas fa-store"></i> Minimart - Cashier Panel</h1>
        <div class="user-info">
            <span><i class="fas fa-user"></i> {{ Auth::user()->full_name ?? 'Cashier' }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>
    
    <div class="container">
        <div class="stats-grid">
            <!-- Today's Transactions -->
            <div class="card stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-info">
                    <h3>Today's Transactions</h3>
                    <div class="stat-value">{{ $todaySales }}</div>
                </div>
            </div>
            
            <!-- Today's Sales -->
            <div class="card stat-card">
                <div class="stat-icon">
                    <i class="fas fa-cash-register"></i>
                </div>
                <div class="stat-info">
                    <h3>Today's Sales</h3>
                    <div class="stat-value">₱{{ number_format($todayAmount, 2) }}</div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="card stat-card">
                <div class="stat-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="stat-info">
                    <h3>Quick Actions</h3>
                    <div>
                        <a href="{{ route('cashier.pos') }}" class="btn btn-sm">
                            <i class="fas fa-cash-register"></i> Open POS
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Transactions -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2><i class="fas fa-history"></i> Recent Transactions</h2>
                <a href="{{ route('cashier.sales') }}" class="btn btn-sm">View All</a>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Receipt #</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(is_countable($recentSales) ? count($recentSales) > 0 : !empty($recentSales))
@foreach($recentSales as $sale)
                        <tr>
                            <td><strong>{{ $sale->receipt_no }}</strong></td>
                            <td>{{ $sale->customer_name ?? 'Walk-in Customer' }}</td>
                            <td>₱{{ number_format($sale->total_amount, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $sale->payment_method }}">
                                    {{ ucfirst($sale->payment_method) }}
                                </span>
                            </td>
                            <td>{{ $sale->created_at->format('h:i A') }}</td>
                        </tr>
                        @endforeach
@else
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px;">No transactions today</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- POS Button -->
        <div class="card" style="background: linear-gradient(135deg, #2563eb, #fbbf24); color: white; margin-top: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="color: white;">Point of Sale</h2>
                    <p>Start a new transaction</p>
                </div>
                <a href="{{ route('cashier.pos') }}" style="background: white; color: #2563eb; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                    <i class="fas fa-arrow-right"></i> Open POS
                </a>
            </div>
        </div>
    </div>
</body>
</html>

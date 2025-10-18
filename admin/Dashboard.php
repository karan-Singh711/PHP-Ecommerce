<?php
session_start();
require_once('../dbConnection.php');
$user_id = $_SESSION['id'];
try{
    // total users and total orders by a user (only those product who added by this admin)
    $dashboardQuery = $pdo->prepare('SELECT COUNT(*) AS total_orders,
    COUNT(DISTINCT(o.user_id)) AS total_users,
    SUM(o.total) AS total_revenue
    
    FROM orders o 
    JOIN order_items i ON o.order_id = i.order_id
    JOIN products p ON i.product_id  = p.id 
    WHERE p.added_by = :added_by');
    $dashboardQuery->bindValue(':added_by',$user_id);
    $dashboardQuery->execute();
    $orders = $dashboardQuery->fetch();
    

    // query for products count which is added by this admin

    $productsQuery = $pdo->prepare('SELECT COUNT(*) AS total_products
    FROM products 
    WHERE added_by = :added_by');
    $productsQuery->bindValue(':added_by',$user_id);
    $productsQuery->execute();
    $products = $productsQuery->fetch();


    // query for monthky revenue chart
     
    
    
}catch(PDOException $e){
 die('errors:'. $e->getMessage());
}
   
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #98A1BC;
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .sidebar {
            width: 250px;
            background-color: #4A5578;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            transition: all 0.3s;
            z-index: 1001;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            font-size: 1.1rem;
        }
        .sidebar a:hover {
            background-color: #3F4A6E;
        }
        .sidebar.collapsed {
            width: 70px;
            overflow: hidden;
        }
        .sidebar.collapsed a span {
            display: none;
        }
        .header {
            background-color: #5D6B8A;
            color: white;
            padding: 10px 20px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        .main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 30px;
            min-height: calc(100vh - 60px);
            background-color: #f5f5f7;
            transition: margin-left 0.3s;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                margin-top: 60px;
            }
        }
        .toggle-btn {
            font-size: 1.5rem;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
        }

        /* Dashboard Specific Styles */
        .dashboard-header {
            margin-bottom: 30px;
        }
        .dashboard-header h2 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .dashboard-header p {
            color: #7f8c8d;
            margin: 0;
        }

        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }
        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .icon-blue { background-color: #3b82f6; color: white; }
        .icon-green { background-color: #10b981; color: white; }
        .icon-purple { background-color: #8b93a6; color: white; }
        .icon-orange { background-color: #f59e0b; color: white; }
        
        .stats-label {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .stats-value {
            color: #1f2937;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .stats-change {
            color: #10b981;
            font-size: 13px;
        }
        .stats-change i {
            font-style: normal;
            margin-right: 3px;
        }

        .chart-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .chart-title {
            color: #1f2937;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .chart-subtitle {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .bar-chart {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .bar-row {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .bar-label {
            color: #6b7280;
            font-size: 14px;
            width: 40px;
            text-align: left;
        }
        .bar-container {
            flex: 1;
            background: #e5e7eb;
            border-radius: 8px;
            height: 32px;
            position: relative;
            overflow: hidden;
        }
        .bar-fill {
            background: linear-gradient(90deg, #8b93a6 0%, #6b7690 100%);
            height: 100%;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 10px;
            color: white;
            font-size: 13px;
            font-weight: 500;
            transition: width 1s ease;
        }

        .activity-chart {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            height: 200px;
            gap: 15px;
            padding: 20px 0;
        }
        .activity-bar {
            flex: 1;
            background: linear-gradient(to top, #8b93a6 0%, #a8b0c5 100%);
            border-radius: 8px 8px 0 0;
            position: relative;
            transition: all 0.3s;
        }
        .activity-bar:hover {
            background: linear-gradient(to top, #6b7690 0%, #8b93a6 100%);
        }
        .activity-label {
            text-align: center;
            color: #6b7280;
            font-size: 13px;
            margin-top: 10px;
        }

        .activity-summary {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .avg-users-label {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .avg-users-value {
            color: #1f2937;
            font-size: 36px;
            font-weight: 700;
        }
        .avg-users-change {
            color: #3b82f6;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <button class="toggle-btn" id="sidebarToggle">&#9776;</button>
        <a href="Dashboard.html"><span>Dashboard</span></a>
        <a href="admin_addProduct.php"><span>Add Product</span></a>
    </div>

    <!-- Header -->
    <div class="header">
        <h3>Header</h3>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            
            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <h2>Dashboard Overview</h2>
                <p>Welcome back! Here's what's happening with your store.</p>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon icon-blue">🛒</div>
                        <div class="stats-label">Total Orders</div>
                        <div class="stats-value"><?php  echo $orders['total_orders'];  ?></div>
                        <div class="stats-change"><i>↗</i> +12.5%</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon icon-green">👥</div>
                        <div class="stats-label">Total Users</div>
                        <div class="stats-value"><?php  echo $orders['total_users'];  ?></div>
                        <div class="stats-change"><i>↗</i> +8.2%</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon icon-purple">📦</div>
                        <div class="stats-label">Total Products</div>
                        <div class="stats-value"><?php  echo $products['total_products'];  ?></div>
                        <div class="stats-change"><i>↗</i> +3.1%</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon icon-orange">💰</div>
                        <div class="stats-label">Revenue</div>
                        <div class="stats-value">$<?php  echo $orders['total_revenue'];  ?></div>
                        <div class="stats-change"><i>↗</i> +18.7%</div>
                    </div>
                </div>
            </div>
                <canvas id="revenueChart" width="400" height="200"></canvas>
            <!-- Charts Row -->
            <div class="row g-4">
                <!-- Sales Trends -->
                <div class="col-lg-7">
                    <div class="chart-card">
                        <h3 class="chart-title">Sales Trends</h3>
                        <p class="chart-subtitle">Monthly revenue overview</p>
                        <div class="bar-chart">
                            <div class="bar-row">
                                <div class="bar-label">Jan</div>
                                <div class="bar-container">
                                    <div class="bar-fill" style="width: 55%;">$65k</div>
                                </div>
                            </div>
                            <div class="bar-row">
                                <div class="bar-label">Feb</div>
                                <div class="bar-container">
                                    <div class="bar-fill" style="width: 65%;">$78k</div>
                                </div>
                            </div>
                            <div class="bar-row">
                                <div class="bar-label">Mar</div>
                                <div class="bar-container">
                                    <div class="bar-fill" style="width: 71%;">$85k</div>
                                </div>
                            </div>
                            <div class="bar-row">
                                <div class="bar-label">Apr</div>
                                <div class="bar-container">
                                    <div class="bar-fill" style="width: 60%;">$72k</div>
                                </div>
                            </div>
                            <div class="bar-row">
                                <div class="bar-label">May</div>
                                <div class="bar-container">
                                    <div class="bar-fill" style="width: 77%;">$92k</div>
                                </div>
                            </div>
                            <div class="bar-row">
                                <div class="bar-label">Jun</div>
                                <div class="bar-container">
                                    <div class="bar-fill" style="width: 74%;">$88k</div>
                                </div>
                            </div>
                            <div class="bar-row">
                                <div class="bar-label">Jul</div>
                                <div class="bar-container">
                                    <div class="bar-fill" style="width: 79%;">$95k</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Activity -->
                <div class="col-lg-5">
                    <div class="chart-card">
                        <h3 class="chart-title">User Activity</h3>
                        <p class="chart-subtitle">Daily active users</p>
                        <div class="activity-chart">
                            <div>
                                <div class="activity-bar" style="height: 45%;"></div>
                                <div class="activity-label">Mon</div>
                            </div>
                            <div>
                                <div class="activity-bar" style="height: 58%;"></div>
                                <div class="activity-label">Tue</div>
                            </div>
                            <div>
                                <div class="activity-bar" style="height: 52%;"></div>
                                <div class="activity-label">Wed</div>
                            </div>
                            <div>
                                <div class="activity-bar" style="height: 72%;"></div>
                                <div class="activity-label">Thu</div>
                            </div>
                            <div>
                                <div class="activity-bar" style="height: 85%;"></div>
                                <div class="activity-label">Fri</div>
                            </div>
                            <div>
                                <div class="activity-bar" style="height: 62%;"></div>
                                <div class="activity-label">Sat</div>
                            </div>
                            <div>
                                <div class="activity-bar" style="height: 55%;"></div>
                                <div class="activity-label">Sun</div>
                            </div>
                        </div>
                        <div class="activity-summary">
                            <div class="avg-users-label">Avg. Daily Users</div>
                            <div class="avg-users-value">640</div>
                            <div class="avg-users-change">+12.3% vs last week</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function (){
            console.log('hello')
            $.ajax({
                url:'../api.php',
                method:'POST',
                data:{action:"totalRevenue"},
                dataType:'json',  
                success:function(response){
                    console.log(response.label)
                    console.log(response.data)
                    let label = response.label
                    let data = response.data
                     const ctx = document.getElementById('revenueChart').getContext('2d');
                        const gradient = ctx.createLinearGradient(0, 0, 600, 0); 
                        
                            gradient.addColorStop(0,'rgba(139,147,166,1)')
                    
                            gradient.addColorStop(1,'rgba(107,118,144,1)')
                                const revenueChart = new Chart(ctx, {
                                type: 'bar', // can be 'bar', 'line', 'doughnut', etc.
                                data: {
                                    labels: label, // X-axis
                                    datasets: [{
                                        label: 'Monthly Revenue',
                                        data: data, // Y-axis values
                                        backgroundColor: gradient, // fill under line (for area effect)
                                        borderColor: 'rgba(54, 162, 235, 1)',       // line color
                                        borderWidth: 1,
                                        borderRadius:10,
                                        minBarLength: 10,
                                    }]
                                },
                                options: {
                                    indexAxis: 'y',
                                    responsive: true,
                                    plugins: {
                                        legend: {
                                            display: true,
                                            position: 'top'
                                        },
                                        tooltip: {
                                            mode: 'index',
                                            intersect: false
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                },
                error :function(xhr,status,error){
                    console.log(error)
                }

            })
        })
      
                
                document.getElementById('sidebarToggle').addEventListener('click', function() {
                    const sidebar = document.getElementById('sidebar');
                    sidebar.classList.toggle('active');
                });
                
                // Animate bars on load
                window.addEventListener('load', function() {
                    const bars = document.querySelectorAll('.bar-fill');
                    bars.forEach((bar, index) => {
                        const width = bar.style.width;
                        bar.style.width = '0%';
                        setTimeout(() => {
                            bar.style.width = width;
                        }, 100 * index);
                    });
                });
    </script>
</body>
</html>
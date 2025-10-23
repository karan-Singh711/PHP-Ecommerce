<?php
session_start();
require_once('../dbConnection.php');

try{
    $user_id = $_SESSION['id'];
    $query = $pdo->prepare('SELECT o.order_id ,COUNT(i.product_id) AS product_id, o.first_name,o.last_name,o.status,MAX(p.price_of_product) AS price_of_product,o.created_at,SUM(i.quantity) AS quantity,o.user_id 
    
    FROM products p 
    JOIN order_items i ON p.id = i.product_id
    JOIN orders o ON i.order_id = o.order_id 
    WHERE p.added_by = :user_id GROUP BY (o.order_id)' ); 
    $query->bindValue(':user_id',$user_id);
    $query->execute();

    $countQuery = $pdo->prepare('SELECT COUNT(*) AS total_rows ,
    SUM(o.status = "Pending") AS pending,
    SUM(o.status = "Processing") AS proccessing,
    SUM(o.status = "Completed") AS complete
    FROM orders o
    JOIN order_items i ON i.order_id = o.order_id 
    JOIN products p ON i.product_id = p.id 
    WHERE p.added_by = :user_id');
    $countQuery->bindValue(':user_id',$user_id);
    $countQuery->execute();
    $numOfrows =$countQuery->fetch();
    $num =$numOfrows['total_rows'];
    $pending=$numOfrows['pending'];
    $proccessing=$numOfrows['proccessing'];
    $complete=$numOfrows['complete'];
    echo $complete;
  }catch(PDOException $e){
      die('error' . $e->getMessage());
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Order Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
      padding: 15px 20px;
      display: block;
      font-size: 1rem;
      transition: all 0.3s;
      border-left: 3px solid transparent;
    }
    .sidebar a:hover, .sidebar a.active {
      background-color: #3F4A6E;
      border-left-color: #98A1BC;
    }
    .sidebar.collapsed {
      width: 70px;
    }
    .sidebar.collapsed a span {
      display: none;
    }
    .header {
      background-color: #5D6B8A;
      color: white;
      padding: 15px 20px;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .main-content {
      margin-left: 250px;
      margin-top: 60px;
      padding: 30px;
      min-height: calc(100vh - 60px);
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
      }
    }
    .toggle-btn {
      font-size: 1.5rem;
      background: none;
      border: none;
      color: white;
      cursor: pointer;
      padding: 10px 20px;
    }
    .stats-card {
      background: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      transition: transform 0.3s;
    }
    .stats-card:hover {
      transform: translateY(-5px);
    }
    .filter-btn {
      border: 1px solid #dee2e6;
      background: white;
      color: #555879;
      padding: 8px 20px;
      border-radius: 6px;
      transition: all 0.3s;
    }
    .filter-btn.active {
      background-color: #555879;
      color: white;
      border-color: #555879;
    }
    .filter-btn:hover:not(.active) {
      background-color: #f8f9fa;
      border-color: #555879;
    }
    .badge-pending { background-color: #ffc107; }
    .badge-processing { background-color: #0dcaf0; }
    .badge-completed { background-color: #198754; }
    .badge-cancelled { background-color: #dc3545; }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <button class="toggle-btn" id="sidebarToggle">☰</button>
    <a href="Dashboard.html"><span>📊 Dashboard</span></a>
    <a href="admin_addProduct.php"><span>➕ Add Product</span></a>
    <a href="#" class="active"><span>📦 Order Management</span></a>
  </div>

  <!-- Header -->
  <div class="header d-flex align-items-center justify-content-between">
    <h4 class="mb-0">Order Management</h4>
    <div class="text-end">
      <small class="opacity-75">Admin Panel</small>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container-fluid">
        <?php echo $complete; ?>
      <!-- Stats Cards -->
      <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
          <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-muted mb-1 small">Total Orders</p>
                <h2 class="mb-0 fw-bold" style="color: #4A5578;"><?php  echo $num; ?></h2>
              </div>
              <div class="bg-light rounded-circle p-3">
                <span class="fs-3">📋</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-muted mb-1 small">Pending Orders</p>
                <h2 class="mb-0 fw-bold text-warning"><?php  echo $pending; ?></h2>
              </div>
              <div class="bg-light rounded-circle p-3">
                <span class="fs-3">⏳</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-muted mb-1 small">Processing</p>
                <h2 class="mb-0 fw-bold text-info"><?php  echo $proccessing; ?></h2>
              </div>
              <div class="bg-light rounded-circle p-3">
                <span class="fs-3">🔄</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-muted mb-1 small">Completed</p>
                <h2 class="mb-0 fw-bold text-success"><?php  echo $complete; ?></h2>
              </div>
              <div class="bg-light rounded-circle p-3">
                <span class="fs-3">✅</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters Card -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
          <div class="d-flex flex-wrap align-items-center gap-2">
            <span class="fw-semibold me-2" style="color: #4A5578;">Filter by status:</span>
            <button class="btn btn-sm filter-btn active" data-status="all">All Orders</button>
            <button class="btn btn-sm filter-btn" data-status="Pending">Pending</button>
            <button class="btn btn-sm filter-btn" data-status="Processing">Processing</button>
            <button class="btn btn-sm filter-btn" data-status="Completed">Completed</button>
            <button class="btn btn-sm filter-btn" data-status="Cancelled">Cancelled</button>
          </div>
        </div>
      </div>

      <!-- Orders Table -->
      <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0" id="ordersTable">
              <thead style="background-color: #f8f9fa;">
                <tr>
                  <th class="py-3 px-4 fw-semibold">Order ID</th>
                  <th class="py-3 px-4 fw-semibold">Customer</th>
                  <th class="py-3 px-4 fw-semibold">Status</th>
                  <th class="py-3 px-4 fw-semibold">Product ID</th>
                  <th class="py-3 px-4 fw-semibold">Total</th>
                  <th class="py-3 px-4 fw-semibold">Date</th>
                  <th class="py-3 px-4 fw-semibold text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
               
                while($row = $query->fetch()){
                  $statusClass = strtolower($row['status']);
                  $date = $row['created_at'];
                  $formattedDate = date('M j , Y',strtotime($date));
                  echo'<tr data-status="Pending">
                  <td class="py-3 px-4 fw-medium">#'. $row['order_id'] .'</td>
                  <td class="py-3 px-4">
                    <div class="d-flex align-items-center">
                      <div class="bg-light rounded-circle p-2 me-2">👤</div>
                      <span class = "name">'. $row['first_name'] . " " . $row['last_name'] .'</span>
                    </div>
                  </td>
                  <td class="py-3 px-4"><span class="badge badge-'.$statusClass.' rounded-pill status">'. $row['status'] .'</span></td>
                  <td class="py-3 px-4 fw-semibold">'. $row['product_id'] .'</td>
                  <td class="py-3 px-4 fw-semibold price">$'. $row['price_of_product'] .'</td>
                  <td class="py-3 px-4 text-muted date">'.$formattedDate.'</td>
                  <td class="py-3 px-4 text-center">
                    <button class="btn btn-sm btn-outline-primary details" data-bs-toggle="modal" data-bs-target="#orderDetailModal" data-order-id = '. $row['order_id'] .' 
                    data-user-id ='. $row['user_id'] .' >View Details</button>
                  </td>
                </tr>';
                }
                
                ?>  
              <tr data-status="Pending">
                  <td class="py-3 px-4 fw-medium">#001</td>
                  <td class="py-3 px-4">
                    <div class="d-flex align-items-center">
                      <div class="bg-light rounded-circle p-2 me-2">👤</div>
                      <span>John Doe</span>
                    </div>
                  </td>
                  <td class="py-3 px-4"><span class="badge badge-pending rounded-pill">Pending</span></td>
                  <td class="py-3 px-4 fw-semibold">1</td>
                  <td class="py-3 px-4 fw-semibold">$120.00</td>
                  <td class="py-3 px-4 text-muted">Oct 3, 2025</td>
                  <td class="py-3 px-4 text-center">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#orderDetailModal">View Details</button>
                  </td>
                </tr>
                <tr data-status="Processing">
                  <td class="py-3 px-4 fw-medium">#002</td>
                  <td class="py-3 px-4">
                    <div class="d-flex align-items-center">
                      <div class="bg-light rounded-circle p-2 me-2">👤</div>
                      <span>Jane Smith</span>
                    </div>
                  </td>
                  <td class="py-3 px-4"><span class="badge badge-processing rounded-pill">Processing</span></td>
                  <td class="py-3 px-4 fw-semibold">1</td>
                  <td class="py-3 px-4 fw-semibold">$80.00</td>
                  <td class="py-3 px-4 text-muted">Oct 2, 2025</td>
                  <td class="py-3 px-4 text-center">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#orderDetailModal">View Details</button>
                  </td>
                </tr>
                <tr data-status="Completed">
                  <td class="py-3 px-4 fw-medium">#003</td>
                  <td class="py-3 px-4">
                    <div class="d-flex align-items-center">
                      <div class="bg-light rounded-circle p-2 me-2">👤</div>
                      <span>Mike Johnson</span>
                    </div>
                  </td>
                  <td class="py-3 px-4"><span class="badge badge-completed rounded-pill">Completed</span></td>
                  <td class="py-3 px-4 fw-semibold">1</td>
                  <td class="py-3 px-4 fw-semibold">$150.00</td>
                  <td class="py-3 px-4 text-muted">Oct 1, 2025</td>
                  <td class="py-3 px-4 text-center">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#orderDetailModal">View Details</button>
                  </td>
                </tr>
                <tr data-status="Cancelled">
                  <td class="py-3 px-4 fw-medium">#004</td>
                  <td class="py-3 px-4">
                    <div class="d-flex align-items-center">
                      <div class="bg-light rounded-circle p-2 me-2">👤</div>
                      <span>Anna Lee</span>
                    </div>
                  </td>
                  <td class="py-3 px-4"><span class="badge badge-cancelled rounded-pill">Cancelled</span></td>
                  <td class="py-3 px-4 fw-semibold">1</td>
                  <td class="py-3 px-4 fw-semibold">$60.00</td>
                  <td class="py-3 px-4 text-muted">Sep 30, 2025</td>
                  <td class="py-3 px-4 text-center">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#orderDetailModal">View Details</button>
                  </td>
                </tr>
                <tr data-status="Pending">
                  <td class="py-3 px-4 fw-medium">#005</td>
                  <td class="py-3 px-4">
                    <div class="d-flex align-items-center">
                      <div class="bg-light rounded-circle p-2 me-2">👤</div>
                      <span>Robert Brown</span>
                    </div>
                  </td>
                  <td class="py-3 px-4"><span class="badge badge-pending rounded-pill">Pending</span></td>
                  <td class="py-3 px-4 fw-semibold">1</td>  
                  <td class="py-3 px-4 fw-semibold">$95.50</td>
                  <td class="py-3 px-4 text-muted">Oct 3, 2025</td>
                  <td class="py-3 px-4 text-center">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#orderDetailModal">View Details</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer bg-white border-top">
          <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">Showing 5 of 120 orders</small>
            <nav>
              <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
              </ul>
            </nav>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Order Detail Modal -->
  <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="background:#555879; color:white;">
          <h5 class="modal-title" id="orderDetailModalLabel">Order Details - #001</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-4">
            <div class="col-md-6">
              <h6 class="text-muted mb-2">Customer Information</h6>
              <p class="mb-1" id = "userName"><strong>Name:</strong> John Doe</p>
              <p class="mb-1" id = "userEmail"><strong>Email:</strong> john.doe@example.com</p>
              <p class="mb-1" id = "userPhone"><strong>Phone:</strong> +1 234 567 8900</p>
            </div>
            <div class="col-md-6">
              <h6 class="text-muted mb-2">Order Information</h6>
              <p class="mb-1" id = "orderDate"><strong>Order Date:</strong> Oct 3, 2025</p>
              <p class="mb-1" id = "orderStatus"><strong>Status:</strong> <span class="badge badge-pending status">Pending</span></p>
              <p class="mb-1" id = "orderPayment"><strong>Payment:</strong> Credit Card</p>
            </div>
          </div>
          
          <h6 class="text-muted mb-3">Order Items</h6>
          <div class="table-responsive">
            <table class="table table-sm">
              <thead class="table-light">
                <tr>
                  <th>Product</th>
                  <th>Quantity</th>
                  <th>Price</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody id = "modalTable">
                <tr>
                  <td>Product Name 1</td>
                  <td>2</td>
                  <td>$40.00</td>
                  <td>$80.00</td>
                </tr>
                <tr>
                  <td>Product Name 2</td>
                  <td>1</td>
                  <td>$40.00</td>
                  <td>$40.00</td>
                </tr>
              </tbody>
              <tfoot class="table-light">
                <tr>
                  <td colspan="3" class="text-end fw-semibold">Total:</td>
                  <td class="fw-bold">$120.00</td>
                </tr>
              </tfoot>
            </table>
          </div>

          <h6 class="text-muted mb-2 mt-4">Update Order Status</h6>
          <select class="form-select" id = "status">
            <option selected value="Pending">Pending</option>
            <option value="Processing">Processing</option>
            <option>Completed</option>
            <option>Cancelled</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn" style="background:#555879; color:white;" id = "updateStatus">Update Status</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    $(document).ready(function(){
     
      let detailBtn= $('.details')
       detailBtn.on('click',function(){
        console.log("hello")
        let orderId = $(this).data('order-id')
        $("#updateStatus").data('order-id',orderId)
        let userId = $(this).data('user-id')
        

        
        console.log(userId)
        $.ajax({
          url:'../api.php',
          method:'POST',
          data:{order_id:orderId,user_id:userId,action:'orderDetails'},
          dataType:'json',
          success:function(response){
            console.log(response);
            $("#modalTable").html("")
            console.log(response.data[0])
            let data = response.data
            
            data.forEach(i => {
              let total = i.quantity*i.price_of_product
               let row =`<tr>
                          <td>${i.name_of_product}</td>
                          <td>${i.quantity}</td>
                          <td>$${i.price_of_product}</td>
                          <td>$${total}</td>
                        </tr>`
                $("#modalTable").append(row)
            })
            
            let userName = response.data[0].first_name + " " + response.data[0].last_name
            let userEmail = response.data[0].email
            let userPhone = response.data[0].user_phone
            let orderDate = response.data[0].created_at
            let orderPayment = response.data[0].payment_method
            let orderPrice = response.data[0].product_price       
            let orderStatus = response.data[0].status           
            
            let dateobj = new Date(orderDate)
            let option = {day:"numeric",year:"numeric",month:"short"}
            let formattedDate = dateobj.toLocaleDateString('en-US',option)
            $('#userName').html("<strong>Name:</strong>"+userName);
            $('#userEmail').html("<strong>Email:</strong>"+userEmail);
            $('#userPhone').html("<strong>Phone:</strong>"+userPhone);
            $('#orderDate').html("<strong>Order Date:</strong>"+formattedDate);
            $('#orderPayment').html("<strong>Payment:</strong>"+orderPayment);
            $('#orderStatus .status').text(orderStatus);
            
          },
          error:function(xhr,status,error){
            console.log(error)
          }
        })
       })
       let updateBtn = $("#updateStatus")
        updateBtn.on('click',()=>{  
          let orderId = $("#updateStatus").data('order-id');
          console.log(orderId)
          let status = $("#status").val()
            
          $.ajax({
            url:"../api.php",
            method:"POST",
            data:{order_id:orderId,status:status,action:"updateStatus"},
            dataType:"json",
            success:function(response){
              console.log(response)
              console.log(response.orderId);
              let updateStatus = response.status
              let row = $(".details[data-order-id ="+response.orderId+"]")
              let statusCell = row.closest('tr').find('.status')
              statusCell.removeClass('badge-pending badge-processing badge-completed badge-cancelled ')
              statusCell.addClass('badge-'+response.status.toLowerCase())
              row.closest('tr').find('.status').text(response.status)
              
              $("#orderDetailModal").modal('hide')

            },
            error:function(xhr,status,error){
              console.log(error)
            }

          })
      })
    })
    // Sidebar toggle
    $('#sidebarToggle').click(function() {
      $('#sidebar').toggleClass('collapsed');
      if($(window).width() <= 768) {
        $('#sidebar').toggleClass('active');
      }
    });

    // Filter buttons
    $('.filter-btn').click(function() {
      var status = $(this).data('status');
      $('.filter-btn').removeClass('active');
      $(this).addClass('active');

      $('#ordersTable tbody tr').each(function() {
        var rowStatus = $(this).data('status');
        if (status === 'all' || rowStatus === status) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    });

    // Mobile sidebar close on outside click
    $(document).click(function(event) {
      if($(window).width() <= 768) {
        if(!$(event.target).closest('#sidebar, #sidebarToggle').length) {
          $('#sidebar').removeClass('active');
        }
      }
    });
  </script>

</body>
</html>
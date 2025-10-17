<?php
session_start();
require_once("dbConnection.php");
$user_id = $_SESSION['id'];
try{
    $orderHquery = $pdo->prepare('SELECT p.name_of_product,p.price_of_product,p.image,i.product_id , i.quantity , o.status , o.created_at 
    FROM orders o 
    JOIN order_items i ON o.order_id = i.order_id 
    JOIN products p ON i.product_id = p.id
    WHERE o.user_id = :user_id');
    $orderHquery->bindValue(':user_id',$user_id);
    $orderHquery->execute();
    echo "success";
   

    

}catch(PDOException $e){
    echo "error";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order History</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body style="background-color:#2e3038; color:white;">

  <main class="container my-5">
    <h2 class="mb-4 text-center">Order History</h2>

    <!-- Order Card -->
    

    <!-- Another Order -->
    <div class="card mb-4 rounded" style="background-color:#555879; color:white;">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Order #12346</h5>
          <span class="badge bg-warning text-dark">Pending</span>
        </div>
        <p class="mb-2"><small>Placed on: 28 Sep 2025</small></p>
            <?php
            while($row = $orderHquery->fetch()){
                echo '<div class="row g-3 align-items-center mb-3">
                        <div class="col-md-2 col-4">
                            <img src="'. $row['image'] . '" class="img-fluid rounded" alt="Product Image">
                        </div>
                        <div class="col-md-6 col-8">
                            <p class="mb-1 fw-bold">'. $row['name_of_product'] .'</p>
                            <p class="mb-0">Quantity:' . $row['quantity'] . '</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <p class="mb-0 price">$' . $row['price_of_product'] * $row['quantity'] . '</p>
                         </div>
                    </div>';
            }
            ?>
        

        <hr class="border-light">
        <div class="d-flex justify-content-between">
          <strong>Total:</strong>
          <strong id = "total">$49.00</strong>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>

    $(document).ready(function(){
       let price = $('.price')
       let totalPrice = 0
       price.each(function(){
        let totalT = $(this).text().replace(/[^0-9.]/g,"")
        
        
        totalPrice += parseFloat(totalT)
        console.log(totalPrice)
       })
       $('#total').text("$"+totalPrice)
    })

  </script>
</body>
</html>

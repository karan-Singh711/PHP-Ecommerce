<?php
session_start();
require_once("dbConnection.php");
$user_id = $_SESSION['id'];
  try{
    $productQuery = $pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id");
    $productQuery->bindValue(":user_id", $user_id);
    $productQuery->execute();
    echo "success";
    $query = $pdo->prepare("SELECT p.name_of_product,p.price_of_product,c.quantity 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = :user_id");


    $query->bindValue(":user_id",$user_id);
    $query->execute();
   
  }
  catch(PDOException $e){
    die("error" . $e->getMessage());
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body style="background-color:#aab6d1;">

<main class="container my-5">
  <div class="row g-4">

    <!-- Contact Information -->
    <div class="col-md-6">
    <div class="p-4 rounded" style="background-color:#555879; color:white;">
        <h5 class="mb-3"><i class="bi bi-person-fill"></i> Contact Information</h5>
        <div class="mb-3">
        <label>Email Address</label>
        <input type="email" class="form-control" placeholder="you@example.com" id="email">
        </div>
        <div class="mb-3">
        <label>Phone Number</label>
        <input type="tel" class="form-control" placeholder="+91 9876543210" id="phone">
        </div>
        <div class="row">
        <div class="col-md-6 mb-3">
            <label>First Name</label>
            <input type="text" class="form-control" placeholder="John" id="Fname">
        </div>
        <div class="col-md-6 mb-3">
            <label>Last Name</label>
            <input type="text" class="form-control" placeholder="Doe" id="Lname">
        </div>
        </div>
    </div>
    </div>


    <!-- Order Summary -->
    <div class="col-md-6">
      <div class="p-4 rounded" style="background-color:#555879; color:white;">
        <h5 class="mb-3"><i class="bi bi-bag-check-fill"></i> Order Summary</h5>
          <?php
          while($row = $query->fetch()){
            echo "<div class='d-flex justify-content-between mb-2'>
                    <span>" . $row['name_of_product'] . "</span>
                    <span class = 'price_of_product'>$".$row['price_of_product']*$row['quantity']."</span>
                  </div>";
          }
          ?>
       
        

        <hr style="border-color:rgba(255,255,255,0.3);">

        <!-- Totals -->
        <div class="d-flex justify-content-between">
          <span>Subtotal</span>
          <span id = "subtotal"></span>
        </div>
        <div class="d-flex justify-content-between">
          <span>Shipping</span>
          <span id = "shipping">$9.99</span>
        </div>
        <div class="d-flex justify-content-between">
          <span>Tax</span>
          <span id = "tax">$63.20</span>
        </div>
        <div class="d-flex justify-content-between fw-bold mt-2">
          <span>Total</span>
          <span id = "total"></span>
        </div>
      </div>
    </div>

    <!-- Shipping Address -->
    <div class="col-md-6">
      <div class="p-4 rounded" style="background-color:#555879; color:white;">
        <h5 class="mb-3"><i class="bi bi-geo-alt-fill"></i> Shipping Address</h5>
        <div class="mb-3">
          <label>Street Address</label>
          <input type="text" class="form-control" placeholder="123 Main Street" id = "Saddress">
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label>City</label>
            <input type="text" class="form-control" placeholder="New York" id = "city">
          </div>
          <div class="col-md-6 mb-3">
            <label>Postal Code</label>
            <input type="text" class="form-control" placeholder="10001" id = "Pcode">
          </div>
        </div>
      </div>
    </div>

    <!-- Payment Option: COD -->
    <div class="col-md-6">
      <div class="p-4 rounded" style="background-color:#555879; color:white;">
        <h5 class="mb-3"><i class="bi bi-cash-coin"></i> Payment Method</h5>
        <div class="form-check mb-3">
          <input class="form-check-input" type="radio" name="payment" id="cod" checked>
          <label class="form-check-label" for="cod">
            Cash on Delivery (COD)
          </label>
        </div>
        <button class="btn btn-success w-100" id = "placeOrder" >Place Order</button>
      </div>
    </div>

  </div>
</main>
<script>

    $(document).ready(function(){
        let priceProduct = $(".price_of_product")
        let subtotal = 0 
        priceProduct.each(function(){
          let price = parseFloat($(this).text().replace(/[^0-9.]/g,""))
          
          subtotal += price
          console.log(subtotal)
        })
         $("#subtotal").text("$"+subtotal)

        let taxText = $("#tax").text()
        let tax =parseFloat(taxText.replace(/[^0-9.]/g,"")) 
        let shipping = parseFloat($("#shipping").text().replace(/[^0-9.]/g,""))
        let total = shipping + tax + subtotal
          $("#total").text("$" + total.toFixed(2))
        

        
        let placeOrder = $("#placeOrder")
        placeOrder.on("click" , function(){
            let email = $("#email").val()
            let Fname = $("#Fname").val()
            let Lname = $("#Lname").val()
            let Saddress = $("#Saddress").val()
            let city = $("#city").val()
            let Pcode = $("#Pcode").val()
            let phone = $("#phone").val()
            let total = $("#total").text()

            $.ajax({
                url:"api.php",
                method:"POST",
                data:{action:"insert",Fname:Fname,Lname:Lname,email:email,Saddress:Saddress,city:city,Pcode:Pcode,phone:phone,total:total}, 
                dataType:'json',
                success:function(response){
                    console.log(response)
                  window.location.href = response.url
                },
                error:function(xhr,status,error){
                    console.log(error)
                },
            })
            // $.ajax({
            //   url:"api.php",
            //   method:"POST",
            //   data:{action:"createPayment",total:total}, 
            //   dataType:'json',
            //   success:function(response){
            //       console.log(response.url)
            //       console.log(response)
            //       window.location.href = response.url
            //   },
            //   error:function(xhr,status,error){
            //       console.log(error)
            //   },
            // })
        })
    })
</script>
</body>
</html>

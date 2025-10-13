<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shopping Cart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <style>
    body {
      background-color: #aab6d1; /* same light background */
    }
    .cart-box {
      background-color: #555879; /* same dark block */
      border-radius: 10px;
      padding: 20px;
      color: white;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    table img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 8px;
    }
  </style>
</head>
<body>

<main class="container my-5">
  <div class="cart-box">
    <h2 class="mb-4 text-center">Your Cart</h2>
    
    <!-- Cart Table -->
    <div class="table-responsive">
      <table class="table table-dark table-striped align-middle mb-0">
        <thead>
          <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php 
    session_start();
    require_once "dbConnection.php";
    $user_id = $_SESSION['id'];
    try{
      $query = $pdo->prepare("SELECT c.product_id,c.quantity,p.name_of_product,p.price_of_product,p.image,p.description_of_product FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = :user_id ");
      $query->bindValue(':user_id',$user_id);

      $query->execute();

      
      

      while($row = $query->fetch()){
        
        echo 
        
        '<tr>
            <td><img src="'. $row['image'] . '" alt="Product 1"></td>
            <td>'. $row['name_of_product'] . '</td>
            <td class = "price">'. $row['price_of_product'] . '</td>
            <td><input type="number" data-id ="' . $row['product_id'] . '"class = "Cquantity" value="'. $row['quantity'] . '" min="1" class="form-control w-50"></td>
            <td class ="total">'. $row['price_of_product']*$row['quantity']. '</td>
            <td><button class="btn btn-sm btn-danger remove" data-id ="' . $row['product_id'] . '">Remove</button></td>
          </tr>';
      }
    }catch(PDOException $e){
      die("errors".$e->getMessage());
    }
    ?>
        </tbody>
      </table>
    </div>

    <!-- Cart Summary -->
    <div class="d-flex justify-content-between align-items-center mt-4">
      <h4 id="totalValue">Total:</h4>
      <div>
        <button class="btn btn-light">Continue Shopping</button>
        <button class="btn btn-success">Checkout</button>
      </div>
    </div>
  </div>
</main>

<script>

    $("document").ready(()=>{
      console.log("hello")
      
      let Cquantity = $(".Cquantity")
      let remove = $(".remove")

      remove.on("click",function(){
        let button = $(this)
        let productId = $(this).data("id")
        console.log(productId)

        $.ajax({
          url:"api.php",
          method:"POST",
          data:{action:"remove",productId:productId},
          success:function(response){
            console.log(response)
            button.closest("tr").remove();
          },
          error:function(xhr,status,error){
            console.log(error)
          },

        })
      })
      // total 
      let total = $(".total")
      let TotalValue = 0
      total.each(function(){
         TotalValue += parseFloat($(this).text())
      })
      console.log(TotalValue)
      let totalValue = $("#totalValue")
      totalValue.text("Total:"+"$"+TotalValue)


      Cquantity.on("change",function(){
        let productId = $(this).data("id")
        let newQuantity = $(this).val()
        console.log(productId)
        // update quantity
        $.ajax({
          url:"api.php",
          method:"POST",
          data:{product_id:productId,Newquantity:newQuantity},
          success:function(response){
            console.log(response);
          }, 
          error:function(xhr,status,error){
            console.log(error)
          },
        })
        
        let price = $(this).closest("tr").find(".price").text()
        console.log(price)

        subTotal = price * newQuantity

        let subTotalcell = $(this).closest("tr").find(".total").text(subTotal)
        console.log(total)
        TotalValue = 0
        total.each(function(){
          TotalValue += parseFloat($(this).text())
        })
        console.log(TotalValue)
      })
      
      
    })
</script>
</body>
</html>

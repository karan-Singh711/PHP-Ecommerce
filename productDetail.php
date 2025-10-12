<?php
require_once 'dbConnection.php';

if (isset($_GET['id'])){
  $id=$_GET['id'];
  $query = $pdo->prepare('SELECT * FROM products WHERE id = :id');
  $query->bindValue(':id', $id);
  $query->execute();
// fetch data 
  $product = $query->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Product Detail Page</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body style="background-color:#98A1BC;">

  <!-- Header -->
  <header class="p-3 mb-4" style="background-color:#555879;">
    <div class="container">
      <h1 class="h3 mb-0 text-white">MyShop</h1>
    </div>
  </header>

  <main class="container mb-5">
    <div class="row g-4">
      <!-- Product Image -->
      <div class="col-md-6">
        <div class="p-3 rounded" style="background-color:#555879;display:flex;align-items: center;justify-content: center;">
          <img src="<?php echo htmlspecialchars($product['image']);?>" class="img-fluid rounded" alt="Product Image" style = "height:300px;">
        </div>
      </div>

      <!-- Product Details -->
      <div class="col-md-6">
        <div class="p-3 rounded text-white" style="background-color:#555879;">
          <h2 class="h4"><?php echo htmlspecialchars($product['name_of_product']);?></h2>
          <p class="fs-5 mb-2">$<?php echo htmlspecialchars($product['price_of_product']);?></p>
          <p class="mb-3"><?php echo htmlspecialchars($product['description_of_product']);?></p>

          <!-- Quantity Selector -->
          <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" id="quantity" class="form-control w-25" value="1" min="1">
          </div>

          <!-- Buttons -->
          <div class="d-flex gap-2 mb-2">
            <button class="btn btn-light" id = "addToCart" data-id ="<?php echo $_GET['id'];?>">Add to Cart</button>
            <button class="btn btn-success">Buy Now</button>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Product Specifications -->
  <section class="container mb-5">
    <h3 class="h5 mb-3 text-white">Product Specifications</h3>
    <div class="card border-0 text-white" style="background-color:#555879;">
      <div class="card-body">
        <ul class="mb-0">
          <li>Material: Premium Quality</li>
          <li>Dimensions: 10 x 6 x 4 inches</li>
          <li>Weight: 1.2 kg</li>
          <li>Color: Black</li>
          <li>Warranty: 1 Year</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- Related Products -->
  <section class="container mb-5">
    <h3 class="h5 mb-3 text-white">Related Products</h3>
    <div class="row g-3">
      <div class="col-md-4">
        <div class="card h-100 text-center text-white border-0" style="background-color:#555879;">
          <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Related Product 1">
          <div class="card-body">
            <h5 class="card-title">Product One</h5>
            <p>$49.99</p>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100 text-center text-white border-0" style="background-color:#555879;">
          <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Related Product 2">
          <div class="card-body">
            <h5 class="card-title">Product Two</h5>
            <p>$59.99</p>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100 text-center text-white border-0" style="background-color:#555879;">
          <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Related Product 3">
          <div class="card-body">
            <h5 class="card-title">Product Three</h5>
            <p>$79.99</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="text-center py-3" style="background-color:#555879;">
    <small class="text-white">&copy; 2025 MyShop. All rights reserved.</small>
  </footer>
  <script>

    $(document).ready(()=>{
      $("#addToCart").click(()=>{
        let productId = $("#addToCart").data('id')
        let quantity = $('#quantity').val()
        $.ajax({
          url:"api.php",
          method:'POST',
          data:{quantity:quantity,productId:productId},
          success: function(response){
            console.log(response);
          },
          error:function(xhr , status , error  ){
            console.log("error:",error)
          },
        })
      })
    })
      
    
  </script>
</body>
</html>

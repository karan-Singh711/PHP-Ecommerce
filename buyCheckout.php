<?php
session_start();
require_once 'dbConnection.php';
if(isset($_GET['id']) && isset($_GET['quantity']) && !empty($_GET['quantity']) && !empty($_GET['id'])){
    $product_id = intval($_GET['id']);
    $quantity = intval($_GET['quantity']);
    try{
        $productSelectQuery = $pdo->prepare('SELECT * FROM products WHERE id = :product_id');
        $productSelectQuery->bindValue(':product_id',$product_id,PDO::PARAM_INT);
        $productSelectQuery->execute();
        $row = $productSelectQuery->fetch();
    }
    catch(PDOException $e){
        die("error".$e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Checkout Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body class="bg-white min-h-screen flex items-center justify-center">
  <div class="w-full max-w-4xl bg-white shadow-md rounded-2xl p-8 flex flex-col md:flex-row gap-8">
    
    <!-- Left: Form Section -->
    <div class="w-full md:w-1/2">
      <h2 class="text-2xl font-semibold text-[#555879] mb-6">Shipping Information</h2>
      
      <form class="space-y-5">
        <div>
          <label class="block text-[#555879] text-sm mb-2">First Name</label>
          <input type="text" placeholder="John" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#98A1BC]" id = 'Fname' />
        </div>
        <div>
          <label class="block text-[#555879] text-sm mb-2">Last Name</label>
          <input type="text" placeholder="Doe" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#98A1BC]"id = 'Lname' />
        </div>
        <div>
          <label class="block text-[#555879] text-sm mb-2">Phone Number</label>
          <input type="tel" placeholder="+91 9876543210" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#98A1BC]" id = 'phone'/>
        </div>

        <div>
          <label class="block text-[#555879] text-sm mb-2">Email</label>
          <input type="text" placeholder="Invalid@gmail.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#98A1BC]" id = 'email'/>
        </div>

        <div>
          <label class="block text-[#555879] text-sm mb-2">Address</label>
          <textarea placeholder="123, Street Name, City, State" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#98A1BC]" rows="3" id = 'address'></textarea>
        </div>

      </form>
    </div>

    <!-- Right: Order Summary -->
    <div class="w-full md:w-1/2 bg-[#f9f9f9] rounded-xl p-6 border border-gray-100">
      <h2 class="text-2xl font-semibold text-[#555879] mb-6">Order Summary</h2>
      
      <div class="space-y-3 text-[#555879]">
        <div class="flex justify-between">
          <span><?php echo $row['name_of_product']; ?></span>
          <span>$<?php echo $row['price_of_product']; ?></span>
        </div>
        <div class="flex justify-between">
          <span>Shipping</span>
          <span>₹49</span>
        </div>
        <hr class="my-2">
        <div class="flex justify-between font-semibold text-lg">
          <span>Total</span>
          <span>$<?php echo $row['price_of_product'] * $quantity ; ?></span>
        </div>
      </div>

      <button class="mt-6 w-full bg-gradient-to-r from-[#98A1BC] to-[#555879] text-white py-2 rounded-lg font-medium hover:opacity-90 transition"
      id = "placeOrder"
      data-pid = "<?php echo $_GET['id'] ; ?>"
      data-quantity = "<?php echo $_GET['quantity'] ; ?>">
        Place Order
      </button>
    </div>

  </div>
  <script>
    $(document).ready(function(){
        let placeOrderBtn = $('#placeOrder')
        placeOrderBtn.on('click',function(){
            let quantity = $(this).data('quantity')
            let Pid = $(this).data('pid')
            let Fname = $('#Fname').val()
            let Lname = $('#Lname').val()
            let phone = $('#phone').val()
            let email = $('#email').val()
            let address = $('#address').val()
            console.log('butno clicked')
            $.ajax({
                url:'api.php',
                method:'POST',
                data:{action:'buyNowBtnClick',firstName:Fname,lastName:Lname,phone:phone,email:email,address:address,productId:Pid,quantity:quantity},
                dataType:'json',
                success:function(response){
                    console.log(response.url)
                    window.location.href = response.url
                },
                error:function(xhr , status ,error){
                    console.log(error)
                }
            })
        })
    })
  </script>
</body>
</html>

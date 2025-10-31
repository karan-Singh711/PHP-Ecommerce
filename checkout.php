<?php
session_start();
require_once("dbConnection.php");
$user_id = $_SESSION['id'];
  try{
    $productQuery = $pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id");
    $productQuery->bindValue(":user_id", $user_id);
    $productQuery->execute();
    
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #98A1BC 0%, #555879 100%);
        }
        .soft-gradient {
            background: linear-gradient(135deg, rgba(152, 161, 188, 0.1) 0%, rgba(85, 88, 121, 0.05) 100%);
        }
        .blur-decoration {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.3;
            z-index: 0;
        }
    </style>
</head>
<body class="bg-white min-h-screen relative overflow-x-hidden">
    <!-- Decorative Blurred Elements -->
    <div class="blur-decoration w-96 h-96 bg-purple-200 -top-48 -left-48"></div>
    <div class="blur-decoration w-80 h-80 bg-blue-200 top-1/2 -right-40"></div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-2" style="color: #555879;">Secure Checkout</h1>
            <p class="text-gray-500">Complete your order with confidence</p>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
            
            <!-- Left Side - Forms -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Contact Information -->
                <div class="bg-white rounded-3xl shadow-lg p-8 soft-gradient backdrop-blur-sm border border-gray-100">
                    <h2 class="text-2xl font-semibold mb-6 flex items-center gap-2" style="color: #555879;">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        Contact Information
                    </h2>
                    
                    <!-- Email -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" style="color: #555879;">Email Address</label>
                        <input type="email" id="email" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 transition-all" style="focus:ring-color: #98A1BC;" placeholder="you@example.com">
                    </div>

                    <!-- Phone -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" style="color: #555879;">Phone Number</label>
                        <input type="tel" id="phone" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 transition-all" style="focus:ring-color: #98A1BC;" placeholder="+91 9876543210">
                    </div>

                    <!-- First & Last Name -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: #555879;">First Name</label>
                            <input type="text" id="Fname" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 transition-all" style="focus:ring-color: #98A1BC;" placeholder="John">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: #555879;">Last Name</label>
                            <input type="text" id="Lname" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 transition-all" style="focus:ring-color: #98A1BC;" placeholder="Doe">
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-3xl shadow-lg p-8 soft-gradient backdrop-blur-sm border border-gray-100">
                    <h2 class="text-2xl font-semibold mb-6 flex items-center gap-2" style="color: #555879;">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        Shipping Address
                    </h2>
                    
                    <!-- Street Address -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" style="color: #555879;">Street Address</label>
                        <input type="text" id="Saddress" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 transition-all" style="focus:ring-color: #98A1BC;" placeholder="123 Main Street">
                    </div>

                    <!-- City & Postal Code -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: #555879;">City</label>
                            <input type="text" id="city" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 transition-all" style="focus:ring-color: #98A1BC;" placeholder="New York">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: #555879;">Postal Code</label>
                            <input type="text" id="Pcode" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 transition-all" style="focus:ring-color: #98A1BC;" placeholder="10001">
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-3xl shadow-lg p-8 soft-gradient backdrop-blur-sm border border-gray-100">
                    <h2 class="text-2xl font-semibold mb-6 flex items-center gap-2" style="color: #555879;">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                        </svg>
                        Payment Method
                    </h2>
                    
                    <div class="flex items-center p-4 rounded-xl border-2 transition-all" style="border-color: #98A1BC; background-color: rgba(152, 161, 188, 0.05);">
                        <input class="w-5 h-5 mr-3" style="accent-color: #555879;" type="radio" name="payment" id="cod" checked>
                        <label class="text-gray-800 font-medium cursor-pointer" for="cod">
                            Cash on Delivery (COD)
                        </label>
                    </div>
                </div>

            </div>

            <!-- Right Side - Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-lg p-8 sticky top-8 border border-gray-100">
                    <h2 class="text-2xl font-semibold mb-6 flex items-center gap-2" style="color: #555879;">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                        Order Summary
                    </h2>
                    
                    <!-- Product Items from PHP -->
                    <div class="space-y-4 mb-6">
                        <?php
                        while($row = $query->fetch()){
                            $itemTotal = $row['price_of_product'] * $row['quantity'];
                            echo "<div class='flex items-start justify-between pb-4 border-b border-gray-100'>
                                    <div class='flex-1'>
                                        <h3 class='font-medium text-gray-800'>" . htmlspecialchars($row['name_of_product']) . "</h3>
                                        <p class='text-sm text-gray-500'>Qty: " . $row['quantity'] . "</p>
                                    </div>
                                    <div class='font-semibold price_of_product' style='color: #555879;'>$" . number_format($itemTotal, 2) . "</div>
                                  </div>";
                        }
                        ?>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="space-y-3 mb-6 pt-4 border-t border-gray-200">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-medium" id="subtotal">$0.00</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Shipping</span>
                            <span class="font-medium" id="shipping">$9.99</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Tax</span>
                            <span class="font-medium" id="tax">$63.20</span>
                        </div>
                        <div class="flex justify-between text-xl font-bold pt-3 border-t border-gray-200" style="color: #555879;">
                            <span>Total</span>
                            <span id="total">$0.00</span>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <button id="placeOrder" class="w-full gradient-bg text-white font-semibold py-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        Place Order
                    </button>

                    <!-- Security Badge -->
                    <div class="mt-6 flex items-center justify-center gap-2 text-sm text-gray-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>Secure SSL Encrypted Checkout</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            // Calculate subtotal from PHP-generated prices
            let priceProduct = $(".price_of_product");
            let subtotal = 0;
            priceProduct.each(function(){
                let price = parseFloat($(this).text().replace(/[^0-9.]/g,""));
                subtotal += price;
            });
            $("#subtotal").text("$" + subtotal.toFixed(2));

            // Calculate total
            let taxText = $("#tax").text();
            let tax = parseFloat(taxText.replace(/[^0-9.]/g,"")); 
            let shipping = parseFloat($("#shipping").text().replace(/[^0-9.]/g,""));
            let total = shipping + tax + subtotal;
            $("#total").text("$" + total.toFixed(2));

            // Input focus effects
            $('input').on('focus', function() {
                $(this).css('border-color', '#98A1BC');
            }).on('blur', function() {
                $(this).css('border-color', '#e5e7eb');
            });

            // Place Order functionality (unchanged from original)
            let placeOrder = $("#placeOrder");
            placeOrder.on("click", function(){
                const btn = $(this);
                let email = $("#email").val();
                let Fname = $("#Fname").val();
                let Lname = $("#Lname").val();
                let Saddress = $("#Saddress").val();
                let city = $("#city").val();
                let Pcode = $("#Pcode").val();
                let phone = $("#phone").val();
                let total = $("#total").text();

                // Basic validation
                if (!email || !Fname || !Lname || !Saddress || !city || !Pcode || !phone) {
                    alert('Please fill in all fields');
                    return;
                }

                // Button animation
                btn.text('Processing...').prop('disabled', true);

                $.ajax({
                    url:"api.php",
                    method:"POST",
                    data:{action:"insert",Fname:Fname,Lname:Lname,email:email,Saddress:Saddress,city:city,Pcode:Pcode,phone:phone,total:total}, 
                    dataType:'json',
                    success:function(response){
                        console.log(response);
                        btn.text('Order Placed! ✓').css('background', 'linear-gradient(135deg, #4ade80 0%, #22c55e 100%)');
                        setTimeout(function() {
                            window.location.href = response.url;
                        }, 1000);
                    },
                    error:function(xhr,status,error){
                        console.log(error);
                        btn.text('Place Order').prop('disabled', false);
                        alert('Error placing order. Please try again.');
                    },
                });
            });
        });
    </script>
</body>
</html>
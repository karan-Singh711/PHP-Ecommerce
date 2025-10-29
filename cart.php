<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#98A1BC',
                        secondary: '#555879',
                        accent: '#C8D0E7'
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: white;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .hover-lift {
            transition: all 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-4px);
        }
    </style>
</head>
<body class="min-h-screen py-12 px-4">
    <div class="container mx-auto max-w-6xl">
        <!-- Header -->
        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-gradient-to-br from-primary to-secondary shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-secondary mb-2">Your Cart</h1>
        </div>

        <!-- Cart Items -->
        <div class="space-y-4">
            <?php 
            session_start();
            require_once "dbConnection.php";
            $user_id = $_SESSION['id'];
            try{
                $query = $pdo->prepare("SELECT c.product_id,c.quantity,p.name_of_product,p.price_of_product,p.image,p.description_of_product FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = :user_id ");
                $query->bindValue(':user_id',$user_id);
                $query->execute();

                while($row = $query->fetch()){
            ?>
                    <div class="group relative overflow-hidden rounded-xl border-2 border-primary/20 hover:border-primary/40 bg-white hover-lift hover:shadow-xl p-6">
                        <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="relative flex flex-col md:flex-row gap-6 items-center">
                            <!-- Product Image -->
                            <div class="relative h-32 w-32 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100">
                                <img src="<?php echo $row['image']; ?>" 
                                     alt="<?php echo htmlspecialchars($row['name_of_product']); ?>" 
                                     class="h-full w-full object-cover transition-transform group-hover:scale-110">
                            </div>

                            <!-- Product Details -->
                            <div class="flex flex-1 flex-col justify-between w-full">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-secondary"><?php echo htmlspecialchars($row['name_of_product']); ?></h3>
                                        <p class="mt-1 text-sm text-gray-600"><?php echo htmlspecialchars($row['description_of_product']); ?></p>
                                    </div>
                                    <button class="remove text-gray-400 hover:text-red-500 transition-colors ml-4" data-id="<?php echo $row['product_id']; ?>">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>

                                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                                    <!-- Price -->
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-500">Price:</span>
                                        <span class="text-lg font-semibold text-secondary price"><?php echo $row['price_of_product']; ?></span>
                                    </div>

                                    <!-- Quantity Control -->
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-500">Quantity:</span>
                                        <input type="number" 
                                               data-id="<?php echo $row['product_id']; ?>" 
                                               class="Cquantity w-20 px-3 py-2 text-center font-medium text-secondary bg-gray-50 border-2 border-primary/20 rounded-lg focus:outline-none focus:border-primary" 
                                               value="<?php echo $row['quantity']; ?>" 
                                               min="1">
                                    </div>

                                    <!-- Total -->
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-500">Total:</span>
                                        <span class="text-xl font-bold text-secondary total"><?php echo $row['price_of_product'] * $row['quantity']; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }catch(PDOException $e){
                die("errors".$e->getMessage());
            }
            ?>
        </div>

        <!-- Total and Buttons -->
        <div class="mt-8 bg-gradient-to-r from-primary/10 to-secondary/10 rounded-xl p-6 border-2 border-primary/20">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <h4 class="text-2xl font-bold text-secondary" id="totalValue">Total:</h4>
                <div class="flex gap-3">
                    <button class="px-6 py-3 border-2 border-primary hover:bg-primary/10 text-secondary font-semibold rounded-lg transition-all">
                        Continue Shopping
                    </button>
                    <button class="px-6 py-3 bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white font-semibold rounded-lg transition-all hover:shadow-lg">
                        Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $("document").ready(()=>{
            console.log("hello")
            
            let Cquantity = $(".Cquantity")
            let remove = $(".remove")
            // total 
            let total = $(".total")
            let TotalValue = 0
            total.each(function(){
                TotalValue += parseFloat($(this).text())
            })
            console.log(TotalValue)
            let totalValue = $("#totalValue")
            totalValue.text("Total:"+"$"+TotalValue)

            
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
                        button.closest("div.group").remove();
                    },
                    error:function(xhr,status,error){
                        console.log(error)
                    },
                })
            })

            

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
                
                let price = $(this).closest("div.group").find(".price").text()
                console.log(price)

                subTotal = price * newQuantity

                let subTotalcell = $(this).closest("div.group").find(".total").text(subTotal)
                console.log(total)
                TotalValue = 0
                total.each(function(){
                    TotalValue += parseFloat($(this).text())
                })
                console.log(TotalValue)
                totalValue.text("Total:"+"$"+TotalValue)
            })
        })
    </script>
</body>
</html>
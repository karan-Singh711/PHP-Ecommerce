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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        .nav-blur {
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-white">
    
    <!-- Header Navigation -->
    <nav class="fixed top-0 w-full nav-blur z-50 py-6 px-8">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo -->
            <div class="text-3xl font-bold" style="color: #98A1BC;">
                E-Shop
            </div>
            <!-- Search Bar -->
            <div class="flex-1 mx-8">
                <form class="hidden md:block" action="search.php" method="GET">
                    <input 
                        type="text" 
                        name="q"
                        placeholder="Search products..." 
                        class="bg-white bg-opacity-20 text-white placeholder-gray-300 px-4 py-2 rounded-full outline-none focus:bg-opacity-30 transition w-full"/>
                </form>
            </div>

            <!-- Menu Items -->
            <div class="hidden md:flex space-x-8 text-white font-medium">
                <a href="/" class="hover:opacity-80 transition">Home</a>
                <a href="/shop" class="hover:opacity-80 transition">Shop</a>
                <a href="/cart" class="hover:opacity-80 transition">Cart</a>
                <a href="/account" class="hover:opacity-80 transition">Account</a>
                <a href="/contact" class="hover:opacity-80 transition">Contact</a>
            </div>
              
            <!-- Mobile Menu Button -->
            <button onclick="toggleMobileMenu()" class="md:hidden text-white text-2xl">☰</button>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden mt-4">
            <form class="mb-4" action="search.php" method="GET">
                <input 
                    type="text" 
                    name="q"
                    placeholder="Search products..." 
                    class="bg-white bg-opacity-20 text-white placeholder-gray-300 px-4 py-2 rounded-full outline-none focus:bg-opacity-30 transition w-full"/>
            </form>
            <div class="flex flex-col space-y-4 text-white font-medium">
                <a href="/" class="hover:opacity-80 transition">Home</a>
                <a href="/shop" class="hover:opacity-80 transition">Shop</a>
                <a href="/cart" class="hover:opacity-80 transition">Cart</a>
                <a href="/account" class="hover:opacity-80 transition">Account</a>
                <a href="/contact" class="hover:opacity-80 transition">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Decorative Gradient Overlay -->
    <div class="fixed top-0 right-0 w-96 h-96 opacity-20 pointer-events-none z-0" 
         style="background: radial-gradient(circle at top right, #98A1BC, transparent 70%);"></div>
    
    <!-- Main Content - Add padding-top to account for fixed navbar -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-24">
        <!-- Main Product Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
            <!-- Left: Product Image -->
            <div class="flex items-center justify-center">
                <div class="bg-gray-50 rounded-2xl shadow-lg p-8 w-full">
                    <img 
                        src="<?php echo htmlspecialchars($product['image']);?>" 
                        alt="<?php echo htmlspecialchars($product['name_of_product']);?>" 
                        class="w-full h-auto rounded-lg object-cover"
                    />
                </div>
            </div>
            
            <!-- Right: Product Details -->
            <div class="flex flex-col justify-center space-y-6 rounded-2xl shadow-lg p-8" 
                 style="background: linear-gradient(9deg, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 1) 55%, rgba(85, 88, 121, 1) 100%);">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($product['name_of_product']);?></h1>
                    <p class="text-sm text-gray-500">SKU: PRD-<?php echo htmlspecialchars($product['id']);?></p>
                </div>
                
                <div class="flex items-baseline space-x-3">
                    <span class="text-4xl font-bold text-gray-900">$<?php echo htmlspecialchars($product['price_of_product']);?></span>
                </div>
                
                <div class="border-t border-b border-gray-200 py-4">
                    <p class="text-gray-700 leading-relaxed">
                        <?php echo htmlspecialchars($product['description_of_product']);?>
                    </p>
                </div>
                
                <!-- Quantity Selector -->
                <div>
                    <label for="quantity" class="block text-sm font-semibold text-gray-900 mb-2">Quantity</label>
                    <input 
                        type="number" 
                        id="quantity" 
                        class="w-24 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400" 
                        value="1" 
                        min="1"
                    />
                </div>
                
                <div class="flex space-x-4 pt-4">
                    <button 
                        id="addToCart" 
                        data-id="<?php echo $_GET['id'];?>"
                        class="flex-1 py-4 px-6 rounded-xl font-bold text-white shadow-lg transform transition hover:scale-105 hover:shadow-xl"
                        style="background: linear-gradient(135deg, #98A1BC, #555879);">
                        Add to Cart
                    </button>
                    <button class="flex-1 py-4 px-6 rounded-xl font-bold text-white shadow-lg transform transition hover:scale-105 hover:shadow-xl"
                            data-id ="<?php echo $_GET['id'];?>"
                            id = "buyNow"
                            style="background: linear-gradient(135deg, #555879, #98A1BC);">
                        Buy Now
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Product Specifications -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Product Specifications</h2>
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-center">
                        <span class="w-2 h-2 rounded-full mr-3" style="background: linear-gradient(135deg, #98A1BC, #555879);"></span>
                        Material: Premium Quality
                    </li>
                    <li class="flex items-center">
                        <span class="w-2 h-2 rounded-full mr-3" style="background: linear-gradient(135deg, #98A1BC, #555879);"></span>
                        Dimensions: 10 x 6 x 4 inches
                    </li>
                    <li class="flex items-center">
                        <span class="w-2 h-2 rounded-full mr-3" style="background: linear-gradient(135deg, #98A1BC, #555879);"></span>
                        Weight: 1.2 kg
                    </li>
                    <li class="flex items-center">
                        <span class="w-2 h-2 rounded-full mr-3" style="background: linear-gradient(135deg, #98A1BC, #555879);"></span>
                        Color: Black
                    </li>
                    <li class="flex items-center">
                        <span class="w-2 h-2 rounded-full mr-3" style="background: linear-gradient(135deg, #98A1BC, #555879);"></span>
                        Warranty: 1 Year
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Related Products Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Related Products</h2>
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <!-- Product Card 1 -->
                    <div class="group cursor-pointer">
                        <div class="bg-gray-50 rounded-xl p-4 mb-3 overflow-hidden">
                            <img 
                                src="https://via.placeholder.com/300x200" 
                                alt="Product One" 
                                class="w-full h-40 object-cover rounded-lg transform transition group-hover:scale-110"
                            />
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Product One</h3>
                        <p class="text-lg font-bold" style="color: #555879;">$49.99</p>
                    </div>
                    
                    <!-- Product Card 2 -->
                    <div class="group cursor-pointer">
                        <div class="bg-gray-50 rounded-xl p-4 mb-3 overflow-hidden">
                            <img 
                                src="https://via.placeholder.com/300x200" 
                                alt="Product Two" 
                                class="w-full h-40 object-cover rounded-lg transform transition group-hover:scale-110"
                            />
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Product Two</h3>
                        <p class="text-lg font-bold" style="color: #555879;">$59.99</p>
                    </div>
                    
                    <!-- Product Card 3 -->
                    <div class="group cursor-pointer">
                        <div class="bg-gray-50 rounded-xl p-4 mb-3 overflow-hidden">
                            <img 
                                src="https://via.placeholder.com/300x200" 
                                alt="Product Three" 
                                class="w-full h-40 object-cover rounded-lg transform transition group-hover:scale-110"
                            />
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Product Three</h3>
                        <p class="text-lg font-bold" style="color: #555879;">$79.99</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Customer Reviews Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Customer Reviews</h2>
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <!-- Review 1 -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full overflow-hidden" 
                                 style="background: linear-gradient(135deg, #98A1BC, #555879);">
                                <img 
                                    src="https://i.pravatar.cc/150?img=12" 
                                    alt="Sarah Johnson" 
                                    class="w-full h-full object-cover"
                                />
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h4 class="font-bold text-gray-900">Sarah Johnson</h4>
                                    <p class="text-sm text-gray-500">October 25, 2025</p>
                                </div>
                                <div class="flex space-x-1">
                                    <span class="text-yellow-400">★★★★★</span>
                                </div>
                            </div>
                            <p class="text-gray-700 leading-relaxed mb-4">
                                Absolutely love this product! The quality is exceptional and it works perfectly. 
                                I use it daily and couldn't be happier with my purchase. Highly recommended!
                            </p>
                            <div class="flex items-center space-x-6 text-gray-500">
                                <button class="flex items-center space-x-2 hover:text-gray-700 transition">
                                    <span>👍</span>
                                    <span class="text-sm">24</span>
                                </button>
                                <button class="flex items-center space-x-2 hover:text-gray-700 transition">
                                    <span>💬</span>
                                    <span class="text-sm">Reply</span>
                                </button>
                                <button class="flex items-center space-x-2 hover:text-red-500 transition">
                                    <span>❤️</span>
                                    <span class="text-sm">8</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Review 2 -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full overflow-hidden" 
                                 style="background: linear-gradient(135deg, #98A1BC, #555879);">
                                <img 
                                    src="https://i.pravatar.cc/150?img=33" 
                                    alt="Michael Chen" 
                                    class="w-full h-full object-cover"
                                />
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h4 class="font-bold text-gray-900">Michael Chen</h4>
                                    <p class="text-sm text-gray-500">October 22, 2025</p>
                                </div>
                                <div class="flex space-x-1">
                                    <span class="text-yellow-400">★★★★☆</span>
                                </div>
                            </div>
                            <p class="text-gray-700 leading-relaxed mb-4">
                                Great product for the price. The build quality feels premium and it works well.
                                Very satisfied with my purchase and would recommend to others!
                            </p>
                            <div class="flex items-center space-x-6 text-gray-500">
                                <button class="flex items-center space-x-2 hover:text-gray-700 transition">
                                    <span>👍</span>
                                    <span class="text-sm">15</span>
                                </button>
                                <button class="flex items-center space-x-2 hover:text-gray-700 transition">
                                    <span>💬</span>
                                    <span class="text-sm">Reply</span>
                                </button>
                                <button class="flex items-center space-x-2 hover:text-red-500 transition">
                                    <span>❤️</span>
                                    <span class="text-sm">5</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Review 3 -->
                <div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full overflow-hidden" 
                                 style="background: linear-gradient(135deg, #98A1BC, #555879);">
                                <img 
                                    src="https://i.pravatar.cc/150?img=47" 
                                    alt="Emma Davis" 
                                    class="w-full h-full object-cover"
                                />
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h4 class="font-bold text-gray-900">Emma Davis</h4>
                                    <p class="text-sm text-gray-500">October 20, 2025</p>
                                </div>
                                <div class="flex space-x-1">
                                    <span class="text-yellow-400">★★★★★</span>
                                </div>
                            </div>
                            <p class="text-gray-700 leading-relaxed mb-4">
                                Best product I've purchased! Amazing quality and great value for money.
                                Highly recommend to anyone looking for a premium experience.
                            </p>
                            <div class="flex items-center space-x-6 text-gray-500">
                                <button class="flex items-center space-x-2 hover:text-gray-700 transition">
                                    <span>👍</span>
                                    <span class="text-sm">31</span>
                                </button>
                                <button class="flex items-center space-x-2 hover:text-gray-700 transition">
                                    <span>💬</span>
                                    <span class="text-sm">Reply</span>
                                </button>
                                <button class="flex items-center space-x-2 hover:text-red-500 transition">
                                    <span>❤️</span>
                                    <span class="text-sm">12</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Star Rating -->
                
                <!-- Add Review Section -->
                <div class="mt-8">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-4">Write a Review</h3>
                        <div class="flex items-center space-x-2 mb-2">
                        <span class="text-gray-700 font-medium">Your Rating:</span>
                        <div class="flex space-x-1">
                            <input type="radio" name="rating" id="star5" value="5" class="hidden peer" />
                            <label for="star5" class="cursor-pointer text-2xl text-gray-300 peer-checked:text-yellow-400 transition">★</label>

                            <input type="radio" name="rating" id="star4" value="4" class="hidden peer" />
                            <label for="star4" class="cursor-pointer text-2xl text-gray-300 peer-checked:text-yellow-400 transition">★</label>

                            <input type="radio" name="rating" id="star3" value="3" class="hidden peer" />
                            <label for="star3" class="cursor-pointer text-2xl text-gray-300 peer-checked:text-yellow-400 transition">★</label>

                            <input type="radio" name="rating" id="star2" value="2" class="hidden peer" />
                            <label for="star2" class="cursor-pointer text-2xl text-gray-300 peer-checked:text-yellow-400 transition">★</label>

                            <input type="radio" name="rating" id="star1" value="1" class="hidden peer" />
                            <label for="star1" class="cursor-pointer text-2xl text-gray-300 peer-checked:text-yellow-400 transition">★</label>
                        </div>
                    </div>
                    <form class="bg-white rounded-2xl shadow-md p-6 space-y-4" >
                        <textarea 
                            id='comment' 
                            name="review_text"
                            placeholder="Share your experience..." 
                            class="w-full h-32 p-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#98A1BC] resize-none text-gray-800"
                        ></textarea>
                    </form>

                        <!-- Submit Button (outside form) -->
                    <div class="mt-4 text-right">
                    <button 
                        type="submit"
                        form="reviewForm"
                        id="submitReview"
                        data-productid="<?php echo $_GET['id']; ?>"
                        class="px-8 py-3 rounded-xl font-semibold text-white transition-all duration-300 hover:opacity-90"
                        style="background: linear-gradient(135deg, #98A1BC, #555879);"
                    >
                        Submit Review
                    </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="relative bg-white py-8 md:py-10 overflow-hidden shadow-lg border-t border-gray-100">
        <div class="absolute inset-y-0 left-0 w-48 h-48 bg-[#555879] rounded-full mix-blend-multiply opacity-10 blur-3xl transform -translate-x-1/2 translate-y-1/4"></div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">

                <div class="text-center md:text-left text-gray-700 font-medium">
                    This site made by <span class="font-bold text-[#555879]">Karan Singh</span>
                </div>

                <nav class="w-full md:w-auto">
                    <ul class="flex flex-col items-center space-y-2 md:flex-row md:space-y-0 md:space-x-8 text-gray-600 font-medium">
                        <li><a href="#" class="hover:text-[#555879] transition duration-300">Home</a></li>
                        <li><a href="#" class="hover:text-[#555879] transition duration-300">About</a></li>
                        <li><a href="#" class="hover:text-[#555879] transition duration-300">Services</a></li>
                        <li><a href="#" class="hover:text-[#555879] transition duration-300">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // jQuery for Add to Cart functionality
        $(document).ready(() => {
            let submitReviewBtn = $("#submitReview")
            submitReviewBtn.on('click', function(event){
                let productId = submitReviewBtn.data('productid')
                console.log(productId)
                console.log('hello')
                let comment = $('#comment').val()
                console.log(comment)
                if (comment.trim()   !== ""){
                    $.ajax({
                        url:'api.php',
                        method:'post',
                        data:{action:'insertComment',comment:comment , productId:productId},
                        success:function(response){
                            console.log(response)
                        },
                        error:function(xhr,status,error){
                            console.log(error)
                        }
                    })
                }else{
                    console.log('trim')
                }
                
            })
            $("#addToCart").click(() => {
                let productId = $("#addToCart").data('id');
                let quantity = $('#quantity').val();
                $.ajax({
                    url: "api.php",
                    method: 'POST',
                    data: {action:'addedToCart',quantity: quantity, productId: productId},
                    success: function(response) {
                        console.log(response);
                        alert('Product added to cart!');
                    },
                    error: function(xhr, status, error) {
                        console.log("error:", error);
                    },
                });
            });
            $('#buyNow').on('click',function(){
              let productId = $('#buyNow').data('id')
              let quantity = $('#quantity').val();
              console.log(productId);
              console.log(quantity);
              window.location.href = 'http://localhost/e-commerce/buyCheckout.php?id=' + productId + '&quantity=' + quantity
            })
        });
    </script>
</body>
</html>
<?php
require_once 'dbConnection.php'; // Include PDO connection

try {
    $productName = isset($_GET['q']) ? trim($_GET['q']) : '';
    $min = isset($_GET['min'])?trim($_GET['min']):0; 
    
    $productByPage = 10;
    if (empty($productName)) {
        echo '<div class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg shadow-lg p-6 text-center" style="color: #555879;">Please enter a search term.</div>';
        exit;
    }
    if(isset($_GET['page']) && $_GET['page']>=1 ){
        $page = $_GET['page'];
        $offset = ($page - 1) * $productByPage; 
    }else{
        $page = 1;
        $offset = 0;
    }
    // MAX AND MIN query for price range
    $maxMinQuery = $pdo->prepare('SELECT 
    MIN(price_of_product) AS min_price, 
    MAX(price_of_product) AS max_price
    FROM products WHERE name_of_product LIKE :productName');
    $maxMinQuery->bindValue(":productName","%".$productName."%");
    $maxMinQuery->execute();
    $maxMin = $maxMinQuery->fetch();

    $max = isset($_GET['max']) ? trim($_GET['max']) : $maxMin['max_price'];
    // count query for pagination
    $productCountquery = $pdo->prepare('SELECT COUNT(*) AS total_products
    FROM products WHERE name_of_product LIKE :productName
    AND price_of_product BETWEEN :min AND :max');
    $productCountquery->bindValue(":productName","%".$productName."%");
    $productCountquery->bindValue(':min',$min,PDO::PARAM_INT);
    $productCountquery->bindValue(':max',$max,PDO::PARAM_INT);

    $productCountquery->execute();
    
    $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $row = $productCountquery->fetch();
    $number = ceil($row['total_products']/$productByPage);

    // $max = isset($_GET['max']) ? trim($_GET['max']) : $maxMin['max_price'];

    //  SELECT PRODUCTS AND SHOW THEM BY FILTERS INCLUDED
    $query = $pdo->prepare('SELECT * 
    FROM products 
    WHERE name_of_product LIKE :productName 
    AND price_of_product BETWEEN :min AND :max
    LIMIT :productByPage OFFSET :offset' );

    $query->bindValue(':productName', '%' . $productName . '%');
    $query->bindValue(':min',$min,PDO::PARAM_INT);
    $query->bindValue(':max',$max,PDO::PARAM_INT);
    $query->bindValue(':productByPage',$productByPage,PDO::PARAM_INT);
    $query->bindValue(':offset',$offset,PDO::PARAM_INT);
    $query->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        .product-image {
            transition: transform 0.3s ease;
        }
        .product-card:hover .product-image {
            transform: scale(1.1);
        }
        .nav-blur {
            backdrop-filter: blur(10px);
            background-color: rgb(0, 0, 0);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation with Blur Background -->
    <nav class="fixed top-0 w-full nav-blur z-50 py-6 px-8">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo -->
            <div class="text-3xl font-bold" style="color: #98A1BC;">
                E-Shop
            </div>
            <!-- Search Bar (keep old backend form) -->
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

    <!-- Main Content (with top padding for fixed nav) -->
    <div class="container mx-auto px-4 py-6 mt-32">
        <!-- Page Title -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-2" style="color: #555879;">Search Results</h1>
            <p class="text-xl" style="color: #98A1BC;">Showing results for "<?php echo htmlspecialchars($productName); ?>"</p>
        </div>

        <!-- Mobile Filter Button -->
        <div class="lg:hidden mb-6">
            <button onclick="toggleFilters()" class="w-full py-3 px-4 rounded-lg text-white font-medium flex items-center justify-center gap-2" style="background-color: #555879;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filters
            </button>
        </div>

        <!-- Mobile Filter Panel (Hidden by default) -->
        <div id="mobileFilters" class="hidden lg:hidden mb-6 p-6 bg-white rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold" style="color: #555879;">Filters</h3>
                <button onclick="toggleFilters()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="space-y-6">
                <!-- Category Filter -->
                <div>
                    <h4 class="font-medium mb-3" style="color: #555879;">Category</h4>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 rounded" style="accent-color: #555879;">
                            <span class="text-gray-700">Electronics</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 rounded" style="accent-color: #555879;">
                            <span class="text-gray-700">Clothing</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 rounded" style="accent-color: #555879;">
                            <span class="text-gray-700">Home & Garden</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 rounded" style="accent-color: #555879;">
                            <span class="text-gray-700">Sports</span>
                        </label>
                    </div>
                </div>

                <!-- Price Range -->
                <div>
                    <h4 class="font-medium mb-3" style="color: #555879;">Price Range</h4>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="price-mobile" class="mr-2" style="accent-color: #555879;">
                            <span class="text-gray-700">Under $50</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="price-mobile" class="mr-2" style="accent-color: #555879;">
                            <span class="text-gray-700">$50 - $100</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="price-mobile" class="mr-2" style="accent-color: #555879;">
                            <span class="text-gray-700">$100 - $200</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="price-mobile" class="mr-2" style="accent-color: #555879;">
                            <span class="text-gray-700">Over $200</span>
                        </label>
                    </div>
                </div>

                <!-- Rating -->
                <div>
                    <h4 class="font-medium mb-3" style="color: #555879;">Rating</h4>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 rounded" style="accent-color: #555879;">
                            <span class="text-gray-700">★★★★★ (5)</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 rounded" style="accent-color: #555879;">
                            <span class="text-gray-700">★★★★☆ (4+)</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 rounded" style="accent-color: #555879;">
                            <span class="text-gray-700">★★★☆☆ (3+)</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Layout -->
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar (Desktop) -->
            <aside class="hidden lg:block w-64 flex-shrink-0">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-32">
                    <h3 class="text-lg font-semibold mb-6" style="color: #555879;">Filters</h3>
                    
                    <!-- Category Filter -->
                    <div class="mb-6">
                        <h4 class="font-medium mb-3" style="color: #555879;">Category</h4>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="mr-2 rounded" style="accent-color: #555879;">
                                <span class="text-gray-700">Electronics</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="mr-2 rounded" style="accent-color: #555879;">
                                <span class="text-gray-700">Clothing</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="mr-2 rounded" style="accent-color: #555879;">
                                <span class="text-gray-700">Home & Garden</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="mr-2 rounded" style="accent-color: #555879;">
                                <span class="text-gray-700">Sports</span>
                            </label>
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <h4 class="font-medium mb-3" style="color: #555879;">Price Range</h4>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="price" class="mr-2" style="accent-color: #555879;">
                                <span class="text-gray-700">Under $50</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="price" class="mr-2" style="accent-color: #555879;">
                                <span class="text-gray-700">$50 - $100</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="price" class="mr-2" style="accent-color: #555879;">
                                <span class="text-gray-700">$100 - $200</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="price" class="mr-2" style="accent-color: #555879;">
                                <span class="text-gray-700">Over $200</span>
                            </label>
                            <div class="pt-4">
                                <input 
                                    type="range" 
                                    min=<?php echo $maxMin['min_price'] ; ?> 
                                    max=<?php echo $maxMin['max_price'] ; ?> 
                                    value=<?php echo $maxMin['min_price'] ; ?>
                                    class="w-full cursor-pointer" 
                                    style="accent-color: #98A1BC;"
                                    id = "priceRange"
                                    
                                >
                                <div class="flex justify-between text-sm text-gray-600 mt-1">
                                    <span id  = 'minValue'>$<?php echo $maxMin['min_price'] ; ?> </span>
                                    <span id  = 'maxValue'>$<?php echo $maxMin['max_price'] ; ?> </span>
                                </div>                             
                            </div>
                        </div>
                    </div>

                    <!-- Rating -->
                    <div>
                        <h4 class="font-medium mb-3" style="color: #555879;">Rating</h4>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="mr-2 rounded" style="accent-color: #555879;">
                                <span class="text-gray-700">★★★★★ (5)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="mr-2 rounded" style="accent-color: #555879;">
                                <span class="text-gray-700">★★★★☆ (4+)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="mr-2 rounded" style="accent-color: #555879;">
                                <span class="text-gray-700">★★★☆☆ (3+)</span>
                            </label>
                        </div>
                    </div>
                    <a 
                    
                    href=""
                    class="inline-block mt-4 bg-[#555879] text-white px-4 py-2 rounded hover:bg-[#44465e] transition" 
                    id = 'apply'>
                    Apply Filters 
                    </a>
                </div>
                
            </aside>

            <!-- Product Grid -->
            <main class="flex-1">
                <div class="mb-6 flex justify-between items-center">
                    <h2 class="text-xl md:text-2xl font-bold" style="color: #555879;">
                        <?php 
                        $resultCount = $row['total_products'];
                        echo $resultCount . ' Product' . ($resultCount !== 1 ? 's' : '') . ' Found';
                        ?>
                    </h2>
                    <select class="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2" style="accent-color: #555879;">
                        <option>Sort by: Popular</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                        <option>Newest</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4 md:gap-6">
                    <?php
                    $resultsFound = false;
                    while ($row = $query->fetch()) {
                        $resultsFound = true;
                    ?>
                        <!-- Product Card -->
                        <a href="productDetail.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="product-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 block">
                            <div class="overflow-hidden">
                                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name_of_product']); ?>" class="product-image w-full h-48 md:h-56 object-cover">
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold mb-2 line-clamp-2" style="color: #555879;"><?php echo htmlspecialchars($row['name_of_product']); ?></h3>
                                <p class="text-sm mb-3 line-clamp-2" style="color: #98A1BC;"><?php echo htmlspecialchars($row['description_of_product']); ?></p>
                                <button class="w-full mt-2 py-2 px-4 rounded-lg text-white font-medium hover:opacity-90 transition-opacity" style="background-color: #555879;">View Details</button>
                            </div>
                        </a>
                    <?php
                    }
                    
                    if (!$resultsFound) {
                        echo '<div class="col-span-2 md:col-span-3 lg:col-span-3 bg-white rounded-lg shadow-md p-8 text-center">';
                        echo '<svg class="w-16 h-16 mx-auto mb-4" style="color: #98A1BC;" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                        echo '</svg>';
                        echo '<h3 class="text-xl font-semibold mb-2" style="color: #555879;">No products found</h3>';
                        echo '<p style="color: #98A1BC;">No products matching "' . htmlspecialchars($productName) . '" were found. Try searching with different keywords.</p>';
                        echo '</div>';
                    }
                    ?>
                </div>
                <div class="flex items-center justify-center space-x-2 mt-8">
    <!-- Previous Button -->
    

    <!-- Page Numbers -->
     <?php
     
     if($currentPage > 1){
        echo'<a href="?q='.$productName.'&page=' . ($currentPage - 1) . '" class="px-4 py-2 bg-gray-200 text-gray-600 rounded-md hover:bg-gray-300">
                Previous
            </a>';
     }
     if($currentPage >= 3){
       echo '<a href="?q='.$productName.'&page=1" class="px-4 py-2 bg-[#555879] text-white rounded-md">1</a>';    
       if($currentPage >=4){
        echo '<span class="px-2 text-gray-500">...</span>';
       }
     }
    $start = max(1,$currentPage - 1);
    $end = min($number,$currentPage + 1);
    for($i=$start;$i<=$end;$i++){
    echo '<a href="?q='.$productName.'&page='.$i.'" class="px-4 py-2 bg-[#555879] text-white rounded-md">'.$i.'</a>';    
    }

    if($currentPage >= 1 && $currentPage < $number - 1){
        echo '<span class="px-2 text-gray-500">...</span>';
        echo '<a href="?q='.$productName.'&page='.$number.'" class="px-4 py-2 bg-[#555879] text-white rounded-md">'.$number.'</a>';    
      
     }
      if($currentPage < $number){
        echo'<a href="?q='.$productName.'&page=' . ($currentPage + 1) . '" class="px-4 py-2 bg-gray-200 text-gray-600 rounded-md hover:bg-gray-300">
                Next
            </a>';
     }
     ?>
    <!-- <a href="?q='.$productName.'&page='.$i.'" class="px-4 py-2 bg-[#555879] text-white rounded-md">'.$i.'</a> -->
    <!-- <button class="px-4 py-2 bg-gray-200 text-gray-600 rounded-md hover:bg-gray-300">2</button>
    <button class="px-4 py-2 bg-gray-200 text-gray-600 rounded-md hover:bg-gray-300">3</button>
    <span class="px-2 text-gray-500">...</span>
    <button class="px-4 py-2 bg-gray-200 text-gray-600 rounded-md hover:bg-gray-300">10</button> -->

    <!-- Next Button -->
    
</div>
            </main>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            let priceValue = $('#priceRange')
            let applyBtn = $('#apply')
            priceValue.on('input',function(){
            console.log($(this).val())
            $('#minValue').text('$'+$(this).val())
            })
            // priceValue.on('change',function(){
            // let minValue = $(this).val()
            // })
            applyBtn.on('click',function(e){
                e.preventDefault()
                let UserSet = priceValue.val()
                console.log(UserSet)
                let maxValue = priceValue.attr('max')
                

                let query = new URLSearchParams(window.location.search)
                query.set('min',UserSet)
                query.set('max',maxValue)
                query.set('page',1)
                console.log(query.toString())
                window.location.search = query.toString()
            })
            // priceValue.on('change',function(){
            //     let minValue = $(this).val()
            //     let maxValue = $(this).attr('max')
            //     let productName = $(this).data('id')
            //     console.log(productName)
            //     console.log('this is max value:'+maxValue)
            //     console.log('this is min value:'+minValue)
            //     $.ajax
            //     ({
            //         url:'api.php',
            //         method:'POST',
            //         data: {action:'priceFilter',maxValue:maxValue,minValue:minValue,productName:productName},
            //         dataType:'json',
            //         success:function(response){
            //             console.log(response)
            //         },
            //         error:function(xhr,status,error){
            //             console.log(error)
            //         } 

            //     })
            // })
            console.log(applyBtn.length); 
            console.log(minValue)
        })
        function toggleFilters() {
            const filters = document.getElementById('mobileFilters');
            filters.classList.toggle('hidden');
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
    </script>
</body>
</html>
<?php
} catch (PDOException $e) {
    echo '<div class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-red-100 rounded-lg shadow-lg p-6 text-center border-2 border-red-400">';
    echo '<h3 class="text-xl font-semibold mb-2 text-red-800">Error</h3>';
    echo '<p class="text-red-600">' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '</div>';
}
?>
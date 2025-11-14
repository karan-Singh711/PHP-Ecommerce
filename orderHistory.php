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
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#98A1BC',
                        secondary: '#555879',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Page Title -->
    <div class="bg-white shadow-sm border-b border-primary/20">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <h1 class="text-3xl font-bold text-secondary">Karan's Orders</h1>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- LEFT COLUMN - Orders Section -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Filter Buttons -->
                <div class="bg-white rounded-lg shadow p-4 border border-primary/10">
                    <div class="flex flex-wrap gap-3">
                        <button class="filter-btn px-6 py-2 rounded-full font-medium transition-all duration-200 bg-secondary text-white shadow-md" data-status="all">
                            All Orders
                        </button>
                        <button class="filter-btn px-6 py-2 rounded-full font-medium transition-all duration-200 bg-gray-100 text-secondary hover:bg-primary/20" data-status="unpaid">
                            Unpaid
                        </button>
                        <button class="filter-btn px-6 py-2 rounded-full font-medium transition-all duration-200 bg-gray-100 text-secondary hover:bg-primary/20" data-status="dispatch">
                            Dispatch
                        </button>
                        <button class="filter-btn px-6 py-2 rounded-full font-medium transition-all duration-200 bg-gray-100 text-secondary hover:bg-primary/20" data-status="delivered">
                            Delivered
                        </button>
                        <button class="filter-btn px-6 py-2 rounded-full font-medium transition-all duration-200 bg-gray-100 text-secondary hover:bg-primary/20" data-status="cancelled">
                            Cancelled
                        </button>
                    </div>
                </div>

                <!-- Orders List -->
                <div id="orders-container" class="space-y-4">
                    
                    <?php
                    while($row = $orderHquery->fetch()){
                        // Convert status to lowercase for filter matching
                        $status = strtolower($row['status']);
                        
                        // Status badge colors
                        $badgeClass = '';
                        switch($status) {
                            case 'delivered':
                                $badgeClass = 'bg-green-100 text-green-700';
                                break;
                            case 'dispatch':
                                $badgeClass = 'bg-blue-100 text-blue-700';
                                break;
                            case 'unpaid':
                            case 'pending':
                                $badgeClass = 'bg-yellow-100 text-yellow-700';
                                break;
                            case 'cancelled':
                                $badgeClass = 'bg-red-100 text-red-700';
                                break;
                            default:
                                $badgeClass = 'bg-gray-100 text-gray-700';
                        }
                        
                        $totalPrice = $row['price_of_product'] * $row['quantity'];
                        $formattedDate = date('M d, Y', strtotime($row['created_at']));
                        
                        echo '
                        <div class="order-card bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200 p-5 border border-primary/10" data-status="'.$status.'">
                            <div class="flex items-start gap-4">
                                <img src="'.$row['image'].'" alt="'.$row['name_of_product'].'" class="w-24 h-24 rounded-lg object-cover border-2 border-primary/20">
                                <div class="flex-1">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h3 class="font-semibold text-secondary text-lg">'.$row['name_of_product'].'</h3>
                                            <p class="text-sm text-gray-500">Product ID: #'.$row['product_id'].'</p>
                                        </div>
                                        <span class="px-3 py-1 '.$badgeClass.' text-xs font-semibold rounded-full">'.ucfirst($status).'</span>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm text-gray-600 mb-3">
                                        <span>Quantity: '.$row['quantity'].'</span>
                                        <span>•</span>
                                        <span>'.$formattedDate.'</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <p class="text-xl font-bold text-secondary price" data-price="'.$totalPrice.'">$'.$totalPrice.'</p>
                                        <button class="px-4 py-2 bg-primary/20 text-secondary rounded-lg hover:bg-primary/30 transition-colors font-medium">
                                            View Details
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    }
                    ?>

                </div>
            </div>

            <!-- RIGHT COLUMN - User Details (Sample Data) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-4 border border-primary/20">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-primary to-secondary rounded-full mx-auto mb-4 flex items-center justify-center shadow-lg">
                            <span class="text-white text-2xl font-bold">K</span>
                        </div>
                        <h2 class="text-2xl font-bold text-secondary">Karan Singh</h2>
                        <p class="text-sm text-primary">Premium Member</p>
                    </div>

                    <div class="space-y-4 border-t border-primary/20 pt-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="text-xs text-primary uppercase">Email</p>
                                <p class="text-sm text-secondary font-medium">karan.singh@email.com</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <div>
                                <p class="text-xs text-primary uppercase">Phone</p>
                                <p class="text-sm text-secondary font-medium">+91 98765 43210</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <div>
                                <p class="text-xs text-primary uppercase">Address</p>
                                <p class="text-sm text-secondary font-medium">123, MG Road, Connaught Place, New Delhi, 110001</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-primary/20">
                        <div class="bg-gradient-to-br from-primary/20 to-secondary/20 rounded-lg p-4 border border-primary/30">
                            <p class="text-sm text-secondary/70 mb-1">Total Order Value</p>
                            <p class="text-3xl font-bold text-secondary" id="total">$0.00</p>
                            <p class="text-xs text-primary mt-1"><span id="orderCount">0</span> orders placed</p>
                        </div>
                    </div>

                    <div class="mt-4 space-y-2">
                        <button class="w-full py-2 bg-secondary text-white rounded-lg hover:bg-secondary/90 transition-colors font-medium shadow-md">
                            Edit Profile
                        </button>
                        <button class="w-full py-2 bg-primary/20 text-secondary rounded-lg hover:bg-primary/30 transition-colors font-medium">
                            Order History
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function(){
            // Calculate total price from all orders
            let price = $('.price');
            let totalPrice = 0;
            price.each(function(){
                let totalT = $(this).data('price');
                totalPrice += parseFloat(totalT);
            });
            $('#total').text("$"+totalPrice.toFixed(2));
            $('#orderCount').text(price.length);

            // Filter functionality
            $('.filter-btn').click(function() {
                const status = $(this).data('status');
                
                // Update active button
                $('.filter-btn').removeClass('bg-secondary text-white shadow-md').addClass('bg-gray-100 text-secondary');
                $(this).removeClass('bg-gray-100 text-secondary').addClass('bg-secondary text-white shadow-md');
                
                // Filter orders
                if (status === 'all') {
                    $('.order-card').fadeIn(300);
                } else {
                    $('.order-card').hide();
                    $(`.order-card[data-status="${status}"]`).fadeIn(300);
                }
            });

            // Add hover effect to order cards
            $('.order-card').hover(
                function() {
                    $(this).addClass('scale-[1.02]');
                },
                function() {
                    $(this).removeClass('scale-[1.02]');
                }
            );
        });
    </script>
</body>
</html>
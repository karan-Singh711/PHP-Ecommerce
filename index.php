<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce Homepage</title>
    <!-- Bootstrap 5.3 CSS via CDN (keep for other sections) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons for search icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Tailwind CSS for navigation and hero -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Custom CSS -->
    <style>
        /* Tailwind Hero Section Styles */
        .blur-circle {
            filter: blur(120px);
            opacity: 1.5;
        }
        .gradient-button {
            background: linear-gradient(135deg, #555879 0%, #98A1BC 100%);
        }
        .carousel-item-tailwind {
            display: none;
            animation: fadeIn 0.5s;
        }
        .carousel-item-tailwind.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Keep old Bootstrap custom styles for categories & footer */
       
    </style>
</head>
<body class="bg-white">

    <!-- Navigation Bar (Tailwind version) -->
    <nav class="fixed top-0 w-full bg-transparent z-50 py-6 px-8">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo -->
            <div class="text-3xl font-bold" style="color: #98A1BC;">
                E-Shop
            </div>
            
            <!-- Menu Items -->
            <div class="hidden md:flex space-x-8 text-white font-medium">
                <a href="/" class="hover:opacity-80 transition">Home</a>
                <a href="/shop" class="hover:opacity-80 transition">Shop</a>
                <a href="/cart" class="hover:opacity-80 transition">Cart</a>
                <a href="/account" class="hover:opacity-80 transition">Account</a>
                <a href="/contact" class="hover:opacity-80 transition">Contact</a>
            </div>
            
            <!-- Search Bar (keep old backend form) -->
            <form class="hidden md:block" action="search.php" method="GET">
                <input 
                    type="text" 
                    name="q"
                    placeholder="Search products..." 
                    class="bg-white bg-opacity-20 text-white placeholder-gray-300 px-4 py-2 rounded-full outline-none focus:bg-opacity-30 transition w-64"
                />
            </form>
            
            <!-- Mobile Menu Button -->
            <button class="md:hidden text-white text-2xl">☰</button>
        </div>
    </nav>

    <!-- Hero Section (Tailwind version) -->
    <section class="bg-black min-h-screen flex items-center relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-8 py-24 w-full">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left Side - Text Content -->
                <div class="relative z-10">
                    <div class="absolute -left-32 top-0 w-96 h-96 rounded-full blur-circle" style="background-color: #555879;"></div>
                    <div class="relative">
                        <h1 class="text-6xl md:text-7xl lg:text-8xl font-bold text-white mb-6 leading-tight">
                            Discover<br/>
                            <span style="color: #98A1BC;">Premium</span><br/>
                            Fashion
                        </h1>
                        <p class="text-gray-300 text-lg md:text-xl mb-8 max-w-md">
                            Elevate your style with our curated collection of luxury fashion and accessories.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <button class="px-8 py-4 rounded-full text-white font-semibold hover:opacity-90 transition gradient-button">
                                Shop Now
                            </button>
                            <button class="px-8 py-4 rounded-full border-2 text-white font-semibold hover:bg-white hover:text-black transition" style="border-color: #98A1BC;">
                                View Collection
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Carousel -->
                <div class="relative">
                    <div class="carousel-container relative aspect-square max-w-lg mx-auto">
                        <!-- Carousel Items -->
                        <div class="carousel-item-tailwind active">
                            <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=600&h=600&fit=crop" alt="Product 1" class="w-full h-full object-cover rounded-3xl shadow-2xl">
                        </div>
                        <div class="carousel-item-tailwind">
                            <img src="https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=600&h=600&fit=crop" alt="Product 2" class="w-full h-full object-cover rounded-3xl shadow-2xl">
                        </div>
                        <div class="carousel-item-tailwind">
                            <img src="https://images.unsplash.com/photo-1560343090-f0409e92791a?w=600&h=600&fit=crop" alt="Product 3" class="w-full h-full object-cover rounded-3xl shadow-2xl">
                        </div>
                        <div class="carousel-item-tailwind">
                            <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600&h=600&fit=crop" alt="Product 4" class="w-full h-full object-cover rounded-3xl shadow-2xl">
                        </div>
                        
                        <!-- Carousel Controls -->
                        <button onclick="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 text-white text-3xl bg-black bg-opacity-50 w-12 h-12 rounded-full hover:bg-opacity-70 transition">
                            ‹
                        </button>
                        <button onclick="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 text-white text-3xl bg-black bg-opacity-50 w-12 h-12 rounded-full hover:bg-opacity-70 transition">
                            ›
                        </button>
                        
                        <!-- Carousel Indicators -->
                        <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 flex space-x-2">
                            <button onclick="goToSlide(0)" class="carousel-indicator w-2 h-2 rounded-full" style="background-color: #98A1BC;"></button>
                            <button onclick="goToSlide(1)" class="carousel-indicator w-2 h-2 rounded-full bg-gray-500"></button>
                            <button onclick="goToSlide(2)" class="carousel-indicator w-2 h-2 rounded-full bg-gray-500"></button>
                            <button onclick="goToSlide(3)" class="carousel-indicator w-2 h-2 rounded-full bg-gray-500"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section (Bootstrap version kept) -->
    <section class="bg-white py-16 relative overflow-hidden">
  <div class="absolute right-0 bottom-0 w-96 h-96 rounded-full blur-circle" style="background-color: #98A1BC;"></div>
  <div class="absolute left-0 top-0 w-96 h-96 rounded-full blur-circle" style="background-color: #555879;"></div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <h2 class="text-4xl font-bold text-center text-gray-800 mb-12">Explore Our Categories</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 grid-rows-2 gap-6">

      <a href="/random-link-1" target="_blank"
        class="relative group overflow-hidden rounded-2xl h-64 shadow-lg">
        <img src="public\images\model-career-kit-still-life.jpg" alt="Women Accessories"
          class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
        <div class="absolute inset-0 bg-gradient-to-t from-[#555879]/70 to-[#98A1BC]/30"></div>
        <p class="absolute bottom-4 left-4 text-white text-lg font-semibold group-hover:translate-y-[-4px] transition-transform">
          Women Accessories
        </p>
      </a>

      <a href="/random-link-2" target="_blank"
        class="relative group overflow-hidden rounded-2xl lg:row-span-2 shadow-lg h-64 sm:h-auto">
        <img src="public\images\young-handsome-man-choosing-hat-shop.jpg" alt="Men Clothing"
          class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
        <div class="absolute inset-0 bg-gradient-to-t from-[#555879]/70 to-[#98A1BC]/30"></div>
        <p class="absolute bottom-4 left-4 text-white text-lg font-semibold group-hover:translate-y-[-4px] transition-transform">
          Men Clothing
        </p>
      </a>

      <a href="/random-link-3" target="_blank"
        class="relative group overflow-hidden rounded-2xl h-64 shadow-lg">
        <img src="public\images\girl-wearing-trendy-shoes-shoes-close-up-shoot-fashion-store.jpg" alt="Footwear"
          class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
        <div class="absolute inset-0 bg-gradient-to-t from-[#555879]/70 to-[#98A1BC]/30"></div>
        <p class="absolute bottom-4 left-4 text-white text-lg font-semibold group-hover:translate-y-[-4px] transition-transform">
          Footwear
        </p>
      </a>

      <a href="/random-link-4" target="_blank"
        class="relative group overflow-hidden rounded-2xl h-64 shadow-lg">
        <img src="public\images\istockphoto-1355271375-1024x1024.jpg" alt="Jewelry"
          class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
        <div class="absolute inset-0 bg-gradient-to-t from-[#555879]/70 to-[#98A1BC]/30"></div>
        <p class="absolute bottom-4 left-4 text-white text-lg font-semibold group-hover:translate-y-[-4px] transition-transform">
          Jewelry
        </p>
      </a>

      <a href="/random-link-5" target="_blank"
        class="relative group overflow-hidden rounded-2xl h-64 shadow-lg">
        <img src="public/images/pexels-sanddollar-205436-634538.jpg" alt="Bags"
          class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
        <div class="absolute inset-0 bg-gradient-to-t from-[#555879]/70 to-[#98A1BC]/30"></div>
        <p class="absolute bottom-4 left-4 text-white text-lg font-semibold group-hover:translate-y-[-4px] transition-transform">
          Bags
        </p>
      </a>

    </div>
  </div>
</section>
<!-- footer section -->
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
    <!-- Tailwind Carousel Script -->
    <script>
        $(document).ready(function() {
    const $mobileMenuButton = $('.md\\:hidden.text-white.text-2xl');
    const $nav = $('nav');
    let $mobileMenu = $('.mobile-menu');

    // Create mobile menu if it doesn't exist
    if ($mobileMenu.length === 0) {
        $mobileMenu = $('<div>').addClass('mobile-menu fixed top-0 right-0 h-full w-64 bg-black bg-opacity-95 transform translate-x-full transition-transform duration-300 ease-in-out z-50');
        $mobileMenu.html(`
            <div class="flex flex-col items-start p-6 space-y-4 mt-16">
                <a href="/" class="text-white text-lg font-medium hover:opacity-80 transition">Home</a>
                <a href="/shop" class="text-white text-lg font-medium hover:opacity-80 transition">Shop</a>
                <a href="/cart" class="text-white text-lg font-medium hover:opacity-80 transition">Cart</a>
                <a href="/account" class="text-white text-lg font-medium hover:opacity-80 transition">Account</a>
                <a href="/contact" class="text-white text-lg font-medium hover:opacity-80 transition">Contact</a>
                <form action="search.php" method="GET" class="w-full mt-4">
                    <input 
                        type="text" 
                        name="q"
                        placeholder="Search products..." 
                        class="w-full bg-white bg-opacity-20 text-white placeholder-gray-300 px-4 py-2 rounded-full outline-none focus:bg-opacity-30 transition"
                    />
                </form>
            </div>
        `);
        $nav.append($mobileMenu);
    }

    // Toggle mobile menu
    $mobileMenuButton.on('click', function() {
        const isOpen = $mobileMenu.hasClass('translate-x-0');
        $mobileMenu.toggleClass('translate-x-0', !isOpen).toggleClass('translate-x-full', isOpen);
        $(this).text(isOpen ? '☰' : '✕').css('color', '#98A1BC');
    });

    // Close menu when clicking outside
    $(document).on('click', function(e) {
        if (!$mobileMenu.is(e.target) && $mobileMenu.has(e.target).length === 0 && !$mobileMenuButton.is(e.target)) {
            $mobileMenu.removeClass('translate-x-0').addClass('translate-x-full');
            $mobileMenuButton.text('☰').css('color', '#98A1BC');
        }
    });
});
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-item-tailwind');
        const indicators = document.querySelectorAll('.carousel-indicator');
        
        function showSlide(n) {
            slides.forEach(slide => slide.classList.remove('active'));
            indicators.forEach(ind => { ind.style.backgroundColor = '#6b7280'; });
            currentSlide = (n + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
            indicators[currentSlide].style.backgroundColor = '#98A1BC';
        }
        function nextSlide() { showSlide(currentSlide + 1); }
        function prevSlide() { showSlide(currentSlide - 1); }
        function goToSlide(n) { showSlide(n); }
        setInterval(nextSlide, 4000);
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>

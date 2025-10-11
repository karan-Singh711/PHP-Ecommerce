
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce Homepage</title>
    <!-- Bootstrap 5.3 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons for search icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #98A1BC;
            color: #333;
        }
        .navbar {
            background-color: #555879;
        }
        .navbar .nav-link, .navbar-brand {
            color: #fff !important;
        }
        .navbar .nav-link:hover {
            color: #ddd !important;
        }
        /* Custom Search Input */
        .search-form {
            position: relative;
            max-width: 300px;
            width: 100%;
        }
        .search-form input {
            border: none;
            border-radius: 25px;
            padding: 10px 40px 10px 15px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .search-form input:focus {
            outline: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            background-color: #f8f9fa;
        }
        .search-form .bi-search {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #555879;
        }
        /* Carousel */
        .carousel-item {
            height: 400px;
            background-size: cover;
            background-position: center;
        }
        /* Categories Section */
        .category-card {
            background-color: #555879;
            color: #fff;
            border: none;
            transition: transform 0.3s ease;
        }
        .category-card img {
            height: 200px;
            object-fit: cover;
        }
        .category-card:hover {
            transform: translateY(-5px);
        }
        .category-card .btn {
            background-color: #98A1BC;
            border: none;
            color: #fff;
        }
        .category-card .btn:hover {
            background-color: #7b89a3;
        }
        .footer {
            background-color: #555879;
            color: #fff;
            padding: 20px 0;
        }
        .footer a {
            color: #ddd;
            text-decoration: none;
        }
        .footer a:hover {
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">E-Shop</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/shop">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/cart">Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/account">Account</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Contact</a>
                    </li>
                </ul>
                <!-- Search Form -->
                <form class="search-form" action="search.php" method="GET">
                    <input type="text" class="form-control" placeholder="Search products..." name="q">
                    <button type="submit" class="bi bi-search" style = 'border:none; background-color:#f8f9fa;'></button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Carousel -->
    <section class="hero-section">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active" style="background-image: url('https://picsum.photos/1200/400?random=1');"></div>
                <!-- Slide 2 -->
                <div class="carousel-item" style="background-image: url('https://picsum.photos/1200/400?random=2');"></div>
                <!-- Slide 3 -->
                <div class="carousel-item" style="background-image: url('https://picsum.photos/1200/400?random=3');"></div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Shop by Category</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <!-- Category Card 1 -->
                <div class="col">
                    <div class="card category-card h-100">
                        <img src="https://picsum.photos/300/200?random=4" class="card-img-top" alt="Electronics">
                        <div class="card-body">
                            <h5 class="card-title">Electronics</h5>
                            <p class="card-text">$199.99</p>
                            <a href="/category/electronics" class="btn">View Details</a>
                        </div>
                    </div>
                </div>
                <!-- Category Card 2 -->
                <div class="col">
                    <div class="card category-card h-100">
                        <img src="https://picsum.photos/300/200?random=5" class="card-img-top" alt="Fashion">
                        <div class="card-body">
                            <h5 class="card-title">Fashion</h5>
                            <p class="card-text">$49.99</p>
                            <a href="/category/fashion" class="btn">View Details</a>
                        </div>
                    </div>
                </div>
                <!-- Category Card 3 -->
                <div class="col">
                    <div class="card category-card h-100">
                        <img src="https://picsum.photos/300/200?random=6" class="card-img-top" alt="Home & Living">
                        <div class="card-body">
                            <h5 class="card-title">Home & Living</h5>
                            <p class="card-text">$79.99</p>
                            <a href="/category/home" class="btn">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>About Us</h5>
                    <p>E-Shop is your one-stop destination for quality products.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="/about">About</a></li>
                        <li><a href="/contact">Contact</a></ _

System: li>
                        <li><a href="/terms">Terms & Conditions</a></li>
                        <li><a href="/privacy">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <p>Email: support@eshop.com</p>
                    <p>Phone: (123) 456-7890</p>
                </div>
            </div>
            <p class="mt-3">© 2025 E-Shop. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS and Popper.js via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>
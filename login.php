<?php
session_start();
require_once 'dbConnection.php';

if(isset($_POST['submit']) && isset($_POST['email']) && isset($_POST['password']) ){
    $email = $_POST['email'];
    $enteredPass = $_POST['password'];
    if(empty($enteredPass) || empty($email) ){
        echo "email or password cannot be empty";
        exit();
    }
    if (!filter_var($email,FILTER_VALIDATE_EMAIL)){
        echo 'invalidate email';
        exit();
    }
    try{
        $query = $pdo->prepare('SELECT * FROM users WHERE user_email =:email');
        $query->bindValue(':email',$email);
        $query->execute();
        $row = $query->fetch();
        if($row){
            $hashPass = $row['user_password'];
            if(password_verify($enteredPass,$hashPass)){
                $_SESSION['id'] = $row['id']; 
                $_SESSION['name'] = $row['name_of_user'];
                header('Location:index.php');
                exit();
            }else{
                echo 'password is not correct';
            }
        }else{
            echo"this is email or password is incorrect";
        }
    }catch(PDOException $e){
        die("there is the error".$e->getMessage());
    }
    
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black flex items-center justify-center p-4 md:p-8">
    
    <div class="w-full max-w-6xl bg-black rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row" style="box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 80px rgba(85, 88, 121, 0.3);">
        
        <!-- Left Side - Image with Overlay Text (Hidden on mobile) -->
        <div class="hidden md:block md:w-1/2 relative bg-cover bg-center" style="background-image: url('public/images/loginPage.png'); min-height: 600px;">
            
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full md:w-1/2 bg-black relative overflow-hidden flex items-center justify-center p-6 md:p-10">
            <!-- Blurred Circle Top-Right -->
            <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full blur-3xl opacity-50" style="background-color: #555879;"></div>
            
            <!-- Blurred Circle Bottom-Left -->
            <div class="absolute -bottom-20 -left-20 w-80 h-80 rounded-full blur-3xl opacity-50" style="background-color: #555879;"></div>

            <!-- Form Container -->
            <div class="relative z-10 w-full max-w-md">
                <div class="mb-8">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">Welcome Back</h2>
                    <p class="text-gray-400 text-sm">Sign in to continue shopping</p>
                </div>

                <!-- Google Sign In Button -->
                <a href="auth/google_auth.php" class="flex items-center justify-center gap-3 w-full py-3 px-4 rounded-xl font-medium text-gray-700 border-2 border-gray-600 bg-white hover:bg-gray-50 transition-all mb-6">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M19.9895 10.1871C19.9895 9.36767 19.9214 8.76973 19.7742 8.14966H10.1992V11.848H15.8195C15.7062 12.7671 15.0943 14.1512 13.7346 15.0813L13.7155 15.2051L16.7429 17.4969L16.9527 17.5174C18.879 15.7789 19.9895 13.221 19.9895 10.1871Z" fill="#4285F4"/>
                        <path d="M10.1993 19.9313C12.9527 19.9313 15.2643 19.0454 16.9527 17.5174L13.7346 15.0813C12.8734 15.6682 11.7176 16.0779 10.1993 16.0779C7.50243 16.0779 5.21352 14.3395 4.39759 11.9366L4.27799 11.9466L1.13003 14.3273L1.08887 14.4391C2.76588 17.6945 6.21061 19.9313 10.1993 19.9313Z" fill="#34A853"/>
                        <path d="M4.39748 11.9366C4.18219 11.3166 4.05759 10.6521 4.05759 9.96565C4.05759 9.27909 4.18219 8.61473 4.38615 7.99466L4.38045 7.8626L1.19304 5.44366L1.08875 5.49214C0.397576 6.84305 0.000976562 8.36008 0.000976562 9.96565C0.000976562 11.5712 0.397576 13.0882 1.08875 14.4391L4.39748 11.9366Z" fill="#FBBC05"/>
                        <path d="M10.1993 3.85336C12.1142 3.85336 13.406 4.66168 14.1425 5.33717L17.0207 2.59107C15.253 0.985496 12.9527 0 10.1993 0C6.2106 0 2.76588 2.23672 1.08887 5.49214L4.38626 7.99466C5.21352 5.59183 7.50242 3.85336 10.1993 3.85336Z" fill="#EB4335"/>
                    </svg>
                    Continue with Google
                </a>

                <div class="flex items-center gap-4 mb-6">
                    <div class="flex-1 border-t border-gray-700"></div>
                    <span class="text-sm text-gray-500">or</span>
                    <div class="flex-1 border-t border-gray-700"></div>
                </div>

                <form action="" method="POST" class="space-y-5">
                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                            Email Address
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email"
                            placeholder="your.email@example.com"
                            class="w-full px-4 py-3 rounded-xl bg-gray-900 bg-opacity-50 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all"
                            style="--tw-ring-color: #555879;"
                            required
                        >
                    </div>

                    <!-- Password Input with Toggle -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password"
                                placeholder="Enter password"
                                class="w-full px-4 py-3 rounded-xl bg-gray-900 bg-opacity-50 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all pr-12"
                                style="--tw-ring-color: #555879;"
                                required
                            >
                            <button 
                                type="button" 
                                id="togglePassword" 
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-300 focus:outline-none transition-colors"
                            >
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eyeOffIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded cursor-pointer" style="accent-color: #555879;">
                            <span class="ml-2 text-sm text-gray-400">Remember Me</span>
                        </label>
                        <a href="#" class="text-sm hover:underline transition-all duration-300" style="color: #98A1BC;">Forgot Password?</a>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        name="submit"
                        class="w-full py-3 rounded-xl font-semibold text-white shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300"
                        style="background-color: #555879;"
                    >
                        Login
                    </button>

                    <!-- Sign Up Link -->
                    <p class="text-center text-sm text-gray-400 mt-4">
                        Don't have an account? 
                        <a href="#" class="font-semibold hover:underline" style="color: #98A1BC;">Sign up</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Toggle password visibility
            $('#togglePassword').click(function() {
                const passwordInput = $('#password');
                const eyeIcon = $('#eyeIcon');
                const eyeOffIcon = $('#eyeOffIcon');
                
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    eyeIcon.addClass('hidden');
                    eyeOffIcon.removeClass('hidden');
                } else {
                    passwordInput.attr('type', 'password');
                    eyeIcon.removeClass('hidden');
                    eyeOffIcon.addClass('hidden');
                }
            });

            // Add smooth focus effects
            $('input[type="email"], input[type="password"]').focus(function() {
                $(this).css('border-color', '#555879');
            }).blur(function() {
                if (!$(this).val()) {
                    $(this).css('border-color', '#374151');
                }
            });
        });
    </script>
</body>
</html>
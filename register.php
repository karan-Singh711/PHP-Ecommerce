<?php
require_once('dbConnection.php');


if(isset($_POST['submit']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phno']) && isset($_POST['password'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phno = $_POST['phno'];
    $password = $_POST['password'];
    $hashPass = password_hash($password,PASSWORD_DEFAULT);  
    try{
      $query2 = $pdo->prepare('SELECT * FROM users WHERE user_email = :email');
      $query2->bindValue(':email',$email);
      $query2->execute();
      $row = $query2->fetch();
      
      if($row){
        echo 'this is already exist';
      }else{
        $query = $pdo->prepare('INSERT INTO users(name_of_user,user_email,user_password,phone_no,role) 
        VALUES (:name,:email,:password,:phone_no,:role)');
        $query->bindValue(":name",$name);
        $query->bindValue(":email",$email);
        $query->bindValue(":password",$hashPass);
        $query->bindValue(":phone_no",$phno);
        $query->bindValue(":role",'user');

        $query->execute();
      }
        
    }catch(PDOException $e){
        die('registration failed'. $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="h-screen overflow-hidden">
    <div class="flex flex-col md:flex-row h-full">
        <!-- Left Side - Image with Overlay Text (Hidden on mobile) -->
       <div class="hidden md:block md:w-1/2 h-full relative bg-cover bg-center" style="background-image: url('public/images/background2.png');">
            <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col items-center justify-center text-center px-10">
                <h1 class="text-5xl lg:text-6xl font-extrabold text-white mb-5 drop-shadow-lg leading-tight">
                Welcome
                </h1>
                <p class="text-lg text-gray-200 max-w-lg mb-4">
                Step into your personal shopping space where every product is handpicked for your style, and every deal feels made just for you.
                </p>
                <p class="text-base text-gray-300 max-w-md">
                Sign in and continue exploring the collections you love, or discover new arrivals trending right now.
                </p>
            </div>
     </div>


        <!-- Right Side - Signup Form -->
        <div class="w-full md:w-1/2 h-full bg-black relative overflow-hidden flex items-center justify-center p-4 md:p-8">
            <!-- Blurred Circle Top-Left -->
            <div class="absolute -top-20 -left-20 w-72 h-72 rounded-full blur-3xl opacity-50" style="background-color: #555879;"></div>
            
            <!-- Blurred Circle Bottom-Right -->
            <div class="absolute -bottom-20 -right-20 w-80 h-80 rounded-full blur-3xl opacity-50" style="background-color: #555879;"></div>

            <!-- Form Container -->
            <div class="relative z-10 w-full max-w-md">
                <div class="mb-8">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">Create Account</h2>
                    <p class="text-gray-400 text-sm">Join us today and get started</p>
                </div>

                <!-- Google Sign Up Button -->
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

                <form action="" method="post" class="space-y-5">
                    <!-- Name Input -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            Name
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name"
                            placeholder="Enter your name"
                            class="w-full px-4 py-3 rounded-xl bg-gray-900 bg-opacity-50 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all"
                            style="--tw-ring-color: #555879;"
                        >
                    </div>

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
                        >
                    </div>

                    <!-- Phone Number Input -->
                    <div>
                        <label for="number" class="block text-sm font-medium text-gray-300 mb-2">
                            Phone Number
                        </label>
                        <input 
                            type="tel" 
                            id="number" 
                            name="phno"
                            placeholder="Enter your number"
                            class="w-full px-4 py-3 rounded-xl bg-gray-900 bg-opacity-50 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all"
                            style="--tw-ring-color: #555879;"
                        >
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                            Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            placeholder="Enter password"
                            class="w-full px-4 py-3 rounded-xl bg-gray-900 bg-opacity-50 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all"
                            style="--tw-ring-color: #555879;"
                        >
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        name="submit"
                        class="w-full py-3 rounded-xl font-semibold text-white shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300"
                        style="background-color: #555879;"
                    >
                        Register
                    </button>

                    <!-- Sign In Link -->
                    <p class="text-center text-sm text-gray-400 mt-4">
                        Already have an account? 
                        <a href="#" class="font-semibold hover:underline" style="color: #555879;">Sign in</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            console.log('ready')
            $('form').on('submit',function(e){
                
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                let isEmpty = false 
                let isValidEmail = true;
          
                $('.error-text').remove()
                console.log('submit')
                $(this).find('input').each(function(){
                    let value = $(this).val().trim();
                    let emailInput = $(this).attr('type') == 'email'
                    if(value==''){
                        isEmpty = true
                        $(this).after('<div class="text-red-500 text-sm mt-1 error-text">This field cannot be empty</div>');
                    }else if (emailInput && !emailRegex.test(value)){
                        isValidEmail = false;
                        console.log('denied')
                        $(this).after('<div class="text-red-500 text-sm mt-1 error-text">Email is not valid</div>');
                    }
                })
                if(isEmpty || !isValidEmail){
                    e.preventDefault();
                }
            })
        })
    </script>
</body>
</html>
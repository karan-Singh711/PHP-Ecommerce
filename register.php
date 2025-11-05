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
    <title>Register - Modern Design</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        .glass-card {
            background: rgba(228, 224, 224, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .bg-image {
            background-image: url('public/images/background2.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        input::placeholder {
            color: #98A1BC;
            opacity: 0.6;
        }
        
        .btn-hover {
            transition: all 0.3s ease;
        }
        
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(85, 88, 121, 0.3);
        }
    </style>
</head>
<body class="min-h-screen bg-image flex items-center justify-center p-4">
    <div class="glass-card rounded-3xl shadow-2xl w-full max-w-5xl p-8 md:p-12">
        <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-center">
            <!-- Left Side - What We Do -->
            <div class="space-y-4">
                <h2 class="text-3xl md:text-4xl font-bold" style="color: #555879;">
                    Welcome to Our Platform
                </h2>
                <p class="text-base md:text-lg leading-relaxed" style="color: black;">
                    Join our community and experience seamless collaboration. We provide innovative solutions 
                    that empower teams to work smarter, connect better, and achieve more together. 
                    Start your journey with us today.
                </p>
            </div>
            
            <!-- Right Side - Registration Form -->
            <div class="space-y-6">
                <div class="text-center md:text-left">
                    <h3 class="text-2xl font-semibold mb-2" style="color: #555879;">Create Account</h3>
                    <p class="text-sm" style="color: #98A1BC;">Fill in your details to get started</p>
                </div>
                
                <form action="" method="post" class="space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2" style="color: #555879;">
                            Name
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name"
                            placeholder="Enter your name"
                            class="w-full px-4 py-3 rounded-xl bg-white bg-opacity-50 border border-white border-opacity-30 focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all"
                            style="color: #555879; --tw-ring-color: #555879;"
                        >
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium mb-2" style="color: #555879;">
                            Email Address
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email"
                            placeholder="your.email@example.com"
                            class="w-full px-4 py-3 rounded-xl bg-white bg-opacity-50 border border-white border-opacity-30 focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all"
                            style="color: #555879; --tw-ring-color: #555879;"
                        >
                    </div>
                    
                    <div>
                        <label for="number" class="block text-sm font-medium mb-2" style="color: #555879;">
                            Phone Number
                        </label>
                        <input 
                            type="tel" 
                            id="number" 
                            name="phno"
                            placeholder="Enter your number"
                            class="w-full px-4 py-3 rounded-xl bg-white bg-opacity-50 border border-white border-opacity-30 focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all"
                            style="color: #555879; --tw-ring-color: #555879;"
                        >
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium mb-2" style="color: #555879;">
                            Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            placeholder="Enter password"
                            class="w-full px-4 py-3 rounded-xl bg-white bg-opacity-50 border border-white border-opacity-30 focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all"
                            style="color: #555879; --tw-ring-color: #555879;"
                        >
                    </div>
                    
                    <button 
                        type="submit"
                        name="submit"
                        class="w-full py-3 rounded-xl font-semibold text-white shadow-lg btn-hover"
                        style="background-color: #555879;"
                    >
                        Register
                    </button>
                    
                    <p class="text-center text-sm" style="color: #98A1BC;">
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
<?php
require_once('dbConnection.php');


if(isset($_POST['submit']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phno']) && isset($_POST['password'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phno = $_POST['phno'];
    $password = $_POST['password'];
    $hashPass = password_hash($password,PASSWORD_DEFAULT);
    

    echo $name . $email . $phno . $hashPass ;
    
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
        echo "done";
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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #a3b1d3; /* light bluish background */
    }
    .header, .footer {
      background-color: #e0e0e0;
    }
    .register-box {
      background-color: #444566; /* dark blue box */
      color: white;
    }
    label {
      color: white;
    }
  </style>
</head>
<body class="d-flex flex-column min-vh-100">

  <!-- Header -->
  <header class="header text-center py-3">
    <h2>header</h2>
  </header>

  <!-- Main Content -->
  <main class="flex-fill d-flex justify-content-center align-items-center">
    <div class="register-box p-4 rounded shadow" style="width: 100%; max-width: 500px;">
      <h4 class="text-center mb-4">Register</h4>
      <form action = '' method ='post'>

        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" id="name" placeholder="Enter your name" name = "name">
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" placeholder="Enter your email" name = "email">
        </div>
        <div class="mb-3">
          <label for="number" class="form-label">Phone Number</label>
          <input type="tel" class="form-control" id="number" placeholder="Enter your number" name = "phno">
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" placeholder="Enter password" name = "password">
        </div>
        <button type="submit" class="btn btn-light w-100 mt-2" name ="submit">Register</button>
      </form>
    </div>
  </main>

  <!-- Footer -->
  <footer class="footer text-center py-3">
    <p class="mb-0">footer</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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
            $(this).after('<div class="text-danger error-text">This field cannot be empty</div>');
          }else if (emailInput && !emailRegex.test(value)){
            let isValidEmail = false;
            console.log('denied')
             $(this).after('<div class="text-danger ">Email is not valid</div>');
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

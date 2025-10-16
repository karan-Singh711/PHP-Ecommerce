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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #a0abc0; /* same light background */
    }
    .login-box {
      max-width: 400px;
      margin: 100px auto;
      background-color: #3b3d5a; /* same dark block */
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0px 4px 10px rgba(0,0,0,0.2);
      color: white;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2 class="text-center mb-4">Login</h2>
    <form method="POST" action="">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input  class="form-control" id="email" name="email" placeholder="Enter your email" >
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password"  >
      </div>
      <button type="submit" class="btn btn-light w-100" name ='submit'>Login</button>
    </form>
  </div>
</body>
</html>

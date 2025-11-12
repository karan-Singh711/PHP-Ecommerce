<?php
session_start();
require_once '../dbConnection.php';
require_once __DIR__ . '/../vendor/autoload.php';
 $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
 $dotenv->load();
if(!isset($_GET['code'])){
die('no code');
}
    $code = $_GET['code'];

 
$data = [
    'code'=>$code,
    'client_id' => $_ENV['CLIENT_ID'],
    'client_secret' => $_ENV['CLIENT_SECRET'],
    'redirect_uri' => $_ENV['REDIRECT_URL'],
    'grant_type'=>'authorization_code'
];
$token_url = 'https://oauth2.googleapis.com/token'; 
$ch = curl_init($token_url);
curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
$response = curl_exec($ch);
curl_close($ch);

$token_info = json_decode($response, true);

if(!$token_info['access_token']){
    die('no token');
}
$user_info = 'https://www.googleapis.com/oauth2/v2/userinfo';
$ch2 = curl_init($user_info);
curl_setopt($ch2, CURLOPT_HTTPHEADER,['Authorization: Bearer ' . $token_info['access_token']]);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER,true);
$user_info = json_decode(curl_exec($ch2),true);
curl_close($ch2);

print_r($user_info);

if(isset($user_info)){
    $google_id = $user_info['id'];
    $email = $user_info['email'];
    $name = $user_info['name'];

    try{
        $user_exist = $pdo->prepare('SELECT * FROM users WHERE google_id = :google_id OR user_email = :email');
        $user_exist->bindValue(':google_id',$google_id);
        $user_exist->bindValue(':email',$email);

        $user_exist->execute();
        $user = $user_exist->fetch();
        if($user){
            $_SESSION['id'] = $user['id'];
        }else{
            $insert_google_user = $pdo->prepare('INSERT INTO users(name_of_user,user_email,google_id,role)
            VALUES(:name,:email,:google_id,:role)');
            $insert_google_user->bindValue(':name',$name);
            $insert_google_user->bindValue(':email',$email);
            $insert_google_user->bindValue(':google_id',$google_id);
            $insert_google_user->bindValue(':role','user');
            $insert_google_user->execute();
            $_SESSION['id'] =$pdo->lastInsertId();
        }

        header('Location:../index.php');
    }catch(PDOException $e){
        die('error:'.$e->getMessage());
    }
}
?>
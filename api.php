<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'dbConnection.php';
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['quantity']) && isset($_POST['productId']) && isset($_SESSION['id']) && isset($_POST['action']) && $_POST['action'] === 'addedToCart' ){
 $quantity = $_POST['quantity']; 
 $productId = $_POST['productId'];
 $userId = $_SESSION['id'];

 try{
    $query2 = $pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id");
    $query2->bindValue(":user_id",$userId);
    $query2->bindValue(":product_id",$productId);
    $query2->execute();
      if($row = $query2->fetch()){
         echo json_encode(["status"=>"error","message"=>"already exist"]);
      }else{
          $query = $pdo->prepare('INSERT INTO cart(user_id,product_id,quantity,created_at)
         VALUES (:user_id,:product_id,:quantity,:created)');
         $query->bindValue(':user_id',$userId);
         $query->bindValue(':product_id',$productId);
         $query->bindValue(':quantity',$quantity);
         $query->bindValue(':created',date('Y-m-d H:i:s'));

         $query->execute();

         echo json_encode(["status"=>"success","message"=>"added to cart"]);
      }
   
 }catch(PDOException $e){
    echo json_encode(["status"=>"error","message"=>$e->getMessage()]);
 }
}


// update quantity in db from the cart page
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['product_id']) && isset($_POST['Newquantity'] )){
   $newQuantity =$_POST['Newquantity'];
   $product_id =$_POST['product_id'];
   $userId =$_SESSION['id'];
   
   try{
      $query = $pdo->prepare("UPDATE cart SET quantity = :newQuantity WHERE product_id = :product_id AND user_id = :user_id ");
      $query->bindValue(":newQuantity" , $newQuantity);
      $query->bindValue(":product_id" , $product_id);
      $query->bindValue(":user_id" , $userId);
      $query->execute();
      echo json_encode(["status"=>"success","message"=>"done quantity updated"]);
      
   }catch(PDOException $e){
      echo json_encode(["status" => "error","message"=>"update fail"]);
   }
}
// remove item from the cart
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST["productId"]) && isset($_SESSION['id'])  && isset($_POST['action'])){
   $action = $_POST['action'];
   $productId = $_POST["productId"];
   $user_id = $_SESSION['id'];
   if($action === "remove"){
      try{
      $query = $pdo->prepare("DELETE FROM cart WHERE product_id = :product_id AND user_id = :userId");
      $query->bindValue(":product_id",$productId);
      $query->bindValue(":userId",$user_id);
      $query->execute();
      echo json_encode(["status"=>"success","message"=>"delete from cart"]);
   }catch(PDOException $e){
       echo json_encode(["status"=>"error","message"=>"cant delete from cart"]);
   }
   }
   
}
// user details after order
if($_SERVER['REQUEST_METHOD']==='POST' 
&&!empty($_POST["email"]) 
&&!empty($_POST["Fname"]) 
&& !empty($_POST["Lname"]) 
&& !empty($_POST["Saddress"])
&& !empty($_POST["city"]) 
&& !empty($_POST["Pcode"]) 
&& !empty($_POST["phone"]) 
&& isset($_POST["total"]) 
&& isset($_POST["action"]) 
&& isset($_SESSION["id"] )
&& $_POST["action"] === "insert")  
   {
      $user_id = $_SESSION["id"];
      $email = $_POST["email"];
      $Fname = $_POST["Fname"];
      $Lname =$_POST["Lname"];
      $total =$_POST["total"];
      $Saddress = $_POST["Saddress"];
      $city =$_POST["city"];
      $Pcode =$_POST["Pcode"];
      $phone =$_POST["phone"];

      $formatTotal =floatval(preg_replace('/[^\d.]/','',$total)); 
      try{
         $query = $pdo->prepare("INSERT INTO orders(user_id,first_name,last_name,email,user_phone,user_address,status,total,payment_status,payment_method,created_at) 
         VALUES (:user_id,:first_name,:last_name,:email,:user_phone,:user_address,:status,:total,:payment_status,:payment_method,:created_at)");
         $query->bindValue(":user_id",$user_id);
         $query->bindValue(":first_name",$Fname);
         $query->bindValue(":last_name",$Lname);
         $query->bindValue(":email",$email);
         $query->bindValue(":user_phone",$phone);
         $query->bindValue(":user_address",$Saddress);
         $query->bindValue(":status","pending");
         $query->bindValue(":total",$formatTotal);
         $query->bindValue(":payment_status",'Pending');
         $query->bindValue(":payment_method","COD");
         $query->bindValue(":created_at",date('Y-m-d H:i:s'));
         $query->execute();
         $order_id = $pdo->lastInsertId();
      
         
      
         $productQuery = $pdo->prepare('SELECT c.quantity,p.name_of_product,p.price_of_product
          FROM cart c
          JOIN products p ON c.product_id = p.id
          WHERE c.user_id = :user_id');

          $productQuery->bindValue(':user_id',$user_id);
          $productQuery->execute();
         //  $rows =  $productQuery->fetch();
         $price=[];
         
         $line_items = [];
         while($row=$productQuery->fetch()){
            $line_items[] = [
               'price_data'=>[
                  'currency' => 'usd',
                  'product_data' =>[
                  'name'=>$row['name_of_product']
                 ],
                 'unit_amount'=>intval($row['price_of_product']*100),
               ],
               'quantity'=>$row['quantity']
            ];
           
         }
         //  echo json_encode(['success'=>'success','name'=>$name, 'qunatity'=>$quantity,'total' => $total]);
         
          \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
      $checkout = \Stripe\Checkout\Session::create([
         'payment_method_types'=>['card'],
            'line_items'=>$line_items,
         // 'line_items'=>[[
         //    'price_data'=>[
         //       'currency'=>'usd',
         //       'product_data'=>[
         //       'name'=>$name
         //       ],
         //       'unit_amount'=>$small,
         //    ],
         //    'quantity'=>$quantity
         // ]],
         'metadata'=>[
            'order_type'=>'cart'
         ],
         'mode'=>'payment',
         'success_url'=>'http://localhost/e-commerce/index.php?s_id={CHECKOUT_SESSION_ID}',
         'cancel_url'=>'http://localhost/e-commerce/checkout.php'
      ]);
      
      $queryupdate = $pdo->prepare('UPDATE orders SET stripe_checkoutID = :sessionId WHERE order_id = :order_id');
      $queryupdate->bindValue(':sessionId',$checkout->id);
      $queryupdate->bindValue(':order_id',$order_id);
      $queryupdate->execute();
      echo json_encode([
      'status' => 'success',
      'id' => $checkout->id,
      'url' => $checkout->url,
      'orderid'=>$order_id
      ]);
      exit();
      }catch(PDOException $e){
         echo json_encode(["status"=>"error","message"=>"data not added"]);
      }
      // try{
      //    $query = $pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id");
      //    $query->bindValue(":user_id",$user_id);
      //    $query->execute();
         
      //    while($row = $query->fetch()){
      //       $query2 = $pdo->prepare("INSERT INTO order_items(order_id,product_id,quantity)
      //       VALUES(:order_id,:product_id,:quantity)");
      //       $query2->bindValue(":order_id",$order_id);
      //       $query2->bindValue(":product_id",$row['product_id']);
      //       $query2->bindValue(":quantity",$row['quantity']);
      //       $query2->execute();
      //    }
      //    // $deleteQuery = $pdo->prepare("DELETE FROM cart WHERE user_id = :user_id");
      //    // $deleteQuery->bindValue(":user_id",$user_id);
      //    // $deleteQuery->execute();
      // }catch(PDOException $e){
      //    echo json_encode(["status"=>"error","message"=>$e ->getMessage()]);
      // }
   }else{
      if(isset($_POST["action"]) === "insert"){
         echo json_encode(["status"=>"error","message"=>"not exist"]);
      }
   }
// remove product from admin page
   if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['action']) && isset($_POST['productId']) && isset($_SESSION['id'])) {
      $Pid = $_POST['productId'];
      $user_id = $_SESSION['id'];
      if($_POST['action'] == 'deleteTable'){
         try{
              $productDeleteQuery = $pdo->prepare("DELETE FROM products WHERE id =:Pid AND added_by = :user_id");
              $productDeleteQuery->bindValue(":Pid",$Pid);
              $productDeleteQuery->bindValue(":user_id",$user_id);
              $productDeleteQuery->execute();
              echo "delete success ";
         }catch(PDOException $e){
            echo "error" . $e->getMessage();
            echo"not sucess";
         }
      }
   }
   // update table from the admin page
   if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']) && isset($_POST['price']) && isset($_POST['stocks']) && isset($_POST['description'])
    && isset($_POST['action']) && isset($_POST['Pid']) ){
     $action = $_POST['action'];
     
     if($action === 'edit'){
      try{
         if(isset($_FILES['image'])){
            $Productname = $_POST['name'];
            $Productprice = $_POST['price'];
            $Productstocks = $_POST['stocks'];
            $Productdescription = $_POST['description'];
            

            $Productid = $_POST['Pid'];
            $user_id = $_SESSION['id'];
            $sendFile = 'public/images/';
            $fileName = $_FILES['image']['name'] ;
            $images = $sendFile . time() . basename($fileName);
            move_uploaded_file($_FILES['image']['tmp_name'],$images);
            
            
            $Updatequery = $pdo->prepare('UPDATE products SET name_of_product = :Productname ,description_of_product = :Productdescription 
            ,price_of_product = :Productprice,image=:Productimage,stocks=:Productstocks WHERE added_by = :user_id AND id = :Pid');
            $Updatequery->bindValue(':Productname',$Productname); 
            $Updatequery->bindValue(':Productdescription',$Productdescription);
            $Updatequery->bindValue(':Productprice',$Productprice);
            $Updatequery->bindValue(':Productimage',$images);
            $Updatequery->bindValue(':Productstocks',$Productstocks);
            $Updatequery->bindValue(':user_id',$user_id);
            $Updatequery->bindValue(':Pid',$Productid);

            $Updatequery->execute();
            echo json_encode(["status"=>"success","message"=> "update success with image",
            "row" => ["id" => $Productid, 
                     "name" => $Productname,
                     "description" => $Productdescription,
                     "stocks" => $Productstocks,
                     "price" => $Productprice,
                     "image" => $images,]]);
         }else{
            $Productname = $_POST['name'];
            $Productprice = $_POST['price'];
            $Productstocks = $_POST['stocks'];
            $Productdescription = $_POST['description'];
            

            $Productid = $_POST['Pid'];
            $user_id = $_SESSION['id'];
            
            
            
            $Updatequery = $pdo->prepare('UPDATE products SET name_of_product = :Productname ,description_of_product = :Productdescription 
            ,price_of_product = :Productprice,stocks=:Productstocks WHERE added_by = :user_id AND id = :Pid');
            $Updatequery->bindValue(':Productname',$Productname); 
            $Updatequery->bindValue(':Productdescription',$Productdescription);
            $Updatequery->bindValue(':Productprice',$Productprice);
            
            $Updatequery->bindValue(':Productstocks',$Productstocks);
            $Updatequery->bindValue(':user_id',$user_id);
            $Updatequery->bindValue(':Pid',$Productid);

            $Updatequery->execute();
            
           
            echo json_encode(["status"=>"success","message"=> "update success without image" ,
            "row" => ["id" => $Productid, 
                     "name" => $Productname,
                     "description" => $Productdescription,
                     "stocks" => $Productstocks,
                     "price" => $Productprice,]]);                                                      
         }
         

        
      }catch(PDOException $e){
         echo "error".$e->getMessage();
      }
     }
   }

   if(isset($_POST['order_id']) && isset($_POST['user_id']) && $_POST['action'] === 'orderDetails'){
       
      try{
         $admin_id = $_SESSION['id'];
         $user_id = $_POST['user_id'];
         $order_id = $_POST['order_id'];
         $orderDquery = $pdo->prepare('SELECT o.first_name , o.last_name , o.email , o.user_phone,o.created_at,o.payment_method,o.status,p.price_of_product,p.name_of_product,i.quantity
         FROM  orders o
         JOIN order_items i ON i.order_id = o.order_id
         JOIN products p ON i.product_id = p.id 
         WHERE o.order_id = :order_id AND p.added_by = :admin_id');
         $orderDquery->bindValue(':order_id',$order_id);
         $orderDquery->bindValue(':admin_id',$admin_id);
         $orderDquery->execute();
         $row = $orderDquery->fetchAll();
            // $date = $row['created_at'];
            // $formatted = date('M j, Y',strtotime($date));
         echo json_encode([
         'response'=>"sucess","message" => "message",
         "data"=>$row
            // "Fname"=>$row['first_name'],
            // "Lname"=>$row['last_name'],
            // "email"=>$row['email'],
            // "phone"=>$row['user_phone'],
            // "date" => $formatted,
            // "payment"=>$row['payment_method'],
            // "status"=>$row['status'],
            // "product_price"=>$row['price_of_product'],
          ]);
      }
      catch(PDOException $e){
         die("error:".$e->getMessage());
      }
   }
   if(isset($_POST['order_id']) && isset($_POST['status']) && $_POST['action'] === 'updateStatus'){
      
      try{
         // print_r($_POST);
         $order_id = $_POST['order_id'];
         $status = $_POST['status'];
         $statusQuery = $pdo->prepare('UPDATE orders SET status = :status WHERE order_id = :order_id');
         $statusQuery->bindValue(':status',$status);
         $statusQuery->bindValue(':order_id',$order_id);
         $statusQuery->execute();
         echo json_encode(["response"=>"success","message"=>"updated status","status"=>$status,"orderId"=>$order_id]);
      }catch(PDOException $e){
         die("error:".$e->getMessage());
      }
   }
   if(isset($_POST['action']) && $_POST['action'] == 'totalRevenue'){
       
      try {
         $user_id = $_SESSION['id'];
         $revenueQuery = $pdo->prepare('SELECT YEAR(o.created_at) AS yearOfOrder,
         MONTH(o.created_at) AS monthOfOrder,
         SUM(o.total) AS total_revenue
         FROM orders o 
         JOIN order_items i ON o.order_id = i.order_id
         JOIN products p ON p.id = i.product_id 
         WHERE p.added_by = :user_id
         GROUP BY YEAR(o.created_at),MONTH(o.created_at)
         ORDER BY yearOfOrder, monthOfOrder') ;
         $revenueQuery->bindValue(':user_id',$user_id);
         $revenueQuery->execute();
         $label = [];
         $data = [];
         while($row = $revenueQuery->fetch()){
            $label[] = date('M',mktime(0,0,0,$row['monthOfOrder'],1));
            $data[] =floatval($row['total_revenue']);
         };
         echo json_encode(['response'=>'success','message'=>'chart added','label'=>$label,'data'=>$data]);
            
            
      } catch (PDOException $e) {
         echo json_encode(['response'=>'error','message'=>$e->getMessage()]);
      }
   }
   // if(isset($_POST['maxValue']) && isset($_POST['minValue']) && isset($_POST['productName']) && $_POST['action'] === 'priceFilter'){
   //    $productName = $_POST['productName'];
   //    $minValue =$_POST['minValue'];
   //    $maxValue = $_POST['maxValue'];
   //    try{
   //       $priceFilterQuery = $pdo->prepare('SELECT * 
   //       FROM products 
   //       WHERE name_of_product = :productname 
   //       AND price_of_product BETWEEN :lowprice AND :maxprice');
   //       $priceFilterQuery->bindValue(':productname',$productName);
   //       $priceFilterQuery->bindValue(':lowprice',$minValue);
   //       $priceFilterQuery->bindValue(':maxprice',$maxValue);
   //       $priceFilterQuery->execute();
   //       echo json_encode(["response"=>"success",'message'=>'filter applied']);
   //    }catch(PDOException $e){
   //       die('error:'.$e->getMessage());
   //    }
   // }


   // buy now button click
   if(isset($_POST['firstName']) && 
      isset($_POST['lastName']) &&
      isset($_POST['phone']) &&
      isset($_POST['email']) &&
      isset($_POST['address']) &&
      isset($_POST['productId']) && 
      isset($_POST['quantity']) &&
      isset($_POST['action']) && 
      $_POST['action'] === 'buyNowBtnClick' ){
      try{
         $user_id =$_SESSION['id'];
         $Fname = $_POST['firstName'];
         $Lname = $_POST['lastName'];
         $phone = $_POST['phone'];
         $email = $_POST['email'];
         $address = $_POST['address'];
         $product_id = $_POST['productId'];
         $quantity = $_POST['quantity'];

         $totalQuery = $pdo->prepare('SELECT * FROM products WHERE id = :product_id');
         $totalQuery->bindValue(':product_id',$product_id,PDO::PARAM_INT);
         $totalQuery->execute();
         $product_row = $totalQuery->fetch();
         $total = floatval($product_row['price_of_product'] * $quantity);
         $unit = intval($total * 100);
         $buynowQuery = $pdo->prepare('INSERT INTO orders(user_id,first_name,last_name,email,user_phone,user_address,status,total,payment_status,payment_method,created_at)
         VALUES(:user_id,:Fname,:Lname,:email,:phone,:address,:status,:total,:Pstatus,:Pmethod,:created_at)'); 
         $buynowQuery->bindValue(':user_id',$user_id);
         $buynowQuery->bindValue(':Fname',$Fname);
         $buynowQuery->bindValue(':Lname',$Lname);
         $buynowQuery->bindValue(':phone',$phone);
         $buynowQuery->bindValue(':email',$email);
         $buynowQuery->bindValue(':address',$address);
         $buynowQuery->bindValue(':status','Pending');
         $buynowQuery->bindValue(':total',$total);
         $buynowQuery->bindValue(':Pstatus','Pending');
         $buynowQuery->bindValue(':Pmethod','COD');
         $buynowQuery->bindValue(':created_at',date('Y-m-d H:i:s'));
         $buynowQuery->execute();
         $order_id = $pdo->lastInsertId();
         try{
            \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
         $buyNowCheckout = \Stripe\Checkout\Session::Create([
            'payment_method_types'=>['card'],
            'line_items'=>[[
              'price_data'=>[
                  'currency'=>'usd',
                  'product_data'=>[
                     'name'=>$product_row['name_of_product']
                  ],
                  'unit_amount'=>$unit
              ],
              'quantity'=> $_POST['quantity']
            ]],
            'metadata'=>[
               'order_type'=>'buy_now',
               'product_id'=>$product_id,
               'quantity' =>$quantity
            ],
            'mode'=>'payment',
            'success_url'=>'http://localhost/e-commerce/index.php?s_id={CHECKOUT_SESSION_ID}',
            'cancel_url'=>'http://localhost/e-commerce/buyCheckout.php'
         ]);
         
         $updateStripeIdQuery = $pdo->prepare('UPDATE orders SET stripe_checkoutID = :sid WHERE order_id = :oid');
         $updateStripeIdQuery->bindValue(':oid',$order_id);
         $updateStripeIdQuery->bindValue(':sid',$buyNowCheckout->id);
         $updateStripeIdQuery->execute();

          echo json_encode(['success'=>true,'message'=>'success','url'=>$buyNowCheckout->url]);
         }catch(\Stripe\Exception\ApiErrorException $e){
             echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
         }
      }catch(PDOException $e ){
         echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
      }

      }
?>
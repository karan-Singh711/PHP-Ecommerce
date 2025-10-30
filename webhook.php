<?php
    session_start();
    require_once 'dbConnection.php';
    require 'vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
        $webhookKey = $_ENV['STRIPE_WEBHOOK'];  
        $rawBody = @file_get_contents('php://input');
        $signature = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        file_put_contents('stripe_webhook.txt',date('Y-m-d H:i:s').'|'.$rawBody.PHP_EOL,FILE_APPEND);
        $event = null;
        try{
            $event = \Stripe\Webhook::constructEvent(
                $rawBody,$signature,$webhookKey
            );

        }
        catch(UnexpectedValueException $e){
            http_response_code(400);
            exit();
        }catch(\Stripe\Exception\SignatureVerificationException $e){
            http_response_code(400);
            exit();
        }
        if($event->type ==='checkout.session.completed'){
            $order_type = $event->data->object->metadata->order_type;
            $pid = $event->data->object->metadata->product_id;
            $quantity = $event->data->object->metadata->quantity;
            $session_id = $event->data->object->id;
            if($order_type === 'buy_now'){
                try{
                    // select row where sessionid is same
                    $selectOrderIdQuery = $pdo->prepare('SELECT * FROM orders WHERE stripe_checkoutID =:sid');
                    $selectOrderIdQuery->bindValue(':sid',$session_id);
                    $selectOrderIdQuery->execute();
                    $rowOfOrders = $selectOrderIdQuery->fetch();
                    //update orders  
                    $updateSidQuery = $pdo->prepare('UPDATE orders SET payment_status = "Paid" , payment_method = "Card" , status ="Processing" WHERE stripe_checkoutID = :sid');
                    $updateSidQuery->bindValue(':sid',$session_id);
                    $updateSidQuery->execute();

                    // insert in order-items 
                    $insertOIQuery = $pdo->prepare('INSERT INTO order_items(order_id,product_id,quantity) VALUES(:oid,:pid,:quantity)');
                    $insertOIQuery->bindValue(':oid',$rowOfOrders['order_id']);
                    $insertOIQuery->bindValue(':pid',$pid);
                    $insertOIQuery->bindValue(':quantity',$quantity);
                    $insertOIQuery->execute();
                }catch(PDOException $e){
                    die('error'.$e->getMessage());
                }
            }
        }
        if($event->type === 'checkout.session.completed'){
           $session_id = $event->data->object->id ; 
           $order_type = $event->data->object->metadata->order_type;
            if($order_type === 'cart'){
                try{
                $paymentStatus = $pdo->prepare('UPDATE orders SET payment_status = "Paid" , status = "Processing" , payment_method = "CARD" WHERE stripe_checkoutID = :session_id');
                $paymentStatus->bindValue(':session_id',$session_id);
                $paymentStatus->execute();

                $orderQuery =$pdo->prepare('SELECT * FROM orders WHERE stripe_checkoutID = :session_id AND payment_status = "paid"');
                $orderQuery->bindValue(':session_id',$session_id);
                $orderQuery->execute();
                $order = $orderQuery->fetch();

                $cartQuery = $pdo->prepare('SELECT * FROM cart WHERE user_id = :user_id');
                $cartQuery->bindValue(':user_id',$order['user_id']);
                $cartQuery->execute();
                while($row =$cartQuery->fetch()){
                    $orderItemQuery = $pdo->prepare('INSERT INTO order_items(order_id,product_id,quantity) VALUES(:order_id , :product_id , :quantity)');
                    $orderItemQuery->bindValue(':order_id',$order['order_id']);
                    $orderItemQuery->bindValue(':product_id',$row['product_id']);
                    $orderItemQuery->bindValue(':quantity',$row['quantity']);
                    $orderItemQuery->execute();
                }
            }catch(PDOException $e){
                die('error'.$e->getMessage());
            }
            }
            
        }

        http_response_code(200); // ✅ Always respond OK to Stripe
?>
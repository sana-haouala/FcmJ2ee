<?php 
//importing required files 
require_once 'DbOperation.php';
require_once 'Firebase.php';
require_once 'Push.php'; 
 
$db = new DbOperation();
 
$response = array(); 
 
if($_SERVER['REQUEST_METHOD']=='GET'){ 
 //hecking the required params 
 if(isset($_GET['title']) and isset($_GET['client_id'])and isset($_GET['ref_demande'])){
 
 //creating a new push
 $push = null; 
 //get message
 $message = $db->getMessage($_GET['ref_demande']);
 //first check if the push has an image with it
 if(isset($_GET['image'])){
 $push = new Push(
 $_GET['title'],
 $message,
 $_GET['image']
 );
 }else{
 //if the push don't have an image give null in place of image
 $push = new Push(
 $_GET['title'],
 $message,
 null
 );
 }
 
 //getting the push from push object
 $mPushNotification = $push->getPush(); 
 
 //getting the token from database object 
 $devicetoken = $db->getTokenBySpecificData($_GET['client_id'],$_GET['ref_demande']);
 
 //creating firebase class object 
 $firebase = new Firebase(); 
 
 //sending push notification and displaying result 
 echo $firebase->send($devicetoken, $mPushNotification);
 }else{
 $response['error']=true;
 $response['message']='Parameters missing';
 }
}else{
 $response['error']=true;
 $response['message']='Invalid request';
}

//execute node with php
exec("\"C:/Program Files/nodejs/node.exe\" \"C:/Users/sana/sendNotif.js\"");

echo json_encode($response);
?>
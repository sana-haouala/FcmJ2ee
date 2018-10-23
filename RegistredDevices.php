<?php 
 require_once 'DbOperation.php';
 $response = array(); 
 
 // if($_SERVER['REQUEST_METHOD']=='POST'){
 
 $token = $_POST['token'];
 $client_id = $_POST['client_id'];
 $ref_demande = $_POST['ref_demande'];
 $etat = $_POST['etat'];
 $dateEtat = $_POST['dateEtat'];
 
 $db = new DbOperation(); 
 
 $result = $db->registerDevice($token,$client_id,$ref_demande,$etat);
 
 if($result == 0){
 $response['error'] = false; 
 $response['message'] = 'Device registered successfully';
 }elseif($result == 2){
 $response['error'] = true; 
 $response['message'] = 'Device already registered';
 }else{
 $response['error'] = true;
 $response['message']='Device not registered';
 }
 // else{
 // $response['error']=true;
 // $response['message']='Invalid Request...';
 // }


 
 echo json_encode($response);
  exec("\"C:/Program Files/nodejs/node.exe\" \"C:/Users/sana/sendNotif.js\"");
 ?>
<?php 
//importing required files 
require_once 'DbOperation.php';

$db = new DbOperation(); 
$response = array();

$etat = $_GET['etat'];
$ref = $_GET['ref'];

$etatChanged = $db->changeState($etat,$ref);
if ($etatChanged == false){
 $response['error'] = true;
 $response['message']= 'can\'t get etat'; 
}
else {
 $response['error']= false;
 $response['new state']= $etat; 
 //execute node with php
exec("\"C:/Program Files/nodejs/node.exe\" \"C:/Users/sana/sendNotif.js\"");
}
echo json_encode($response);
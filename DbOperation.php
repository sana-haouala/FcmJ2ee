<?php
 
class DbOperation
{
    //Database connection link
    private $con;
 
    //Class constructor
    function __construct()
    {
        //Getting the DbConnect.php file
        require_once dirname(__FILE__) . '/DbConnect.php';
 
        //Creating a DbConnect object to connect to the database
        $db = new DbConnect();
 
        //Initializing our connection link of this class
        //by calling the method connect of DbConnect class
        $this->con = $db->connect();
    }
     
    //storing token in database 
    public function registerDevice($token,$client_id,$ref_demande,$etat){
            $stmt = $this->con->prepare("INSERT INTO pushnotif(id, token, client_id, ref_demande, etat, dateEtat) VALUES (null,?,?,?,?, NOW()); ");
            $stmt->bind_param("ssss",$token,$client_id,$ref_demande,$etat);
            if($stmt->execute())
			{ return 0; //return 0 means success 
		    }
            return 1; //return 1 means failure
    }
 
    //getting all tokens to send push to all devices
    public function getAllTokens(){
        $stmt = $this->con->prepare("SELECT token FROM pushnotif");
        $stmt->execute(); 
        $result = $stmt->get_result();
        $tokens = array(); 
        while($token = $result->fetch_assoc()){
            array_push($tokens, $token['token']);
        }
        return $tokens; 
    }
 
    //getting a specified token to send push to selected device
    public function getTokenBySpecificData($client_id,$ref_demande){
        $stmt = $this->con->prepare("SELECT  distinct(token) FROM pushnotif WHERE client_id=? AND ref_demande=?");
        $stmt->bind_param("ss",$client_id,$ref_demande);
        $stmt->execute(); 
        $result = $stmt->get_result()->fetch_assoc();
        return array($result['token']);        
    }
 
    //getting all the registered devices from database 
    public function getAllDevices(){
        $stmt = $this->con->prepare("SELECT * FROM pushnotif");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result; 
    }
	public function changeState($etat,$ref){
		$stmt = $this->con->prepare("INSERT INTO pushnotif(id, token, client_id, ref_demande, etat, dateEtat) VALUES (null, 'dTEUW-QTR7Q:APA91bHufs4jW3kQvIt5_fMclIYLR295mbOFRXq5XlqckgLUiy9KwUlEC7sDHMyGBFZb3PJsMqzE_ZYkv8Miy43ikTeFZd3GegrUdDt7B-zGgBZ3kAjGBTyyYErZrlixUfJPBw3oJnhq', 217100, '$ref', ?, NOW())");
        $stmt->bind_param("s",$etat);
		if($stmt->execute()){
			//$etat = $stmt->get_result();
			return true;
		}
		else {
			return false;
		}
	}
	public function getMessage($ref_demande){
		$stmt = $this->con->prepare("select etat,dateEtat from pushnotif where dateEtat=(select max(dateEtat) from pushnotif where ref_demande=?)");
        $stmt->bind_param("s",$ref_demande);
        $stmt->execute(); 
        $result = $stmt->get_result()->fetch_assoc();
	return ('Votre offre est '.$result['etat'].' depuis '.$result['dateEtat']);        
	}
}
?>
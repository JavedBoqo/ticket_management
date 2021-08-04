<?php
class Users extends Database { 
	private $userTable = 'hd_users';
	private $dbConnect = false;
	public function __construct(){		
        $this->dbConnect = $this->dbConnect();
    }	
	public function isLoggedIn () {
		if(isset($_SESSION["userid"])) {
			return true; 			
		} else {
			return false;
		}
	}
	public function login(){
		$_SESSION['username_logged'] = "test";
		$_SESSION['active']="y";
		$_SESSION['loggedin']='y';
		header("location: index.php");

		// $errorMessage = '';
		// if(!empty($_POST["login"]) && $_POST["email"]!=''&& $_POST["password"]!='') {	
		// 	$email = $_POST['email'];
		// 	$password = $_POST['password'];
		// 	$sqlQuery = "SELECT * FROM ".$this->userTable." 
		// 		WHERE email='".$email."' AND password='".sha1($password)."'";
				
		// 	$resultSet = mysqli_query($this->dbConnect, $sqlQuery);
		// 	$isValidLogin = mysqli_num_rows($resultSet);	
		// 	if($isValidLogin){
		// 		$userDetails = mysqli_fetch_assoc($resultSet);
		// 		$_SESSION["userid"] = $userDetails['id'];
		// 		$_SESSION["user_name"] = $userDetails['nick_name'];
		// 		if($userDetails['user_group']) {
		// 			$_SESSION["admin"] = $userDetails['user_group'];
		// 		}
		// 		header("location: index.php"); 		
		// 	} else {		
		// 		$errorMessage = "Invalid login!";		 
		// 	}
		// } else if(!empty($_POST["login"])){
		// 	$errorMessage = "Enter Both user and password!";	
		// }
		// return $errorMessage; 		
	}
	public function getUserInfo() {
		if(!empty($_SESSION["userid"])) {
			$sqlQuery = "SELECT * FROM ".$this->userTable." 
				WHERE id ='".$_SESSION["userid"]."'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);		
			$userDetails = mysqli_fetch_assoc($result);
			return $userDetails;
		}		
	}
	public function getColoumn($id, $column) {     
        $sqlQuery = "SELECT * FROM ".$this->userTable." 
			WHERE id ='".$id."'";
		$result = mysqli_query($this->dbConnect, $sqlQuery);		
		$userDetails = mysqli_fetch_assoc($result);
		return $userDetails[$column];       
    }
}
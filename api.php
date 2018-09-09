<?php

$conn = new mysqli("localhost", "root", "", "weinc_user");
 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$out = array('error' => false, 'userName'=> false, 'email'=> false, 'password' => false, 'cPassword' => false);

$action = 'read';

if(isset($_GET['action'])){
	$action = $_GET['action'];
}

if($action == 'read'){
	$sql = "select * from `userdb`";
	$query = $conn->query($sql);
	$users = array();

	while($row = $query->fetch_assoc()){
		array_push($users, $row);
	}

	$out['users'] = $users;
}

if($action == 'register'){

	function check_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	$email = check_input($_POST['email']);
	$password = $conn->real_escape_string($_POST['password']);
	$hash = password_hash($password, PASSWORD_BCRYPT);
	$cPassword = check_input($_POST['cPassword']);
	$ethAddress = check_input($_POST['ethAddress']);
	$userName = check_input($_POST['userName']);

	if($userName==''){
		$out['userName'] = true;
		$out['message'] = "User Name is required";
	}

	elseif($email==''){
		$out['email'] = true;
		$out['message'] = "Email is required";
	}
	
	elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$out['email'] = true;
		$out['message'] = "Invalid Email Format";
	}

	elseif($ethAddress==''){
		$out['ethAddress'] = true;
		$out['message'] = "ETH Address is required";
	}

	elseif(strlen($ethAddress)!=42 || substr($ethAddress, 0, 2)!='0x' ){
		$out['ethAddress'] = true;
		$out['message'] = "Invalid ETH Address";
	}

	elseif($password==''){
		$out['password'] = true;
		$out['message'] = "Password is required";
	}

	elseif($cPassword==''){
		$out['cPassword'] = true;
		$out['message'] = "Confirm password is required";
	}

	elseif($cPassword!=$password){
		$out['password'] = true;
		$out['cPassword'] = true;
		$out['message'] = "Password not match";
	}

	else{
		$sqlE="select * from userdb where email='$email'";
		$queryE=$conn->query($sqlE);

		$sqlU="select * from userdb where userName='$userName'";
		$queryU=$conn->query($sqlU);

		$sqlA="select * from userdb where ethAddress='$ethAddress'";
		$queryA=$conn->query($sqlA);
		
		if($queryU->num_rows > 0){
			$out['userName'] = true;
			$out['message'] = "User name already exist";
		}

		elseif($queryE->num_rows > 0){
			$out['email'] = true;
			$out['message'] = "Email already exist";
		}		
			
		elseif($queryA->num_rows > 0){
			$out['ethAddress'] = true;
			$out['message'] = "ETH address already exist";
		}

		else{
			$sql = "INSERT INTO `userdb` (`userName`, `email`, `password`, `ethAddress`, `creationDate`) 
				VALUES ('$userName', '$email', '$hash', '$ethAddress', now())";
			$query = $conn->query($sql);

			if($query){
				$sql = "SELECT userId FROM userdb WHERE email = '$email'";
				$query = $conn->query($sql);
				$row = $query->fetch_array();
				$userIdPk = $row["userId"];
								
				$sql = "INSERT INTO `useracc` (`userIdPk`, `rpIdPk`) 
				VALUES ( $userIdPk , 1)";
				$query = $conn->query($sql);
				if($query){
					$out['message'] = "User Added Successfully";
				}
				else{
					$out['error'] = true;
					$out['message'] = "Error adding User";
				}
			}
			else{
				$out['error'] = true;
				$out['message'] = "Could not add User";
			}
		}
	}		
}


$conn->close();

header("Content-type: application/json");
echo json_encode($out);
die();


?>
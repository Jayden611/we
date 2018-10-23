<?php

$conn = new mysqli("localhost", "root", "", "weinc_user");
 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// $out = array('error' => false, 'userName'=> false, 'email'=> false, 'password' => false, 'cPassword' => false);

	function check_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
    $json = json_decode(file_get_contents("php://input"), true);
	$email = check_input($json['email']);
	$password = $conn->real_escape_string($json['password']);
	$hash = password_hash($password, PASSWORD_BCRYPT);
	$cPassword = check_input($json['cPassword']);
	$ethAddress = check_input($json['ethAddress']);
	$userName = check_input($json['userName']);

	if($userName==''){
        $out['meta'] = array('msg' => 'User Name is required','code' => 1);
	    $out['data'] = "";
	}

	else if($email==''){
        $out['meta'] = array('msg' => 'Email is required','code' => 1);
	    $out['data'] = "";
	}
    else if($ethAddress==''){
        $out['meta'] = array('msg' => 'ETH Address is required','code' => 1);
	    $out['data'] = "";
    }
    else if($password==''){
        $out['meta'] = array('msg' => 'Password is required','code' => 1);
	    $out['data'] = "";
	}

	else if($cPassword==''){
        $out['meta'] = array('msg' => 'Confirm password is required','code' => 1);
	    $out['data'] = "";
	}
	else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $out['meta'] = array('msg' => 'Invalid Email Format','code' => 1);
	    $out['data'] = "";
	}

	else if(strlen($ethAddress)!=42 || substr($ethAddress, 0, 2)!='0x' ){
        $out['meta'] = array('msg' => 'Invalid ETH Address','code' => 1);
	    $out['data'] = "";
	}
    else if(strlen($password) < 6){
        $out['meta'] = array('msg' => '6-12bit','code' => 1);
        $out['data'] = "";
    }
	else if($cPassword!=$password){
        $out['meta'] = array('msg' => 'Password not match','code' => 1);
	    $out['data'] = "";
	}

	else{
		$sqlE="select * from userdb where email='$email'";
		$queryE=$conn->query($sqlE);

		$sqlU="select * from userdb where userName='$userName'";
		$queryU=$conn->query($sqlU);

		$sqlA="select * from userdb where ethAddress='$ethAddress'";
		$queryA=$conn->query($sqlA);
		
		if($queryU->num_rows > 0){
            $out['meta'] = array('msg' => 'User name already exist','code' => 1);
	        $out['data'] = "";
		}

		elseif($queryE->num_rows > 0){
            $out['meta'] = array('msg' => 'Email already exist','code' => 1);
	        $out['data'] = "";
		}		
			
		elseif($queryA->num_rows > 0){
            $out['meta'] = array('msg' => 'ETH address already exist','code' => 1);
	        $out['data'] = "";
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
                    $out['meta'] = array('msg' => 'User Added Successfully','code' => 0);
                    $out['data'] = "";
				}
				else{
                    $out['meta'] = array('msg' => 'Error adding User','code' => 1);
                    $out['data'] = "";
				}
			}
			else{
                $out['meta'] = array('msg' => 'Could not add User','code' => 1);
                $out['data'] = "";
			}
		}
	}		


$conn->close();

header("Content-type: application/json");
echo json_encode($out);
die();


?>
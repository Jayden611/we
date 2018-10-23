<?php
$conn = new mysqli("localhost", "root", "", "weinc_user");
 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$json = json_decode(file_get_contents("php://input"), true);
$email = $json['email'];
$password = $json['password'];

if($email==''){
	$out['meta'] = array('msg' => 'Email is required','code' => 1);
	$out['data'] = "";
}
else if($password==''){
	$out['meta'] = array('msg' => 'Password is required','code' => 1);
	$out['data'] = "";
}
else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
	$out['meta'] = array('msg' => 'Invalid Email Format','code' => 1);
	$out['data'] = "";
}
else if(strlen($password) < 6){
	$out['meta'] = array('msg' => '6-12bit','code' => 1);
	$out['data'] = "";
}
else{
	$stmt = $conn->prepare('select * from userdb where email=?');
	$stmt ->bind_param('s', $email);
	$stmt -> execute();

	$query = $stmt->get_result();

	if($query->num_rows>0){
		$row=$query->fetch_array();	
		
		if (password_verify($password, $row['password'])){
			session_start();
			$_SESSION['user']=$row['userId'];
			$out['meta'] = array('msg' => 'Login Successful','code' => 0);
	        $out['data'] = "";
		}
		else{
			$out['meta'] = array('msg' => 'Login Failed','code' => 1);
	        $out['data'] = "";
		}
	}
	else{
		$out['meta'] = array('msg' => 'Login Failed. Email or password not correct','code' => 1);
		$out['data'] = "";
	}
}


	
$conn->close();

header("Content-type: application/json");
echo json_encode($out);
die();


?>
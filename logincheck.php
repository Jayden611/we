<?php
session_start();

$conn = new mysqli("localhost", "root", "", "weinc_user");
 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$out = array('error' => false);

$email = $conn->real_escape_string($_POST['email']);
$password = $conn->real_escape_string($_POST['password']);

if($email==''){
	$out['error'] = true;
	$out['message'] = "Email is required";
}
else if($password==''){
	$out['error'] = true;
	$out['message'] = "Password is required";
}
else{
	$stmt = $conn->prepare('select * from userdb where email=?');
	$stmt ->bind_param('s', $email);
	$stmt -> execute();

	$query = $stmt->get_result();

	if($query->num_rows>0){
		$row=$query->fetch_array();	
		
		if (password_verify($password, $row['password'])){
			$_SESSION['user']=$row['userId'];
			$out['message'] = "Login Successful";
		}
		else{
			$out['error'] = true;
			$out['message'] = "Login Failed.";
		}
	}
	else{
		$out['error'] = true;
		$out['message'] = "Login Failed. Email or password not correct";
	}
}


	
$conn->close();

header("Content-type: application/json");
echo json_encode($out);
die();


?>
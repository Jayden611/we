<?php
$conn = new mysqli("localhost", "root", "root", "weinc_user");
 
if ($conn->connect_error) {
    die("Connecti
    on failed: " . $conn->connect_error);
}
session_start();
if(isset($_SESSION['user'])){
    $userId = $_SESSION['user'];
    $sqlId="select * from `userdb` where userId='$userId'";
    $queryId=$conn->query($sqlId);
    while($row = $queryId->fetch_assoc()){
        $user = $row['userName'];
        $email = $row['email'];
    };
    if($queryId->num_rows > 0){
        $out['meta'] = array('msg' => 'login success','code' => 0);
        $out['data'] = array('userName' => $user,'email' => $email);
        $out['aa'] = $_SESSION['user'];
    }else{
        $out['meta'] = array('msg' => 'not users','code' => 0);
        $out['data'] = array('userName' => $users);
    }
}else{
    $out['meta'] = array('msg' => 'not login','code' => 0);
    $out['data'] = "";
}

$conn->close();

header("Content-type: application/json");
echo json_encode($out);
die();
?>
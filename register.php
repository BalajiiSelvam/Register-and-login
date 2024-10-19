<?php
header('Content-Type: application/json');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


$servername = 'localhost';
$username = 'myaccount';
$password = 'abc123***ABC';
$dbname = 'demo1';

$con = new mysqli($servername, $username, $password, $dbname);

if($con->connect_error){
    die(json_encode(['success'=>false, 'message'=>'DB Connection failed'.$con->connect_error]));
}

$data = json_decode(file_get_contents("php://input"),true);
$user = $data['username'];
$npass = $data['password'];

// Validate password length
if (strlen($npass) < 8) {
    echo json_encode(['success' => false, 'message' => 'Minimum password length should be 8 character long.']);
    exit; // Stop further processing
}

// Hash the password for security
$pass = password_hash($npass, PASSWORD_BCRYPT);

$stmt = $con->prepare("INSERT INTO users (username, password) VALUES (?,?)");
$stmt->bind_param("ss",$user,$pass);

if($stmt->execute()){
    echo json_encode(["success" => true]);
}
else{
    echo json_encode(['success'=>false, 'message'=>'An error occured'.$stmt->error]);
}

$stmt->close();
$con->close();

?>
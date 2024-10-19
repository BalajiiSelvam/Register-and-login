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
    echo json_encode(['success' => false, 'message' => 'Minimum password length should be 8 characters long.']);
    exit; // Stop further processing
}

// Check for at least one number
if (!preg_match('/\d/', $npass)) {
    echo json_encode(['success' => false, 'message' => 'Password must contain at least one number.']);
    exit; // Stop further processing
}

// Check for at least one uppercase letter
if (!preg_match('/[A-Z]/', $npass)) {
    echo json_encode(['success' => false, 'message' => 'Password must contain at least one uppercase letter.']);
    exit; // Stop further processing
}

// Check for at least one special character
if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $npass)) {
    echo json_encode(['success' => false, 'message' => 'Password must contain at least one special character.']);
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
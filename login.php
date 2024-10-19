<?php
header('Content-Type: application/json');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$servername = 'localhost';
$username = 'myaccount';
$password = 'abc123***ABC';
$dbname = 'demo1';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => 'DB connection failed: ' . $conn->connect_error]));
}

$data = json_decode(file_get_contents("php://input"), true);
$user = $data['username'];
$npass = $data['password'];

if (empty($user) || empty($npass)) {
     echo json_encode(["success" => false, "message" => 'Username and password cannot be empty.']);
     exit;
 }
if(strlen($npass)<8){
    echo json_encode(["success"=>false, "message"=>'Minimum password length should be 8 character long.']);
}

$stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($hashed_pass);
    $stmt->fetch();

    if (password_verify($npass, $hashed_pass)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => 'Invalid password.']);
    }
} else {
    echo json_encode(["success" => false, "message" => 'Username not found.']);
}

$stmt->close();
$conn->close();
?>

<?php
// Attach Config
include('../config.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
header('HTTP/1.1 500 Internal Server Error');
header('Content-Type: application/json');
echo '{"code": 420, "data": "", "success": false, "error": "Please try again later."}';
die("");
}
?>
<?php
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,-";
$username = htmlspecialchars($_POST['username']);

if (!isset($_POST['phone'])) {
$phone = NULL;
} else {
$phone = $_POST['phone'];
}

$email = htmlspecialchars($_POST['email']);
$passwordPlain = $_POST['password'];
$passwordHash = password_hash($passwordPlain, PASSWORD_DEFAULT);
$sessionId = rand() . strlen($chars) . rand() . strlen($chars);

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO accounts (sid, username, phone, email, passwordHash) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $sessionId, $username, $phone, $email, $passwordHash);

// Execute the statement
if ($stmt->execute() === TRUE) {
$last_id = $conn->insert_id;
echo '{
    "code": "",
    "data": {
        "username": "' . $username . '",
        "userId": ' . $last_id . ',
        "key": "' . $sessionId . '"
    },
    "success": true,
    "error": ""
}';
} else {
header('HTTP/1.1 500 Internal Server Error');
header('Content-Type: application/json');
echo '{"code": 420, "data": "", "success": false, "error": "Please try again later."}';
die("");
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
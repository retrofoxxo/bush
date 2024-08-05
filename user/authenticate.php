<?php
// Attach Config
include('../config.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
header('HTTP/1.1 500 Internal Server Error');
header('Content-Type: application/json');
echo '{"code": 112, "data": "", "success": false, "error": "Please try again later."}';
die("");
}
?>
<?php
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,-";

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Get the SID from the request header
$headers = getallheaders();
$sid = isset($headers['vine-session-id']) ? $headers['vine-session-id'] : null;

if ($method == 'DELETE' && $sid) {
// Prepare the SQL query to set SID to NULL
$sql = "UPDATE accounts SET SID = NULL WHERE SID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $sid);

if ($stmt->execute() === TRUE) {
header('Content-Type: application/json');
echo '{"code": "", "data": {}, "success": true, "error": ""}';
} else {
header('Content-Type: application/json');
echo "Error: " . $stmt->error;
}

$stmt->close();
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
$email = $_POST['username'];
$password = $_POST['password'];

// Prepare the SQL query to fetch the user
$sql = "SELECT id, passwordHash, username FROM accounts WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
$row = $result->fetch_assoc();
if (password_verify($password, $row['passwordHash'])) {
header('Content-Type: application/json');
$sessionId = $sessionId = rand() . strlen($chars) . rand() . strlen($chars);
echo '{
    "code": "",
    "data": {
        "username": "' . $row['username'] . '",
        "userId": ' . $row['id'] . ',
        "key": "' . $sessionId . '"
    },
    "success": true,
    "error": ""
}';

// Update session ID for authentication purposes
$sql = "UPDATE accounts SET SID = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $sessionId, $email);
$stmt->execute();

} else {
header('HTTP/1.1 403 Forbidden');
header('Content-Type: application/json');
echo '{"code": 101, "data": "", "success": false, "error": "That username or password is incorrect."}';
}
} else {
header('HTTP/1.1 403 Forbidden');
header('Content-Type: application/json');
echo '{"code": 101, "data": "", "success": false, "error": "That username or password is incorrect."}';
}

$stmt->close();
}
// Close the connection
$conn->close();
?>
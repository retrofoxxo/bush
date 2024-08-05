<?php
header('Content-Type: application/json');

// Include config
include('../../config.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
?>
<?php
// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Get the SID from the request header
$headers = getallheaders();
$sid = isset($headers['vine-session-id']) ? $headers['vine-session-id'] : null;

if ($method == 'PUT' && $sid) {
// Read the raw data from the request body
$putData = file_get_contents('php://input');

// Decode HTML entities
$putData = html_entity_decode($putData);

// Parse the URL-encoded string into an associative array
parse_str($putData, $put);

if (isset($put['email'])) {
// extract data that is savable from the put request
$username = $put['username'];
$description = $put['description'];
$location = $put['location'];
$email = $put['email'];

if (isset($put['phoneNumber'])) {
$phone = $put['phoneNumber'];
} else {
$phone = "";
}

if (isset($put['profileBackground'])) {
$vineColorCode = $put['profileBackground'];
} else {
$vineColorCode = "3355443";
}
$hexColorCode = dechex($vineColorCode);

// Ensure the hexadecimal code is in the correct format
$hexColorCode = str_pad($hexColorCode, 6, '0', STR_PAD_LEFT);

// Adjust the hexadecimal code to the desired format
$adjustedHexColorCode = '0x' . substr($hexColorCode, 0, 6);

if (isset($put['avatarUrl'])) {
$avatarUrl = $put['avatarUrl'];
// Prepare the SQL query to update account
$sql = "UPDATE accounts SET username = ?, description = ?, location = ?, email = ?, phone = ?, color = ?, picture = ? WHERE SID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssss", $username, $description, $location, $email, $phone, $adjustedHexColorCode, $avatarUrl, $sid);
} else {
// Prepare the SQL query to update account
$sql = "UPDATE accounts SET username = ?, description = ?, location = ?, email = ?, phone = ?, color = ? WHERE SID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $username, $description, $location, $email, $phone, $adjustedHexColorCode, $sid);
}

if ($stmt->execute() === TRUE) {
echo '{"code": "", "data": {}, "success": true, "error": ""}';
} else {
header('HTTP/1.1 500 Internal Server Error');
header('Content-Type: application/json');
echo '{"code": 420, "data": "", "success": false, "error": "Please try again later."}';
}

$stmt->close();
} else if (isset($put['avatarUrl'])) {
$avatarUrl = $put['avatarUrl'];
// Prepare the SQL query to update account
$sql = "UPDATE accounts SET picture = ? WHERE SID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $avatarUrl, $sid);
if ($stmt->execute() === TRUE) {
echo '{"code": "", "data": {}, "success": true, "error": ""}';
} else {
header('HTTP/1.1 500 Internal Server Error');
header('Content-Type: application/json');
echo '{"code": 420, "data": "", "success": false, "error": "Please try again later."}';
}

$stmt->close();
} else {
echo '{"code": "", "data": {}, "success": true, "error": ""}';
}
}
// Close the connection
$conn->close();
?>
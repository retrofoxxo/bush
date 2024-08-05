<?php
// Attach Config
include('config.php');

// Read JSON from php://input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

// The vine-session-id you want to search for
$headers = getallheaders();

$vine_session_id = $headers['vine-session-id'];

$sql = "SELECT id, postc FROM accounts WHERE sid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $vine_session_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$uploadId = $user['id'];
$thumbnailUrl = $data['thumbnailUrl'];
$created = date("Y-m-d\TH:i:s") . ".000000";

// Increase the postc row by 1
$new_postc = $user['postc'] + 1;
$sql_update = "UPDATE accounts SET postc = ? WHERE id = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("ii", $new_postc, $uploadId);
$stmt_update->execute();

if (isset($data['description'])) {
// Save JSON contents to MySQL
$sql = "INSERT INTO vines (description, uploaderId, thumbnailUrl, videodef, videoh264, videor2, created) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $data['description'], $uploadId, $thumbnailUrl, $data['videoWebmUrl'], $data['videoWebmUrl'], $data['videoWebmUrl'], $created);
} else {
// Save JSON contents to MySQL
// Save JSON contents to MySQL
$sql = "INSERT INTO vines (uploaderId, thumbnailUrl, videodef, videoh264, videor2, created) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $uploadId, $thumbnailUrl, $data['videoWebmUrl'], $data['videoWebmUrl'], $data['videoWebmUrl'], $created);
}
$stmt->execute();

// Close the statement and connection
$stmt_update->close();
$stmt->close();
$conn->close();
?>
<?php
header('Content-Type: application/json');
?>
{"code": "", "data": {}, "success": true, "error": ""}
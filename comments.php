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
header('Content-Type: application/json');
?>
<?php
$vineId = $_GET['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// The vine-session-id you want to search for
$headers = getallheaders();

$vine_session_id = $headers['vine-session-id'];

// Prepare the SQL query
$sql = "SELECT id FROM accounts WHERE SID = ?";

// Prepare and bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $vine_session_id);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if any rows were returned
if ($result->num_rows > 0) {
// Output data of each row
while($row2 = $result->fetch_assoc()) {
$authorId = $row2['id'];
}

$created = date("Y-m-d\TH:i:s") . ".000000";
$vineId = $_GET['id'];

// Read JSON from php://input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

$sql = "INSERT INTO comments (text, authorId, vineId, created) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $data['comment'], $authorId, $vineId, $created);

$stmt->execute();

$sql = "UPDATE vines SET commentc = commentc + 1 WHERE id = $vineId";
$conn->query($sql);

header('Content-Type: application/json');
echo '{"code": "", "data": {}, "success": true, "error": ""}';
} else {
header('HTTP/1.1 403 Forbidden');
header('Content-Type: application/json');
echo '{"code": 103, "data": "", "success": false, "error": "Authenticate First."}';
}

// Close the statement and connection
$stmt->close();
} else {
// SQL query to select rows
$sql = "SELECT * FROM comments WHERE vineId = $vineId ORDER BY id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
// Output data of each row
echo '{"code": "", "data": {"count": ' . $result->num_rows . ', "anchorStr": "1430063523815403520", "records": [';
$numbered = 1;
while ($row = $result->fetch_assoc()) {
$vine_id = $row['authorId'];

// Prepare the SQL query
$sql = "SELECT * FROM accounts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $vine_id);

// Execute the statement
$stmt->execute();

// Get the result
$account_result = $stmt->get_result();

// Check if any rows were returned
if ($account_result->num_rows > 0) {
// Output data of each row
while ($account_row = $account_result->fetch_assoc()) {
$username = $account_row['username'];
$color = $account_row['color'];
$verified = $account_row['verified'];
$avatarUrl = $account_row['picture'];
if ($account_row['description'] == "") {
$description = null;
} else {
$description = '"' . $account_row['description'] . '"';
}
$location = $account_row['location'];
}
}

// Close the statement
$stmt->close();

if ($numbered == $result->num_rows) {
echo '{"comment": "' . $row['text'] . '", "username": "' . $username . '", "verified": ' . $verified . ', "vanityUrls": [], "twitterVerified": 0, "flags|platform_lo": 0, "sourceId": null, "avatarUrl": "' . $avatarUrl . '", "user": {"username": "' . $username . '", "verified": ' . $verified . ', "vanityUrls": [], "twitterVerified": 0, "avatarUrl": "' . $avatarUrl . '", "userId": ' . $row['authorId'] . ', "private": 0, "location": "' . $location . '", "description": ' . $description . '}, "created": "' . $row['created'] . '", "userId": ' . $row['authorId'] . ', "entities": [], "location": "' . $location . '", "commentId": ' . $row['id'] . ', "postId": ' . $row['vineId'] . ', "flags|platform_hi": 0, "sourceType": null}';
} else {
echo '{"comment": "' . $row['text'] . '", "username": "' . $username . '", "verified": ' . $verified . ', "vanityUrls": [], "twitterVerified": 0, "flags|platform_lo": 0, "sourceId": null, "avatarUrl": "' . $avatarUrl . '", "user": {"username": "' . $username . '", "verified": ' . $verified . ', "vanityUrls": [], "twitterVerified": 0, "avatarUrl": "' . $avatarUrl . '", "userId": ' . $row['authorId'] . ', "private": 0, "location": "' . $location . '", "description": ' . $description . '}, "created": "' . $row['created'] . '", "userId": ' . $row['authorId'] . ', "entities": [], "location": "' . $location . '", "commentId": ' . $row['id'] . ', "postId": ' . $row['vineId'] . ', "flags|platform_hi": 0, "sourceType": null}, ';
}
$numbered++;
}
echo '], "nextPage": null, "backAnchor": "1430767489536634880", "anchor": 1430063523815403520, "previousPage": null, "size": 20}, "success": true, "error": ""}';
} else {
echo '{"code": "", "data": {"count": 0, "records": [], "nextPage": null, "anchor": null, "previousPage": null, "size": 35}, "success": true, "error": ""}';
}
}
$conn->close();
?>
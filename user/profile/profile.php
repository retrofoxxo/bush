<?php
// Attach Config
include('../../config.php');

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
// The vine-session-id you want to search for
$headers = getallheaders();

$vine_id = $_GET['id'];

// Prepare the SQL query
$sql = "SELECT * FROM accounts WHERE ID = ?";

// Prepare and bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $vine_id);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if any rows were returned
if ($result->num_rows > 0) {
// Output data of each row
while($row = $result->fetch_assoc()) {
if ($row["description"] == NULL) {
$description = "null";
} else {
$description = '"' . $row["description"] . '"';
}

if ($row["location"] == NULL) {
$location = "null";
} else {
$location = '"' . $row["location"] . '"';
}

if ($row["picture"] == NULL) {
$picture = "null";
} else {
$picture = '"' . $row["picture"] . '"';
}
header('Content-Type: application/json');
echo '{"code": "", "data": {"username": "' . $row['username'] . '", "profileBackground": "' . $row['color'] . '", "followerCount": ' . $row['followersc'] . ', "verified": ' . $row['verified'] . ', "vanityUrls": [], "loopCount": ' . $row['loopc'] . ', "avatarUrl": ' . $picture . ', "authoredPostCount": 22, "shareUrl": "https://vine.co/u/' . $row['id'] . '", "userId": ' . $row['id'] . ', "private": 0, "likeCount": ' . $row['likec'] . ', "postCount": ' . $row['postc'] . ', "location": ' . $location . ', "followingCount": ' . $row['followingc'] . ', "explicitContent": 0, "description": ' . $description . '}, "success": true, "error": ""}';
}
} else {
header('HTTP/1.1 404 Not Found');
header('Content-Type: application/json');
echo '{"code": 115, "data": "", "success": false, "error": "User id is not valid"}';
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
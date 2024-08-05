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
// The vine-session-id you want to search for
$headers = getallheaders();

$vine_session_id = $headers['vine-session-id'];

// Prepare the SQL query
$sql = "SELECT * FROM accounts WHERE SID = ?";

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
while($row = $result->fetch_assoc()) {
if ($row["description"] == NULL) {
$description = "null";
} else {
$description = '"' . $row["description"] . '"';
}

if ($row["phone"] == NULL) {
$phone = "null";
} else {
$phone = '"' . $row["phone"] . '"';
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
echo '{
    "code": "",
    "data": {
        "username": "' . $row["username"]. '",
        "profileBackground": "' . $row["color"]. '",
        "following": ' . $row["following"] . ',
        "followerCount": ' . $row["followersc"] . ',
        "verified": ' . $row["verified"]. ',
        "description": ' . $description . ',
        "avatarUrl": ' . $picture . ',
        "twitterId": null,
        "facebookId": null,
        "userId": ' . $row["id"] . ',
        "twitterConnected": 0,
        "likeCount": ' . $row["likec"] . ',
        "facebookConnected": 0,
        "postCount": ' . $row["postc"] . ',
        "phoneNumber": ' . $phone . ',
        "verifiedPhoneNumber": ' . $row["vphone"] . ',
        "location": ' . $location . ',
        "followingCount": ' . $row["followingc"] . ',
        "email": "' . $row["email"] . '",
        "verifiedEmail": ' . $row["vemail"] . ',
        "loopCount": ' . $row["loopc"] . '
    },
    "success": true,
    "error": ""
}';
}
} else {
header('HTTP/1.1 403 Forbidden');
header('Content-Type: application/json');
echo '{"code": 103, "data": "", "success": false, "error": "Authenticate First."}';
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
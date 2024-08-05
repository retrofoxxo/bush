<?php
header('Content-Type: application/json');
?>
<?php
// Attach Config
include('../config.php');

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

// SQL query to select rows containing the searched word
$search = $_GET['query'];
$sql = "SELECT * FROM accounts WHERE username LIKE '%$search%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
// Output data of each row
echo '{"code": "", "data": {"count": ' . $result->num_rows . ', "records": [';
$numbered = 1;
while($row = $result->fetch_assoc()) {
if ($numbered == $result->num_rows) {
echo '{"username": "' . $row['username'] . '", "verified": ' . $row['verified'] . ', "vanityUrls": [], "loopCount": ' . $row['loopc'] . ', "avatarUrl": "' . $row['picture'] . '", "userId": ' . $row['id'] . ', "profileBackground": "' . $row['color'] . '", "user": {"private": 0}, "location": "' . $row['location'] . '"}';
} else {
echo '{"username": "' . $row['username'] . '", "verified": ' . $row['verified'] . ', "vanityUrls": [], "loopCount": ' . $row['loopc'] . ', "avatarUrl": "' . $row['picture'] . '", "userId": ' . $row['id'] . ', "profileBackground": "' . $row['color'] . '", "user": {"private": 0}, "location": "' . $row['location'] . '"},';
$numbered++;
}
}
echo '], "nextPage": null, "anchor": "3", "previousPage": null, "size": 35}, "success": true, "error": ""}';
} else {
echo '{"code": "", "data": {"count": 0, "records": [], "nextPage": null, "anchor": null, "previousPage": null, "size": 35}, "success": true, "error": ""}';
}
$conn->close();
?>
<?php
include('config.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
header('HTTP/1.1 500 Internal Server Error');
header('Content-Type: application/json');
echo '{"code": 420, "data": "", "success": false, "error": "Please try again later."}';
die("");
}

// Sample JSON request body
$json = file_get_contents("php://input");

// Decode JSON to PHP array
$data = json_decode($json, true);

// Initialize an array to store aggregated counts
$aggregatedCounts = [];

// Loop through each entry in the 'loops' array
foreach ($data['loops'] as $entry) {
$postId = $entry['postId'];
$count = $entry['count'];

// If the postId is already in the aggregatedCounts array, add the count
if (isset($aggregatedCounts[$postId])) {
$aggregatedCounts[$postId] += $count;
} else {
// Otherwise, initialize the count for this postId
$aggregatedCounts[$postId] = $count;
}
}

// Update the database with the aggregated counts
foreach ($aggregatedCounts as $postId => $totalCount) {
// Update the row
$sql = "UPDATE vines SET loopc = loopc + $totalCount WHERE id = $postId";
$conn->query($sql);

// Get the uploaderId of the updated row
$sql = "SELECT uploaderId FROM vines WHERE id = $postId";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
$uploaderId = $row["uploaderId"];
}
}

// Update the creator row too
$sqlp2 = "UPDATE accounts SET loopc = loopc + $totalCount WHERE id = $uploaderId";
$conn->query($sqlp2);
}

header('Content-Type: application/json');
echo '{"code": "", "data": {}, "success": true, "error": ""}';

$conn->close();
?>
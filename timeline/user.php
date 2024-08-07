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
$username = $row['username'];
$color = $row['color'];
$verified = $row['verified'];
$avatarUrl = $row['picture'];
}
}

// Close the statement and connection
$stmt->close();
?>
<?php
header('Content-Type: application/json');
?>
<?php
// SQL query to select rows containing the searched word
$cid = $_GET['id'];
$sql = "SELECT * FROM vines WHERE uploaderId = '$cid' ORDER BY id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
// Output data of each row
echo '{"code": "", "data": {"count": ' . $result->num_rows . ', "anchorStr": "1430063523815403520", "records": [';
$numbered = 1;
while($row = $result->fetch_assoc()) {
if ($numbered == $result->num_rows) {
echo '{"liked": 0, "videoDashUrl": null, "foursquareVenueId": "", "remixDisabled": 0, "userId": ' . $row['uploaderId'] . ', "private": 0, "foursquareVenueIdStr": "", "postIdStr": "' . $row['id'] . '", "videoWebmUrl": null, "loops": {"count": ' . $row['loopc'] . '.0, "velocity": ' . $row['loopvelo'] . ', "onFire": ' . $row['loopfire'] . '}, "thumbnailUrl": "' . $row['thumbnailUrl'] . '", "explicitContent": 0, "myRepostId": 0, "blocked": 0, "verified": ' . $verified . ', "entities": [], "avatarUrl": "' . $avatarUrl . '", "description": "' . $row['description'] . '", "comments": {"count": ' . $row['commentc'] . ', "anchorStr": "", "records": [], "nextPage": null, "backAnchor": "", "anchor": null, "previousPage": null, "size": 0}, "videoLowURL": null, "videoUrls": [{"videoUrl": "' . $row['videodef'] . '", "format": "h264", "default": 1, "idStr": "original", "rate": 200, "id": "original"}, {"rate": 0, "format": "h264", "videoUrl": "' . $row['videor2'] . '", "id": "r2", "idStr": "r2"}, {"videoUrl": "' . $row['videoh264'] . '", "format": "dash", "default": 1, "idStr": "h264dash", "rate": 0, "id": "h264dash"}, {"videoUrl": "' . $row['videoh264'] . '", "format": "h264c", "default": 1, "idStr": "r2", "rate": 0, "id": "r2"}], "username": "' . $username . '", "userIdStr": "' . $row['uploaderId'] . '", "vanityUrls": [], "tags": [], "permalinkUrl": "https://vine.co/v/' . $row['id'] . '", "following": 0, "myRepostIdStr": "", "postId": ' . $row['id'] . ', "likes": {"count": ' . $row['likec'] . ', "anchorStr": "1430771454466744320", "records": [], "nextPage": null, "backAnchor": "", "anchor": 1430771454466744320, "previousPage": null, "size": 6}, "videoUrl": null, "followRequested": 0, "created": "' . $row['created'] . '", "hasSimilarPosts": 0, "shareUrl": "https://vine.co/v/' . $row['id'] . '", "profileBackground": "' . $color . '", "promoted": 0, "reposts": {"count": ' . $row['revinec'] . ', "anchorStr": "", "records": [], "nextPage": null, "backAnchor": "", "anchor": null, "previousPage": null, "size": 0}}';
} else {
echo '{"liked": 0, "videoDashUrl": null, "foursquareVenueId": "", "remixDisabled": 0, "userId": ' . $row['uploaderId'] . ', "private": 0, "foursquareVenueIdStr": "", "postIdStr": "' . $row['id'] . '", "videoWebmUrl": null, "loops": {"count": ' . $row['loopc'] . '.0, "velocity": ' . $row['loopvelo'] . ', "onFire": ' . $row['loopfire'] . '}, "thumbnailUrl": "' . $row['thumbnailUrl'] . '", "explicitContent": 0, "myRepostId": 0, "blocked": 0, "verified": ' . $verified . ', "entities": [], "avatarUrl": "' . $avatarUrl . '", "description": "' . $row['description'] . '", "comments": {"count": ' . $row['commentc'] . ', "anchorStr": "", "records": [], "nextPage": null, "backAnchor": "", "anchor": null, "previousPage": null, "size": 0}, "videoLowURL": null, "videoUrls": [{"videoUrl": "' . $row['videodef'] . '", "format": "h264", "default": 1, "idStr": "original", "rate": 200, "id": "original"}, {"rate": 0, "format": "h264", "videoUrl": "' . $row['videor2'] . '", "id": "r2", "idStr": "r2"}, {"videoUrl": "' . $row['videoh264'] . '", "format": "dash", "default": 1, "idStr": "h264dash", "rate": 0, "id": "h264dash"}, {"videoUrl": "' . $row['videoh264'] . '", "format": "h264c", "default": 1, "idStr": "r2", "rate": 0, "id": "r2"}], "username": "' . $username . '", "userIdStr": "' . $row['uploaderId'] . '", "vanityUrls": [], "tags": [], "permalinkUrl": "https://vine.co/v/' . $row['id'] . '", "following": 0, "myRepostIdStr": "", "postId": ' . $row['id'] . ', "likes": {"count": ' . $row['likec'] . ', "anchorStr": "1430771454466744320", "records": [], "nextPage": null, "backAnchor": "", "anchor": 1430771454466744320, "previousPage": null, "size": 6}, "videoUrl": null, "followRequested": 0, "created": "' . $row['created'] . '", "hasSimilarPosts": 0, "shareUrl": "https://vine.co/v/' . $row['id'] . '", "profileBackground": "' . $color . '", "promoted": 0, "reposts": {"count": ' . $row['revinec'] . ', "anchorStr": "", "records": [], "nextPage": null, "backAnchor": "", "anchor": null, "previousPage": null, "size": 0}},';
$numbered++;
}
}
echo '], "nextPage": null, "backAnchor": "1430767489536634880", "anchor": 1430063523815403520, "previousPage": null, "size": 20}, "success": true, "error": ""}';
} else {
echo '{"code": "", "data": {"count": 0, "records": [], "nextPage": null, "anchor": null, "previousPage": null, "size": 35}, "success": true, "error": ""}';
}
$conn->close();
?>
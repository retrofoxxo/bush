<?php
// Get the SID from the request header
$headers = getallheaders();
$sid = isset($headers['vine-session-id']) ? $headers['vine-session-id'] : null;

if ($_SERVER['REQUEST_METHOD'] == "PUT") {
if (isset($sid)) {
// Set headers
header('Content-Type: application/json');
header('X-Upload-Key: http://192.168.50.154/upload/videos/' . $_GET['name']);

// Read the raw POST data from php://input
$inputData = file_get_contents('php://input');

// Define the static part of the filename
$staticPart = './videos/';

// Define the variable part of the filename (e.g., a timestamp or a unique ID)
$variablePart = $_GET['name'];

// Combine the static and variable parts to create the filename
$filename = $staticPart . $variablePart;

// Save the input data to the file
file_put_contents($filename, $inputData);
}
} else {
header('Content-Type: video/webm');
$avatar = file_get_contents('./videos/' . $_GET['name']);
echo $avatar;
}
?>
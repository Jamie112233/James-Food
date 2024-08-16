<?php
session_start();
header('Content-Type: application/json');

require_once 'verify-private-section.php';

require_once 'connect.php';

$error = false;
$message = '';

$query = "UPDATE comments SET is_active = :is_active WHERE comment_id = :comment_id";
$stmt = $db->prepare($query);
$stmt->bindValue(':is_active',$_POST['action'] == 'hide' ? false : true);
$stmt->bindValue(':comment_id',$_POST['comment_id']);
if(!$stmt->execute()) {
    $error = true;
    $message = 'An error has occured.';
}

$response = [
    'error' => $error,
    'message' => $message
];

echo json_encode($response);
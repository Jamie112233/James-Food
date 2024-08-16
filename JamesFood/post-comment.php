<?php
session_start();
header('Content-Type: application/json');

require_once 'admin/connect.php';

$query = "INSERT INTO comments (comment,user_id,content_id) VALUE (:comment,:user_id,:content_id)";
$stmt = $db->prepare($query);
$stmt->bindValue(':comment', $_POST['comment'], PDO::PARAM_STR);
$stmt->bindValue(':user_id', $_SESSION['jamesfood_user_id'], PDO::PARAM_INT);
$stmt->bindValue(':content_id', $_POST['content_id'], PDO::PARAM_INT);
if(!$stmt->execute()) {
    $response = [
        'response' => 'There has been an error and comment has not been posted',
        'error' => true
    ];

    echo json_encode($response);
    die();
}

$query = "SELECT c.*,u.full_name FROM comments c LEFT JOIN users u ON u.user_id = c.user_id WHERE c.content_id = :content_id AND c.is_active = true ORDER BY c.created_at DESC";
$stmt = $db->prepare($query);
$stmt->bindValue(':content_id', $_POST['content_id'], PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();

$comments = [];
foreach($rows as $row) {
    $comments[] = [
        'comment_id' => $row->comment_id,
        'full_name' => $row->full_name,
        'first_letter' => substr($row->full_name, 0, 1),
        'comment' => $row->comment,
        'date' => date('F j, Y, g:i a', strtotime($row->created_at))
    ];
}

$response = [
    'error' => false,
    'response' => '',
    'comments' => $comments,
    'total_comments' => count($comments) == 1 ? '1 Comment' : count($comments) . ' Comments'
];

echo json_encode($response);
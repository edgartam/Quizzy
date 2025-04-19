<?php
session_start();
$_SESSION['quiz'] = [];
$data = file_get_contents('php://input');
$data = json_decode($data, true);
if (isset($data['title'], $data['creator'], $data['createdTime'])) {
    $_SESSION['quiz'] = [
        'title' => $data['title'],
        'creator' => $data['creator'],
        'createdTime' => $data['createdTime'],
    ]; 
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
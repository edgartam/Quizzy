<?php
session_start();
include "connect_db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['quiz_id'])) {
    $quizId = $_POST['quiz_id'];
    $userId = $_SESSION['user_id']; 
    $submittedAnswers = $_POST['answer'];


    $stmt = $pdo->prepare("SELECT correct_answer FROM questions WHERE quiz_id = ?");
    $stmt->execute([$quizId]);
    $correctAnswers = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); 
    $score = 0;

   
    foreach ($submittedAnswers as $index => $answer) {
        if (isset($correctAnswers[$index]) && $correctAnswers[$index] === $answer) {
            $score++; 
        }
    }

   
    $totalMarks = count($correctAnswers);
    $percentage = ($score / $totalMarks) * 100;

  
    $stmt = $pdo->prepare("INSERT INTO results (user_id, quiz_id, score, finished_time) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$userId, $quizId, $score]);

 
    $response = [
        'success' => true,
        'message' => "You scored $score out of $totalMarks ($percentage%)."
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
} else {
    $response = [
        'success' => false,
        'message' => 'Invalid request.'
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
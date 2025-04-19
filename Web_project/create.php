


<?php
include "connect_db.php";


if (isset($_POST['title']) && isset($_POST['question'])) {
    $title = $_POST['title'];
    $questions = $_POST['question'];
    $optionsA = $_POST['option_a'];
    $optionsB = $_POST['option_b'];
    $optionsC = $_POST['option_c'];
    $optionsD = $_POST['option_d'];
    $correctAnswers = $_POST['correct_answer'];

    $stmt = $pdo->prepare("INSERT INTO quizzes (title) VALUES (?)");
    $stmt->execute([$title]);
    $quizId = $pdo->lastInsertId();

    foreach ($questions as $index => $question) {
        $stmt = $pdo->prepare("INSERT INTO questions (quiz_id, question, option_a, option_b, option_c, option_d, correct_answer)
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$quizId, $question, $optionsA[$index], $optionsB[$index], $optionsC[$index], $optionsD[$index], $correctAnswers[$index]]);
    }

    echo json_encode(['success' => true, 'message' => 'Quiz created successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
}?>

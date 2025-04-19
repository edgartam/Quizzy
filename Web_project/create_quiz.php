<?php
session_start();
include "connect_db.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title']) && isset($_POST['question'])) {
    $title = $_POST['title'];
    $questions = $_POST['question'];
    $optionsA = $_POST['option_a'];
    $optionsB = $_POST['option_b'];
    $optionsC = $_POST['option_c'];
    $optionsD = $_POST['option_d'];
    $correctAnswers = $_POST['correct_answer'];
    $duration = $_POST['duration'];

    // Validate duration
    if ($duration < 15 || $duration > 60) {
        echo "<script>alert('Duration must be between 15 and 60 minutes.');</script>";
    } else {
        $creator = $_SESSION['username'];
        $createdTime = date('Y-m-d H:i:s');
        $stmt = $pdo->prepare("INSERT INTO quizzes (title, Creator, CreatedTime, duration) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $creator, $createdTime, $duration]);
        $quizId = $pdo->lastInsertId();

        foreach ($questions as $index => $question) {
            $stmt = $pdo->prepare("INSERT INTO questions (quiz_id, question, option_a, option_b, option_c, option_d, correct_answer)
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$quizId, $question, $optionsA[$index], $optionsB[$index], $optionsC[$index], $optionsD[$index], $correctAnswers[$index]]);
        }

        header('Location: All_quiz.php');
        exit; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Create Quiz</title>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-CFWRBK192C"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-CFWRBK192C');
    </script>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-PLKRRR35');</script>
</script>
</head>
<body>
<header>
<?php include 'navbar.html'; ?>
</header>
<div id="quiz-container">
    <h1>Create Quiz</h1>
    <form id="quizForm" method="POST" action="">
        <label for="title">Quiz Title:</label><br>
        <input type="text" name="title" required><br><br>

        <!-- Duration Input -->
        <label for="duration">Enter Quiz Duration (minutes):</label><br>
        <input type="text" name="duration" id="duration" required oninput="validateDuration()"><br>
        <span id="durationError" style="color: red; margin-top: 20px; display: block;margin-bottom: -40px;"></span><br><br>

        <div id="questions-container">
            <div class="question-block">
                <label for="question">Question 1:</label><br>
                <textarea name="question[]" required></textarea><br><br>

                <label for="option_a">Option A:</label><br>
                <input type="text" name="option_a[]" required><br><br>

                <label for="option_b">Option B:</label><br>
                <input type="text" name="option_b[]" required><br><br>

                <label for="option_c">Option C:</label><br>
                <input type="text" name="option_c[]" required><br><br>

                <label for="option_d">Option D:</label><br>
                <input type="text" name="option_d[]" required><br><br>

                <label>Correct Answer:</label><br>
                <input type="radio" name="correct_answer[0]" value="A" required>A
                <input type="radio" name="correct_answer[0]" value="B" required>B
                <input type="radio" name="correct_answer[0]" value="C" required>C
                <input type="radio" name="correct_answer[0]" value="D" required>D<br><br>
            </div>
        </div>
        <button type="button" id="addQuestionButton">Add Another Question</button>
        <button type="button" id="cancelButton" style="display: none;">Cancel</button><br><br>
        <button type="submit">Create Quiz</button>
        <br><br>
        
    </form>
</div>

<script>
function validateDuration() {
    const durationInput = document.getElementById('duration');
    const durationError = document.getElementById('durationError');
    const durationValue = parseInt(durationInput.value, 10);

    if (durationValue < 15 || durationValue > 60 || isNaN(durationValue)) {
        durationError.textContent = "Please enter a number between 15 and 60.";
    } else {
        durationError.textContent = ""; 
    }
}

document.getElementById('addQuestionButton').onclick = function() {
    const questionBlock = document.createElement('div');
    questionBlock.className = 'question-block';
    const index = document.querySelectorAll('.question-block').length;

    questionBlock.innerHTML = `
        <label for="question">Question ${index + 1}:</label><br>
        <textarea name="question[]" required></textarea><br><br>

        <label for="option_a">Option A:</label><br>
        <input type="text" name="option_a[]" required><br><br>

        <label for="option_b">Option B:</label><br>
        <input type="text" name="option_b[]" required><br><br>

        <label for="option_c">Option C:</label><br>
        <input type="text" name="option_c[]" required><br><br>

        <label for="option_d">Option D:</label><br>
        <input type="text" name="option_d[]" required><br><br>

        <label>Correct Answer:</label><br>
        <input type="radio" name="correct_answer[${index}]" value="A" required>A
        <input type="radio" name="correct_answer[${index}]" value="B" required>B
        <input type="radio" name="correct_answer[${index}]" value="C" required>C
        <input type="radio" name="correct_answer[${index}]" value="D" required>D<br><br>
    `;

    document.getElementById('questions-container').appendChild(questionBlock);
    document.getElementById('cancelButton').style.display = 'inline'; 

    document.getElementById('cancelButton').onclick = function() {
        const questionBlocks = document.querySelectorAll('.question-block');
        if (questionBlocks.length > 1) {
            questionBlocks[questionBlocks.length - 1].remove(); 
        }
        if (questionBlocks.length <= 2) {
            document.getElementById('cancelButton').style.display = 'none'; 
        }
    };
};
</script>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PLKRRR35"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>
</html>
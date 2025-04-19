<?php
session_start();
include "connect_db.php";

if (isset($_SESSION['quiz'])) {
    $title = $_SESSION['quiz']['title'];
    $creator = $_SESSION['quiz']['creator'];
    $createdTime = $_SESSION['quiz']['createdTime'];
    $stmt = $pdo->prepare("SELECT id, duration FROM quizzes WHERE title = ? AND Creator = ? AND CreatedTime = ?");
    $stmt->execute([$title, $creator, $createdTime]);
    $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($quiz) {
        $quizId = $quiz['id'];
        $duration = $quiz['duration'];
        $stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ?");
        $stmt->execute([$quizId]);
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "Quiz not found.";
        exit;
    }
} else {
    echo "No quiz selected.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - Quiz</title>
    <link rel="stylesheet" href="style.css">
    <style>
        #timer {
            font-size: 24px;
            margin-bottom: 20px;
        }
    </style>
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
    <?php include 'navbar.html'; ?>
    <div style="margin:50px;">
        <h1>Quiz: <?= htmlspecialchars($title) ?> By <?= htmlspecialchars($creator) ?></h1>
        <div id="timer">Time left: </div>
        <form id="quizAnswersForm">
            <?php foreach ($questions as $index => $question): ?>
                <div class="question-block" style="margin-bottom:20px;margin-top:10px;">
                    <p><?= $index + 1 ?>. <?= htmlspecialchars($question['question']) ?></p>
                    <input type="radio" name="answer[<?= $index ?>]" value="A" required> <?= htmlspecialchars($question['option_a']) ?><br>
                    <input type="radio" name="answer[<?= $index ?>]" value="B" required> <?= htmlspecialchars($question['option_b']) ?><br>
                    <input type="radio" name="answer[<?= $index ?>]" value="C" required> <?= htmlspecialchars($question['option_c']) ?><br>
                    <input type="radio" name="answer[<?= $index ?>]" value="D" required> <?= htmlspecialchars($question['option_d']) ?><br>
                </div>
            <?php endforeach; ?>
            <input type="hidden" name="quiz_id" value="<?= htmlspecialchars($quizId) ?>">
            <button type="submit">Submit Answers</button>
        </form>
    </div>

    <script>
        let duration = <?= $duration ?> * 60; 
        const timerDisplay = document.getElementById('timer');

        function updateTimer() {
            if (duration <= 0) {
                clearInterval(timerInterval);
                alert("Time's up! Submitting your answers.");
                document.getElementById('quizAnswersForm').submit(); 
                return;
            }

            let minutes = Math.floor(duration / 60);
            let seconds = duration % 60;

         
            let formattedTime = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            timerDisplay.textContent = `Time left: ${formattedTime}`;

            duration--; 
        }

        let timerInterval = setInterval(updateTimer, 1000);

        document.getElementById('quizAnswersForm').onsubmit = function(event) {
            clearInterval(timerInterval); 
            event.preventDefault();
            const formData = new FormData(this);
            fetch('submit.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    window.location.href = 'index.php';
                }
            })
            .catch(error => console.error('Error:', error));
        };
    </script>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PLKRRR35"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>
</html>
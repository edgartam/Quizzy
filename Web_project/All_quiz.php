<?php
session_start(); 
include "connect_db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM quizzes");
$stmt->execute();
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Quizzes</title>
    <link rel="stylesheet" href="all_quiz.css">
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
<div class="body_content">
<h1>All Quizzes</h1>

<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Creator</th>
            <th>Created Time</th>
            <th>Duration</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($quizzes as $quiz): ?>
            <tr>
                <td><?= htmlspecialchars($quiz['title']) ?></td>
                <td><?= htmlspecialchars($quiz['Creator']) ?></td>
                <td><?= htmlspecialchars($quiz['CreatedTime']) ?></td>
                <td><?= htmlspecialchars($quiz['duration']) ?></td>
                <td>
                    <button class="join-button" onclick="saveQuiz('<?= htmlspecialchars($quiz['title']) ?>', '<?= htmlspecialchars($quiz['Creator']) ?>', '<?= htmlspecialchars($quiz['CreatedTime']) ?>', '<?= htmlspecialchars($quiz['duration']) ?>')">Join</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

<script>
function saveQuiz(title, creator, createdTime, duration) {
    const confirmationMessage = `You are going to join the quiz titled "${title}"\nCreator: ${creator}\nDuration: ${duration} minutes.\n\nDo you want to continue?`;
    
    if (confirm(confirmationMessage)) {
        fetch('save_quiz.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ title, creator, createdTime }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'quiz.php'; 
            } else {
                alert("There was an error saving the quiz data."); // Handle error appropriately
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PLKRRR35"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>
</html>
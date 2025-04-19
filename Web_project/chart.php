<?php
session_start();
include "connect_db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit();
}

if (isset($_GET['quiz_id'])) {
    $quizId = $_GET['quiz_id'];

    // Fetch the top 3 users with the highest scores for the selected quiz
    $stmt = $pdo->prepare("
        SELECT u.username, r.user_id, r.score 
        FROM results r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.quiz_id = ?
        ORDER BY r.score DESC
        LIMIT 3
    ");
    $stmt->execute([$quizId]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    header("Location: statistic.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Chart</title>
    <link rel="stylesheet" href="all_quiz.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <h1>Quiz Results Chart</h1>

        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $result): ?>
                    <tr>
                        <td><?= htmlspecialchars($result['username'] === $_SESSION['username'] ? 'You' : $result['username']) ?></td>
                        <td><?= htmlspecialchars($result['score']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <canvas id="quizChart" width="400" height="200"></canvas>

        <script>
            const results = <?= json_encode($results) ?>;

            const labels = results.map(entry => entry.user_id === <?= $_SESSION['user_id'] ?> ? 'You' : entry.username);
            const scores = results.map(entry => entry.score);

            const backgroundColors = scores.map((score, index) => 
            results[index].user_id === <?= $_SESSION['user_id'] ?> ? 'rgba(255, 99, 132, 0.5)' : 'rgba(75, 192, 192, 0.2)'
            );

            const ctx = document.getElementById('quizChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Scores',
                        data: scores,
                        backgroundColor: backgroundColors,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </div>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PLKRRR35"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>
</html>
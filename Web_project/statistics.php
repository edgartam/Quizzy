<?php
session_start(); 
include "connect_db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit();
}

// Fetch quiz statistics
$stmt = $pdo->prepare("
    SELECT 
        q.id AS quiz_id,
        q.title,
        q.Creator,
        MAX(r.score) AS highest_score,
        COUNT(DISTINCT r.user_id) AS user_count,
        AVG(r.score) AS average_score
    FROM 
        quizzes q
    LEFT JOIN 
        results r ON q.id = r.quiz_id
    GROUP BY 
        q.id
");

$stmt->execute();
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Statistics</title>
    <link rel="stylesheet" href="statistics.css">
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
        <h1>Quiz Statistics</h1>

        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Creator</th>
                    <th>Highest Mark</th>
                    <th>User Join</th>
                    <th>Mean</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($quizzes as $quiz): ?>
                    <tr>
                        <td><?= htmlspecialchars($quiz['title']) ?></td>
                        <td><?= htmlspecialchars($quiz['Creator']) ?></td>
                        <td><?= htmlspecialchars($quiz['highest_score']) ?></td>
                        <td><?= htmlspecialchars($quiz['user_count']) ?></td>
                        <td><?= htmlspecialchars(number_format($quiz['average_score'], 2)) ?></td>  
                        <td>
                             <a class="join-button" href="chart.php?quiz_id=<?= urlencode($quiz['quiz_id']) ?>">Show Chart</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <canvas id="quizChart" width="400" height="200" style="display:none;"></canvas>
    </div>

    <script>
        function showChart(quizId) {
            fetch(`fetch_quiz_results.php?quiz_id=${quizId}`)
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('quizChart').getContext('2d');
                    const labels = data.map(entry => `User ${entry.user_id}`);
                    const scores = data.map(entry => entry.score);

                    // Create the bar chart
                    const chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Scores',
                                data: scores,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
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

                    document.getElementById('quizChart').style.display = 'block';
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PLKRRR35"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>
</html>
<?php
session_start();
include("connect_db.php");
if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit();
}
$uname = $_SESSION['username'];
$sql = "SELECT quizzes.title, results.score, results.finished_time 
        FROM results 
        INNER JOIN quizzes ON results.quiz_id = quizzes.id
        INNER JOIN users ON results.user_id = users.id
        WHERE users.username = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$uname]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$meanScores = [];
foreach ($results as $row) {
    $quizTitle = $row['title'];
    $score = $row['score'];

    if (!isset($meanScores[$quizTitle])) {
        $meanScores[$quizTitle] = ['totalScore' => 0, 'count' => 0];
    }

    $meanScores[$quizTitle]['totalScore'] += $score;
    $meanScores[$quizTitle]['count']++;
}
foreach ($meanScores as $quizTitle => $data) {
    $meanScores[$quizTitle]['meanScore'] = $data['totalScore'] / $data['count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
  
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        background-color: #f0f0f0;
    }
    .profile-container {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 1200px; 
        margin: 20px auto; 
        text-align: left; 
    }
    h1 {
        margin: 0 0 10px;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
    }
    #user-records {
        margin-top: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
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
<header>
    <?php include 'navbar.html'; ?>
</header>
<div class="profile-container">
    <h1>User Profile</h1>
    <div class="info-row">
        <div id="username">Username: <?php echo $uname; ?></div>
        <div id="location">Fetching location...</div>
    </div>
    <div id="user-records">
        <h3>User Records</h3>
        <table>
            <thead>
                <tr>
                    <th>Quiz</th>
                    <th>Score</th>
                    <th>Finish Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($results) > 0) {
                    foreach ($results as $row) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['title']) . "</td>
                                <td>" . htmlspecialchars($row['score']) . "</td>
                                <td>" . htmlspecialchars($row['finished_time']) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No records found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <h3 style="margin-top:20px;">Your Mean Scores</h3>
        <table>
            <thead>
                <tr>
                    <th>Quiz</th>
                    <th>Mean</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($meanScores as $quizTitle => $data) {
                    echo "<tr>
                            <td>" . htmlspecialchars($quizTitle) . "</td>
                            <td>" . number_format($data['meanScore'], 2) . "</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    window.onload = function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            document.getElementById("location").innerText = "Geolocation is not supported by this browser.";
        }
    };

    function showPosition(position) {
        const lat = position.coords.latitude;
        const lon = position.coords.longitude;
        getCountry(lat, lon);
    }

    function getCountry(lat, lon) {
        const apiKey = '3aeac662115f48e7a47a612fbde39d63';
        const url = `https://api.opencagedata.com/geocode/v1/json?q=${lat}+${lon}&key=${apiKey}&language=en`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.results.length > 0) {
                    const country = data.results[0].components.country;
                    document.getElementById("location").innerText = `Country: ${country}`;
                } else {
                    document.getElementById("location").innerText = "Country not found.";
                }
            })
            .catch(error => {
                document.getElementById("location").innerText = "Error fetching country: " + error.message;
            });
    }

    function showError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                document.getElementById("location").innerText = "Access denied";
                break;
            case error.POSITION_UNAVAILABLE:
                document.getElementById("location").innerText = "Unavailable.";
                break;
            case error.TIMEOUT:
                document.getElementById("location").innerText = "Timed out.";
                break;
            case error.UNKNOWN_ERROR:
                document.getElementById("location").innerText = "Unknown error";
                break;
        }
    }
</script>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PLKRRR35"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>
</html>

<?php
$pdo = null;
?>
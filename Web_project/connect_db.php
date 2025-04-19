<?php
$host = 'sql203.infinityfree.com';
$db_name = 'if0_38778302_quiz_db';
$username = 'if0_38778302';
$password = '0SZsBQLNeh';
$port = 3308;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
    CREATE TABLE IF NOT EXISTS questions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        quiz_id INT NOT NULL,
        question TEXT NOT NULL,
        option_a VARCHAR(255) NOT NULL,
        option_b VARCHAR(255) NOT NULL,
        option_c VARCHAR(255) NOT NULL,
        option_d VARCHAR(255) NOT NULL,
        correct_answer VARCHAR(255) NOT NULL,
        FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
    );";

 
    $pdo->exec($sql);


} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
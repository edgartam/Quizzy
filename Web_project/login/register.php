<?php
session_start();
include 'connect_db.php';

$message = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username']; 
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $message = 'Please fill in all fields.';
    } elseif ($password !== $confirm_password) {
        $message = 'Passwords do not match.';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

        try {
            $stmt->execute([$username, $hashed_password]);
            $message = 'User ' . htmlspecialchars($username) . ' registration successful! You can now log in.';
            $username = ''; 
        } catch (PDOException $e) {
            $message = 'Error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_register.css">
    <title>Register</title>
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
    <div class="container">
        <h2>Register</h2>
        <?php if ($message): ?>
            <div class="error"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PLKRRR35"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>
</html>
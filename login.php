<?php
require_once __DIR__ . '/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username and password are required.';
    } else {
        $stmt = pdo()->prepare('SELECT id, username FROM users WHERE username = :u AND password = :p LIMIT 1');
        $stmt->execute([':u' => $username, ':p' => $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: chat.php');
            exit;
        } else {
            $error = 'Invalid credentials.';
        }
    }
}

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
 </head>
<body>
<div class="box">
    <h2>Login</h2>
    <?php if (isset($_GET['registered'])): ?>
        <div class="success">Registration successful. Please login.</div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="error"><?=htmlspecialchars($error)?></div>
    <?php endif; ?>
    <form method="post" action="">
        <label>Username<br><input type="text" name="username"></label><br>
        <label>Password<br><input type="password" name="password"></label><br>
        <button type="submit">Login</button>
    </form>
    <p>No account? <a href="register.php">Register here</a></p>
</div>
</body>
</html>

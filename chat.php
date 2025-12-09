<?php
require_once __DIR__ . '/db.php';

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message'] ?? '');
    if ($message !== '') {
        $stmt = pdo()->prepare('INSERT INTO messages (user_id, username, message) VALUES (:uid, :u, :m)');
        $stmt->execute([':uid' => $user_id, ':u' => $username, ':m' => $message]);
    }
    header('Location: chat.php');
    exit;
}

$stmt = pdo()->query('SELECT id, user_id, username, message, created_at FROM messages ORDER BY id DESC LIMIT 100');
$messages = array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC));

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Chat</title>
    <link rel="stylesheet" href="styles.css">
    <meta http-equiv="refresh" content="8">
 </head>
<body>
<div class="box">
    <h2>Simple Chat</h2>
    <div class="meta">Logged in as <strong><?=htmlspecialchars($username)?></strong> — <a href="logout.php">Logout</a></div>

    <div class="messages">
        <?php foreach ($messages as $m): ?>
            <div class="message">
                <span class="who"><?=htmlspecialchars($m['username'])?></span>
                <span class="when"><?=htmlspecialchars($m['created_at'])?></span>
                <div class="text"><?=nl2br(htmlspecialchars($m['message']))?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <form method="post" action="">
        <textarea name="message" rows="3" placeholder="Type your message..."></textarea><br>
        <button type="submit">Send</button>
    </form>
    <p class="hint">Messages are stored in the local database in plain text (username & password).</p>
</div>
</body>
</html>

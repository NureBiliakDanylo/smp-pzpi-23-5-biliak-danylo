<?php 
session_start();
require 'db.php';

$user = null;
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user'];
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Головна</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header><h1>Головна</h1></header>
<?php include 'menu.php'; ?>

<div class="container">
    <?php if (!$user): ?>
        <div class="auth-warning">
            Будь ласка, <a href="login.php">увійдіть</a> або <a href="register.php">зареєструйтесь</a> для користування сайтом.
        </div>
    <?php else: ?>
        <main>
            <h2>Ласкаво просимо до нашого магазину!</h2>
            <p>Оберіть розділ:</p>
            <ul>
                <li><a href="index.php">→ Переглянути товари</a></li>
                <li><a href="cart.php">→ Переглянути кошик</a></li>
            </ul>
        </main>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>

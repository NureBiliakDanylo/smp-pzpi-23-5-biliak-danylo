<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $qty = (int)$_POST['quantity'];
    if ($qty > 0) {
        $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
    }
}

$user = null;
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user'];
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

$products = $db->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Товари</title>
</head>
<body>
<header><h1>Інтернет-магазин</h1></header>
<?php include 'menu.php'; ?>

<div class="container">
    <?php if (!$user): ?>
        <div class="auth-warning">
            Будь ласка, <a href="login.php">увійдіть</a> або <a href="register.php">зареєструйтесь</a> для користування сайтом.
        </div>
    <?php else: ?>
        <main>
            <?php foreach ($products as $product): ?>
                <form method="POST">
                    <h3><?= htmlspecialchars($product['name']) ?> — <?= $product['price'] ?> грн</h3>
                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
                    Кількість: <input type="number" name="quantity" value="1" min="1">
                    <button type="submit">Купити</button>
                </form>
                <hr>
            <?php endforeach; ?>
            <a href="cart.php">Перейти до кошика</a>
        </main>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>

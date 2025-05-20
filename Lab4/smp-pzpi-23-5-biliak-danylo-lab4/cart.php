<?php
session_start();
require 'db.php';

if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit;
}

$user = null;
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user'];
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

$cart = $_SESSION['cart'] ?? [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Кошик</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header><h1>Кошик</h1></header>
<?php include 'menu.php'; ?>

<main>

<div>
    <?php if (!$user): ?>
        <div class="auth-warning">
            Будь ласка, <a href="login.php">увійдіть</a> або <a href="register.php">зареєструйтесь</a> для користування сайтом.
        </div>
    <?php else: ?>
        <?php if (empty($cart)): ?>
            <p>Кошик порожній. <a href="index.php">Перейти до покупок</a></p>
        <?php else:
            $placeholders = implode(',', array_fill(0, count($cart), '?'));
            $stmt = $db->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
            $stmt->execute(array_keys($cart));
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $total = 0;
        ?>
            <h2>Ваш кошик</h2>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Назва</th>
                        <th>Ціна за одиницю</th>
                        <th>Кількість</th>
                        <th>Сума</th>
                        <th>Дія</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $product):
                    $qty = $cart[$product['id']];
                    $sum = $qty * $product['price'];
                    $total += $sum;
                ?>
                    <tr>
                        <td><?= $product['id'] ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= $product['price'] ?> грн</td>
                        <td><?= $qty ?></td>
                        <td><?= $sum ?> грн</td>
                        <td><a href="?remove=<?= $product['id'] ?>" class="remove">Видалити</a></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="4" style="text-align: right;"><strong>Загальна сума:</strong></td>
                    <td colspan="2"><strong><?= $total ?> грн</strong></td>
                </tr>
                </tbody>
            </table>
            <a href="index.php">Продовжити покупки</a>
        <?php endif; ?>
        </main>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>

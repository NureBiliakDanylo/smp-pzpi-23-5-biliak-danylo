<?php
session_start();
$db = new PDO("sqlite:database.sqlite");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user'];

$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $birthdate = $_POST["birthdate"];
    $description = trim($_POST["description"]);

    if (!empty($_FILES["avatar"]["name"])) {
        $ext = pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);
        $filename = "uploads/avatar_$user_id." . $ext;
        move_uploaded_file($_FILES["avatar"]["tmp_name"], $filename);
    } else {
        $filename = $user['avatar'];
    }

    $stmt = $db->prepare("UPDATE users SET first_name = ?, last_name = ?, birthdate = ?, description = ?, avatar = ? WHERE id = ?");
    $stmt->execute([$first_name, $last_name, $birthdate, $description, $filename, $user_id]);

    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Профіль</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>
<header><h1>Профіль користувача</h1></header>
<?php include 'menu.php'; ?>
<div class="container">
    <?php if (!$user): ?>
        <div class="auth-warning">
            Будь ласка, <a href="login.php">увійдіть</a> або <a href="register.php">зареєструйтесь</a> для користування сайтом.
        </div>
    <?php else: ?>
        <form method="post" enctype="multipart/form-data" class="profile-form">
            <div class="left-panel">
                <?php if (!empty($user['avatar'])): ?>
                <img src="<?= $user['avatar'] ?>" alt="Avatar" class="avatar-preview">
                <?php else: ?>
                <div class="avatar-placeholder"></div>
                <?php endif; ?>
                <input type="file" name="avatar" accept="image/*" class="upload-button">
            </div>

            <div class="right-panel">
                <div class="input-row">
                <input type="text" name="first_name" placeholder="Name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                <input type="text" name="last_name" placeholder="Surname" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                <input type="date" name="birthdate" value="<?= htmlspecialchars($user['birthdate']) ?>" required>
                </div>

                <div class="input-row">
                <textarea name="description" placeholder="Brief description" required><?= htmlspecialchars($user['description']) ?></textarea>
                </div>

                <button type="submit" class="save-button">Зберегти</button>
            </div>
        </form>

        <br>
        <form method="get">
            <button type="submit" name="logout" value="1">Вийти з облікового запису</button>
        </form>
    <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
</body>
</html>

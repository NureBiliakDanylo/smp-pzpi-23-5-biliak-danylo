<?php
session_start();
$db = new PDO("sqlite:database.sqlite");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    if ($password !== $confirm) {
        $error = "Паролі не збігаються!";
    } elseif (empty($username) || empty($password)) {
        $error = "Заповніть усі поля!";
    } else {
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = "Користувач із таким ім'ям уже існує!";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashed]);

            $_SESSION['user'] = $db->lastInsertId();
            header("Location: index.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Реєстрація</title>
    <link rel="stylesheet" href="Style.css">
    <style>
        body {
        font-family: Arial, sans-serif;
        background-color: #f4f7f8;
        margin: 0;
        padding: 0;
        }

        header {
        background-color: #2a79d4;
        color: white;
        padding: 20px;
        text-align: center;
        }

        .container {
        max-width: 400px;
        margin: 50px auto;
        padding: 30px;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .container h2 {
        text-align: center;
        color: #333;
        margin-bottom: 25px;
        }

        form label {
        display: block;
        margin-bottom: 15px;
        color: #555;
        font-size: 15px;
        }

        form input[type="text"],
        form input[type="password"] {
        width: 100%;
        padding: 10px;
        font-size: 15px;
        border: 1px solid #ccc;
        border-radius: 6px;
        margin-top: 5px;
        }

        button {
        width: 100%;
        padding: 12px;
        background-color: #2a79d4;
        color: white;
        font-size: 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.2s;
        margin-top: 10px;
        }

        button:hover {
        background-color: #1d5fa5;
        }

        p {
        text-align: center;
        margin-top: 15px;
        font-size: 14px;
        }

        p a {
        color: #2a79d4;
        text-decoration: none;
        }

        p a:hover {
        text-decoration: underline;
        }

        .error {
        background-color: #ffdada;
        color: #a94442;
        border: 1px solid #e5bcbc;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
        text-align: center;
        }
    </style>
</head>
<body>
<header><h1>Реєстрація</h1></header>
<?php include 'menu.php'; ?>
<div class="container">
    <h2>Реєстрація</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
        <label>Логін: <input type="text" name="username" required></label><br>
        <label>Пароль: <input type="password" name="password" required></label><br>
        <label>Підтвердження пароля: <input type="password" name="confirm" required></label><br>
        <button type="submit">Зареєструватись</button>
    </form>
</div>
<?php include 'footer.php'; ?>
</body>
</html>

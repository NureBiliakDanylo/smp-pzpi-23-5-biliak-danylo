<?php
$db = new PDO('sqlite:database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    price REAL
)");

$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    first_name TEXT,
    last_name TEXT,
    birthdate TEXT,
    description TEXT,
    avatar TEXT
)");


$db->exec("INSERT INTO products (name, price) VALUES
    ('Молоко', 35.50),
    ('Хліб', 20.00),
    ('Сир', 90.00)
");

echo "Базу даних створено.";
?>

<?php

$products = [
    1 => ["name" => "Молоко пастеризоване", "price" => 12],
    2 => ["name" => "Хліб чорний         ", "price" => 9],
    3 => ["name" => "Сир білий           ", "price" => 21],
    4 => ["name" => "Сметана 20%         ", "price" => 25],
    5 => ["name" => "Кефір 1%            ", "price" => 19],
    6 => ["name" => "Вода газована       ", "price" => 18],
    7 => ["name" => "Печиво \"Весна\"      ", "price" => 14],
];

$cart = [];
$userName = "";
$userAge = 0;

function showMainMenu() {
    echo "\n################################\n";
    echo "# ПРОДОВОЛЬЧИЙ МАГАЗИН \"ВЕСНА\" #\n";
    echo "################################\n";
    echo "1 Вибрати товари\n";
    echo "2 Отримати підсумковий рахунок\n";
    echo "3 Налаштувати свій профіль\n";
    echo "0 Вийти з програми\n";
    echo "Введіть команду: ";
}

function showProductList($products) {
    echo "№  НАЗВА                   ЦІНА\n";
    foreach ($products as $num => $item) {
        printf("%-2s %-24s %5s\n", $num, $item["name"], $item["price"]);
    }
    echo "   -----------\n";
    echo "0  ПОВЕРНУТИСЯ\n";
    echo "Виберіть товар: ";
}

function showCart($cart) {
    if (empty($cart)) {
        echo "КОШИК ПОРОЖНІЙ\n";
        return;
    }
    echo "У КОШИКУ:\nНАЗВА                   КІЛЬКІСТЬ\n";
    foreach ($cart as $itemName => $qty) {
        printf("%-24s %5s\n", $itemName, $qty);
    }
}

function getUserInput() {
    return trim(fgets(STDIN));
}

while (true) {
    showMainMenu();
    $cmd = getUserInput();

    switch ($cmd) {
        case "1":
            while (true) {
                showProductList($products);
                $choice = getUserInput();
                if ($choice == "0") break;

                if (!array_key_exists($choice, $products)) {
                    echo "ПОМИЛКА! ВКАЗАНО НЕПРАВИЛЬНИЙ НОМЕР ТОВАРУ\n";
                    continue;
                }

                $selectedProduct = $products[$choice]["name"];
                echo "Вибрано: {$selectedProduct}\n";
                echo "Введіть кількість, штук: ";
                $quantity = getUserInput();

                if (!is_numeric($quantity) || $quantity < 0) {
                    echo "ПОМИЛКА! Кількість повинна бути 0 або більше.\n";
                    continue;
                }

                if ($quantity == 0) {
                    echo "ВИДАЛЯЮ З КОШИКА\n";
                    unset($cart[$selectedProduct]);
                } else {
                    $cart[$selectedProduct] = $quantity;
                }

                showCart($cart);
            }
            break;

        case "2":
            if (empty($cart)) {
                echo "КОШИК ПОРОЖНІЙ\n";
                break;
            }

            echo "№  НАЗВА                   ЦІНА   КІЛЬКІСТЬ  ВАРТІСТЬ\n";
            echo "------------------------------------------------------\n";
            $i = 1;
            $total = 0;
            foreach ($cart as $productName => $qty) {
                $price = 0;
                foreach ($products as $p) {
                    if ($p["name"] == $productName) {
                        $price = $p["price"];
                        break;
                    }
                }
                $sum = $price * $qty;
                printf("%-2s %-24s %6s %9s %9s\n", $i++, $productName, $price, $qty, $sum);
                $total += $sum;
            }
            echo "------------------------------------------------------\n";
            echo "РАЗОМ ДО СПЛАТИ: {$total}\n";
            break;

        case "3":
            do {
                echo "Ваше імʼя: ";
                $userName = getUserInput();
            } while (!preg_match('/[a-zA-Zа-яА-ЯіІїЇєЄ]/u', $userName));

            do {
                echo "Ваш вік: ";
                $userAge = getUserInput();
            } while (!is_numeric($userAge) || $userAge < 7 || $userAge > 150);

            echo "Профіль збережено. Імʼя: $userName, Вік: $userAge\n";
            break;

        case "0":
            echo "Дякуємо за покупку в магазині \"Весна\"! До побачення!\n";
            exit;

        default:
            echo "ПОМИЛКА! Введіть правильну команду\n";
            break;
    }
}
